<?php

namespace Kozak\Tomas\App\Router;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return Nette\Routing\Router
	 */
	public function createRouter(): Nette\Routing\Router
	{
		$router = new RouteList();
		$router[] = new Nette\Application\Routers\Route('/resume', 'Homepage:resume');
		$router[] = new SimpleRouter('Homepage:default');
		return $router;
	}

}
