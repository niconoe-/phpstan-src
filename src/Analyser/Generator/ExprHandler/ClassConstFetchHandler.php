<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator\ExprHandler;

use Generator;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PHPStan\Analyser\ExpressionContext;
use PHPStan\Analyser\Generator\ExprAnalysisResult;
use PHPStan\Analyser\Generator\ExprHandler;
use PHPStan\Analyser\Generator\GeneratorScope;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\Constant\ConstantStringType;

/**
 * @implements ExprHandler<ClassConstFetch>
 */
#[AutowiredService]
final class ClassConstFetchHandler implements ExprHandler
{

	public function supports(Expr $expr): bool
	{
		return $expr instanceof ClassConstFetch;
	}

	public function analyseExpr(Stmt $stmt, Expr $expr, GeneratorScope $scope, ExpressionContext $context): Generator
	{
		if (
			$expr->class instanceof Name
			&& $expr->name instanceof Identifier
			&& $expr->name->toLowerString() === 'class'
		) {
			yield from [];
			return new ExprAnalysisResult(
				new ConstantStringType($expr->class->toString()),
				$scope,
				hasYield: false,
				isAlwaysTerminating: false,
				throwPoints: [],
				impurePoints: [],
			);
		}

		throw new ShouldNotHappenException('Not implemented');
	}

}
