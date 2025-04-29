<?php

namespace App\Utils;

class Common
{

    /**
     * 获取重定向后的地址
     * @param string $url
     * @return string
     */
    public static function getLocation($url)
    {
        $loc = @get_headers($url, true)['Location'];
        return is_array($loc) ? end($loc) : $loc;
    }

    /**
     * 发送网络请求
     * @param string $url
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public static function getCurl($url, $data = array(), $header = array())
    {
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($con, CURLOPT_AUTOREFERER, 1);
        if (isset($header)) {
            curl_setopt($con, CURLOPT_HTTPHEADER, $header);
        }
        if (isset($data)) {
            curl_setopt($con, CURLOPT_POST, true);
            curl_setopt($con, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($con, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($con);
        return $result;
    }
}
