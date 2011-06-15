<?php
/**
 * 该脚本是用来处理打印机在接受或取消订单后的操作
 * 打印机接受或取消订单后请求该脚本时附带的get参数请参照《GPRS打印机订单打印说明文档.docx》
 * 请求的参数编码为gb2312
 */


require './common.php';

if (isset($_GET['OrderState']) && isset($_GET['a'])) {
    echo (int)set_print_state($_GET['a'], $_GET['OrderState']);
    exit(0);
}

$orderno = strip_tags(trim($_GET['o']));
if (empty($orderno)) exit(0);


//file_put_contents('../logs/reply9999.log', var_export($_GET, true));

// $_GET['ak'] 为中文，编码为gb2312，此脚本为utf-8编码，所以在进行比较的时候先把“您的订单已接受！”转为gb2312，然后再进行比较是否相等
$ak = strip_tags(trim($_GET['ak']));
$accept_refuse = ($ak == iconv('utf-8', 'gb2312', '您的订单已接受！'));
$m = $accept_refuse ? '': iconv('gb2312', 'utf-8', strip_tags(trim($_GET['m'])));

/*
 * 订单状态 3接受，4取消
 */
$status = $accept_refuse ? 3 : 4;
$dt = strip_tags(trim($_GET['dt']));
$dt = $dt ? $dt : '';
$sql = "UPDATE $tblOrder SET `is_print`=1, `status`=$status, `deliver_time`='$dt', `cancel_reason`='$m' WHERE `id`=$orderno";
//$sql = "UPDATE $tblOrder SET `is_print`=1 WHERE `id`=$orderno";
$result = mysql_query($sql);

echo (int)$result;

if ($result) {
    $order = fetchOneOrder($orderno);
    if ($order) {
        if (filter_mobile($order['telphone']))
            $to[] = $order['telphone'];
            
        if (empty($to) && filter_mobile($order['mobile']))
            $to[] = $order['mobile'];

        if (empty($to)) exit(0);
        
        $orderid = $order['order_sn'] . $order['id'];
        
        $sql = 'select `telphone`, `shop_name` from ' . $tblShop . ' where `id` = ' . $order['shop_id'];
        $result = mysql_query($sql);
        $shop = mysql_fetch_assoc($result);
        
        if ($accept_refuse)
            // 订单被接受
            $content = iconv('utf-8', 'gb2312', '您在' . trim($shop['shop_name']) . '定的外卖预计' . $dt . '送达，请保持电话畅通，单号：' . $orderid. '，http://52wm.com');
        else {
            // 订单被取消
            if ($shop) {
                $telphone = $shop['telphone'];
                preg_match('/([\d\-]+)[\/\,\，\s]*/i', $telphone, $matches);
                $shop_contact = '如有疑问请致电商铺：' . $matches[1];
            }
            else
                $shop_contact = '';
            $m = (strtolower($m) == 'timeout') ? '人手不足' : $m;
            $content = iconv('utf-8', 'gb2312', '很抱歉，您在我爱外卖网下的订单'. $orderid .'已被商家取消，取消原因:'. $m .'。' . $shop_contact);
        
        }
        // 发送通知短信
        $d = send_sms($to, $content, $time);
        echo $d;
    }
}



