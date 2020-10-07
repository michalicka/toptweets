<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use App\Model\Tweet;

class TweetsManager
{
	use Nette\SmartObject;

	const MAX_RESULTS = 100;

	/** @var \DG\Twitter\Twitter */
	private $twitter;

	/** @var \Nette\Http\Request */
	private $request;

	/** @var string */
	private $query;

	/** @var array */
	private $tweets = [];

	public function __construct(\DG\Twitter\Twitter $twitter, \Nette\Http\Request $request)
	{
		$this->twitter = $twitter;
		$this->request = $request;
	}


	private function getMax() : int
	{
		return min(intval($this->request->getQuery('max') ?: $this::MAX_RESULTS), $this::MAX_RESULTS);
	}

	private function getQuery() : string
	{
		return $this->request->getQuery('q') ?: '#pilulka OR #pilulkacz OR (url:pilulka.cz)';
	}

	private function loadNext(): int 
	{
		$result = $this->twitter->request('search/tweets', 'GET', $this->query);
		$this->tweets = array_merge($this->tweets, $result->statuses ?: []);
		return $this->getLastTweetId();
	}

	private function getLastTweetId(): int 
	{
		return $this->tweets[array_key_last($this->tweets)]->id;
	}

	private function getNextMaxId(): int
	{
		return $this->getLastTweetId() - 1;
	}

	public function load(): TweetsManager
	{
		$this->query = [ 'q' => $this->getQuery(), 'max_results' => $this->getMax()];
		$this->loadNext();

		while (count($this->tweets) < $this->getMax()) {

			$this->query['max_id'] = $this->getNextMaxId();
			$this->loadNext();

		}

		return $this;
	}

	public function getTweets(): array
	{
		return array_slice($this->tweets, 0, $this->getMax());
	}
	
	public function getJsonData(): array
	{
		return array(
			'max' => $this->getMax(),
			'query' => $this->getQuery(),
			'data' => $this->getTweets()
		);
	}

	public function getTemplateData(): array
	{
		$result = [];
		foreach ($this->getTweets() as $tweet) $result[] = new Tweet($tweet);
		return $result;
	}


	
}