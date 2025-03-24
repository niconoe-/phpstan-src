<?php

namespace Bug12778;

use stdClass;

class Test
{
	public function test(stdClass $category): void
	{
		echo $category->{''};
	}
}
