<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Expr;
use PHPStan\ShouldNotHappenException;
use SplObjectStorage;
use function get_class;
use function sprintf;

final class ExprAnalysisResultStorage
{

	/** @var SplObjectStorage<Expr, ExprAnalysisResult> */
	private SplObjectStorage $expressionAnalysisResults;

	public function __construct()
	{
		$this->expressionAnalysisResults = new SplObjectStorage();
	}

	public function duplicate(): self
	{
		$new = new self();
		$new->expressionAnalysisResults->addAll($this->expressionAnalysisResults);
		return $new;
	}

	public function storeExprAnalysisResult(Expr $expr, ExprAnalysisResult $result): void
	{
		$this->expressionAnalysisResults[$expr] = $result;
	}

	public function findExprAnalysisResult(Expr $expr): ?ExprAnalysisResult
	{
		return $this->expressionAnalysisResults[$expr] ?? null;
	}

	public function getExprAnalysisResult(Expr $expr): ExprAnalysisResult
	{
		if (!isset($this->expressionAnalysisResults[$expr])) {
			throw new ShouldNotHappenException(sprintf('Result for expr %s not found', get_class($expr)));
		}

		return $this->expressionAnalysisResults[$expr];
	}

}
