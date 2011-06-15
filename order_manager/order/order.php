<?php
$no = strip_tags(trim($_GET['a']));
//@conn_log($no);

require './common.php';

/*
 * 每次收到打印机请求后，检查并更新打印机状态为在线
 */
checkPrinterState($no);

// 获取一个新订单
$order = fetchNewOrder($no);

if (empty($order)) exit('no order');

//print_r($order);

// 将订单格式化为打印机识别的格式
$data = makeOrderFormat($order);
//echo $data;exit;

// 输出订单
echoOrder($data);

exit();


/**
 * 记录每次连接日志，便于分析打印机网络连接稳定性
 * @param string $no 打印机编号
 */
function conn_log($no)
{
    $filename = '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $no . '.log';
    $str = date('Y-m-d H:i:s') . " - $no - Printer Connected\n";
    $handle = fopen($filename, 'a');
    flock($handle, LOCK_EX);
    fwrite($handle, $str);
    flock($handle, LOCK_UN);
    fclose($handle);
}
