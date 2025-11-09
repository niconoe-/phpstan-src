<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhp;

#[RequiresPhp('>= 8.1')]
class GeneratorNodeScopeResolverTest extends TypeInferenceTestCase
{

	public static function dataFileAsserts(): iterable
	{
		yield from self::gatherAssertTypes(__DIR__ . '/data/gnsr.php');
	}

	/**
	 * @param mixed ...$args
	 */
	#[DataProvider('dataFileAsserts')]
	public function testFileAsserts(
		string $assertType,
		string $file,
		...$args,
	): void
	{
		$this->assertFileAsserts($assertType, $file, ...$args);
	}

	protected static function createNodeScopeResolver(): GeneratorNodeScopeResolver
	{
		return new GeneratorNodeScopeResolver(self::createReflectionProvider());
	}

	/**
	 * @param string[] $dynamicConstantNames
	 */
	protected static function createScope(
		string $file,
		array $dynamicConstantNames = [],
	): GeneratorScope
	{
		return new GeneratorScope([]);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../../conf/bleedingEdge.neon',
		];
	}

}
