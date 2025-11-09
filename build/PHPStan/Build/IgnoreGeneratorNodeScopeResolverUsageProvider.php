<?php declare(strict_types = 1);

namespace PHPStan\Build;

use Override;
use PHPStan\Analyser\Generator\GeneratorNodeScopeResolver;
use ReflectionMethod;
use ShipMonk\PHPStan\DeadCode\Provider\ReflectionBasedMemberUsageProvider;
use ShipMonk\PHPStan\DeadCode\Provider\VirtualUsageData;

final class IgnoreGeneratorNodeScopeResolverUsageProvider extends ReflectionBasedMemberUsageProvider
{

	#[Override]
	protected function shouldMarkMethodAsUsed(ReflectionMethod $method): ?VirtualUsageData
	{
		if ($method->getDeclaringClass()->getName() === GeneratorNodeScopeResolver::class) {
			return VirtualUsageData::withNote('WIP');
		}

		return null;
	}

}
