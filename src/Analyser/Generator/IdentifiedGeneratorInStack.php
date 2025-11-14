<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Generator;
use PhpParser\Node;

/**
 * @phpstan-import-type GeneratorTValueType from GeneratorNodeScopeResolver
 * @phpstan-import-type GeneratorTSendType from GeneratorNodeScopeResolver
 */
final class IdentifiedGeneratorInStack
{

	/**
	 * @param (
	 *     Generator<int, GeneratorTValueType, GeneratorTSendType, StmtAnalysisResult>| // analyseStmt
	 *     Generator<int, GeneratorTValueType, GeneratorTSendType, StmtAnalysisResult>| // analyseStmts
	 *     Generator<int, GeneratorTValueType, GeneratorTSendType, void>| // analyseAttrGroups
	 *     Generator<int, GeneratorTValueType, GeneratorTSendType, ExprAnalysisResult>| // analyseExpr
	 *     Generator<int, GeneratorTValueType, GeneratorTSendType, TypeExprResult> // analyseExprForType
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
