<?php

namespace MethodInternalTagOne {

	class Foo
	{
		/** @internal */
		public function doInternal()
		{

		}

		public function doNotInternal()
		{

		}
	}

	/**
	 * @internal
	 */
	class FooInternal
	{

		public function doFoo(): void
		{

		}

	}

	function (Foo $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (FooInternal $foo): void {
		$foo->doFoo();
	};

}

namespace MethodInternalTagOne\Test {

	function (\MethodInternalTagOne\Foo $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (\MethodInternalTagOne\FooInternal $foo): void {
		$foo->doFoo();
	};
}

namespace MethodInternalTagTwo {

	function (\MethodInternalTagOne\Foo $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (\MethodInternalTagOne\FooInternal $foo): void {
		$foo->doFoo();
	};

}

namespace {

	function (\MethodInternalTagOne\Foo $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (\MethodInternalTagOne\FooInternal $foo): void {
		$foo->doFoo();
	};

	class FooWithInternalMethodWithoutNamespace
	{
		/** @internal */
		public function doInternal()
		{

		}

		public function doNotInternal()
		{

		}
	}

	/**
	 * @internal
	 */
	class FooInternalWithoutNamespace
	{

		public function doFoo(): void
		{

		}

	}

	function (FooWithInternalMethodWithoutNamespace $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (FooInternalWithoutNamespace $foo): void {
		$foo->doFoo();
	};

}

namespace SomeNamespace {

	function (\FooWithInternalMethodWithoutNamespace $foo): void {
		$foo->doInternal();
		$foo->doNotInternal();
	};

	function (\FooInternalWithoutNamespace $foo): void {
		$foo->doFoo();
	};

}
