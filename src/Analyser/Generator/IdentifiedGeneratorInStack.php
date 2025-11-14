<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node;

final class IdentifiedGeneratorInStack
{

	/**
	 * @param (
	 *     Generator<int, StmtAnalysisRequest, StmtAnalysisResult, StmtAnalysisResult>| // analyseStmts
	 *     Generator<int, ExprAnalysisRequest|StmtAnalysisRequest|StmtsAnalysisRequest|NodeCallbackRequest|AlternativeNodeCallbackRequest, ExprAnalysisResult|StmtAnalysisResult, StmtAnalysisResult>| // analyseStmt
	 *     Generator<int, TypeExprRequest|ExprAnalysisRequest|NodeCallbackRequest|AlternativeNodeCallbackRequest, ExprAnalysisResult, TypeExprResult|ExprAnalysisResult>| // analyseExpr
	 *     Generator<int, TypeExprRequest, TypeExprResult, TypeExprResult> // analyseExprForType
	 * ) $generator
	 * @param Node|Node[] $node
	 */
	public function __construct(
		public Generator $generator,
		public Node|array $node,
		public ?string $file,
		public ?int $line,
	)
	{
	}

}
