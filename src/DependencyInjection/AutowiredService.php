<?php declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

use Attribute;

/**
 * Registers a service in the DI container.
 *
 * Auto-adds service extension tags based on implemented interfaces.
 *
 * Works thanks to https://github.com/ondrejmirtes/composer-attribute-collector
 * and AutowiredAttributeServicesExtension.
 */
#[Attribute(flags: Attribute::TARGET_CLASS)]
final class AutowiredService
{

	public function __construct(public ?string $name = null)
	{
	}

}
