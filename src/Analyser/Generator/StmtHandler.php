<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node\Stmt;

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
	 * @return Generator<int, ExprAnalysisRequest|StmtAnalysisRequest|NodeCallbackRequest, ExprAnalysisResult|StmtAnalysisResult, StmtAnalysisResult>
	 */
	public function analyseStmt(Stmt $stmt, GeneratorScope $scope): Generator;

}
