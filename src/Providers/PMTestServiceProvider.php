<?php
namespace PMTest\Providers;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Frontend\Services;
use Plenty\Modules\Template\Design\Config\Models;

use IO\Helper\TemplateContainer;


/**
 * Class PMTestServiceProvider
 * @package PMTest\Providers
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
		$events = $this->getEventDispatcher();
	}

	public function boot(Twig $twig, Dispatcher $eventDispatcher, ConfigRepository $config)
	{
		//$services->addJsFile($this->getScriptURL($config));
		
		// Register Twig String Loader to use function: template_from_string
		$twig->addExtension('Twig_Extension_StringLoader');

		// provide template to use for blog categories
//		$eventDispatcher->listen('tpl.category.blog', function(Services\FileService $service) {
//			$service->addJsFile("http://localhost/v1/1465/tracking.js");
//		}, 0);
		
		// provide template to use for container categories
//		$eventDispatcher->listen('tpl.category.container', function(Services\FileService $service) {
//			$service->addJsFile("http://localhost/v1/1465/tracking.js");
//		}, 0);

		$eventDispatcher->listen('tpl.category.container', function(TemplateContainer $container, $templateData) {
//			$templateData = $container->getTemplateData();
//			$container->withData("PMTest::PageDesign.PageDesign", $templateData['identifier']);
//			$service->addJsFile("http://localhost/v1/1465/tracking.js");
		}, 0);

	}

	private function getScriptURL(ConfigRepository $config):string
	{
		$mandator = $config->get('PMTest.customer.id');
		$plugin = $config->get('PMTest.plugin.id');
		$plugin = $plugin ? '/' . $plugin : '';
		$scriptOverwrite = $config->get('PMTest.overwrite.endpoint');

		if ($scriptOverwrite) {
			$scriptOverwrite = (!preg_match('/^(http|\/\/)/', $scriptOverwrite) ? '//' : '') . $scriptOverwrite;
			$scriptUrl = preg_replace('(^https?:)', '', $scriptOverwrite);
		} else {
			$scriptUrl = $config->get('PMTest.performance') ?
				self::AMAZON_CDN_SCRIPT : self::YOOCHOOSE_CDN_SCRIPT;
		}

		$scriptUrl = $scriptUrl . 'v1/'. $mandator . '/' . $plugin. '/tracking.js';
//        $result = sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl . 'js');
//        $result .= sprintf('<link type="text/css" rel="stylesheet" href="%s">', $scriptUrl . 'css');

		return $scriptUrl;
	}
}
