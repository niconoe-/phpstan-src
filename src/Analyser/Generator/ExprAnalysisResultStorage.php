<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Expr;
use SplObjectStorage;

final class ExprAnalysisResultStorage
{

	/** @var SplObjectStorage<Expr, ExprAnalysisResult> */
	private SplObjectStorage $expressionAnalysisResults;

	public function __construct()
	{
		$this->expressionAnalysisResults = new SplObjectStorage();
	}

	public function storeExprAnalysisResult(Expr $expr, ExprAnalysisResult $result): void
	{
		$this->expressionAnalysisResults[$expr] = $result;
	}

	public function findExprAnalysisResult(Expr $expr): ?ExprAnalysisResult
	{
		return $this->expressionAnalysisResults[$expr] ?? null;
	}

}
