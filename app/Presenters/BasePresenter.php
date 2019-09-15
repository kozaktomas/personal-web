<?php

namespace Kozak\Tomas\App\Presenters;

use Nette;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * @property-write Template|\stdClass $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected function startup()
	{
		parent::startup();
	}
}
