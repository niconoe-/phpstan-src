<?php declare(strict_types = 1);

namespace PHPStan\Rules\RestrictedUsage;

use PHPStan\Rules\Rule as TRule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RestrictedStaticPropertyUsageRule>
 */
class RestrictedStaticPropertyUsageRuleTest extends RuleTestCase
{

	protected function getRule(): TRule
	{
		$reflectionProvider = $this->createReflectionProvider();
		return new RestrictedStaticPropertyUsageRule(
			self::getContainer(),
			$reflectionProvider,
			new RuleLevelHelper($reflectionProvider, true, false, true, true, true, false, true),
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/restricted-property.php'], [
			[
				'Cannot access $foo',
				34,
			],
		]);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/restricted-usage.neon',
			...parent::getAdditionalConfigFiles(),
		];
	}

}
