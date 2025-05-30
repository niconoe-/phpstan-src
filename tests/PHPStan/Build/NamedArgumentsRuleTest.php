<?php declare(strict_types = 1);

namespace PHPStan\Build;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<NamedArgumentsRule>
 */
class NamedArgumentsRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new NamedArgumentsRule($this->createReflectionProvider(), new PhpVersion(PHP_VERSION_ID));
	}

	public function testRule(): void
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Test requires PHP 8.0.');
		}

		$this->analyse([__DIR__ . '/data/named-arguments.php'], [
			[
				'You\'re passing a non-default value Exception to parameter $previous but previous argument is passing default value to its parameter ($code). You can skip it and use named argument for $previous instead.',
				14,
			],
			[
				'Named argument $code can be omitted, type 0 is the same as the default value.',
				16,
			],
			[
				'You\'re passing a non-default value Exception to parameter $previous but previous arguments are passing default values to their parameters ($message, $code). You can skip them and use named argument for $previous instead.',
				20,
			],
			[
				'You\'re passing a non-default value 3 to parameter $yetAnother but previous argument is passing default value to its parameter ($another). You can skip it and use named argument for $yetAnother instead.',
				41,
			],
		]);
	}

	public function testNoFix(): void
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Test requires PHP 8.0.');
		}

		$this->fix(
			__DIR__ . '/data/named-arguments-no-errors.php',
			__DIR__ . '/data/named-arguments-no-errors.php',
		);
	}

	public function testFix(): void
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Test requires PHP 8.0.');
		}

		$this->fix(
			__DIR__ . '/data/named-arguments.php',
			__DIR__ . '/data/named-arguments.php.fixed',
		);
	}

	public function testFixFileWithMatch(): void
	{
		if (PHP_VERSION_ID < 80000) {
			$this->markTestSkipped('Test requires PHP 8.0.');
		}

		$this->fix(
			__DIR__ . '/data/named-arguments-match.php',
			__DIR__ . '/data/named-arguments-match.php.fixed',
		);
	}

}
