<?php

namespace Bug13507;

/**
 * @return object{
 *  '-1': bool,
 * }
 */
function test(bool $value): object
{
	return (object)[
		'-1' => $value,
	];
}
