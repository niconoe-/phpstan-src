<?php

namespace EditorModeE2E;

class Bar
{

	public function doFoo(Foo $foo): void
	{
		$this->requireString($foo->doFoo());
	}

	public function requireString(string $s): void
	{

	}

}
