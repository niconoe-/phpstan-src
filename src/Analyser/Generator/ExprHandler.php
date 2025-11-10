<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;

/**
 * @template T of Expr
 */
interface ExprHandler
{

	public const HANDLER_TAG = 'phpstan.gnsr.exprHandler';

	/**
	 * @phpstan-assert-if-true T $expr
	 */
	public function supports(Expr $expr): bool;

	/**
	 * @param T $expr
	 * @return Generator<int, ExprAnalysisRequest|NodeCallbackRequest, ExprAnalysisResult, ExprAnalysisResult>
	 */
	public function analyseExpr(Stmt $stmt, Expr $expr, GeneratorScope $scope, ExpressionContext $context): Generator;

}
