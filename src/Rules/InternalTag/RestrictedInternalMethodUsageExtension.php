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
use function strtolower;

final class RestrictedInternalMethodUsageExtension implements RestrictedMethodUsageExtension
{

	public function isRestrictedMethodUsage(
		ExtendedMethodReflection $methodReflection,
		Scope $scope,
	): ?RestrictedUsage
	{
		$isMethodInternal = $methodReflection->isInternal()->yes();
		$isDeclaringClassInternal = $methodReflection->getDeclaringClass()->isInternal();
		if (!$isMethodInternal && !$isDeclaringClassInternal) {
			return null;
		}

		$currentNamespace = $scope->getNamespace();
		$declaringClassName = $methodReflection->getDeclaringClass()->getName();
		$namespace = array_slice(explode('\\', $declaringClassName), 0, -1)[0] ?? null;
		if ($currentNamespace === null) {
			return $this->buildRestrictedUsage($methodReflection, $namespace, $isMethodInternal);
		}

		$currentNamespace = explode('\\', $currentNamespace)[0];
		if (str_starts_with($namespace . '\\', $currentNamespace . '\\')) {
			return null;
		}

		return $this->buildRestrictedUsage($methodReflection, $namespace, $isMethodInternal);
	}

	private function buildRestrictedUsage(ExtendedMethodReflection $methodReflection, ?string $namespace, bool $isMethodInternal): RestrictedUsage
	{
		if ($namespace === null) {
			if (!$isMethodInternal) {
				return RestrictedUsage::create(
					sprintf(
						'Call to method %s() of internal %s %s.',
						$methodReflection->getName(),
						strtolower($methodReflection->getDeclaringClass()->getClassTypeDescription()),
						$methodReflection->getDeclaringClass()->getDisplayName(),
					),
					sprintf('method.internal%s', $methodReflection->getDeclaringClass()->getClassTypeDescription()),
				);
			}

			return RestrictedUsage::create(
				sprintf('Call to internal method %s::%s().', $methodReflection->getDeclaringClass()->getDisplayName(), $methodReflection->getName()),
				'method.internal',
			);
		}

		if (!$isMethodInternal) {
			return RestrictedUsage::create(
				sprintf(
					'Call to method %s() of internal %s %s from outside its root namespace %s.',
					$methodReflection->getName(),
					strtolower($methodReflection->getDeclaringClass()->getClassTypeDescription()),
					$methodReflection->getDeclaringClass()->getDisplayName(),
					$namespace,
				),
				sprintf('method.internal%s', $methodReflection->getDeclaringClass()->getClassTypeDescription()),
			);
		}

		return RestrictedUsage::create(
			sprintf('Call to internal method %s::%s() from outside its root namespace %s.', $methodReflection->getDeclaringClass()->getDisplayName(), $methodReflection->getName(), $namespace),
			'method.internal',
		);
	}

}
