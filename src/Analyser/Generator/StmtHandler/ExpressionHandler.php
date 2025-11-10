<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\StmtHandler;

use Generator;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Generator\ExprAnalysisRequest;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\Analyser\Generator\StmtAnalysisResult;
use PHPStan\Analyser\Generator\StmtHandler;
use PHPStan\Analyser\StatementContext;
use PHPStan\DependencyInjection\AutowiredService;

/**
 * @implements StmtHandler<Expression>
 */
#[AutowiredService]
final class ExpressionHandler implements StmtHandler
{

	public function supports(Stmt $stmt): bool
	{
		return $stmt instanceof Expression;
	}

	public function analyseStmt(Stmt $stmt, GeneratorScope $scope, StatementContext $context): Generator
	{
		$result = yield new ExprAnalysisRequest($stmt, $stmt->expr, $scope, ExpressionContext::createTopLevel());

		return new StmtAnalysisResult(
			$result->scope,
			hasYield: $result->hasYield,
			isAlwaysTerminating: $result->isAlwaysTerminating,
			exitPoints: [],
			throwPoints: $result->throwPoints,
			impurePoints: $result->impurePoints,
		);
	}

}
