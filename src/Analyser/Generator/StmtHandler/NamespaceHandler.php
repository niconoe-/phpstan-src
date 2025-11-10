<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\StmtHandler;

use Generator;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\Analyser\Generator\StmtAnalysisRequest;
use PHPStan\Analyser\Generator\StmtAnalysisResult;
use PHPStan\Analyser\Generator\StmtHandler;
use PHPStan\DependencyInjection\AutowiredService;

/**
 * @implements StmtHandler<Namespace_>
 */
#[AutowiredService]
final class NamespaceHandler implements StmtHandler
{

	public function supports(Stmt $stmt): bool
	{
		return $stmt instanceof Namespace_;
	}

	public function analyseStmt(Stmt $stmt, GeneratorScope $scope): Generator
	{
		/*if ($stmt->name !== null) {
			$scope = $scope->enterNamespace($stmt->name->toString());
		}*/

		foreach ($stmt->stmts as $innerStmt) {
			$result = yield new StmtAnalysisRequest($innerStmt, $scope);
			$scope = $result->scope;
		}

		return new StmtAnalysisResult($scope);
	}

}
