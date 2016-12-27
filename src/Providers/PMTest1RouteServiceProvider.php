<?php
namespace PMTest1\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class PMTest1RouteServiceProvider
 * @package PMTest1\Providers
 */
class PMTest1RouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map(Router $router)
	{
		$router->get('hello', 'PMTest1\Controllers\ContentController@sayHello');
		$router->get('yc/export', 'PMTest1\Controllers\ExportController@export');

		//settings
		$router->post('pmtest1/settings/', 'PMTest1\Controllers\SettingsController@saveSettings');
		$router->get('pmtest1/settings/', 'PMTest1\Controllers\SettingsController@loadSettings');
        $router->get('pmtest1/settings/verify', 'PMTest1\Controllers\SettingsController@verifyCredentials');
	}

}
