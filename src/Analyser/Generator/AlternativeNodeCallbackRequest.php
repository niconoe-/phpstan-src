<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

final class AlternativeNodeCallbackRequest
{

	/**
	 * @param callable(Node, Scope, callable(Node, Scope): void): void $nodeCallback
	 */
	public function __construct(
		public Node $node,
		public Scope $scope,
		public $nodeCallback,
	)
	{
	}

}
