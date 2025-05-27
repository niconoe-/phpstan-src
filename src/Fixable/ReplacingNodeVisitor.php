<?php declare(strict_types = 1);

namespace PHPStan\Fixable;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Node\VirtualNode;
use PHPStan\ShouldNotHappenException;

final class ReplacingNodeVisitor extends NodeVisitorAbstract
{

	/**
	 * @param callable(Node): Node $newNodeCallable
	 */
	public function __construct(private Node $originalNode, private $newNodeCallable)
	{
	}

	public function enterNode(Node $node): ?Node
	{
		$origNode = $node->getAttribute('origNode');
		if ($origNode !== $this->originalNode) {
			return null;
		}

		$callable = $this->newNodeCallable;
		$newNode = $callable($node);
		if ($newNode instanceof VirtualNode) {
			throw new ShouldNotHappenException('Cannot print VirtualNode.');
		}

		return $newNode;
	}

}
