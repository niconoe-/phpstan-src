<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\StmtHandler;

use Generator;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\Analyser\Generator\StmtHandler;
use PHPStan\Analyser\Generator\StmtsAnalysisRequest;
use PHPStan\Analyser\StatementContext;
use PHPStan\DependencyInjection\AutowiredService;

/**
 * @implements StmtHandler<Class_|Interface_|Enum_>
 */
#[AutowiredService]
final class ClassLikeHandler implements StmtHandler
{

	public function supports(Stmt $stmt): bool
	{
		return $stmt instanceof Class_ || $stmt instanceof Interface_ || $stmt instanceof Enum_;
	}

	public function analyseStmt(Stmt $stmt, GeneratorScope $scope, StatementContext $context, ?callable $alternativeNodeCallback): Generator
	{
		//$scope = $scope->enterClass();
		return yield new StmtsAnalysisRequest($stmt->stmts, $scope, $context, $alternativeNodeCallback);
	}

}
