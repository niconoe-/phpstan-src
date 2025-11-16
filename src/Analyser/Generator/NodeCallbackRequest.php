<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

final class NodeCallbackRequest
{

	/**
	 * @param (callable(Node, Scope, callable(Node, Scope): void): void)|null $alternativeNodeCallback
	 */
	public function __construct(
		public readonly Node $node,
		public readonly Scope $scope,
		public readonly mixed $alternativeNodeCallback,
	)
	{
	}

}
