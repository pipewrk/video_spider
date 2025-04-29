<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\VideoParser;

$url = $_REQUEST['url'] ?? '';

if (!$url) {
    echo json_encode(['code' => 201, 'msg' => '传入的参数不正确']);
    exit;
}

try {
    $parser = new VideoParser();
    $result = $parser->parse($url);
    echo json_encode($result);
} catch (Exception $e) {
    // 将详细错误信息记录到日志文件中
    error_log('Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());

    echo json_encode([
        'code' => 500,
        'msg' => '发生了一个错误，请稍后再试。'
    ]);
}
