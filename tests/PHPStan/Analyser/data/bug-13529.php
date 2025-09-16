<?php

namespace Bug13529;

function broken(string $x): void {
	$tmp = json_decode($x, false);

	if (!isset($tmp->foo) || !isset($tmp->bar)) {
	}
}
