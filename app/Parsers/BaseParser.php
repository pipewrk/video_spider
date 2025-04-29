<?php

namespace App\Parsers;

abstract class BaseParser
{
    abstract protected static function getHeaders();
    abstract public static function parse($url);

    protected static function success($data)
    {
        return [
            'code' => 200,
            'msg' => 'success',
            'data' => $data
        ];
    }

    protected static function error($code, $msg)
    {
        return [
            'code' => $code,
            'msg' => $msg
        ];
    }
} 