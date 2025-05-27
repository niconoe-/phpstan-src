<?php // lint >= 8.0

namespace NamedArgumentRule;

use Exception;

class Foo
{

	public function doFoo(): void
	{
		new Exception('foo', 0);
		new Exception('foo', 0, null);
		new Exception('foo', 0, new Exception('previous'));
		new Exception('foo', previous: new Exception('previous'));
		new Exception('foo', code: 0, previous: new Exception('previous'));
		new Exception('foo', code: 1, previous: new Exception('previous'));
		new Exception('foo', 1, new Exception('previous'));
		new Exception('foo', 1);
		new Exception('', 0, new Exception('previous'));
	}

}
