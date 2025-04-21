<?php declare(strict_types = 1);

namespace PHPStan\Rules;

use PHPStan\Analyser\Scope;

final class ClassNameCheck
{

	public function __construct(
		private ClassCaseSensitivityCheck $classCaseSensitivityCheck,
		private ClassForbiddenNameCheck $classForbiddenNameCheck,
	)
	{
	}

	/**
	 * @param ClassNameNodePair[] $pairs
	 * @return list<IdentifierRuleError>
	 */
	public function checkClassNames(
		Scope $scope,
		array $pairs,
		ClassNameUsageLocation $location,
		bool $checkClassCaseSensitivity = true,
	): array
	{
		$errors = [];

		if ($checkClassCaseSensitivity) {
			foreach ($this->classCaseSensitivityCheck->checkClassNames($pairs) as $error) {
				$errors[] = $error;
			}
		}
		foreach ($this->classForbiddenNameCheck->checkClassNames($pairs) as $error) {
			$errors[] = $error;
		}

		return $errors;
	}

}
