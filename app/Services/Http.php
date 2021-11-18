<?php

namespace App\Services;

use Exception;

class Http {
    public static function get($url, $params = []) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . self::makeParams($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception("Unable to complete HTTP GET request to $url");
        }

        return $response;
    }

    public static function post($url, $params = []) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . self::makeParams($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception("Unable to complete HTTP POST request to $url");
        }

        return $response;
    }

    protected static function makeParams($params) {
        if (count($params) == 0) {
            return "";
        }

        $joinedKeyVal = array_map(
            function($key, $value){ return "$key=$value"; },
            array_keys($params),
            array_values($params)
        );

        return "?" . implode('&', $joinedKeyVal);
    }
}
