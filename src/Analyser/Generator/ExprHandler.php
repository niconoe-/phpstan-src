<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Scope;

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
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 * @return Generator<int, ExprAnalysisRequest|NodeCallbackRequest|TypeExprRequest, ExprAnalysisResult|TypeExprResult, ExprAnalysisResult>
	 */
	public function analyseExpr(
		Stmt $stmt,
		Expr $expr,
		GeneratorScope $scope,
		ExpressionContext $context,
		?callable $alternativeNodeCallback,
	): Generator;

}
