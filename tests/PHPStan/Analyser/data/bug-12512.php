<?php // lint >= 8.1

namespace Bug12512;

enum FooBarEnum: string
{
	case CASE_ONE = 'case_one';
	case CASE_TWO = 'case_two';
	case CASE_THREE = 'case_three';
	case CASE_FOUR = 'case_four';

	public function matchFunction(): string
	{
		return match ($this) {
			self::CASE_ONE => 'one',
			self::CASE_TWO => 'two',
			default => throw new \Exception(
				sprintf('"%s" is not implemented yet', get_debug_type($this))
			)
		};
	}
}
