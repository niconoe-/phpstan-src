<?php declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

final class BleedingEdgeToggle
{

	private static bool $bleedingEdge = false;

	public static function isBleedingEdge(): bool // @phpstan-ignore shipmonk.deadMethod (kept for future use)
	{
		return self::$bleedingEdge;
	}

	public static function setBleedingEdge(bool $bleedingEdge): void
	{
		self::$bleedingEdge = $bleedingEdge;
	}

}
