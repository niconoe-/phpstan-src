<?php declare(strict_types = 1);

namespace PHPStan\Rules\Classes;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<NewStaticInAbstractClassStaticMethodRule>
 */
class NewStaticInAbstractClassStaticMethodRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return self::getContainer()->getByType(NewStaticInAbstractClassStaticMethodRule::class);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/new-static-in-abstract-class-static-method.php'], [
			[
				'Unsafe usage of new static() in abstract class NewStaticInAbstractClassStaticMethod\Bar in static method staticDoFoo().',
				30,
				'Direct call to NewStaticInAbstractClassStaticMethod\Bar::staticDoFoo() would crash because an abstract class cannot be instantiated.',
			],
		]);
	}

}
