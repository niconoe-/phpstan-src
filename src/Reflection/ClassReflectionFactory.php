<?php declare(strict_types = 1);

namespace PHPStan\Reflection;

use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Generic\TemplateTypeVarianceMap;

interface ClassReflectionFactory
{

	public function create(
		string $displayName,
		ReflectionClass|ReflectionEnum $reflection,
		?string $anonymousFilename,
		?TemplateTypeMap $resolvedTemplateTypeMap,
		?ResolvedPhpDocBlock $stubPhpDocBlock,
		?string $extraCacheKey = null,
		?TemplateTypeVarianceMap $resolvedCallSiteVarianceMap = null,
		?bool $finalByKeywordOverride = null,
	): ClassReflection;

}
