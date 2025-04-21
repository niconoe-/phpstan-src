<?php declare(strict_types = 1);

namespace PHPStan\Rules;

use function array_key_exists;

final class ClassNameUsageLocation
{

	public const TRAIT_USE = 'traitUse';
	public const STATIC_PROPERTY_ACCESS = 'staticPropertyAccess';
	public const PHPDOC_TAG_ASSERT = 'phpDocTagAssert';
	public const ATTRIBUTE = 'attribute';
	public const EXCEPTION_CATCH = 'exceptionCatch';
	public const CLASS_CONSTANT_ACCESS = 'classConstantAccess';
	public const CLASS_IMPLEMENTS = 'classImplements';
	public const ENUM_IMPLEMENTS = 'enumImplements';
	public const INTERFACE_EXTENDS = 'interfaceExtends';
	public const CLASS_EXTENDS = 'classExtends';
	public const INSTANCEOF = 'instanceof';
	public const PROPERTY_TYPE = 'propertyType';
	public const USE_STATEMENT = 'use';
	public const PARAMETER_TYPE = 'parameterType';
	public const RETURN_TYPE = 'returnType';
	public const PHPDOC_TAG_SELF_OUT = 'phpDocTagSelfOut';
	public const PHPDOC_TAG_VAR = 'phpDocTagVar';
	public const INSTANTIATION = 'new';
	public const TYPE_ALIAS = 'typeAlias';
	public const PHPDOC_TAG_METHOD = 'phpDocTagMethod';
	public const PHPDOC_TAG_MIXIN = 'phpDocTagMixin';
	public const PHPDOC_TAG_PROPERTY = 'phpDocTagProperty';
	public const PHPDOC_TAG_REQUIRE_EXTENDS = 'phpDocTagRequireExtends';
	public const PHPDOC_TAG_REQUIRE_IMPLEMENTS = 'phpDocTagRequireImplements';
	public const STATIC_METHOD_CALL = 'staticMethodCall';
	public const PHPDOC_TAG_TEMPLATE_BOUND = 'phpDocTemplateBound';
	public const PHPDOC_TAG_TEMPLATE_DEFAULT = 'phpDocTemplateDefault';

	/** @var array<string, self> */
	public static array $registry = [];

	/**
	 * @param self::* $value
	 */
	private function __construct(string $value) // @phpstan-ignore constructor.unusedParameter
	{
	}

	/**
	 * @param self::* $value
	 */
	public static function from(string $value): self
	{
		if (array_key_exists($value, self::$registry)) {
			return self::$registry[$value];
		}

		return self::$registry[$value] = new self($value);
	}

}
