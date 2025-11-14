<?php

namespace PHPStan\Analyser\Generator;

use Generator;
use function PHPStan\Testing\assertType;

class FooTestYield
{

	/**
	 * @return Generator<int, ExprAnalysisRequest|StmtAnalysisRequest|StmtsAnalysisRequest|NodeCallbackRequest, ExprAnalysisResult|StmtAnalysisResult, StmtAnalysisResult>
	 */
	public function doFoo(): Generator
	{
		assertType(ExprAnalysisResult::class, yield new ExprAnalysisRequest());
		assertType(TypeExprResult::class, yield new TypeExprRequest());
		assertType(StmtAnalysisResult::class, yield new StmtAnalysisRequest());
		assertType(StmtAnalysisResult::class, yield new StmtsAnalysisRequest());
		assertType('null', yield new NodeCallbackRequest());
		assertType('null', yield new AlternativeNodeCallbackRequest());
	}

}
