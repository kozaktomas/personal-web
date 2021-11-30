<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Presenters;

use Nette;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * @property Template $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected function startup()
    {
        parent::startup();
    }
}
