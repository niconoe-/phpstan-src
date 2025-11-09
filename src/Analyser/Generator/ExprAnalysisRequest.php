<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Expr;

final class ExprAnalysisRequest
{

	public function __construct(
		public Expr $expr,
		public GeneratorScope $scope,
	)
	{
	}

}
