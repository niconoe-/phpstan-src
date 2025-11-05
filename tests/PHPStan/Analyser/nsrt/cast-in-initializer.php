<?php // lint >= 8.5

namespace CastInInitializer;

use function PHPStan\Testing\assertType;

assertType('true', FOO);
