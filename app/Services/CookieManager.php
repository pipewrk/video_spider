<?php

namespace App\Services;

class CookieManager
{
    private static $instance = null;
    private $cookies;

    private function __construct()
    {
        $this->cookies = require __DIR__ . '/../../config/cookies.php';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 获取指定平台的 Cookie
     *
     * @param string $platform
     * @return string
     */
    public function getCookie(string $platform): string
    {
        return $this->cookies[$platform]['cookie'] ?? '';
    }
}
