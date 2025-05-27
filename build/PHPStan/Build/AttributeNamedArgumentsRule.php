<?php declare(strict_types = 1);

namespace PHPStan\Build;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use function sprintf;

/**
 * @implements Rule<Attribute>
 */
final class AttributeNamedArgumentsRule implements Rule
{

	public function getNodeType(): string
	{
		return Attribute::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		foreach ($node->args as $arg) {
			if ($arg->name !== null) {
				continue;
			}

			return [
				RuleErrorBuilder::message(sprintf('Attribute %s is not using named arguments.', $node->name->toString()))
					->identifier('phpstan.attributeWithoutNamedArguments')
					->nonIgnorable()
					->build(),
			];
		}

		return [];
	}

}
