<?php declare(strict_types=1);

namespace Kozak\Tomas\App\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\SimpleRouter;
use Nette\Routing\Router;

class RouterFactory
{

    public function createRouter(): Router
    {
        $router = new RouteList();
        $router[] = new Route('/', 'Homepage:default');
        $router[] = new Route('/resume', 'Homepage:resume');
        $router[] = new Route('/contact', 'Homepage:contact');
        $router[] = new Route('/my-setup', 'Homepage:setup');
        $router[] = new Route('/speeches', 'Homepage:speeches');
        $router[] = new Route('/talks', 'Homepage:talks');

        $router[] = new Route('/sitemap.xml', 'Homepage:sitemap');
        $router[] = new Route('/robots.txt', 'Homepage:robots');

        $router[] = new SimpleRouter('Homepage:default');
        return $router;
    }

}
