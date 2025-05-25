<?php declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

use Nette\DI\CompilerExtension;
use olvlvl\ComposerAttributeCollector\Attributes;
use PHPStan\Broker\BrokerFactory;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use ReflectionClass;

final class AutowiredAttributeServicesExtension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		require_once __DIR__ . '/../../vendor/attributes.php';
		$autowiredServiceClasses = Attributes::findTargetClasses(AutowiredService::class);
		$builder = $this->getContainerBuilder();

		$interfaceToTag = [
			DynamicFunctionReturnTypeExtension::class => BrokerFactory::DYNAMIC_FUNCTION_RETURN_TYPE_EXTENSION_TAG,
		];

		foreach ($autowiredServiceClasses as $class) {
			$reflection = new ReflectionClass($class->name);

			$definition = $builder->addDefinition(null)
				->setType($class->name)
				->setAutowired();

			foreach ($interfaceToTag as $interface => $tag) {
				if (!$reflection->implementsInterface($interface)) {
					continue;
				}

				$definition->addTag($tag);
			}
		}
	}

}
