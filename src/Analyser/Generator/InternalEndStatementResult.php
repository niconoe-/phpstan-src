<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

use PhpParser\Node\Stmt;
use PHPStan\Analyser\EndStatementResult;

final class InternalEndStatementResult
{

	public function __construct(
		private Stmt $statement,
		private StmtAnalysisResult $result,
	)
	{
	}

	public function toPublic(): EndStatementResult
	{
		return new EndStatementResult($this->statement, $this->result->toPublic());
	}

	public function getStatement(): Stmt
	{
		return $this->statement;
	}

	public function getResult(): StmtAnalysisResult
	{
		return $this->result;
	}

}
