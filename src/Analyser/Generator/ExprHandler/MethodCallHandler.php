<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\ExprHandler;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Generator\ExprAnalysisRequest;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\ExprAnalysisResultStorage;
use PHPStan\Analyser\Generator\ExprHandler;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\ShouldNotHappenException;
use function array_merge;

/**
 * @implements ExprHandler<MethodCall>
 */
#[AutowiredService]
final class MethodCallHandler implements ExprHandler
{

	public function supports(Expr $expr): bool
	{
		return $expr instanceof MethodCall;
	}

	public function analyseExpr(Stmt $stmt, Expr $expr, GeneratorScope $scope, ExprAnalysisResultStorage $storage, ExpressionContext $context): Generator
	{
		if (!$expr->name instanceof Identifier) {
			throw new ShouldNotHappenException('Not implemented');
		}

		$varResult = yield new ExprAnalysisRequest($stmt, $expr->var, $scope, $context->enterDeep());
		$throwPoints = $varResult->throwPoints;
		$impurePoints = $varResult->impurePoints;
		$isAlwaysTerminating = $varResult->isAlwaysTerminating;
		$hasYield = $varResult->hasYield;
		$scope = $varResult->scope;
		$argTypes = [];

		foreach ($expr->getArgs() as $arg) {
			$argResult = yield new ExprAnalysisRequest($stmt, $arg->value, $scope, $context->enterDeep());
			$argTypes[] = $argResult->type;
			$scope = $argResult->scope;
			$throwPoints = array_merge($throwPoints, $argResult->throwPoints);
			$impurePoints = array_merge($impurePoints, $argResult->impurePoints);
			$isAlwaysTerminating = $isAlwaysTerminating || $argResult->isAlwaysTerminating;
			$hasYield = $hasYield || $argResult->hasYield;
		}

		if ($varResult->type->hasMethod($expr->name->toString())->yes()) {
			$method = $varResult->type->getMethod($expr->name->toString(), $scope);
			return new ExprAnalysisResult(
				$method->getOnlyVariant()->getReturnType(),
				$scope,
				hasYield: $hasYield,
				isAlwaysTerminating: $isAlwaysTerminating,
				throwPoints: $throwPoints,
				impurePoints: $impurePoints,
			);
		}

		throw new ShouldNotHappenException('Not implemented');
	}

}
