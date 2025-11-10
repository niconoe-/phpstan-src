<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\StatementContext;

final class StmtsAnalysisRequest
{

	/**
	 * @param Stmt[] $stmts
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public readonly array $stmts,
		public readonly GeneratorScope $scope,
		public readonly StatementContext $context,
		public readonly mixed $alternativeNodeCallback = null,
	)
	{
	}

}
