<?php

namespace RestrictedUsage;

function doFoo(): void
{

}

function doBar(): void
{

}

function (): void {
	doNonexistent();
	doFoo();
	doBar();
};
