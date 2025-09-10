<?php declare(strict_types = 1);

namespace PHPStan\Rules\Methods;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ConsistentConstructorDeclarationRule>
 */
class ConsistentConstructorDeclarationRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ConsistentConstructorDeclarationRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/consistent-constructor-declaration.php'], [
			[
				'Private constructor cannot be enforced as consistent for child classes.',
				31,
			],
		]);
	}

}
