<?php declare(strict_types = 1);

namespace PHPStan\Rules\Playground;

use PHPStan\Node\InClassMethodNode;
use PHPStan\Php\PhpVersion;
use PHPStan\Reflection\Php\PhpClassReflectionExtension;
use PHPStan\Rules\Methods\MethodParameterComparisonHelper;
use PHPStan\Rules\Methods\MethodSignatureRule;
use PHPStan\Rules\Methods\MethodVisibilityComparisonHelper;
use PHPStan\Rules\Methods\OverridingMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<PromoteParameterRule<InClassMethodNode>>
 */
class PromoteParameterRuleWithOriginalRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new PromoteParameterRule(
			new OverridingMethodRule(
				self::getContainer()->getByType(PhpVersion::class),
				self::getContainer()->getByType(MethodSignatureRule::class),
				true,
				self::getContainer()->getByType(MethodParameterComparisonHelper::class),
				self::getContainer()->getByType(MethodVisibilityComparisonHelper::class),
				self::getContainer()->getByType(PhpClassReflectionExtension::class),
				true,
			),
			self::getContainer(),
			InClassMethodNode::class,
			false,
			'checkMissingOverrideMethodAttribute',
		);
	}

	public function testRule(): void
	{
		if (PHP_VERSION_ID < 80300) {
			$this->markTestSkipped('Test requires PHP 8.3.');
		}

		$this->analyse([__DIR__ . '/data/promote-missing-override.php'], [
			[
				'Method PromoteMissingOverride\Bar::doFoo() overrides method PromoteMissingOverride\Foo::doFoo() but is missing the #[\Override] attribute.',
				18,
				'This error would be reported if the <fg=cyan>checkMissingOverrideMethodAttribute: true</> parameter was enabled in your <fg=cyan>%configurationFile%</>.',
			],
		]);
	}

}
