<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Fiber;
use Generator;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;
use PHPStan\NeverException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ErrorType;
use PHPStan\Type\ObjectType;
use function array_map;
use function array_pop;
use function count;
use function get_class;
use function get_debug_type;
use function implode;
use function is_string;
use function sprintf;

/**
 * Work In Progress.
 *
 * This is next-get NodeScopeResolver. It aims to solve several problems:
 *
 * 1) Many expressions are processed multiple times. For example, resolving type
 *    of BooleanAnd has to process left side in order to accurately get the type
 *    of the right side. For things like MethodCall, even dynamic return type extensions
 *    and ParametersAcceptorSelector are called multiple times.
 * 2) For complicated scope-changing expressions like assigns happening inside other expressions,
 *    the current type inference is imprecise. Take $foo->doFoo($a = 1, $a);
 *    When a rule hooks onto MethodCall and iterates over its args, the type of `$a`
 *    in the second argument should be `int`, but currently it's often `mixed` because
 *    the assignment in the first argument hasn't been processed yet in the context
 *    where the rule is checking.
 *
 * This class (I will refer to it as GNSR from now on) aims to merge the tasks currently handled
 * by NodeScopeResolver, MutatingScope and TypeSpecifier all into one, because they are very similar
 * and their work should happen all at once without duplicit processing.
 *
 * This rewrite should fix 1) to improve performance and 2) to improve type inference precision.
 *
 * Architecture:
 * - Uses generators (with `yield`) for internal analysis code to handle recursive traversal
 *   without deep call stacks and to explicitly manage when sub-expressions are analyzed
 * - Uses Fibers when calling custom extension code (rules, dynamic return type extensions, etc.)
 *   to preserve backward compatibility
 * - Calls to `$scope->getType()` in custom extensions do not need to be preceded with `yield`.
 *   Instead, if the type is not yet available, the call will transparently suspend the current
 *   Fiber and resume once the type has been computed
 * - All computed types and analysis results are stored during traversal so that subsequent
 *   lookups (from rules or other extensions) hit cached values with no duplicate work
 * - Synthetic/virtual expressions (e.g., a rule constructing `new MethodCall(...)` to query
 *   a hypothetical method call) are analyzed on-demand when requested, with the Fiber
 *   suspending until analysis completes
 */
final class GeneratorNodeScopeResolver
{

	public function __construct(private ReflectionProvider $reflectionProvider)
	{
	}

	/**
	 * @param string[] $files
	 */
	public function setAnalysedFiles(array $files): void
	{
	}

	/**
	 * @param array<Node\Stmt> $stmts
	 * @param callable(Node, Scope): void $nodeCallback
	 */
	public function processNodes(
		array $stmts,
		GeneratorScope $scope,
		callable $nodeCallback,
	): void
	{
		$storage = new NodeScopeResolverRunStorage();
		$this->processStmtNodes(
			$storage,
			$stmts,
			$scope,
			$nodeCallback,
		);
	}

	/**
	 * @param array<Node\Stmt> $stmts
	 * @param callable(Node, Scope): void $nodeCallback
	 */
	private function processStmtNodes(
		NodeScopeResolverRunStorage $storage,
		array $stmts,
		GeneratorScope $scope,
		callable $nodeCallback,
	): StmtAnalysisResult
	{
		$stack = [];

		$gen = $this->analyzeStmts($stmts, $scope);
		$gen->current();

		// Trampoline loop
		while (true) {
			$this->processPendingFibers($storage);

			if ($gen->valid()) {
				$yielded = $gen->current();

				if ($yielded instanceof NodeCallbackRequest) {
					$this->invokeNodeCallback(
						$storage,
						$yielded->node,
						$yielded->scope,
						$nodeCallback,
					);

					$gen->next();
					continue;
				} elseif ($yielded instanceof ExprAnalysisRequest) {
					$stack[] = $gen;
					$gen = $this->analyzeExpr($storage, $yielded->expr, $yielded->scope);
					$gen->current();
					continue;
				} elseif ($yielded instanceof StmtAnalysisRequest) {
					$stack[] = $gen;
					$gen = $this->analyzeStmt($yielded->stmt, $yielded->scope);
					$gen->current();
					continue;
				} else { // phpcs:ignore
					throw new NeverException($yielded);
				}
			}

			$result = $gen->getReturn();
			if (count($stack) === 0) {
				foreach ($storage->pendingFibers as $pending) {
					$request = $pending['request'];
					$exprAnalysisResult = $this->lookupExprAnalysisResult($storage, $request->expr);

					if ($exprAnalysisResult !== null) {
						throw new ShouldNotHappenException('Pending fibers with an empty stack should be about synthetic nodes');
					}

					$this->processStmtNodes(
						$storage,
						[new Stmt\Expression($request->expr)],
						$request->scope,
						static function () {
						},
					);
				}

				if (count($storage->pendingFibers) === 0) {
					if (!$result instanceof StmtAnalysisResult) {
						throw new ShouldNotHappenException('Top node should be Stmt');
					}
					return $result;
				}

				throw new ShouldNotHappenException(sprintf('Cannot finish analysis, pending fibers about: %s', implode(', ', array_map(static fn (array $fiber) => get_class($fiber['request']->expr), $storage->pendingFibers))));
			}

			$gen = array_pop($stack);
			$gen->send($result);
		}
	}

	/**
	 * @param array<Stmt> $stmts
	 * @return Generator<int, StmtAnalysisRequest, StmtAnalysisResult, StmtAnalysisResult>
	 */
	private function analyzeStmts(array $stmts, GeneratorScope $scope): Generator
	{
		foreach ($stmts as $stmt) {
			$result = yield new StmtAnalysisRequest($stmt, $scope);
			$scope = $result->scope;
		}

		return new StmtAnalysisResult($scope);
	}

	/**
	 * @return Generator<int, NodeCallbackRequest|ExprAnalysisRequest, ExprAnalysisResult, StmtAnalysisResult>
	 */
	private function analyzeStmt(Stmt $stmt, GeneratorScope $scope): Generator
	{
		yield new NodeCallbackRequest($stmt, $scope);

		if ($stmt instanceof Stmt\Namespace_) {
			/*if ($stmt->name !== null) {
				$scope = $scope->enterNamespace($stmt->name->toString());
			}*/

			yield from $this->analyzeStmts($stmt->stmts, $scope); // @phpstan-ignore generator.valueType, generator.sendType

			return new StmtAnalysisResult($scope);
		}

		if ($stmt instanceof Stmt\Use_) {
			foreach ($stmt->uses as $useItem) {
				yield new NodeCallbackRequest($useItem, $scope);
			}

			return new StmtAnalysisResult($scope);
		}

		if ($stmt instanceof Stmt\Class_ && $stmt->name !== null) {
			//$scope = $scope->enterClass();
			yield from $this->analyzeStmts($stmt->stmts, $scope); // @phpstan-ignore generator.valueType, generator.sendType

			return new StmtAnalysisResult($scope);
		}

		if ($stmt instanceof Stmt\ClassMethod) {
			//$scope = $scope->enterClassMethod();

			if ($stmt->stmts !== null) {
				yield from $this->analyzeStmts($stmt->stmts, $scope); // @phpstan-ignore generator.valueType, generator.sendType
			}

			return new StmtAnalysisResult($scope);
		}

		if ($stmt instanceof Stmt\Expression) {
			$result = yield new ExprAnalysisRequest($stmt->expr, $scope);

			return new StmtAnalysisResult($result->scope);
		}

		if ($stmt instanceof Stmt\Return_) {
			if ($stmt->expr !== null) {
				$result = yield new ExprAnalysisRequest($stmt->expr, $scope);

				return new StmtAnalysisResult($result->scope);
			}
			return new StmtAnalysisResult($scope);
		}

		if ($stmt instanceof Stmt\Nop) {
			return new StmtAnalysisResult($scope);
		}

		throw new ShouldNotHappenException('Unhandled stmt: ' . get_class($stmt));
	}

	/**
	 * @return Generator<int, ExprAnalysisRequest|NodeCallbackRequest, ExprAnalysisResult, ExprAnalysisResult>
	 */
	private function analyzeExpr(NodeScopeResolverRunStorage $storage, Expr $expr, GeneratorScope $scope): Generator
	{
		yield new NodeCallbackRequest($expr, $scope);

		if (
			$expr instanceof Expr\Assign
			&& $expr->var instanceof Expr\Variable
			&& is_string($expr->var->name)
		) {
			$variableName = $expr->var->name;
			$exprResult = yield new ExprAnalysisRequest($expr->expr, $scope);
			$this->storeExprAnalysisResult($storage, $expr->expr, $exprResult);

			$varResult = yield new ExprAnalysisRequest($expr->var, $scope);
			$this->storeExprAnalysisResult($storage, $expr->var, $varResult);

			$assignResult = new ExprAnalysisResult($exprResult->type, $varResult->scope->assignVariable($variableName, $exprResult->type));
			$this->storeExprAnalysisResult($storage, $expr, $assignResult);
			return $assignResult;
		}

		if ($expr instanceof Expr\New_ && $expr->class instanceof Node\Name) {
			$result = new ExprAnalysisResult(new ObjectType($expr->class->toString()), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		if ($expr instanceof Expr\Variable && is_string($expr->name)) {
			$exprTypeFromScope = $scope->expressionTypes['$' . $expr->name] ?? null;
			if ($exprTypeFromScope !== null) {
				$result = new ExprAnalysisResult($exprTypeFromScope, $scope);
				$this->storeExprAnalysisResult($storage, $expr, $result);
				return $result;
			}
			return $this->lookupExprAnalysisResult($storage, $expr) ?? new ExprAnalysisResult(new ErrorType(), $scope);
		}

		if ($expr instanceof Node\Scalar\Int_) {
			$result = new ExprAnalysisResult(new ConstantIntegerType($expr->value), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		if ($expr instanceof Node\Scalar\String_) {
			$result = new ExprAnalysisResult(new ConstantStringType($expr->value), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		if ($expr instanceof Node\Expr\Cast\Int_) {
			$exprResult = yield new ExprAnalysisRequest($expr->expr, $scope);
			$this->storeExprAnalysisResult($storage, $expr->expr, $exprResult);
			$result = new ExprAnalysisResult($exprResult->type->toInteger(), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		if ($expr instanceof MethodCall && $expr->name instanceof Node\Identifier) {
			$varResult = yield new ExprAnalysisRequest($expr->var, $scope);

			$this->storeExprAnalysisResult($storage, $expr->var, $varResult);
			$currentScope = $varResult->scope;
			$argTypes = [];

			foreach ($expr->getArgs() as $arg) {
				$argResult = yield new ExprAnalysisRequest($arg->value, $currentScope);
				$this->storeExprAnalysisResult($storage, $arg->value, $argResult);
				$argTypes[] = $argResult->type;
				$currentScope = $argResult->scope;
			}

			if ($varResult->type->hasMethod($expr->name->toString())->yes()) {
				$method = $varResult->type->getMethod($expr->name->toString(), $scope);
				$result = new ExprAnalysisResult($method->getOnlyVariant()->getReturnType(), $scope);
				$this->storeExprAnalysisResult($storage, $expr, $result);
				return $result;
			}
		}

		if ($expr instanceof Expr\Closure) {
			yield from $this->analyzeStmts($expr->stmts, $scope); // @phpstan-ignore generator.valueType, generator.sendType

			$result = new ExprAnalysisResult(new ClosureType(), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		if ($expr instanceof Expr\FuncCall) {
			if ($expr->name instanceof Expr) {
				$nameResult = yield new ExprAnalysisRequest($expr->name, $scope);
				$this->storeExprAnalysisResult($storage, $expr->name, $nameResult);
				$scope = $nameResult->scope;
			}

			$argTypes = [];

			foreach ($expr->getArgs() as $arg) {
				$argResult = yield new ExprAnalysisRequest($arg->value, $scope);
				$this->storeExprAnalysisResult($storage, $arg->value, $argResult);
				$argTypes[] = $argResult->type;
				$scope = $argResult->scope;
			}

			if ($expr->name instanceof Node\Name) {
				if ($this->reflectionProvider->hasFunction($expr->name, $scope)) {
					$function = $this->reflectionProvider->getFunction($expr->name, $scope);
					$result = new ExprAnalysisResult($function->getOnlyVariant()->getReturnType(), $scope);
					$this->storeExprAnalysisResult($storage, $expr, $result);
					return $result;
				}
			}
		}

		if (
			$expr instanceof Expr\ClassConstFetch
			&& $expr->class instanceof Node\Name
			&& $expr->name instanceof Node\Identifier
			&& $expr->name->toLowerString() === 'class'
		) {
			$result = new ExprAnalysisResult(new ConstantStringType($expr->class->toString()), $scope);
			$this->storeExprAnalysisResult($storage, $expr, $result);
			return $result;
		}

		throw new ShouldNotHappenException('Unhandled expr: ' . get_class($expr));
	}

	/**
	 * @param callable(Node, Scope): void $nodeCallback
	 */
	private function invokeNodeCallback(NodeScopeResolverRunStorage $storage, Node $node, Scope $scope, callable $nodeCallback): void
	{
		$fiber = new Fiber(static function () use ($node, $scope, $nodeCallback) {
			$nodeCallback($node, $scope);
		});
		$request = $fiber->start();
		$this->runFiber($storage, $fiber, $request);
	}

	/**
	 * @param Fiber<mixed, ExprAnalysisResult, null, ExprAnalysisRequest> $fiber
	 */
	private function runFiber(NodeScopeResolverRunStorage $storage, Fiber $fiber, ?ExprAnalysisRequest $request): void
	{
		while (!$fiber->isTerminated()) {
			if ($request instanceof ExprAnalysisRequest) {
				$result = $this->lookupExprAnalysisResult($storage, $request->expr);

				if ($result !== null) {
					// Result ready - continue the loop to resume
					$request = $fiber->resume($result);
					continue;
				}

				// Park the fiber - can't make progress yet
				$storage->pendingFibers[] = [
					'fiber' => $fiber,
					'request' => $request,
				];
				return;
			}

			throw new ShouldNotHappenException(
				'Unknown fiber suspension: ' . get_debug_type($request),
			);
		}

		if ($request !== null) {
			throw new ShouldNotHappenException(
				'Fiber terminated but we did not handle its request ' . get_debug_type($request),
			);
		}
	}

	private function processPendingFibers(NodeScopeResolverRunStorage $storage): void
	{
		foreach ($storage->pendingFibers as $key => $pending) {
			$request = $pending['request'];
			$exprAnalysisResult = $this->lookupExprAnalysisResult($storage, $request->expr);

			if ($exprAnalysisResult === null) {
				continue;
			}

			unset($storage->pendingFibers[$key]);

			$fiber = $pending['fiber'];
			$request = $fiber->resume($exprAnalysisResult);
			$this->runFiber($storage, $fiber, $request);
		}
	}

	private function storeExprAnalysisResult(NodeScopeResolverRunStorage $storage, Expr $expr, ExprAnalysisResult $result): void
	{
		$storage->expressionAnalysisResults[$expr] = $result;
	}

	private function lookupExprAnalysisResult(NodeScopeResolverRunStorage $storage, Expr $expr): ?ExprAnalysisResult
	{
		return $storage->expressionAnalysisResults[$expr] ?? null;
	}

}
