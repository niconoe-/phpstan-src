<?php declare(strict_types = 1);

namespace PHPStan\Rules\Methods;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<CallToStaticMethodStatementWithNoDiscardRule>
 */
class CallToStaticMethodStatementWithNoDiscardRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$reflectionProvider = self::createReflectionProvider();
		return new CallToStaticMethodStatementWithNoDiscardRule(
			new RuleLevelHelper($reflectionProvider, true, false, true, false, false, false, true),
			$reflectionProvider,
			new PhpVersion(PHP_VERSION_ID),
		);
	}

	#[RequiresPhp('>= 8.5')]
	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/static-method-call-statement-result-discarded.php'], [
			[
				'Call to static method MethodCallStatementResultDiscarded\ClassWithStaticSideEffects::staticMethod() on a separate line discards return value.',
				19,
			],
			[
				'Call to static method MethodCallStatementResultDiscarded\ClassWithStaticSideEffects::differentCase() on a separate line discards return value.',
				27,
			],
			[
				'Call to static method MethodCallStatementResultDiscarded\Foo::canDiscard() in (void) cast but method allows discarding return value.',
				41,
			],
		]);
	}

}
