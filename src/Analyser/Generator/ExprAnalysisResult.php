<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PHPStan\Type\Type;

final class ExprAnalysisResult
{

	public function __construct(
		public Type $type,
		public GeneratorScope $scope,
	)
	{
	}

}
