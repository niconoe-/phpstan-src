<?php declare(strict_types = 1);

namespace PHPStan\Testing;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @extends RuleTestCase<Rule<FuncCall>>
 */
class NonexistentAnalysedClassRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new /** @implements Rule<FuncCall> */class implements Rule {

			public function getNodeType(): string
			{
				return FuncCall::class;
			}

			public function processNode(Node $node, Scope $scope): array
			{
				return [];
			}

		};
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/../../notAutoloaded/nonexistentClasses.php'], [
			[
				'Class NamespaceForNonexistentClasses\Foo not found in ReflectionProvider. Configure "autoload-dev" section in composer.json to include your tests directory.',
				7,
			],
			[
				'Trait NamespaceForNonexistentClasses\FooTrait not found in ReflectionProvider. Configure "autoload-dev" section in composer.json to include your tests directory.',
				17,
			],
		]);
	}

}
