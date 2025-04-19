<?php

namespace RestrictedUsage;

class Foo
{

	public function doTest(Nonexistent $c): void
	{
		$c->test();
		$this->doNonexistent();
		$this->doBar();
		$this->doFoo();
	}

	public function doBar(): void
	{

	}

	public function doFoo(): void
	{

	}

}
