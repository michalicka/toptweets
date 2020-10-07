<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

class User
{
	use Nette\SmartObject;

	/** @var string */
	public $user_name;

	/** @var string */
	public $full_name;

	/** @var string */
	public $image;

	public function __construct(Object $user)
	{
		$this->user_name = $user->screen_name;
		$this->full_name = $user->name;
		$this->image = $user->profile_image_url_https;
	}

	function link(): string
	{
		return "https://twitter.com/@{$this->user_name}";
	}
}