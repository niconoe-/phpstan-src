<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Analyser\ScopeContext;
use PHPStan\DependencyInjection\AutowiredService;

#[AutowiredService(as: InternalGeneratorScopeFactory::class)]
final class LazyInternalGeneratorScopeFactory implements InternalGeneratorScopeFactory
{

	public function create(ScopeContext $context, array $expressionTypes = []): GeneratorScope
	{
		return new GeneratorScope(
			$this,
			$context,
			$expressionTypes,
		);
	}

}
