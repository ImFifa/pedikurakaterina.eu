<?php declare(strict_types = 1);

namespace App\ProjectModule\Presenters;

use App\Components\BoxComponent;
use App\Components\IBoxComponentFactory;
use App\CoreModule\Presenters\FrontBasePresenter;
use h4kuna\DateFilter\DateTimeFormat;

abstract class BasePresenter extends FrontBasePresenter
{

	/** @var IBoxComponentFactory @inject */
	public $boxFactory;

	public function startup(): void
	{
		parent::startup();
		$this->template->isHomepage = false;
	}

	public function createComponentBox(): BoxComponent
	{
		return $this->boxFactory->create();
	}
}