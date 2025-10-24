<?php // lint >= 8.5

namespace AttributeReflectionTest;

use Deprecated;

#[MyAttr(1, 2)]
const ExampleConstWithAttribute = 1;

#[Deprecated]
const DeprecatedConst = 1;

/** @deprecated */
const DeprecatedConstWithPhpDoc = 2;
