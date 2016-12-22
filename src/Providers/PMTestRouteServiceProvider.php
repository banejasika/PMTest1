<?php
namespace PMTest1\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class PMTestRouteServiceProvider
 * @package PMTest1\Providers
 */
class PMTestRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map(Router $router)
	{
		$router->get('hello', 'PMTest1\Controllers\ContentController@sayHello');
	}

}
