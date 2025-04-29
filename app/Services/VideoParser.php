<?php

namespace App\Services;

class VideoParser
{
    private static $platforms = [
        'pipixia' => [
            'class' => \App\Parsers\PipixiaParser::class,
            'domains' => ['pipix.com']
        ],
        'douyin' => [
            'class' => \App\Parsers\DouyinParser::class,
            'domains' => ['douyin.com']
        ],
        'weibo' => [
            'class' => \App\Parsers\WeiboParser::class,
            'domains' => ['weibo.com']
        ]
    ];

    /**
     * 解析视频 URL
     */
    public function parse($url)
    {
        $platform = $this->getPlatform($url);
        
        if (!$platform) {
            return ['code' => 201, 'msg' => '不支持的视频平台'];
        }

        $parserClass = self::$platforms[$platform]['class'];
        return $parserClass::parse($url);
    }

    /**
     * 根据 URL 获取平台标识
     */
    private function getPlatform($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return null;
        }

        $host = preg_replace('/^www\./', '', $host);

        foreach (self::$platforms as $platform => $config) {
            foreach ($config['domains'] as $domain) {
                if (strpos($host, $domain) !== false) {
                    return $platform;
                }
            }
        }

        return null;
    }

    /**
     * 获取所有支持的平台列表
     */
    public function getSupportedPlatforms()
    {
        return array_keys(self::$platforms);
    }
}
