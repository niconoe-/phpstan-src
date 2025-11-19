<?php declare(strict_types = 1);

namespace PHPStan\Rules\Classes;

use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\ClassForbiddenNameCheck;
use PHPStan\Rules\ClassNameCheck;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ExistingClassInInstanceOfRule>
 */
class ExistingClassInInstanceOfRuleWithoutNarrowMethodScopeTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflectionProvider = self::createReflectionProvider();
		return new ExistingClassInInstanceOfRule(
			$reflectionProvider,
			new ClassNameCheck(
				new ClassCaseSensitivityCheck($reflectionProvider, true),
				new ClassForbiddenNameCheck(self::getContainer()),
				$reflectionProvider,
				self::getContainer(),
			),
			true,
			true,
		);
	}

	public function testRememberClassExistsFromConstructorDisabled(): void
	{
		$this->analyse([__DIR__ . '/data/remember-class-exists-from-constructor.php'], [
			[
				'Class SomeUnknownClass not found.',
				19,
				'Learn more at https://phpstan.org/user-guide/discovering-symbols',
			],
			[
				'Class SomeUnknownInterface not found.',
				38,
				'Learn more at https://phpstan.org/user-guide/discovering-symbols',
			],
		]);
	}

}
