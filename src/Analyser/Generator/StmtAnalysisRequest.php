<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\Scope;

final class StmtAnalysisRequest
{

	/**
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public Stmt $stmt,
		public GeneratorScope $scope,
		public $alternativeNodeCallback = null,
	)
	{
	}

}
