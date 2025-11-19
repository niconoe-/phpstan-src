<?php declare(strict_types = 1);

namespace PHPStan\Rules\Properties;

use PHPStan\Reflection\ConstructorsHelper;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\RequiresPhp;
use function array_merge;

/**
 * @extends RuleTestCase<MissingReadOnlyPropertyAssignRule>
 */
class MissingReadOnlyPropertyAssignRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new MissingReadOnlyPropertyAssignRule(
			new ConstructorsHelper(
				self::getContainer(),
				[],
			),
		);
	}

	#[RequiresPhp('>= 8.1')]
	public function testBug10048(): void
	{
		$this->analyse([__DIR__ . '/data/bug-10048.php'], []);
	}

	#[RequiresPhp('>= 8.1')]
	public function testBug11828(): void
	{
		$this->analyse([__DIR__ . '/data/bug-11828.php'], []);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return array_merge(
			parent::getAdditionalConfigFiles(),
			[
				__DIR__ . '/../../../../src/Testing/narrowMethodScopeFromConstructor.neon',
			],
		);
	}

}
