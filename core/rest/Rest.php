<?php
namespace core\rest;

class Rest{

    public static function post($url,$payload,$headers = []){
        ini_set('max_execution_time', 300);
        $cURLConnection = curl_init($url);

        curl_setopt($cURLConnection, CURLOPT_POST, true);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);       
        if(!empty($headers)) curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,$headers);

       

        $apiResponse = curl_exec($cURLConnection);
        if ($apiResponse === false) 
        $apiResponse = curl_error($cURLConnection);
        curl_close($cURLConnection);
       // $apiResponse - available data from the API request
        return json_decode($apiResponse);

    }

    public static function get($url,$headers = []){

        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
       
        if(!empty($headers)) curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,$headers);

        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $jsonArrayResponse - json_decode($phoneList);

    }
}