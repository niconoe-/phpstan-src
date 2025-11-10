<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Analyser\ImpurePoint;
use PHPStan\Type\Type;

final class ExprAnalysisResult
{

	/**
	 * @param InternalThrowPoint[] $throwPoints
	 * @param ImpurePoint[] $impurePoints
	 */
	public function __construct(
		public readonly Type $type,
		public readonly GeneratorScope $scope,
		public readonly bool $hasYield,
		public readonly bool $isAlwaysTerminating,
		public readonly array $throwPoints,
		public readonly array $impurePoints,
	)
	{
	}

}
