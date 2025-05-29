<?php declare(strict_types = 1);

namespace PHPStan\Node;

use PhpParser\Node;
use PHPStan\Reflection\ClassReflection;

/**
 * @api
 */
final class InTraitNode extends Node\Stmt implements VirtualNode
{

	/**
	 * @param Node\Stmt[] $parserNodes
	 */
	public function __construct(private Node\Stmt\Trait_ $originalNode, private array $parserNodes, private ClassReflection $traitReflection, private ClassReflection $implementingClassReflection)
	{
		parent::__construct($originalNode->getAttributes());
	}

	public function getOriginalNode(): Node\Stmt\Trait_
	{
		return $this->originalNode;
	}

	/**
	 * @return Node\Stmt[]
	 */
	public function getParserNodes(): array
	{
		return $this->parserNodes;
	}

	public function getTraitReflection(): ClassReflection
	{
		return $this->traitReflection;
	}

	public function getImplementingClassReflection(): ClassReflection
	{
		return $this->implementingClassReflection;
	}

	public function getType(): string
	{
		return 'PHPStan_Stmt_InTraitNode';
	}

	/**
	 * @return string[]
	 */
	public function getSubNodeNames(): array
	{
		return [];
	}

}
