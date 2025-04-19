<?php

namespace RestrictedUsage;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\RestrictedUsage\RestrictedMethodUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;

class MethodExtension implements RestrictedMethodUsageExtension
{

	public function isRestrictedMethodUsage(
		ExtendedMethodReflection $methodReflection,
		Scope $scope
	): ?RestrictedUsage
	{
		if ($methodReflection->getName() !== 'doFoo') {
			return null;
		}

		return RestrictedUsage::create('Cannot call doFoo', 'restrictedUsage.doFoo');
	}

}
