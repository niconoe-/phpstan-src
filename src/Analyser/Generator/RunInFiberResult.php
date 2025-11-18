<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

/**
 * @template-covariant T
 */
final class RunInFiberResult
{

	/**
	 * @param T $value
	 */
	public function __construct(
		public readonly mixed $value,
	)
	{
	}

}
