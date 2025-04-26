<?php

namespace RestrictedUsage;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Rules\RestrictedUsage\RestrictedClassConstantUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;

class ClassConstantExtension implements RestrictedClassConstantUsageExtension
{

	public function isRestrictedClassConstantUsage(
		ClassConstantReflection $constantReflection,
		Scope $scope
	): ?RestrictedUsage
	{
		if ($constantReflection->getName() !== 'FOO') {
			return null;
		}

		return RestrictedUsage::create('Cannot access FOO', 'restrictedUsage.foo');
	}

}
