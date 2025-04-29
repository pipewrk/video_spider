<?php

namespace App\Parsers;

use App\Utils\Common;
use App\Services\CookieManager;

class WeiboParser extends BaseParser
{
    protected static function getHeaders()
    {
        $cookie = CookieManager::getInstance()->getCookie('weibo');

        if (empty($cookie)) {
            return self::error(400, '请先设置微博 cookie');
        }

        return [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
            'Referer: https://weibo.com/',
            'Cookie: ' . $cookie
        ];
    }

    public static function parse($url)
    {
        if (strpos($url, 'show?fid=')) {
            preg_match('/fid=(.*)/', $url, $id);
        } else {
            preg_match('/\d+\:\d+/', $url, $id);
        }

        if (count($id) < 1) {
            return self::error(400, '无法解析视频 ID');
        }

        $cid = $id[1] ?? $id[0];
        $data = 'data={"Component_Play_Playinfo":{"oid":"' . $cid . '"}}';

        $arr = Common::getCurl("https://weibo.com/tv/api/component?page=/tv/show/{$cid}", $data, self::getHeaders());
        $data = json_decode($arr, true);
        $item = $data['data']['Component_Play_Playinfo'];

        if (!$item) {
            return self::error(500, '视频数据解析失败');
        }

        $video_url = $item['urls'];

        if ($video_url) {
            return self::success([
                'title' => $item['title'],
                'author' => $item['author'],
                'avatar' => self::fixUrl($item['avatar']),
                'time' => $item['real_date'],
                'cover' => self::fixUrl($item['cover_image']),
                'url' => self::fixUrl($video_url[key($video_url)])
            ]);
        }
        return self::error(201, '未找到视频URL');
    }

    private static function fixUrl($url)
    {
        if (strpos($url, 'http') === 0) {
            return $url;
        }
        return 'https:' . $url;
    }
}
