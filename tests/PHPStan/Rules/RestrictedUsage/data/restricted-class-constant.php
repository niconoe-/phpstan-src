<?php

namespace RestrictedUsageClassConstant;

class FooStatic
{

	public const BAR = 1;

	public const FOO = 2;

	public static function doTest(): void
	{
		Nonexistent::TEST;
		self::NONEXISTENT;
		self::BAR;
		self::FOO;
	}

}
