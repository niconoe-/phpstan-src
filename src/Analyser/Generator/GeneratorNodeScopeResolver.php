<?php declare(strict_types = 1);

namespace PHPStan\Analyser\Generator;

/**
 * Work In Progress.
 *
 * This is next-get NodeScopeResolver. It aims to solve several problems:
 *
 * 1) Many expressions are processed multiple times. For example, resolving type
 *    of BooleanAnd has to process left side in order to accurately get the type
 *    of the right side. For things like MethodCall, even dynamic return type extensions
 *    and ParametersAcceptorSelector are called multiple times.
 * 2) For complicated scope-changing expressions like assigns happening inside other expressions,
 *    the current type inference is imprecise. Take $foo->doFoo($a = 1, $a);
 *    When a rule hooks onto MethodCall and iterates over its args, the type of `$a`
 *    in the second argument should be `int`, but currently it's often `mixed` because
 *    the assignment in the first argument hasn't been processed yet in the context
 *    where the rule is checking.
 *
 * This class (I will refer to it as GNSR from now on) aims to merge the tasks currently handled
 * by NodeScopeResolver, MutatingScope and TypeSpecifier all into one, because they are very similar
 * and their work should happen all at once without duplicit processing.
 *
 * This rewrite should fix 1) to improve performance and 2) to improve type inference precision.
 *
 * Architecture:
 * - Uses generators (with `yield`) for internal analysis code to handle recursive traversal
 *   without deep call stacks and to explicitly manage when sub-expressions are analyzed
 * - Uses Fibers when calling custom extension code (rules, dynamic return type extensions, etc.)
 *   to preserve backward compatibility
 * - Calls to `$scope->getType()` in custom extensions do not need to be preceded with `yield`.
 *   Instead, if the type is not yet available, the call will transparently suspend the current
 *   Fiber and resume once the type has been computed
 * - All computed types and analysis results are stored during traversal so that subsequent
 *   lookups (from rules or other extensions) hit cached values with no duplicate work
 * - Synthetic/virtual expressions (e.g., a rule constructing `new MethodCall(...)` to query
 *   a hypothetical method call) are analyzed on-demand when requested, with the Fiber
 *   suspending until analysis completes
 */
final class GeneratorNodeScopeResolver
{

}
