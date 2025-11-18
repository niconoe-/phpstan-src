<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use Fiber;

final class PendingFibersStorage
{

	/** @var array<array{fiber: Fiber<mixed, ExprAnalysisResult|null, null, ExprAnalysisRequest|NodeCallbackRequest>, request: ExprAnalysisRequest}> */
	public array $pendingFibers = [];

}
