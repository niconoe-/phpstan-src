<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

final class RestoreStorageRequest
{

	public function __construct(
		public readonly ExprAnalysisResultStorage $storage,
	)
	{
	}

}
