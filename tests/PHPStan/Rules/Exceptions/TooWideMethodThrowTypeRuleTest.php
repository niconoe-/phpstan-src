<?php declare(strict_types = 1);

namespace PHPStan\Rules\Exceptions;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhp;

/**
 * @extends RuleTestCase<TooWideMethodThrowTypeRule>
 */
class TooWideMethodThrowTypeRuleTest extends RuleTestCase
{

	private bool $implicitThrows = true;

	private bool $checkProtectedAndPublicMethods = true;

	protected function getRule(): Rule
	{
		return new TooWideMethodThrowTypeRule(new TooWideThrowTypeCheck($this->implicitThrows), $this->checkProtectedAndPublicMethods);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/too-wide-throws-method.php'], [
			[
				'Method TooWideThrowsMethod\Foo::doFoo3() has InvalidArgumentException in PHPDoc @throws tag but it\'s not thrown.',
				23,
			],
			[
				'Method TooWideThrowsMethod\Foo::doFoo4() has DomainException in PHPDoc @throws tag but it\'s not thrown.',
				29,
			],
			[
				'Method TooWideThrowsMethod\Foo::doFoo7() has DomainException in PHPDoc @throws tag but it\'s not thrown.',
				51,
			],
			[
				'Method TooWideThrowsMethod\Foo::doFoo8() has DomainException in PHPDoc @throws tag but it\'s not thrown.',
				60,
			],
			[
				'Method TooWideThrowsMethod\Foo::doFoo9() has DomainException in PHPDoc @throws tag but it\'s not thrown.',
				66,
			],
			[
				'Method TooWideThrowsMethod\ChildClass::doFoo() has LogicException in PHPDoc @throws tag but it\'s not thrown.',
				87,
			],
			[
				'Method TooWideThrowsMethod\ImmediatelyCalledCallback::doFoo2() has InvalidArgumentException in PHPDoc @throws tag but it\'s not thrown.',
				167,
			],
		]);
	}

	public function testBug6233(): void
	{
		$this->analyse([__DIR__ . '/data/bug-6233.php'], []);
	}

	public function testImmediatelyCalledArrowFunction(): void
	{
		$this->analyse([__DIR__ . '/data/immediately-called-arrow-function.php'], [
			[
				'Method ImmediatelyCalledArrowFunction\ImmediatelyCalledCallback::doFoo2() has InvalidArgumentException in PHPDoc @throws tag but it\'s not thrown.',
				19,
			],
		]);
	}

	#[RequiresPhp('>= 8.1')]
	public function testFirstClassCallable(): void
	{
		$this->analyse([__DIR__ . '/data/immediately-called-fcc.php'], []);
	}

	public static function dataRuleLookOnlyForExplicitThrowPoints(): iterable
	{
		yield [
			true,
			[],
		];
		yield [
			false,
			[
				[
					'Method TooWideThrowsExplicit\Foo::doFoo() has Exception in PHPDoc @throws tag but it\'s not thrown.',
					11,
				],
			],
		];
	}

	/**
	 * @param list<array{0: string, 1: int, 2?: string|null}> $errors
	 */
	#[DataProvider('dataRuleLookOnlyForExplicitThrowPoints')]
	public function testRuleLookOnlyForExplicitThrowPoints(bool $implicitThrows, array $errors): void
	{
		$this->implicitThrows = $implicitThrows;
		$this->analyse([__DIR__ . '/data/too-wide-throws-explicit.php'], $errors);
	}

	public static function dataAlwaysCheckFinal(): iterable
	{
		yield [
			false,
			[
				[
					'Method TooWideThrowTypeAlwaysCheckFinal\Foo::doFoo() has RuntimeException in PHPDoc @throws tag but it\'s not thrown.',
					14,
				],
				[
					'Method TooWideThrowTypeAlwaysCheckFinal\Baz::doBar() has RuntimeException in PHPDoc @throws tag but it\'s not thrown.',
					46,
				],
			],
		];

		yield [
			true,
			[
				[
					'Method TooWideThrowTypeAlwaysCheckFinal\Foo::doFoo() has RuntimeException in PHPDoc @throws tag but it\'s not thrown.',
					14,
				],
				[
					'Method TooWideThrowTypeAlwaysCheckFinal\Baz::doFoo() has RuntimeException in PHPDoc @throws tag but it\'s not thrown.',
					38,
				],
				[
					'Method TooWideThrowTypeAlwaysCheckFinal\Baz::doBar() has RuntimeException in PHPDoc @throws tag but it\'s not thrown.',
					46,
				],
			],
		];
	}

	/**
	 * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrors
	 */
	#[DataProvider('dataAlwaysCheckFinal')]
	public function testAlwaysCheckFinal(bool $checkProtectedAndPublicMethods, array $expectedErrors): void
	{
		$this->implicitThrows = true;
		$this->checkProtectedAndPublicMethods = $checkProtectedAndPublicMethods;
		$this->analyse([__DIR__ . '/data/too-wide-throw-type-always-check-final.php'], $expectedErrors);
	}

}
