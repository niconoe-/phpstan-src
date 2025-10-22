<?php declare(strict_types = 1);

namespace PHPStan\Rules\Operators;

use PhpParser\Node;
use PhpParser\Node\Expr\ShellExec;
use PHPStan\Analyser\Scope;
use PHPStan\DependencyInjection\RegisteredRule;
use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<ShellExec>
 */
#[RegisteredRule(level: 0)]
final class BacktickRule implements Rule
{

	public function __construct(
		private PhpVersion $phpVersion,
	)
	{
	}

	public function getNodeType(): string
	{
		return ShellExec::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$this->phpVersion->deprecatesBacktickOperator()) {
			return [];
		}

		return [
			RuleErrorBuilder::message('Backtick operator is deprecated in PHP 8.5. Use shell_exec() function call instead.')
				->identifier('backtick.deprecated')
				->build(),
		];
	}

}
