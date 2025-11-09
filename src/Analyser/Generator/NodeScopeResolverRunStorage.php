<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Fiber;
use PhpParser\Node\Expr;
use SplObjectStorage;

final class NodeScopeResolverRunStorage
{

	/** @var SplObjectStorage<Expr, ExprAnalysisResult> */
	public SplObjectStorage $expressionAnalysisResults;

	/** @var array<array{fiber: Fiber<mixed, ExprAnalysisResult, null, ExprAnalysisRequest>, request: ExprAnalysisRequest}> */
	public array $pendingFibers = [];

	public function __construct()
	{
		$this->expressionAnalysisResults = new SplObjectStorage();
	}

}
