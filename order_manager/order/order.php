<?php
$no = strip_tags(trim($_GET['a']));
@conn_log($no);

require './common.php';

checkPrinterState($no);

$order = fetchNewOrder($no);

if (empty($order)) exit('no order');

//print_r($order);

$data = makeOrderFormat($order);
//echo $data;exit;

echoOrder($data);

exit();

/**
 * 记录每次连接日志
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
