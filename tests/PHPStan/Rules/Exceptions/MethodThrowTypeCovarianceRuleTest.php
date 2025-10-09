<?php declare(strict_types = 1);

namespace PHPStan\Rules\Exceptions;

use PHPStan\Reflection\Php\PhpClassReflectionExtension;
use PHPStan\Rules\Methods\ParentMethodHelper;
use PHPStan\Rules\Rule as TRule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @extends RuleTestCase<MethodThrowTypeCovarianceRule>
 */
class MethodThrowTypeCovarianceRuleTest extends RuleTestCase
{

	private bool $implicitThrows;

	protected function getRule(): TRule
	{
		return new MethodThrowTypeCovarianceRule(new ParentMethodHelper(
			self::getContainer()->getByType(PhpClassReflectionExtension::class),
		), $this->implicitThrows);
	}

	public static function dataRule(): iterable
	{
		yield [
			false,
			[
				[
					'Method MethodThrowTypeCovariance\Baz::noThrowType() should not throw Exception because parent method MethodThrowTypeCovariance\Foo::noThrowType() does not have PHPDoc tag @throws.',
					47,
				],
				[
					'PHPDoc tag @throws type RuntimeException of method MethodThrowTypeCovariance\Baz::logicException() should be covariant with PHPDoc @throws type LogicException of method MethodThrowTypeCovariance\Foo::logicException().',
					56,
				],
				[
					'Method MethodThrowTypeCovariance\VoidInChild3::throwVoid() should not throw RuntimeException because parent method MethodThrowTypeCovariance\VoidInParent::throwVoid() has PHPDoc tag @throws void.',
					122,
				],
			],
		];

		yield [
			true,
			[
				[
					'PHPDoc tag @throws type RuntimeException of method MethodThrowTypeCovariance\Baz::logicException() should be covariant with PHPDoc @throws type LogicException of method MethodThrowTypeCovariance\Foo::logicException().',
					56,
				],
				[
					'Method MethodThrowTypeCovariance\VoidInChild3::throwVoid() should not throw RuntimeException because parent method MethodThrowTypeCovariance\VoidInParent::throwVoid() has PHPDoc tag @throws void.',
					122,
				],
			],
		];
	}

	/**
	 * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrors
	 */
	#[DataProvider('dataRule')]
	public function testRule(bool $implicitThrows, array $expectedErrors): void
	{
		$this->implicitThrows = $implicitThrows;
		$this->analyse([__DIR__ . '/data/method-throw-type-covariance.php'], $expectedErrors);
	}

}
