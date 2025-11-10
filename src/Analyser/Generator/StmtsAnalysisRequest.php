<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Stmt;

final class StmtsAnalysisRequest
{

	/**
	 * @param Stmt[] $stmts
	 */
	public function __construct(
		public array $stmts,
		public GeneratorScope $scope,
	)
	{
	}

}
