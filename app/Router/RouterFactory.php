<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Router;

use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\SimpleRouter;
use Nette\Routing\Router;

class RouterFactory
{

    public function createRouter(): Router
    {
        $router = new RouteList();
        $router->addRoute('/', 'Homepage:default');
        $router->addRoute('/resume', 'Homepage:resume');
        $router->addRoute('/contact', 'Homepage:contact');
        $router->addRoute('/my-setup', 'Homepage:setup');
        $router->addRoute('/speeches', 'Homepage:speeches');
        $router->addRoute('/talks', 'Homepage:talks');

        $router->addRoute('/api/live', 'Homepage:live');
        $router->addRoute('/sitemap.xml', 'Homepage:sitemap');
        $router->addRoute('/robots.txt', 'Homepage:robots');

        $router->add(new SimpleRouter('Homepage:default'));
        return $router;
    }

}
