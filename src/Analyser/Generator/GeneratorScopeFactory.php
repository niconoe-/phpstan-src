<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Analyser\ScopeContext;
use PHPStan\DependencyInjection\AutowiredService;

#[AutowiredService]
final class GeneratorScopeFactory
{

	public function __construct(
		private InternalGeneratorScopeFactory $internalScopeFactory,
	)
	{
	}

	public function create(ScopeContext $context): GeneratorScope
	{
		return $this->internalScopeFactory->create($context);
	}

}
