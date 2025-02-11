<?php

namespace Bug12575;

use function PHPStan\Testing\assertType;

/**
 * @template T of object
 */
class Foo
{

	/**
	 * @template U of object
	 * @param class-string<U> $class
	 * @return $this
	 * @phpstan-self-out static<T&U>
	 */
	public function add(string $class)
	{
		return $this;
	}

}

/**
 * @template T of object
 * @extends Foo<T>
 */
class Bar extends Foo
{

}

interface A
{

}

interface B
{

}

/**
 * @param Bar<A> $bar
 * @return void
 */
function doFoo(Bar $bar): void {
	assertType('Bug12575\\Bar<Bug12575\\A&Bug12575\\B>', $bar->add(B::class));
	assertType('Bug12575\\Bar<Bug12575\\A&Bug12575\\B>', $bar);
};

/**
 * @param Bar<A> $bar
 * @return void
 */
function doBar(Bar $bar): void {
	$bar->add(B::class);
	assertType('Bug12575\\Bar<Bug12575\\A&Bug12575\\B>', $bar);
};
