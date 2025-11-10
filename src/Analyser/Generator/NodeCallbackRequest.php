<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

final class NodeCallbackRequest
{

	public function __construct(
		public readonly Node $node,
		public readonly Scope $scope,
	)
	{
	}

}
