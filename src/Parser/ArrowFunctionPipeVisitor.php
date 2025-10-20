<?php declare(strict_types = 1);

namespace PHPStan\Parser;

use Override;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PHPStan\DependencyInjection\AutowiredService;

#[AutowiredService]
final class ArrowFunctionPipeVisitor extends NodeVisitorAbstract
{

	#[Override]
	public function enterNode(Node $node): ?Node
	{
		if (!$node instanceof Node\Expr\BinaryOp\Pipe) {
			return null;
		}

		if (!$node->right instanceof Node\Expr\ArrowFunction) {
			return null;
		}

		$node->right->setAttribute(ArrowFunctionArgVisitor::ATTRIBUTE_NAME, [
			new Node\Arg($node->left),
		]);

		return null;
	}

}
