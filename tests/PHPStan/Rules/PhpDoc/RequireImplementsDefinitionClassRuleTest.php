<?php declare(strict_types = 1);

namespace PHPStan\Rules\PhpDoc;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<RequireImplementsDefinitionClassRule>
 */
class RequireImplementsDefinitionClassRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new RequireImplementsDefinitionClassRule();
	}

	public function testRule(): void
	{
		if (PHP_VERSION_ID < 80100) {
			$this->markTestSkipped('Test requires PHP 8.1.');
		}

		$this->analyse([__DIR__ . '/data/incompatible-require-implements.php'], [
			[
				'PHPDoc tag @phpstan-require-implements is only valid on trait.',
				40,
			],
			[
				'PHPDoc tag @phpstan-require-implements is only valid on trait.',
				45,
			],
		]);
	}

}
