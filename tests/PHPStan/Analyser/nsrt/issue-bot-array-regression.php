<?php // lint >= 8.0

declare(strict_types = 1);

namespace IssueBotArrayRegression;

use function array_unique;
use function array_values;
use function PHPStan\Testing\assertType;

class EvaluateCommand
{
	protected function execute(): int
	{
		$issueCache = new IssueCache();

		foreach ($issueCache->getIssues() as $issue) {
			$deduplicatedExamples = [];
			foreach ($issue->getComments() as $comment) {
				foreach ($comment->getPlaygroundExamples() as $example) {
					if (isset($deduplicatedExamples[$example->getHash()])) {
						assertType('array{example: IssueBotArrayRegression\PlaygroundExample, users: non-empty-list<string>}', $deduplicatedExamples[$example->getHash()]);
						assertType('non-empty-list<string>', $deduplicatedExamples[$example->getHash()]['users']);
						$deduplicatedExamples[$example->getHash()]['users'][] = $comment->getAuthor();
						$deduplicatedExamples[$example->getHash()]['users'] = array_values(array_unique($deduplicatedExamples[$example->getHash()]['users']));
						continue;
					}
					$deduplicatedExamples[$example->getHash()] = [
						'example' => $example,
						'users' => [$comment->getAuthor()],
					];
				}
			}

			assertType('array<string, array{example: IssueBotArrayRegression\PlaygroundExample, users: non-empty-list<string>}>', $deduplicatedExamples);
		}

		return 1;
	}

}

class Comment
{

	/**
	 * @param non-empty-list<PlaygroundExample> $playgroundExamples
	 */
	public function __construct(
		private string $author,
		private string $text,
		private array  $playgroundExamples,
	)
	{
	}

	public function getAuthor(): string
	{
		return $this->author;
	}

	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @return non-empty-list<PlaygroundExample>
	 */
	public function getPlaygroundExamples(): array
	{
		return $this->playgroundExamples;
	}

}


class Issue
{

	/**
	 * @param Comment[] $comments
	 */
	public function __construct(private int $number, private array $comments)
	{
	}

	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * @return Comment[]
	 */
	public function getComments(): array
	{
		return $this->comments;
	}

}

class IssueCache
{

	public function __construct()
	{
	}

	/**
	 * @return array<int, Issue>
	 */
	public function getIssues(): array
	{
		return [];
	}

}

class PlaygroundExample
{

	public function __construct(
		private string $url,
		private string $hash,
	)
	{
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

}
