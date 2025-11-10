<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Analyser\ExpressionTypeHolder;
use PHPStan\Analyser\ScopeContext;

interface InternalGeneratorScopeFactory
{

	/**
	 * @param array<string, ExpressionTypeHolder> $expressionTypes
	 */
	public function create(
		ScopeContext $context,
		array $expressionTypes = [],
	): GeneratorScope;

}
