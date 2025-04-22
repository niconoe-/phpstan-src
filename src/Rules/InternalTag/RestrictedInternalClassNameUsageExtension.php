<?php declare(strict_types = 1);

namespace PHPStan\Rules\InternalTag;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\ClassNameUsageLocation;
use PHPStan\Rules\RestrictedUsage\RestrictedClassNameUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;
use function sprintf;
use function strtolower;

final class RestrictedInternalClassNameUsageExtension implements RestrictedClassNameUsageExtension
{

	public function __construct(
		private RestrictedInternalUsageHelper $helper,
	)
	{
	}

	public function isRestrictedClassNameUsage(
		ClassReflection $classReflection,
		Scope $scope,
		ClassNameUsageLocation $location,
	): ?RestrictedUsage
	{
		if (!$classReflection->isInternal()) {
			return null;
		}

		if (!$this->helper->shouldBeReported($scope, $classReflection->getName())) {
			return null;
		}

		if ($location->value === ClassNameUsageLocation::STATIC_METHOD_CALL) {
			return null;
		}

		return RestrictedUsage::create(
			$location->createMessage(sprintf('internal %s %s', strtolower($classReflection->getClassTypeDescription()), $classReflection->getDisplayName())),
			$location->createIdentifier(sprintf('internal%s', $classReflection->getClassTypeDescription())),
		);
	}

}
