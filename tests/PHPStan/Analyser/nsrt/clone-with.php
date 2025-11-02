<?php // lint >= 8.5

namespace CloneWithType;

use function PHPStan\Testing\assertType;

class Foo
{

	public function doFoo(?object $object, $mixed): void
	{
		assertType('object', clone($object, []));
		assertType('object', clone($mixed, []));
		assertType('static(CloneWithType\\Foo)', clone(new $this, []));
		assertType('static(CloneWithType\\Foo)', clone(new static, []));
		assertType(self::class, clone(new self, []));
	}

	public function doBar(object $object): void
	{
		assertType('object&hasProperty(bar)&hasProperty(foo)', clone($object, [
			'foo' => 1,
			'bar' => 2,
		]));
	}

}
