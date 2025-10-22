<?php declare(strict_types = 1);

namespace PHPStan\Rules\Methods;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<CallToMethodStatementWithNoDiscardRule>
 */
class CallToMethodStatementWithNoDiscardRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new CallToMethodStatementWithNoDiscardRule(new RuleLevelHelper(self::createReflectionProvider(), true, false, true, false, false, false, true), new PhpVersion(PHP_VERSION_ID));
	}

	#[RequiresPhp('>= 8.5')]
	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/method-call-statement-result-discarded.php'], [
			[
				'Call to method MethodCallStatementResultDiscarded\ClassWithInstanceSideEffects::instanceMethod() on a separate line discards return value.',
				20,
			],
			[
				'Call to method MethodCallStatementResultDiscarded\ClassWithInstanceSideEffects::instanceMethod() on a separate line discards return value.',
				21,
			],
			[
				'Call to method MethodCallStatementResultDiscarded\ClassWithInstanceSideEffects::differentCase() on a separate line discards return value.',
				30,
			],
			[
				'Call to method MethodCallStatementResultDiscarded\Foo::canDiscard() in (void) cast but method allows discarding return value.',
				45,
			],
		]);
	}

}
