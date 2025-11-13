<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\ExprHandler;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\ExprAnalysisResultStorage;
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

	public function analyseExpr(Stmt $stmt, Expr $expr, GeneratorScope $scope, ExprAnalysisResultStorage $storage, ExpressionContext $context): Generator
	{
		if (!is_string($expr->name)) {
			throw new ShouldNotHappenException('Not implemented');
		}

		$exprTypeFromScope = $scope->getExpressionType($expr);
		if ($exprTypeFromScope !== null) {
			return new ExprAnalysisResult(
				$exprTypeFromScope,
				$scope,
				hasYield: false,
				isAlwaysTerminating: false,
				throwPoints: [],
				impurePoints: [],
			);
		}

		yield from [];
		return new ExprAnalysisResult(
			new ErrorType(),
			$scope,
			hasYield: false,
			isAlwaysTerminating: false,
			throwPoints: [],
			impurePoints: [],
		);
	}

}
