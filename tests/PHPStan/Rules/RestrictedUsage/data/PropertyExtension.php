<?php

namespace RestrictedUsage;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedPropertyReflection;
use PHPStan\Rules\RestrictedUsage\RestrictedPropertyUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;

class PropertyExtension implements RestrictedPropertyUsageExtension
{

	public function isRestrictedPropertyUsage(
		ExtendedPropertyReflection $propertyReflection,
		Scope $scope
	): ?RestrictedUsage
	{
		if ($propertyReflection->getName() !== 'foo') {
			return null;
		}

		return RestrictedUsage::create('Cannot access $foo', 'restrictedUsage.foo');
	}

}
