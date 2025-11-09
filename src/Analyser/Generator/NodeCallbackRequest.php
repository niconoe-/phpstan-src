<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

final class NodeCallbackRequest
{

	public function __construct(
		public Node $node,
		public Scope $scope,
	)
	{
	}

}
