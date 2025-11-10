<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\ExprHandler;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\ExprHandler;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ErrorType;
use function is_string;

/**
 * @implements ExprHandler<Variable>
 */
#[AutowiredService]
final class VariableHandler implements ExprHandler
{

	public function supports(Expr $expr): bool
	{
		return $expr instanceof Variable;
	}

	public function analyseExpr(Expr $expr, GeneratorScope $scope): Generator
	{
		if (!is_string($expr->name)) {
			throw new ShouldNotHappenException('Not implemented');
		}

		$exprTypeFromScope = $scope->expressionTypes['$' . $expr->name] ?? null;
		if ($exprTypeFromScope !== null) {
			return new ExprAnalysisResult($exprTypeFromScope, $scope);
		}

		yield from [];
		return new ExprAnalysisResult(new ErrorType(), $scope);
	}

}
