<?php declare(strict_types = 1);

namespace PHPStan\Rules;

use PHPStan\Reflection\ExtendedMethodReflection;
use function sprintf;
use function ucfirst;

/**
 * @api
 */
final class ClassNameUsageLocation
{

	public const TRAIT_USE = 'traitUse';
	public const STATIC_PROPERTY_ACCESS = 'staticProperty';
	public const PHPDOC_TAG_ASSERT = 'assert';
	public const ATTRIBUTE = 'attribute';
	public const EXCEPTION_CATCH = 'catch';
	public const CLASS_CONSTANT_ACCESS = 'classConstant';
	public const CLASS_IMPLEMENTS = 'classImplements';
	public const ENUM_IMPLEMENTS = 'enumImplements';
	public const INTERFACE_EXTENDS = 'interfaceExtends';
	public const CLASS_EXTENDS = 'classExtends';
	public const INSTANCEOF = 'instanceof';
	public const PROPERTY_TYPE = 'property';
	public const PARAMETER_TYPE = 'parameter';
	public const RETURN_TYPE = 'return';
	public const PHPDOC_TAG_SELF_OUT = 'selfOut';
	public const PHPDOC_TAG_VAR = 'varTag';
	public const INSTANTIATION = 'new';
	public const TYPE_ALIAS = 'typeAlias';
	public const PHPDOC_TAG_METHOD = 'methodTag';
	public const PHPDOC_TAG_MIXIN = 'mixin';
	public const PHPDOC_TAG_PROPERTY = 'propertyTag';
	public const PHPDOC_TAG_REQUIRE_EXTENDS = 'requireExtends';
	public const PHPDOC_TAG_REQUIRE_IMPLEMENTS = 'requireImplements';
	public const STATIC_METHOD_CALL = 'staticMethod';
	public const PHPDOC_TAG_TEMPLATE_BOUND = 'templateBound';
	public const PHPDOC_TAG_TEMPLATE_DEFAULT = 'templateDefault';

	/**
	 * @param self::* $value
	 * @param mixed[] $data
	 */
	private function __construct(public readonly string $value, public readonly array $data)
	{
	}

	/**
	 * @param self::* $value
	 * @param mixed[] $data
	 */
	public static function from(string $value, array $data = []): self
	{
		return new self($value, $data);
	}

	public function getMethod(): ?ExtendedMethodReflection
	{
		return $this->data['method'] ?? null;
	}

	public function createMessage(string $part): string
	{
		switch ($this->value) {
			case self::TRAIT_USE:
				return sprintf('Usage of %s.', $part);
			case self::STATIC_PROPERTY_ACCESS:
				return sprintf('Access to static property on %s.', $part);
			case self::PHPDOC_TAG_ASSERT:
				return sprintf('Assert tag references %s.', $part);
			case self::ATTRIBUTE:
				return sprintf('Attribute references %s.', $part);
			case self::EXCEPTION_CATCH:
				return sprintf('Catching %s.', $part);
			case self::CLASS_CONSTANT_ACCESS:
				return sprintf('Access to constant on %s.', $part);
			case self::CLASS_IMPLEMENTS:
				return sprintf('Class implements %s.', $part);
			case self::ENUM_IMPLEMENTS:
				return sprintf('Enum implements %s.', $part);
			case self::INTERFACE_EXTENDS:
				return sprintf('Interface extends %s.', $part);
			case self::CLASS_EXTENDS:
				return sprintf('Class extends %s.', $part);
			case self::INSTANCEOF:
				return sprintf('Instanceof references %s.', $part);
			case self::PROPERTY_TYPE:
				return sprintf('Property references %s in its type.', $part);
			case self::PARAMETER_TYPE:
				return sprintf('Parameter references %s in its type.', $part);
			case self::RETURN_TYPE:
				return sprintf('Return type references %s.', $part);
			case self::PHPDOC_TAG_SELF_OUT:
				return sprintf('PHPDoc tag @phpstan-self-out references %s.', $part);
			case self::PHPDOC_TAG_VAR:
				return sprintf('PHPDoc tag @var references %s.', $part);
			case self::INSTANTIATION:
				return sprintf('Instantiating %s.', $part);
			case self::TYPE_ALIAS:
				return sprintf('Type alias references %s.', $part);
			case self::PHPDOC_TAG_METHOD:
				return sprintf('PHPDoc tag @method references %s.', $part);
			case self::PHPDOC_TAG_MIXIN:
				return sprintf('PHPDoc tag @mixin references %s.', $part);
			case self::PHPDOC_TAG_PROPERTY:
				return sprintf('PHPDoc tag @property references %s.', $part);
			case self::PHPDOC_TAG_REQUIRE_EXTENDS:
				return sprintf('PHPDoc tag @phpstan-require-extends references %s.', $part);
			case self::PHPDOC_TAG_REQUIRE_IMPLEMENTS:
				return sprintf('PHPDoc tag @phpstan-require-implements references %s.', $part);
			case self::STATIC_METHOD_CALL:
				$method = $this->getMethod();
				if ($method !== null) {
					return sprintf('Call to static method %s() on %s.', $method->getName(), $part);
				}

				return sprintf('Call to static method on %s.', $part);
			case self::PHPDOC_TAG_TEMPLATE_BOUND:
				return sprintf('PHPDoc tag @template bound references %s.', $part);
			case self::PHPDOC_TAG_TEMPLATE_DEFAULT:
				return sprintf('PHPDoc tag @template default references %s.', $part);
		}
	}

	public function createIdentifier(string $secondPart): string
	{
		if ($this->value === self::CLASS_IMPLEMENTS) {
			return sprintf('class.implements%s', ucfirst($secondPart));
		}
		if ($this->value === self::ENUM_IMPLEMENTS) {
			return sprintf('enum.implements%s', ucfirst($secondPart));
		}
		if ($this->value === self::INTERFACE_EXTENDS) {
			return sprintf('interface.extends%s', ucfirst($secondPart));
		}
		if ($this->value === self::CLASS_EXTENDS) {
			return sprintf('class.extends%s', ucfirst($secondPart));
		}
		if ($this->value === self::PHPDOC_TAG_TEMPLATE_BOUND) {
			return sprintf('generics.%sBound', $secondPart);
		}
		if ($this->value === self::PHPDOC_TAG_TEMPLATE_DEFAULT) {
			return sprintf('generics.%sDefault', $secondPart);
		}

		return sprintf('%s.%s', $this->value, $secondPart);
	}

}
