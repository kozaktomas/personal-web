<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Nette;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected function startup()
	{
		parent::startup();
	}
}
