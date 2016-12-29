<?php
namespace PMTest\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class PMTestRouteServiceProvider
 * @package PMTest\Providers
 */
class PMTestRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map(Router $router)
	{
		$router->get('hello', 'PMTest\Controllers\ContentController@sayHello');
		$router->get('yc/export', 'PMTest\Controllers\ExportController@export');

		//settings
		$router->post('pmtest/settings/', 'PMTest\Controllers\SettingsController@saveSettings');
		$router->get('pmtest/settings/', 'PMTest\Controllers\SettingsController@loadSettings');
	}

}
