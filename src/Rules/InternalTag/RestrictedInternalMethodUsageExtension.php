<?php declare(strict_types = 1);

namespace PHPStan\Rules\InternalTag;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Rules\RestrictedUsage\RestrictedMethodUsageExtension;
use PHPStan\Rules\RestrictedUsage\RestrictedUsage;
use function array_slice;
use function explode;
use function sprintf;
use function str_starts_with;

final class RestrictedInternalMethodUsageExtension implements RestrictedMethodUsageExtension
{

	public function isRestrictedMethodUsage(
		ExtendedMethodReflection $methodReflection,
		Scope $scope,
	): ?RestrictedUsage
	{
		if (!$methodReflection->isInternal()->yes()) {
			return null;
		}

		$currentNamespace = $scope->getNamespace();
		$declaringClassName = $methodReflection->getDeclaringClass()->getName();
		$namespace = array_slice(explode('\\', $declaringClassName), 0, -1)[0] ?? null;
		if ($currentNamespace === null) {
			return $this->buildRestrictedUsage($methodReflection, $namespace);
		}

		$currentNamespace = explode('\\', $currentNamespace)[0];
		if (str_starts_with($namespace . '\\', $currentNamespace . '\\')) {
			return null;
		}

		return $this->buildRestrictedUsage($methodReflection, $namespace);
	}

	private function buildRestrictedUsage(ExtendedMethodReflection $methodReflection, ?string $namespace): RestrictedUsage
	{
		if ($namespace === null) {
			return RestrictedUsage::create(
				sprintf('Call to internal method %s::%s().', $methodReflection->getDeclaringClass()->getDisplayName(), $methodReflection->getName()),
				'method.internal',
			);
		}
		return RestrictedUsage::create(
			sprintf('Call to internal method %s::%s() from outside its root namespace %s.', $methodReflection->getDeclaringClass()->getDisplayName(), $methodReflection->getName(), $namespace),
			'method.internal',
		);
	}

}
