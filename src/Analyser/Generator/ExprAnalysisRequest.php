<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;

final class ExprAnalysisRequest
{

	/**
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public Expr $expr,
		public GeneratorScope $scope,
		public $alternativeNodeCallback = null,
	)
	{
	}

}
