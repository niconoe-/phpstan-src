<?php declare(strict_types = 1);

namespace PHPStan\Build;

use PhpParser\Node\Expr;
use PHPStan\Analyser\Generator\AlternativeNodeCallbackRequest;
use PHPStan\Analyser\Generator\ExprAnalysisRequest;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\NodeCallbackRequest;
use PHPStan\Analyser\Generator\StmtAnalysisRequest;
use PHPStan\Analyser\Generator\StmtAnalysisResult;
use PHPStan\Analyser\Generator\StmtsAnalysisRequest;
use PHPStan\Analyser\Generator\TypeExprRequest;
use PHPStan\Analyser\Generator\TypeExprResult;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ExpressionTypeResolverExtension;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function str_starts_with;

final class GeneratorYieldSendTypeExtension implements ExpressionTypeResolverExtension
{

	public function getType(Expr $expr, Scope $scope): ?Type
	{
		if (!$expr instanceof Expr\Yield_) {
			return null;
		}

		if ($expr->value === null) {
			return null;
		}

		$namespace = $scope->getNamespace();
		if ($namespace === null) {
			return null;
		}

		if (!str_starts_with($namespace . '\\', 'PHPStan\\Analyser\\Generator\\')) {
			return null;
		}

		$valueType = $scope->getType($expr->value);
		if ((new ObjectType(ExprAnalysisRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new ObjectType(ExprAnalysisResult::class);
		}
		if ((new ObjectType(StmtAnalysisRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new ObjectType(StmtAnalysisResult::class);
		}
		if ((new ObjectType(StmtsAnalysisRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new ObjectType(StmtAnalysisResult::class);
		}
		if ((new ObjectType(NodeCallbackRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new NullType();
		}
		if ((new ObjectType(AlternativeNodeCallbackRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new NullType();
		}
		if ((new ObjectType(TypeExprRequest::class))->isSuperTypeOf($valueType)->yes()) {
			return new ObjectType(TypeExprResult::class);
		}

		return null;
	}

}
