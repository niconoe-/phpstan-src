<?php // lint >= 8.5

namespace FinalClassRulePipe;

class Foo
{

	public function doFoo(): void
	{
		$one = '1'
			|> strtoupper(...)
			|> strtolower(...);
	}

}
