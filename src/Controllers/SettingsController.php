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

    /**
     *
     */
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
        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($bodyString),);
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HEADER, 0);
        curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cURL, CURLOPT_TIMEOUT, 10);
        curl_setopt($cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($cURL, CURLOPT_USERPWD, "$customerId:$licenceKey");
        curl_setopt($cURL, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $bodyString);

//            CURLOPT_HEADER => 0,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLINFO_HEADER_OUT => true,
//            CURLOPT_TIMEOUT => 10,
//            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
//            CURLOPT_USERPWD => "$customerId:$licenceKey",
//            CURLOPT_SSL_VERIFYPEER => false,
//            CURLOPT_HTTPHEADER => [
//                'Content-Type: application/json',
//                'Content-Length: ' . strlen($bodyString),
//            ],
//            CURLOPT_POSTFIELDS => $bodyString,

        $response = curl_exec($cURL);
        $result = json_decode($response, true);

//        $headers = curl_getinfo($cURL, CURLINFO_HEADER_OUT);
//        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

//        $eno = curl_errno($cURL);

//        if ($eno && $eno != 22) {
//            $msg = 'I/O error requesting [' . $url . ']. Code: ' . $eno . ". " . curl_error($cURL);
//            curl_close($cURL);
//            return $msg;
//        }

        curl_close($cURL);
//        switch ($status) {
//            case 200:
//                break;
//            case 409:
//                if ($result['faultCode'] === 'pluginAlreadyExistsFault') {
//                    break;
//                }
//            //it will will continue (fall-through) to the default intentionally
//            default:
//                $msg = $result['faultMessage'] . ' With status code: ' . $status;
//                return $msg;
//        }

        return $result;
    }
}