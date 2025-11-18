<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use function debug_backtrace;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

/**
 * @template-covariant T
 */
final class RunInFiberRequest
{

	public ?string $originFile = null;

	public ?int $originLine = null;

	/**
	 * @param callable(): T $callback
	 */
	public function __construct(
		public readonly mixed $callback,
	)
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
		$this->originFile = $trace[0]['file'] ?? null;
		$this->originLine = $trace[0]['line'] ?? null;
	}

}
