<?php

namespace PMTest1\Controllers;

use PMTest1\Services\SettingsService;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;

class SettingsController extends Controller
{
    const YOOCHOOSE_LICENSE_URL = 'https://admin.yoochoose.net/api/v4/';
    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * SettingsController constructor.
     * @param SettingsService $settingsService
     */
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
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
        $pluginId = null;
        $token = 'ufakvceomsv3ett48gpadw9a45l2g20b';
        $design = null;
        $endpoint = 'http://olos111.plentymarkets-cloud01.com';
        $customerId = 1234;
        $licenseKey = 5555;
        $body = [
            'base' => [
                'type' => "MAGENTO2",
                'pluginId' => $pluginId,
                'endpoint' => $endpoint,
                'appKey' => '',
                'appSecret' => $token,
            ],
            'frontend' => [
                'design' => $design,
            ],
            'search' => [
                'design' => $design,
            ],
        ];


        $url = self::YOOCHOOSE_LICENSE_URL . $customerId . '/plugin/update?createIfNeeded=true&fallbackDesign=true';

        return $this->getHttpPage($url, $body, $customerId, $licenseKey);
    }


    public function getHttpPage($url, $body, $customerId, $licenceKey)
    {
        $bodyString = json_encode($body);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$customerId:$licenceKey");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($bodyString),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodyString);

        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        return $result;
    }
}