<?php declare(strict_types = 1);

namespace PHPStan\Analyser;

use PhpParser\Node;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\DependencyInjection\AutowiredService;

/**
 * @api
 */
#[AutowiredService]
final class ScopeFactory
{

	public function __construct(
		private InternalScopeFactoryFactory $internalScopeFactoryFactory,
		private bool $createGeneratorScope = false,
	)
	{
	}

	/**
	 * @internal
	 */
	public function getInternalScopeFactoryFactory(): InternalScopeFactoryFactory
	{
		return $this->internalScopeFactoryFactory;
	}

	/**
	 * @param callable(Node $node, Scope $scope): void $nodeCallback
	 */
	public function create(ScopeContext $context, ?callable $nodeCallback = null): MutatingScope|GeneratorScope
	{
		if ($this->createGeneratorScope) {
			return new GeneratorScope([]);
		}

		return $this->internalScopeFactoryFactory->create($nodeCallback)->create($context);
	}

}
