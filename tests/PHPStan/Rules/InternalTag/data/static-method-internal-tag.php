<?php

namespace StaticMethodInternalTagOne {

	class Foo
	{
		/** @internal */
		public static function doInternal()
		{

		}

		public static function doNotInternal()
		{

		}
	}

	/**
	 * @internal
	 */
	class FooInternal
	{

		public static function doFoo(): void
		{

		}

	}

	function (): void {
		Foo::doInternal();
		Foo::doNotInternal();
	};

	function (): void {
		FooInternal::doFoo();
	};

}

namespace StaticMethodInternalTagOne\Test {

	function (): void {
		\StaticMethodInternalTagOne\Foo::doInternal();
		\StaticMethodInternalTagOne\Foo::doNotInternal();
	};

	function (): void {
		\StaticMethodInternalTagOne\FooInternal::doFoo();
	};
}

namespace StaticMethodInternalTagTwo {

	function (): void {
		\StaticMethodInternalTagOne\Foo::doInternal();
		\StaticMethodInternalTagOne\Foo::doNotInternal();
	};

	function ( $foo): void {
		\StaticMethodInternalTagOne\FooInternal::doFoo();
	};

}

namespace {

	function (): void {
		\StaticMethodInternalTagOne\Foo::doInternal();
		\StaticMethodInternalTagOne\Foo::doNotInternal();
	};

	function (): void {
		\StaticMethodInternalTagOne\FooInternal::doFoo();
	};

	class FooWithInternalStaticMethodWithoutNamespace
	{
		/** @internal */
		public static function doInternal()
		{

		}

		public static function doNotInternal()
		{

		}
	}

	/**
	 * @internal
	 */
	class FooInternalStaticWithoutNamespace
	{

		public static function doFoo(): void
		{

		}

	}

	function (): void {
		FooWithInternalStaticMethodWithoutNamespace::doInternal();
		FooWithInternalStaticMethodWithoutNamespace::doNotInternal();
	};

	function (): void {
		FooInternalStaticWithoutNamespace::doFoo();
	};

}

namespace SomeNamespace {

	function (): void {
		\FooWithInternalStaticMethodWithoutNamespace::doInternal();
		\FooWithInternalStaticMethodWithoutNamespace::doNotInternal();
	};

	function (): void {
		\FooInternalStaticWithoutNamespace::doFoo();
	};

}
