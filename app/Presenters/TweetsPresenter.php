<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\TweetsManager;


final class TweetsPresenter extends Nette\Application\UI\Presenter
{

	/** @var TweetsManager */
	private $tweetsManager;

	public function __construct(TweetsManager $tweetsManager)
	{
		$this->tweetsManager = $tweetsManager;
	}

	public function renderDefault(): void
	{
		$this->template->posts = $this->tweetsManager->load()->getTemplateData();
	}

	public function renderJson(): void
	{
		$this->sendJson($this->tweetsManager->load()->getJsonData());
	}

}
