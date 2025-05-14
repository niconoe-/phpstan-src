<?php

namespace RestrictedUsage;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\RestrictedUsage\RestrictedMethodUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;
use function sprintf;

class MethodExtension implements RestrictedMethodUsageExtension
{

	public function isRestrictedMethodUsage(
		ExtendedMethodReflection $methodReflection,
		Scope $scope
	): ?RestrictedUsage
	{
		if ($methodReflection->getName() !== 'doFoo' && $methodReflection->getName() !== '__toString') {
			return null;
		}

		return RestrictedUsage::create(sprintf('Cannot call %s', $methodReflection->getName()), 'restrictedUsage.doFoo');
	}

}
