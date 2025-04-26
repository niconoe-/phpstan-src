<?php // lint >= 8.0

declare(strict_types = 1);

namespace Bug12934;

function(string $path): void {
	session_set_cookie_params(0, path: $path, secure: true, httponly: true);
};
