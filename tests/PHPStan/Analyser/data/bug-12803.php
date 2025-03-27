<?php

namespace Bug12803;

/** @template T */
class Generic {}

class A
{
	/** @param array{foo: int, bar: string} $foo */
	public function b(array $foo): void
	{
		/** @var Generic<object{foo: 2, bar: 1}> $a */
		$a = $this->c(fn() => (object) ['bar' => 1, 'foo' => 2]);
		$b = $this->c(fn() => (object) ['bar' => 1, 'foo' => 2]);
	}

	/**
	 * @template T
	 * @param callable(): T  $callback
	 * @return Generic<T>
	 */
	public function c(callable $callback): Generic
	{
		return new Generic();
	}
}
