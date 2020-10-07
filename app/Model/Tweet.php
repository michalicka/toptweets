<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use App\Utils;
use App\Model\User;
use Nette\Utils\DateTime;

class Tweet
{
	use Nette\SmartObject;

	/** @var int */
	public $id;

	/** @var string */
	public $text;

	/** @var string */
	public $html;

	/** @var \App\Model\User */
	public $user;

	/** @var int */
	public $time;

	public function __construct(Object $tweet)
	{
		$this->id = $tweet->id;
		$this->text = $tweet->text;
		$this->html = Utils::getHtmlTweet($tweet);
		$this->user = new User($tweet->user);
		$this->time = Utils::getRelativeTime(DateTime::from($tweet->created_at));
	}


	function link(): string
	{
		return "https://twitter.com/{$this->user->user_name}/status/{$this->id}";
	}		


}