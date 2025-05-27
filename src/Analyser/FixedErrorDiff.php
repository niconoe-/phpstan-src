<?php declare(strict_types = 1);

namespace PHPStan\Analyser;

use SebastianBergmann\Diff\Differ;

final class FixedErrorDiff
{

	/**
	 * @param array<array{mixed, Differ::OLD|Differ::ADDED|Differ::REMOVED}> $diff
	 */
	public function __construct(
		public readonly string $originalHash,
		public readonly array $diff,
	)
	{
	}

	/**
	 * @param mixed[] $properties
	 */
	public static function __set_state(array $properties): self
	{
		return new self($properties['originalHash'], $properties['diff']);
	}

}
