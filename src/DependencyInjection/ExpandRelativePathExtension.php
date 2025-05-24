<?php declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class ExpandRelativePathExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::listOf('string');
	}

}
