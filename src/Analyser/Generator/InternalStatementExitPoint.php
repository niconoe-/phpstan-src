<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Stmt;
use PHPStan\Analyser\StatementExitPoint;

final class InternalStatementExitPoint
{

	public function __construct(private Stmt $statement, private GeneratorScope $scope)
	{
	}

	public function toPublic(): StatementExitPoint
	{
		return new StatementExitPoint($this->statement, $this->scope);
	}

	public function getStatement(): Stmt
	{
		return $this->statement;
	}

	public function getScope(): GeneratorScope
	{
		return $this->scope;
	}

}
