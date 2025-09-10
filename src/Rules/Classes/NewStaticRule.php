<?php declare(strict_types = 1);

namespace PHPStan\Rules\Classes;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\DependencyInjection\RegisteredRule;
use PHPStan\Php\PhpVersion;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\Dummy\DummyConstructorReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\TrinaryLogic;
use function strtolower;

/**
 * @implements Rule<Node\Expr\New_>
 */
#[RegisteredRule(level: 0)]
final class NewStaticRule implements Rule
{

	public function __construct(
		private PhpVersion $phpVersion,
	)
	{
	}

	public function getNodeType(): string
	{
		return Node\Expr\New_::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->class instanceof Node\Name) {
			return [];
		}

		if (!$scope->isInClass()) {
			return [];
		}

		if (strtolower($node->class->toString()) !== 'static') {
			return [];
		}

		$classReflection = $scope->getClassReflection();
		if ($classReflection->isFinal()) {
			return [];
		}

		$messages = [
			RuleErrorBuilder::message('Unsafe usage of new static().')
				->identifier('new.static')
				->tip('See: https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static')
				->build(),
		];
		if ($classReflection->hasConsistentConstructor()) {
			return [];
		}
		if (!$classReflection->hasConstructor()) {
			return $messages;
		}

		$constructor = $classReflection->getConstructor();
		if ($constructor->getPrototype()->getDeclaringClass()->isInterface()) {
			return [];
		}

		foreach ($classReflection->getImmediateInterfaces() as $interface) {
			if ($interface->hasConstructor()) {
				return [];
			}
		}

		if ($constructor->isFinal()->yes()) {
			return [];
		}

		$parent = $classReflection->getParentClass();
		if ($parent !== null) {
			$parentConstructor = $this->findConsistentParentConstructor($parent);
			if ($parentConstructor !== null) {
				return [];
			}
		}

		if ($constructor instanceof PhpMethodReflection) {
			$prototype = $constructor->getPrototype();
			if ($prototype->isAbstract()) {
				return [];
			}
		}

		if (
			$this->phpVersion->supportsAbstractTraitMethods()
			&& $scope->isInTrait()
		) {
			$traitReflection = $scope->getTraitReflection();
			if ($traitReflection->hasConstructor()) {
				$isAbstract = $traitReflection->getConstructor()->isAbstract();
				if ($isAbstract === true || ($isAbstract instanceof TrinaryLogic && $isAbstract->yes())) {
					return [];
				}
			}
		}

		return $messages;
	}

	private function findConsistentParentConstructor(ClassReflection $classReflection): ?ExtendedMethodReflection
	{
		if ($classReflection->hasConsistentConstructor()) {
			if ($classReflection->hasConstructor()) {
				return $classReflection->getConstructor();
			}

			return new DummyConstructorReflection($classReflection);
		}

		$parent = $classReflection->getParentClass();
		if ($parent === null) {
			return null;
		}

		return $this->findConsistentParentConstructor($parent);
	}

}
