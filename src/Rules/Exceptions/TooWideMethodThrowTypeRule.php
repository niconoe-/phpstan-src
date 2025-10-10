<?php declare(strict_types = 1);

namespace PHPStan\Rules\Exceptions;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\MethodReturnStatementsNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use function sprintf;

/**
 * @implements Rule<MethodReturnStatementsNode>
 */
final class TooWideMethodThrowTypeRule implements Rule
{

	public function __construct(
		private TooWideThrowTypeCheck $check,
		private bool $checkProtectedAndPublicMethods,
	)
	{
	}

	public function getNodeType(): string
	{
		return MethodReturnStatementsNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$statementResult = $node->getStatementResult();
		$method = $node->getMethodReflection();
		$isFirstDeclaration = $method->getPrototype()->getDeclaringClass() === $method->getDeclaringClass();
		if (!$method->isPrivate()) {
			if (!$method->getDeclaringClass()->isFinal() && !$method->isFinal()->yes()) {
				if (!$this->checkProtectedAndPublicMethods) {
					return [];
				}

				if ($isFirstDeclaration) {
					return [];
				}
			}
		}

		$throwType = $method->getThrowType();
		if ($throwType === null) {
			return [];
		}

		$errors = [];
		foreach ($this->check->check($throwType, $statementResult->getThrowPoints()) as $throwClass) {
			$errors[] = RuleErrorBuilder::message(sprintf(
				'Method %s::%s() has %s in PHPDoc @throws tag but it\'s not thrown.',
				$method->getDeclaringClass()->getDisplayName(),
				$method->getName(),
				$throwClass,
			))
				->identifier('throws.unusedType')
				->build();
		}

		return $errors;
	}

}
