<?php

namespace NestedTooWideMethodReturnType;

final class Foo
{

	/**
	 * @return array<array{int, bool}>
	 */
	public function dataProvider(): array
	{
		return [
			[
				1,
				false,
			],
			[
				2,
				false,
			],
		];
	}

}
