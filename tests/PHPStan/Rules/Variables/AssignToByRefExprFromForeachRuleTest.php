<?php declare(strict_types = 1);

namespace PHPStan\Rules\Variables;

use PHPStan\Node\Printer\ExprPrinter;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AssignToByRefExprFromForeachRule>
 */
class AssignToByRefExprFromForeachRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new AssignToByRefExprFromForeachRule(
			self::getContainer()->getByType(ExprPrinter::class),
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/assign-to-by-ref-expr-from-foreach.php'], [
			[
				'Assign to $item overwrites the last element from array.',
				26,
				'Unset it right after foreach to avoid this problem.',
			],
			[
				'Assign to $item overwrites the last element from array.',
				50,
				'Unset it right after foreach to avoid this problem.',
			],
			[
				'Assign to $item overwrites the last element from array.',
				51,
				'Unset it right after foreach to avoid this problem.',
			],
			[
				'Assign to $item overwrites the last element from array.',
				62,
				'Unset it right after foreach to avoid this problem.',
			],
		]);
	}

}
