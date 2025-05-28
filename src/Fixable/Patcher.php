<?php declare(strict_types = 1);

namespace PHPStan\Fixable;

use Nette\Utils\Strings;
use PhpMerge\internal\Hunk;
use PhpMerge\internal\Line;
use PhpMerge\MergeConflict;
use PhpMerge\PhpMerge;
use PHPStan\Analyser\FixedErrorDiff;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\File\FileReader;
use ReflectionClass;
use SebastianBergmann\Diff\Differ;

#[AutowiredService]
final class Patcher
{

	public function __construct(private Differ $differ)
	{
	}

	/**
	 * @param FixedErrorDiff[] $diffs
	 * @throws FileChangedException
	 * @throws MergeConflictException
	 */
	public function applyDiffs(string $fileName, array $diffs): string
	{
		$fileContents = FileReader::read($fileName);
		$fileHash = sha1($fileContents);
		$diffHunks = [];
		foreach ($diffs as $diff) {
			if ($diff->originalHash !== $fileHash) {
				throw new FileChangedException();
			}

			$diffHunks[] = Hunk::createArray(Line::createArray($diff->diff));
		}

		if (count($diffHunks) === 0) {
			return $fileContents;
		}

		$baseLines = Line::createArray(array_map(
			static fn ($l) => [$l, Differ::OLD],
			self::splitStringByLines($fileContents),
		));

		$refMerge = new ReflectionClass(PhpMerge::class);
		$refMergeMethod = $refMerge->getMethod('mergeHunks');
		$refMergeMethod->setAccessible(true);

		$result = Line::createArray(array_map(
			static fn ($l) => [$l, Differ::OLD],
			$refMergeMethod->invokeArgs(null, [
				$baseLines,
				$diffHunks[0],
				[],
			]),
		));

		for ($i = 0; $i < count($diffHunks); $i++) {
			/** @var MergeConflict[] $conflicts */
			$conflicts = [];
			$merged = $refMergeMethod->invokeArgs(null, [
				$baseLines,
				Hunk::createArray(Line::createArray($this->differ->diffToArray($fileContents, implode('', array_map(static fn ($l) => $l->getContent(), $result))))),
				$diffHunks[$i],
				&$conflicts,
			]);
			if (count($conflicts) > 0) {
				throw new MergeConflictException();
			}

			$result = Line::createArray(array_map(
				static fn ($l) => [$l, Differ::OLD],
				$merged,
			));

		}

		return implode('', array_map(static fn ($l) => $l->getContent(), $result));
	}

	/**
	 * @return string[]
	 */
	private static function splitStringByLines(string $input): array
	{
		return Strings::split($input, '/(.*\R)/', PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	}

}
