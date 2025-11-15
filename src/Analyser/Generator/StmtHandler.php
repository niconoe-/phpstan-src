<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\StatementContext;

/**
 * @template T of Stmt
 */
interface StmtHandler
{

	public const HANDLER_TAG = 'phpstan.gnsr.stmtHandler';

	/**
	 * @phpstan-assert-if-true T $stmt
	 */
	public function supports(Stmt $stmt): bool;

	/**
	 * @param T $stmt
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 * @return Generator<int, ExprAnalysisRequest|StmtAnalysisRequest|StmtsAnalysisRequest|NodeCallbackRequest, ExprAnalysisResult|StmtAnalysisResult, StmtAnalysisResult>
	 */
	public function analyseStmt(
		Stmt $stmt,
		GeneratorScope $scope,
		StatementContext $context,
		?callable $alternativeNodeCallback,
	): Generator;

}
