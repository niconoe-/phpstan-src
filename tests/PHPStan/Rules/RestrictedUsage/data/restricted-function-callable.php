<?php // lint >= 8.1

namespace RestrictedUsage;

function (): void {
	doNonexistent(...);
	doFoo(...);
	doBar(...);
};
