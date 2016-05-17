<?php
/**
 * Created by PhpStorm.
 * User: gelu.neamt
 * Date: 5/17/16
 * Time: 4:56 PM
 */

namespace Gelu;


class EdmundsApi implements ConnectorInterface
{
    const BASE_URL = 'https://api.edmunds.com/api/vehicle/v2/';
    const API_KEY = 'y2zqcuv5n9cnm4d2g8yw6p9w';

    /**
     * @param $path
     * @return mixed
     */
    public function makeRequest($path)
    {
        $cSession = curl_init();

        $url = self::BASE_URL.$path.self::API_KEY;

        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($cSession);
        $json = json_decode($result);

        curl_close($cSession);

        return $json;
    }

}