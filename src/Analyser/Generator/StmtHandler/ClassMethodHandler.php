<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\StmtHandler;

use Generator;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\Analyser\Generator\StmtAnalysisRequest;
use PHPStan\Analyser\Generator\StmtAnalysisResult;
use PHPStan\Analyser\Generator\StmtHandler;
use PHPStan\DependencyInjection\AutowiredService;

/**
 * @implements StmtHandler<ClassMethod>
 */
#[AutowiredService]
final class ClassMethodHandler implements StmtHandler
{

	public function supports(Stmt $stmt): bool
	{
		return $stmt instanceof ClassMethod;
	}

	public function analyseStmt(Stmt $stmt, GeneratorScope $scope): Generator
	{
		//$scope = $scope->enterClassMethod();

		if ($stmt->stmts === null) {
			return new StmtAnalysisResult($scope);
		}

		foreach ($stmt->stmts as $innerStmt) {
			$result = yield new StmtAnalysisRequest($innerStmt, $scope);
			$scope = $result->scope;
		}

		return new StmtAnalysisResult($scope);
	}

}
