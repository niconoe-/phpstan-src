<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Scope;

final class ExprAnalysisRequest
{

	/**
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public readonly Stmt $stmt,
		public readonly Expr $expr,
		public readonly GeneratorScope $scope,
		public readonly ExpressionContext $context,
		public readonly mixed $alternativeNodeCallback = null,
	)
	{
	}

}
