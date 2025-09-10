<?php

namespace bug13481;

use function PHPStan\Testing\assertType;
use function str_increment;

function bug13481() {
	$s = 'ab c1';
	assertType("*NEVER*", str_increment($s));

	++$s;
	assertType("*NEVER*", $s);
}

function bug13481b() {
	$s = '%';
	assertType("*NEVER*", str_increment($s));

	++$s;
	assertType("*NEVER*", $s);
}

