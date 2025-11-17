<?php declare(strict_types = 1);

namespace PHPStan\Build;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ScopeGetTypeInGeneratorNamespaceRule>
 */
class ScopeGetTypeInGeneratorNamespaceRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ScopeGetTypeInGeneratorNamespaceRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/scope-get-type-generator-ns.php'], [
			[
				'Scope::getType() cannot be called in PHPStan\Analyser\Generator namespace.',
				27,
				'Use yield new TypeExprRequest instead.',
			],
			[
				'Scope::getType() cannot be called in PHPStan\Analyser\Generator namespace.',
				43,
				'Use yield new TypeExprRequest instead.',
			],
			[
				'Scope::getType() cannot be called in PHPStan\Analyser\Generator namespace.',
				48,
				'Use yield new TypeExprRequest instead.',
			],
			[
				'Scope::getNativeType() cannot be called in PHPStan\Analyser\Generator namespace.',
				49,
				'Use yield new TypeExprRequest instead.',
			],
			[
				'Scope::filterByTruthyValue() cannot be called in PHPStan\Analyser\Generator namespace.',
				59,
			],
			[
				'Scope::filterByFalseyValue() cannot be called in PHPStan\Analyser\Generator namespace.',
				60,
			],
			[
				'Scope::filterByTruthyValue() cannot be called in PHPStan\Analyser\Generator namespace.',
				65,
			],
			[
				'Scope::filterByFalseyValue() cannot be called in PHPStan\Analyser\Generator namespace.',
				66,
			],
		]);
	}

}
