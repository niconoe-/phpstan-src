<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Fiber;
use NoDiscard;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PHPStan\Analyser\NodeCallbackInvoker;
use PHPStan\Analyser\Scope;
use PHPStan\Php\PhpVersions;
use PHPStan\Reflection\Assertions;
use PHPStan\Reflection\ClassConstantReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\ExtendedPropertyReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpFunctionFromParserNodeReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

final class GeneratorScope implements Scope, NodeCallbackInvoker
{

	/**
	 * @param array<string, Type> $expressionTypes
	 */
	public function __construct(
		public array $expressionTypes,
	)
	{
	}

	#[NoDiscard]
	public function assignVariable(string $variableName, Type $type): self
	{
		$exprString = '$' . $variableName;

		$expressionTypes = $this->expressionTypes;
		$expressionTypes[$exprString] = $type;

		return new self($expressionTypes);
	}

	public function enterNamespace(string $namespaceName): self
	{
		// TODO: Implement enterNamespace() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function enterClass(ClassReflection $classReflection): self
	{
		// TODO: Implement enterClass() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	/**
	 * @param Type[] $phpDocParameterTypes
	 * @param Type[] $parameterOutTypes
	 * @param array<string, bool> $immediatelyInvokedCallableParameters
	 * @param array<string, Type> $phpDocClosureThisTypeParameters
	 */
	public function enterClassMethod(
		Node\Stmt\ClassMethod $classMethod,
		TemplateTypeMap $templateTypeMap,
		array $phpDocParameterTypes,
		?Type $phpDocReturnType,
		?Type $throwType,
		?string $deprecatedDescription,
		bool $isDeprecated,
		bool $isInternal,
		bool $isFinal,
		?bool $isPure = null,
		bool $acceptsNamedArguments = true,
		?Assertions $asserts = null,
		?Type $selfOutType = null,
		?string $phpDocComment = null,
		array $parameterOutTypes = [],
		array $immediatelyInvokedCallableParameters = [],
		array $phpDocClosureThisTypeParameters = [],
		bool $isConstructor = false,
	): self
	{
		// TODO: Implement enterClassMethod() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function generalizeWith(self $otherScope): self
	{
		// TODO: Implement generalizeWith() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInClass(): bool
	{
		// TODO: Implement isInClass() method.
		return false;
	}

	public function getClassReflection(): ?ClassReflection
	{
		// TODO: Implement getClassReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function canAccessProperty(PropertyReflection $propertyReflection): bool
	{
		// TODO: Implement canAccessProperty() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function canReadProperty(ExtendedPropertyReflection $propertyReflection): bool
	{
		// TODO: Implement canReadProperty() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function canWriteProperty(ExtendedPropertyReflection $propertyReflection): bool
	{
		// TODO: Implement canWriteProperty() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function canCallMethod(MethodReflection $methodReflection): bool
	{
		// TODO: Implement canCallMethod() method.
		return true;
	}

	public function canAccessConstant(ClassConstantReflection $constantReflection): bool
	{
		// TODO: Implement canAccessConstant() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getNamespace(): ?string
	{
		// TODO: Implement getNamespace() method.
		return null;
	}

	public function getFile(): string
	{
		// TODO: Implement getFile() method.
		return 'foo.php';
	}

	public function getFileDescription(): string
	{
		// TODO: Implement getFileDescription() method.
		return 'foo.php';
	}

	public function isDeclareStrictTypes(): bool
	{
		// TODO: Implement isDeclareStrictTypes() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInTrait(): bool
	{
		// TODO: Implement isInTrait() method.
		return false;
	}

	public function getTraitReflection(): ?ClassReflection
	{
		// TODO: Implement getTraitReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getFunction(): ?PhpFunctionFromParserNodeReflection
	{
		// TODO: Implement getFunction() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getFunctionName(): ?string
	{
		// TODO: Implement getFunctionName() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getParentScope(): ?self
	{
		// TODO: Implement getParentScope() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function hasVariableType(string $variableName): TrinaryLogic
	{
		// TODO: Implement hasVariableType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getVariableType(string $variableName): Type
	{
		// TODO: Implement getVariableType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function canAnyVariableExist(): bool
	{
		// TODO: Implement canAnyVariableExist() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getDefinedVariables(): array
	{
		// TODO: Implement getDefinedVariables() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getMaybeDefinedVariables(): array
	{
		// TODO: Implement getMaybeDefinedVariables() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function hasConstant(Name $name): bool
	{
		// TODO: Implement hasConstant() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getPropertyReflection(Type $typeWithProperty, string $propertyName): ?ExtendedPropertyReflection
	{
		// TODO: Implement getPropertyReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getInstancePropertyReflection(Type $typeWithProperty, string $propertyName): ?ExtendedPropertyReflection
	{
		// TODO: Implement getInstancePropertyReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getStaticPropertyReflection(Type $typeWithProperty, string $propertyName): ?ExtendedPropertyReflection
	{
		// TODO: Implement getStaticPropertyReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getMethodReflection(Type $typeWithMethod, string $methodName): ?ExtendedMethodReflection
	{
		// TODO: Implement getMethodReflection() method.
		return null;
	}

	public function getConstantReflection(Type $typeWithConstant, string $constantName): ?ClassConstantReflection
	{
		// TODO: Implement getConstantReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getConstantExplicitTypeFromConfig(string $constantName, Type $constantType): Type
	{
		// TODO: Implement getConstantExplicitTypeFromConfig() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getIterableKeyType(Type $iteratee): Type
	{
		// TODO: Implement getIterableKeyType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getIterableValueType(Type $iteratee): Type
	{
		// TODO: Implement getIterableValueType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInAnonymousFunction(): bool
	{
		// TODO: Implement isInAnonymousFunction() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getAnonymousFunctionReflection(): ?ClosureType
	{
		// TODO: Implement getAnonymousFunctionReflection() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getAnonymousFunctionReturnType(): ?Type
	{
		// TODO: Implement getAnonymousFunctionReturnType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getType(Expr $node): Type
	{
		return Fiber::suspend(new ExprAnalysisRequest($node, $this))->type;
	}

	public function getNativeType(Expr $expr): Type
	{
		// TODO: Implement getNativeType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getKeepVoidType(Expr $node): Type
	{
		// TODO: Implement getKeepVoidType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function resolveName(Name $name): string
	{
		// TODO: Implement resolveName() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function resolveTypeByName(Name $name): TypeWithClassName
	{
		// TODO: Implement resolveTypeByName() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	/**
	 * @param mixed $value
	 */
	public function getTypeFromValue($value): Type
	{
		// TODO: Implement getTypeFromValue() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function hasExpressionType(Expr $node): TrinaryLogic
	{
		// TODO: Implement hasExpressionType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInClassExists(string $className): bool
	{
		// TODO: Implement isInClassExists() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInFunctionExists(string $functionName): bool
	{
		// TODO: Implement isInFunctionExists() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInClosureBind(): bool
	{
		// TODO: Implement isInClosureBind() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getFunctionCallStack(): array
	{
		// TODO: Implement getFunctionCallStack() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getFunctionCallStackWithParameters(): array
	{
		// TODO: Implement getFunctionCallStackWithParameters() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isParameterValueNullable(Param $parameter): bool
	{
		// TODO: Implement isParameterValueNullable() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	/**
	 * @param Node\Name|Node\Identifier|Node\ComplexType|null $type
	 */
	public function getFunctionType($type, bool $isNullable, bool $isVariadic): Type
	{
		// TODO: Implement getFunctionType() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInExpressionAssign(Expr $expr): bool
	{
		// TODO: Implement isInExpressionAssign() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isUndefinedExpressionAllowed(Expr $expr): bool
	{
		// TODO: Implement isUndefinedExpressionAllowed() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function filterByTruthyValue(Expr $expr): Scope
	{
		// TODO: Implement filterByTruthyValue() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function filterByFalseyValue(Expr $expr): Scope
	{
		// TODO: Implement filterByFalseyValue() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function isInFirstLevelStatement(): bool
	{
		// TODO: Implement isInFirstLevelStatement() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function getPhpVersion(): PhpVersions
	{
		// TODO: Implement getPhpVersion() method.
		throw new ShouldNotHappenException('Not implemented yet');
	}

	public function invokeNodeCallback(Node $node): void
	{
		Fiber::suspend(new NodeCallbackRequest($node, $this));
	}

}
