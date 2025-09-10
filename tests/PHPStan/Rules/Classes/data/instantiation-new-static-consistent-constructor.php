<?php

namespace InstantiationNewStaticConsistentConstructor;

/**
 * @phpstan-consistent-constructor
 */
class Foo
{

	public function __construct(int $i)
	{

	}

	public function doFoo(): void
	{
		$a = new static('s');
	}

}
