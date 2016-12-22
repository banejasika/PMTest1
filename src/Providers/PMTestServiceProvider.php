<?php
namespace PMTest1\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Frontend\Services;


/**
 * Class PMTestServiceProvider
 * @package PMTest1\Providers
 */
class PMTestServiceProvider extends ServiceProvider
{

	const YOOCHOOSE_CDN_SCRIPT = '//event.yoochoose.net/cdn';
	const AMAZON_CDN_SCRIPT = '//cdn.yoochoose.net';

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->getApplication()->register(PMTestRouteServiceProvider::class);
	}

	public function boot(Twig $twig, Dispatcher $eventDispatcher, ConfigRepository $config)
	{
//		$eventDispatcher->listen('tpl.category.container', function ($services, $config) {
//			$services->addJsFile($this->getScriptURL($config));
//		}, 0);

	}

	private function getScriptURL(ConfigRepository $config):string
	{
		$mandator = $config->get('PMTest1.customer.id');
		$plugin = $config->get('PMTest1.plugin.id');
		$plugin = $plugin ? '/' . $plugin : '';
		$scriptOverwrite = $config->get('PMTest1.overwrite.endpoint');

		if ($scriptOverwrite) {
			$scriptOverwrite = (!preg_match('/^(http|\/\/)/', $scriptOverwrite) ? '//' : '') . $scriptOverwrite;
			$scriptUrl = preg_replace('(^https?:)', '', $scriptOverwrite);
		} else {
			$scriptUrl = $config->get('PMTest1.performance') ?
				self::AMAZON_CDN_SCRIPT : self::YOOCHOOSE_CDN_SCRIPT;
		}

		$scriptUrl = $scriptUrl . 'v1/'. $mandator . '/' . $plugin. '/tracking.js';
//        $result = sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl . 'js');
//        $result .= sprintf('<link type="text/css" rel="stylesheet" href="%s">', $scriptUrl . 'css');

		return $scriptUrl;
	}
}
