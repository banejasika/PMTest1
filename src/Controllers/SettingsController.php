<?php

namespace PMTest\Controllers;

use PMTest\Services\SettingsService;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use PMTest\Helpers\Data;
use Plenty\Modules\Helper\Services\WebstoreHelper;

class SettingsController extends Controller
{
    const YOOCHOOSE_LICENSE_URL = 'https://admin.yoochoose.net/api/v4/';

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var WebstoreHelper
     */
    private $storeHelper;

    /**
     * SettingsController constructor.
     * @param SettingsService $settingsService
     * @param Data $helper
     * @param WebstoreHelper $storeHelper
     */
    public function __construct
    (
        SettingsService $settingsService,
        Data $helper,
        WebstoreHelper $storeHelper
    ) {
        $this->settingsService = $settingsService;
        $this->helper = $helper;
        $this->storeHelper = $storeHelper;
    }

    /**
     * @param Request $request
     * @return mixed|string
     */
    public function saveSettings(Request $request)
    {
        $tests = [];

        $configFields['yc_test'] = $request->get('yc_test');
        $configFields['customer_id'] = $request->get('customer_id');
        $configFields['license_key'] = $request->get('license_key');
        $configFields['plugin_id'] = $request->get('plugin_id');
        $configFields['design'] = $request->get('design');
        $configFields['token'] = $request->get('token');

        foreach ($configFields as $key => $value) {
            if(!empty($value)) {
                switch ($key) {
                    case 'yc_test':
                        $this->settingsService->setSettingsValue('yc_test', $value);
                        break;
                    case 'customer_id':
                        $this->settingsService->setSettingsValue('customer_id', $value);
                        break;
                    case 'license_key':
                        $this->settingsService->setSettingsValue('license_key', $value);
                        break;
                    case 'plugin_id':
                        $this->settingsService->setSettingsValue('plugin_id', $value);
                        break;
                    case 'design':
                        $this->settingsService->setSettingsValue('design', $value);
                        break;
                    case 'token':
                        $this->settingsService->setSettingsValue('token', $value);
                        break;
                }
            }
        }

        $token = $request->get('token');
        if (!$token) {
            return 'Token must be set!';
        }

        /** @var \Plenty\Modules\System\Models\WebstoreConfiguration $webstoreConfig */
        $webstoreConfig = $this->storeHelper->getCurrentWebstoreConfiguration();
        if (is_null($webstoreConfig)) {
            return 'error';
        }
        $baseURL = $webstoreConfig->domain;
        $customerId = $this->settingsService->getSettingsValue('customer_id');
        $licenseKey = $this->settingsService->getSettingsValue('license_key');

        $body = [
            'base' => [
                'type' => "MAGENTO2",
                'pluginId' => $this->settingsService->getSettingsValue('plugin_id'),
                'endpoint' => $baseURL,
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

        return $this->helper->getHttpPage($url, $body, $customerId, $licenseKey);

    }

    /**
     * @return bool|mixed
     */
    public function loadSettings()
    {
        return json_encode($result = array(
            'yc_test' => $this->settingsService->getSettingsValue('yc_test'),
            'customer_id' => $this->settingsService->getSettingsValue('customer_id'),
            'license_key' => $this->settingsService->getSettingsValue('license_key'),
            'plugin_id' => $this->settingsService->getSettingsValue('plugin_id'),
            'design' => $this->settingsService->getSettingsValue('design'),
            'token' => $this->settingsService->getSettingsValue('token'),
    ));
    }

}