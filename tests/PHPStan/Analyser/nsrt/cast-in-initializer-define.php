<?php // lint >= 8.5

namespace CastInInitializer;

use function PHPStan\Testing\assertType;

const FOO = (bool) 1;

assertType('true', FOO);
