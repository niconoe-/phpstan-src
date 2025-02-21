<?php

namespace Bug12627;

class A
{
	public function a(): void
	{
		$this->b();
	}

	private function b(): void
	{
	}
}

$c = new class() {};
