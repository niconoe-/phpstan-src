<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Analyser\ScopeContext;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\DependencyInjection\Container;
use PHPStan\Node\Printer\ExprPrinter;
use PHPStan\Reflection\Php\PhpFunctionFromParserNodeReflection;

#[AutowiredService(as: InternalGeneratorScopeFactory::class)]
final class LazyInternalGeneratorScopeFactory implements InternalGeneratorScopeFactory
{

	public function __construct(private Container $container)
	{
	}

	public function create(
		ScopeContext $context,
		bool $declareStrictTypes = false,
		PhpFunctionFromParserNodeReflection|null $function = null,
		?string $namespace = null,
		array $expressionTypes = [],
		array $nativeExpressionTypes = [],
	): GeneratorScope
	{
		return new GeneratorScope(
			$this,
			$this->container->getByType(ExprPrinter::class),
			$context,
			$declareStrictTypes,
			$function,
			$namespace,
			$expressionTypes,
			$nativeExpressionTypes,
		);
	}

}
