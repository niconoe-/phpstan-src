<?php declare(strict_types = 1);

namespace PHPStan\Rules\Operators;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<BacktickRule>
 */
class BacktickRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new BacktickRule(new PhpVersion(PHP_VERSION_ID));
	}

	public function testRule(): void
	{
		$errors = [];
		if (PHP_VERSION_ID >= 80500) {
			$errors = [
				[
					'Backtick operator is deprecated in PHP 8.5. Use shell_exec() function call instead.',
					4,
				],
			];
		}
		$this->analyse([__DIR__ . '/data/backtick.php'], $errors);
	}

}
