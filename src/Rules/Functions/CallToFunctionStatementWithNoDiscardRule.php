<?php declare(strict_types = 1);

namespace PHPStan\Rules\Functions;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\DependencyInjection\RegisteredRule;
use PHPStan\Php\PhpVersion;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\TrinaryLogic;
use PHPStan\Type\VerbosityLevel;
use function sprintf;

/**
 * @implements Rule<Node\Stmt\Expression>
 */
#[RegisteredRule(level: 0)]
final class CallToFunctionStatementWithNoDiscardRule implements Rule
{

	public function __construct(
		private ReflectionProvider $reflectionProvider,
		private PhpVersion $phpVersion,
	)
	{
	}

	public function getNodeType(): string
	{
		return Node\Stmt\Expression::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->expr instanceof Node\Expr\FuncCall) {
			return [];
		}

		if ($node->expr->isFirstClassCallable()) {
			return [];
		}

		if (!$this->phpVersion->supportsNoDiscardAttribute()) {
			return [];
		}

		$funcCall = $node->expr;
		if ($funcCall->name instanceof Node\Name) {
			if (!$this->reflectionProvider->hasFunction($funcCall->name, $scope)) {
				return [];
			}

			$function = $this->reflectionProvider->getFunction($funcCall->name, $scope);
			if (!$function->mustUseReturnValue()->yes()) {
				return [];
			}

			return [
				RuleErrorBuilder::message(sprintf(
					'Call to function %s() on a separate line discards return value.',
					$function->getName(),
				))->identifier('function.resultDiscarded')->nonIgnorable()->build(),
			];
		}

		$callableType = $scope->getType($funcCall->name);
		if (!$callableType->isCallable()->yes()) {
			return [];
		}

		$mustUseReturnValue = TrinaryLogic::createNo();
		foreach ($callableType->getCallableParametersAcceptors($scope) as $callableParametersAcceptor) {
			$mustUseReturnValue = $mustUseReturnValue->or($callableParametersAcceptor->mustUseReturnValue());
		}

		if (!$mustUseReturnValue->yes()) {
			return [];
		}

		return [
			RuleErrorBuilder::message(sprintf(
				'Call to callable %s on a separate line discards return value.',
				$callableType->describe(VerbosityLevel::value()),
			))->identifier('callable.resultDiscarded')->nonIgnorable()->build(),
		];
	}

}
