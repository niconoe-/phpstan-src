<?php

declare(strict_types=1);

namespace ScopeFunctionInMethod;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpFunctionFromParserNodeReflection;
use PHPStan\Reflection\Php\PhpMethodFromParserNodeReflection;
use function PHPStan\Testing\assertType;

class SomeRule
{

	public function doFoo(Scope $scope): void
	{
		$function = $scope->getFunction();
		if ($function === null) {
			return;
		}

		assertType(PhpFunctionFromParserNodeReflection::class, $function);

		if ($function->isMethodOrPropertyHook()) {
			assertType(PhpMethodFromParserNodeReflection::class, $function);
		} else {
			assertType(PhpFunctionFromParserNodeReflection::class . '~' . PhpMethodFromParserNodeReflection::class, $function);
		}
	}

}
