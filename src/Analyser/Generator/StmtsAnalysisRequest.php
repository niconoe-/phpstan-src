<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;

final class StmtsAnalysisRequest
{

	/**
	 * @param Stmt[] $stmts
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public array $stmts,
		public GeneratorScope $scope,
		public $alternativeNodeCallback = null,
	)
	{
	}

}
