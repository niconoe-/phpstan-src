<?php

namespace GeneratorNodeScopeResolverRule;

class Foo
{

	public function doFoo(): ?string
	{
		return 'foo';
	}

	public function doBar(): ?int
	{
		return 1;
	}

}

function (Foo $foo): void {
	$foo->doFoo(1, 2, 3);
};
