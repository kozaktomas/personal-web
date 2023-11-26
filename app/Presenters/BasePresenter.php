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
        $request = $this->getRequest();
        if ($request instanceof Nette\Application\Request) {
            $action = $request->getParameter('action');
            if (!is_string($action)) {
                $action = '';
            }
            $route = $request->getPresenterName() . ':' . $action;
            $this->getHttpResponse()->addHeader("X-App-Route", $route);
        }

        $this->template->basePath = "https://static.kozak.in/static/kozak-in/public";
    }
}
