<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Stmt;

final class StmtAnalysisRequest
{

	public function __construct(
		public Stmt $stmt,
		public GeneratorScope $scope,
	)
	{
	}

}
