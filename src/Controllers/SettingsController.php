<?php

namespace PMTest1\Controllers;

use PMTest1\Services\SettingsService;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use PMTest1\Helper\Helper;
use Plenty\Modules\System\Models\WebstoreConfiguration;
use Plenty\Modules\Helper\Services\WebstoreHelper;

class SettingsController extends Controller
{
    const YOOCHOOSE_LICENSE_URL = 'https://admin.yoochoose.net/api/v4/';

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var WebstoreConfiguration
     */
    private $storeConfig;

    /**
     * @var WebstoreHelper
     */
    private $storeHelper;

    /**
     * SettingsController constructor.
     * @param SettingsService $settingsService
     * @param Helper $helper
     * @param WebstoreConfiguration $storeConfig
     * @param WebstoreHelper $storeHelper
     */
    public function __construct
    (
        SettingsService $settingsService,
        Helper $helper,
        WebstoreConfiguration $storeConfig,
        WebstoreHelper $storeHelper
    )
    {
        $this->settingsService = $settingsService;
        $this->helper = $helper;
        $this->storeConfig = $storeConfig;
        $this->storeHelper = $storeHelper;
    }

    /**
     * @param Request $request
     */
    public function saveSettings(Request $request)
    {
        $test = $request->get('test');
        $this->settingsService->setSettingsValue('yc_test', $test);
    }

    /**
     * @return bool|mixed
     */
    public function loadSettings()
    {
        echo $this->settingsService->getSettingsValue('yc_test');
    }


    public function verifyCredentials()
    {

//        /** @var \Plenty\Modules\Helper\Services\WebstoreHelper $webstoreHelper */
//        $webstoreHelper = pluginApp(\Plenty\Modules\Helper\Services\WebstoreHelper::class);
//
//        /** @var \Plenty\Modules\System\Models\WebstoreConfiguration $webstoreConfig */
//        $webstoreConfig = $webstoreHelper->getCurrentWebstoreConfiguration();
//        if(is_null($webstoreConfig))
//        {
//            return 'error';
//        }
        $domain = $_SERVER['SERVER_NAME'];
        $token = 'ufakvceomsv3ett48gpadw9a45l2g20b';
        $customerId = $this->settingsService->getSettingsValue('customer_id');
        $licenseKey = $this->settingsService->getSettingsValue('license_key');
        $body = [
            'base' => [
                'type' => "MAGENTO2",
                'pluginId' => $this->settingsService->getSettingsValue('plugin_id'),
                'endpoint' => $domain,
                'appKey' => '',
                'appSecret' => $token,
            ],
            'frontend' => [
                'design' => $this->settingsService->getSettingsValue('design'),
            ],
            'search' => [
                'design' => $this->settingsService->getSettingsValue('design'),
            ],
        ];


        $url = self::YOOCHOOSE_LICENSE_URL . $customerId . '/plugin/update?createIfNeeded=true&fallbackDesign=true';
//        return $domain;
        return $this->helper->getHttpPage($url, $body, $customerId, $licenseKey);
    }

}