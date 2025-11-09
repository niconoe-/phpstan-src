<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

final class StmtAnalysisResult
{

	public function __construct(
		public GeneratorScope $scope,
	)
	{
	}

}
