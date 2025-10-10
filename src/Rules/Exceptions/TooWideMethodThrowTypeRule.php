<?php declare(strict_types = 1);

namespace PHPStan\Rules\Exceptions;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\MethodReturnStatementsNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\FileTypeMapper;
use function sprintf;

/**
 * @implements Rule<MethodReturnStatementsNode>
 */
final class TooWideMethodThrowTypeRule implements Rule
{

	public function __construct(
		private FileTypeMapper $fileTypeMapper,
		private TooWideThrowTypeCheck $check,
		private bool $checkProtectedAndPublicMethods,
		private bool $tooWideImplicitThrows,
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

		$unusedThrowClasses = $this->check->check($throwType, $statementResult->getThrowPoints());
		if (!$this->tooWideImplicitThrows) {
			$docComment = $node->getDocComment();
			if ($docComment === null) {
				return [];
			}

			$classReflection = $node->getClassReflection();
			$resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
				$scope->getFile(),
				$classReflection->getName(),
				$scope->isInTrait() ? $scope->getTraitReflection()->getName() : null,
				$method->getName(),
				$docComment->getText(),
			);

			if ($resolvedPhpDoc->getThrowsTag() === null) {
				return [];
			}

			$explicitThrowType = $resolvedPhpDoc->getThrowsTag()->getType();
			if ($explicitThrowType->equals($throwType)) {
				return [];
			}
		}

		$errors = [];
		foreach ($unusedThrowClasses as $throwClass) {
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
