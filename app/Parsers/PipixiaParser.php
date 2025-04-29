<?php

namespace App\Parsers;

use App\Utils\Common;

class PipixiaParser extends BaseParser
{
    protected static function getHeaders()
    {
        return [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
            'Referer: https://www.pipix.com/'
        ];
    }

    public static function parse($url)
    {
        $loc = Common::getLocation($url);

        if (strpos($loc, '/item/') === false) {
            return self::error(400, '无效的URL格式');
        }

        preg_match('/item\/([^?]*)/', $loc, $id);
        if (empty($id[1])) {
            return self::error(400, '无法解析视频 ID');
        }

        $res = Common::getCurl("https://h5.pipix.com/bds/cell/cell_h5_comment/?cell_id={$id[1]}&aid=1319&app_name=super", null, self::getHeaders());
        $data = json_decode($res, true);

        if (!$data) {
            return self::error(400, '未找到视频数据');
        }

        $item = $data['data']['cell_comments'][0]['comment_info']['item'];
        $video_url = $item['video']['video_high']['url_list'][0]['url'];

        if ($video_url) {
            return self::success([
                'title' =>  $item['content'],
                'author' => $item['author']['name'],
                'url' => $video_url
            ]);
        }
        return self::error(201, '未找到视频URL');
    }
}
