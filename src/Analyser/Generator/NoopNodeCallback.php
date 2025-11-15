<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

final class NoopNodeCallback
{

	/**
	 * @param callable(Node, Scope): void $nodeCallback
	 */
	public function __invoke(Node $node, Scope $scope, callable $nodeCallback): void
	{
	}

}
