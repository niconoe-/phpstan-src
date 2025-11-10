<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\ExprHandler;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Generator\ExprAnalysisRequest;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\ExprHandler;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\ShouldNotHappenException;
use function array_merge;
use function is_string;

/**
 * @implements ExprHandler<Assign>
 */
#[AutowiredService]
final class AssignHandler implements ExprHandler
{

	public function supports(Expr $expr): bool
	{
		return $expr instanceof Assign;
	}

	public function analyseExpr(Stmt $stmt, Expr $expr, GeneratorScope $scope, ExpressionContext $context): Generator
	{
		if (!$expr->var instanceof Expr\Variable || !is_string($expr->var->name)) {
			throw new ShouldNotHappenException('Not implemented');
		}
		$variableName = $expr->var->name;
		$exprResult = yield new ExprAnalysisRequest($stmt, $expr->expr, $scope, $context->enterDeep());
		$varResult = yield new ExprAnalysisRequest($stmt, $expr->var, $scope, $context->enterDeep());

		return new ExprAnalysisResult(
			$exprResult->type,
			$varResult->scope->assignVariable($variableName, $exprResult->type),
			hasYield: $exprResult->hasYield || $varResult->hasYield,
			isAlwaysTerminating: $exprResult->isAlwaysTerminating || $varResult->isAlwaysTerminating,
			throwPoints: array_merge($exprResult->throwPoints, $varResult->throwPoints),
			impurePoints: array_merge($exprResult->impurePoints, $varResult->impurePoints),
		);
	}

}
