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
		$this->template->query = $this->getHttpRequest()->getQuery('q') ?: '';
		$this->template->posts = $this->getHttpRequest()->getQuery('q') ? $this->tweetsManager->load()->getTemplateData() : [];
		$this->template->count = count($this->template->posts);
	}

	public function renderJson(): void
	{
		$this->sendJson($this->tweetsManager->load()->getJsonData());
	}

}
