<?php declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

use Nette\DI\CompilerExtension;
use olvlvl\ComposerAttributeCollector\Attributes;
use ReflectionClass;

final class AutowiredAttributeServicesExtension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		require_once __DIR__ . '/../../vendor/attributes.php';
		$autowiredServiceClasses = Attributes::findTargetClasses(AutowiredService::class);
		$builder = $this->getContainerBuilder();

		foreach ($autowiredServiceClasses as $class) {
			$reflection = new ReflectionClass($class->name);

			$definition = $builder->addDefinition(null)
				->setType($class->name)
				->setAutowired();

			foreach (ValidateServiceTagsExtension::INTERFACE_TAG_MAPPING as $interface => $tag) {
				if (!$reflection->implementsInterface($interface)) {
					continue;
				}

				$definition->addTag($tag);
			}
		}
	}

}
