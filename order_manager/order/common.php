<?php
$user = 'my52wmprinter';
$pwd = strtoupper(md5('nt41qnoeubrwe'));

$no = strip_tags(trim($_GET['a']));
if (empty($no)) exit(0);

/*
 * 判断用户名密码是否正确，以此判断是法为合法请求，注意打印机内置的账号要绝对保密
 */
$u = strip_tags(trim($_GET['u']));
$p = strip_tags(trim($_GET['p']));
if ($u != $user || $p != $pwd) exit('error');

/*
 * 数据库配置文件
 */
$config = array(
    'host' => 'localhost:6603',
    'dbname' => 'wm_my52wm',
    'user' => 'my52wm',
    'password' => '52wm.com',
	'charset' => 'utf8',
    'persistent' => true,
    'tablePrefix' => 'wm_',
);


// 订单是否审核通过
!defined('IS_VERIFY_STATE') && define('IS_VERIFY_STATE', 1);

// 订单购买方式为网络打印机方式
!defined('BUYTYPE_PRINTER') && define('BUYTYPE_PRINTER', 2);

// 订单字符串中换行替换字符串，要与打印机中设置的一致
!defined('BREAKLINE') && define('BREAKLINE', '%%%%');

// 分隔线
!defined('SEPLINE') && define('SEPLINE', '-------------------------' . BREAKLINE);

// 分隔线加换行
!defined('BREAKSEPLINE') && define('BREAKSEPLINE', BREAKLINE . SEPLINE);

// 已经弃用，原来用来设置是否需要自己组织第二联的内容打印出来，现在已经修改为打印机来控制打印的联数
//$secodn_page = true;

/*
 * 相关数据表名
 */
$tblOrder = '`' . $config['tablePrefix'] . 'Order` `o`';
$tblShop = '`' . $config['tablePrefix'] . 'Shop` `s`';
$tblOrderGoods = '`' . $config['tablePrefix'] . 'OrderGoods` `og`';
$tblPrinter = '`' . $config['tablePrefix'] . 'Printer` `p`';

$conn = initMysql($config);






/**
 * 初始化数据库连接
 * @param array $config
 * @return resource mysql数据库连接资源符
 */
function initMysql($config)
{
    $conn = mysql_connect($config['host'], $config['user'], $config['password']) or exit('connect error');
    mysql_query('set names ' . $config['charset']);
    mysql_select_db($config['dbname']) or exit('select db error');
    return $conn;
}

/**
 * 获取新订单信息
 * 从订单表中获取新订单，满足3个条件：
 * - 审核通过
 * - 购买类型为BUYTYPE_PRINTER
 * - 未打印过的
 * @param string $no 打印机编号
 * @return array $order 订单数组
 */
function fetchNewOrder($no)
{
    if (empty($no)) return null;
    
    global $tblOrder, $tblShop, $tblOrderGoods, $tblPrinter;
    
    $sql = "SELECT `o`.*, `s`.`shop_name` FROM $tblOrder, $tblShop, $tblPrinter";
    $sql .= ' WHERE `o`.`verify_state`=' . IS_VERIFY_STATE;
    $sql .= ' and `o`.`buy_type`=' . BUYTYPE_PRINTER;
    $sql .= ' and `o`.`is_print`=0';
    $sql .= ' and `s`.`id`=`o`.`shop_id`';
    $sql .= " and `p`.`code`='$no'";
    $sql .= ' and `s`.`printer_no`=`p`.`id`';
    $sql .= ' ORDER BY `o`.`id` asc LIMIT 1';
    
    $result = mysql_query($sql);
    $order = mysql_fetch_assoc($result);
    mysql_free_result($result);
    
    return $order;
}

/**
 * 按照格式生成订单字符串，结尾必须以0x0D0x0A结束
 * 具体订单格式参考《GPRS打印机订单打印说明文档.docx》
 * @param array $order
 * @return string 整个订单字符串，包括二联
 */
function makeOrderFormat($order)
{
    if (!is_array($order)) return null;
    
//    global $secodn_page;
    
    $order['customerType'] = 5;
    $order['paymentStatus'] = 7;
    $order['deliver_time'] = trim($order['deliver_time']);
    $order['goods'] = makeGoodsFormat(fetchGoods($order['id']));
    $cardno = '';
    $order['prevOrderId'] = '';
    $order['consignee'] = filterChar('姓名：' . trim($order['consignee']));
    $order['address'] = filterChar('地址：' . trim($order['address']));
    $order['telphone'] = filterChar(trim($order['telphone']));
    $order['paying_amount'] = $order['paid_amount'] ? ($order['amount'] - $order['paid_amount']) : $order['amount'];
    $order['amountExtra'] = $order['paid_amount'] ? "已付款{$order['paid_amount']}元, 应收款{$order['paying_amount']}元" : '';
    $order['message'] = filterChar(trim($order['message']));
    $remark = '';
//    if ($secodn_page) $remark .= makeSecondPage($order);
    $deliverTime = BREAKSEPLINE . '要求时间：' . $order['deliver_time'];
    $username = BREAKLINE . $order['consignee'];
    $address = BREAKLINE . $order['address'];
    
    
    $data = sprintf('#%d*1*%d*', $order['shop_id'], $order['id']);
    $data .= $order['goods'];
//    $data .= sprintf('*%.1f;%.1f;%.1f %s;', $order['dispatching_amount'], 0, $order['amount'], $order['amountExtra']);
    $data .= sprintf('*%.1f;%.1f;%.1f %s %s;', $order['dispatching_amount'], 0, $order['amount'], $order['amountExtra'], $deliverTime . $username . $address);
//    $data .= sprintf('%d;%s;%s;%s;%s;%d;%s;%s*', $order['customerType'], $order['consignee'], $order['address'], $order['deliver_time'], $order['prevOrderId'], $order['paymentStatus'], $cardno, $order['telphone']);
    $data .= sprintf('%d;%s;%s;%s;%s;%d;%s;%s*', $order['customerType'], '', '', '', $order['prevOrderId'], $order['paymentStatus'], $cardno, $order['telphone']);
    $data .= $order['message'] . ' ';// . BREAKLINE . BREAKLINE;
    if ($remark) $data .= BREAKLINE . $remark;
    $data = iconv('utf-8', 'gb2312', $data);
    $data .= '#' . chr(13) . chr(10);
    return $data;
}

/**
 * 生成第二联的内容
 * 此方法现在已经无用
 * @param array $order
 * @return string 二联内容字符串
 */
function makeSecondPage($order)
{
    $goods = fetchGoods($order['id']);
    foreach ($goods as $v)
        $rows[] = $v['goods_nums'] . ' X ' . $v['goods_name'] . ' ' . $v['goods_amount'];
    $goods = implode(BREAKLINE, $rows);
    
    $str = SEPLINE . '电话：' . $order['telphone'] . BREAKSEPLINE;
    $str .= '我爱外卖网' . BREAKLINE . 'http://www.52wm.com/';
    
    $str .= str_repeat(BREAKLINE, 6);
    $str .= '      我爱外卖网订餐单B' . BREAKSEPLINE;
    $str .= date('Y-m-d H:i:s') . BREAKLINE;
    $str .= '订单号：' . $order['id'] . BREAKSEPLINE;
    $str .= $goods . BREAKSEPLINE;
    $str .= '送餐费：' . $order['dispatching_amount'] . BREAKLINE;
    $str .= '总计：' . $order['amount'] . ' ' . $order['amountExtra'] . BREAKSEPLINE;
    $str .= $order['consignee'] . BREAKLINE;
    $str .= $order['address'] . BREAKLINE;
    //$str .= '电话：' . $order['telphone'] . BREAKLINE;
    $str .= '备注：' . $order['message'];
    return $str;
}

/**
 * 输入订单信息
 * 打印机只接受无任何处理的text/plain字符串，并且需要 content-length头信息，所以需要使用ob函数来处理下
 * @param string $data
 * @return void
 */
function echoOrder($data)
{
    ob_start();
    echo $data;
    $data = ob_get_contents();
    $len = ob_get_length();
    ob_get_clean();
    header('content-type: text/plain');
    header('Content-Length: ' . $len);
    echo $data;
}

/**
 * 获取一个订单的餐品列表
 * @param integer $orderid
 * @return array 菜单数据
 */
function fetchGoods($orderid)
{
    global $tblOrderGoods;
    static $data = null;
    
    if (null !== $data) return $data;
    
    $orderid = (int)$orderid;
    if (0 === $orderid) return null;
    
    $sql = "SELECT * FROM $tblOrderGoods WHERE `order_id`=$orderid";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data ? (array)$data : array();
}

/**
 * 格式化定单餐品列表，组合成字符串
 * @param array $goods
 * @return string 菜单按照一定格式组合成的字符串
 */
function makeGoodsFormat($goods)
{
    if (null === $goods) return null;
    
    foreach ($goods as $v) {
        $data[] = $v['goods_nums'] . ',' . $v['goods_name'] . ',' . $v['goods_amount'];
    }
    return implode(';', $data);
}

/**
 * 过滤字符串
 * 将打印机保留的一些分隔符进行过滤
 * @param string $str
 * @return string 过滤之后的字符串
 */
function filterChar($str)
{
    // # ; * 是订单格式保留字符
    $chars = array('#', ';', '*', ',', '囧', '￥', '…');
    $str = str_replace('（', '(', $str);
    $str = str_replace('）', ')', $str);
    $str = str_replace('－', '-', $str);
    $str = str_replace('——', '_', $str);
    $str = str_replace('！', '!', $str);
    $str = str_replace('｜', '|', $str);
    $str = str_replace('《', '<<', $str);
    $str = str_replace('》', '>>', $str);
    $str = str_replace('？', '?', $str);
    $str = str_replace('，', ',', $str);
    $str = str_replace('。', '.', $str);
    $str = str_replace('“', '"', $str);
    $str = str_replace('”', '"', $str);
    return str_replace($chars, '', $str);
}

/**
 * 检查更新打印机状态
 * 每次请求都记录请求时间，用以在后台判断打印机是否在线
 * @param string $code
 * @return mixed 更新状态是否成功
 */
function checkPrinterState($code)
{
    global $tblPrinter;
    $t = $_SERVER['REQUEST_TIME'];
    $sql = "UPDATE $tblPrinter SET `last_time`='$t' WHERE `code`='$code'";
    $result = mysql_query($sql);
    
    return $result;
}

/**
 * 根据订单号从数据库获取一个订单详细信息
 * @param string $orderno 订单号
 * @return array 订单信息
 */
function fetchOneOrder($orderno)
{
    if (empty($orderno)) return null;
    
    global $tblOrder;
    
    $sql = "SELECT `id`, `order_sn`, `telphone`, `mobile`, `shop_id` FROM $tblOrder";
    $sql .= ' WHERE `id`=' . (int)$orderno;
    
    $result = mysql_query($sql);
    $order = mysql_fetch_assoc($result);
    mysql_free_result($result);
    
    return $order;
}

/**
 * 调用短信接口发送短信
 * @param string|array $to 接受手机号，可以是字符串（多个手机号中间有英文半角逗号分隔），也可以是数组
 * @param string $content 短信内容，必须为gb2312编码
 * @param string $time 发送时间，如果为空则为立即发送
 * @return string api接口返回内容
 */
function send_sms($to, $content, $time = '')
{
    if (empty($to) || empty($content))
        return false;

    if (is_array($to)) {
        $to = array_unique($to);
        $to = join(',', $to);
    }

    /*
     * winic.org的账号
     */
    $userid = 'davidwu';
    $passwd = '711226';
    
    /*
     * 通过http方式调用接口
     */
    $url = 'http://service.winic.org:8009/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=%s';
    $url = sprintf($url, $userid, $passwd, trim($to), trim($content), trim($time));

    define('DS', DIRECTORY_SEPARATOR);
    $cdcurl = dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'protected' . DS . 'extensions' . DS . 'CdCurl.php';
    require($cdcurl);
    
    $client = new CdCurl();
    $data = $client->get($url)->rawData();
    return $data;
}

/**
 * 过滤电话，只保留手机号
 * @param string $n 待过滤字符串
 * @return string|null 如果是手机号则返回手机号，如果不是返回null
 */
function filter_mobile($n)
{
    /*
     * 粗略过滤
     */
    // $p = '/^1(3|5|8)\d{9}$/';
    
    /*
     * 精确过滤，注意及时更新号段
     * 以1开头符号号段并且是11位的数字
     */
    $p = '/^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/';
    if (preg_match($p, $n))
        return $n;
    else
        return null;
}

/**
 * 设置打印机状态
 * 只是将数据库中的状态值改变，并不会对终端打印机产生直接影响
 * @param string $printer 打印机编号
 * @param integer $state 打印机状态 0 or 1
 * @return boolean 设置是否成功
 */
function set_print_state($printer, $state)
{
    global $tblPrinter;
    $state = (int)(bool)$state;
    $sql = "UPDATE $tblPrinter SET `status`=$state WHERE `code`='$printer'";
    $result = mysql_query($sql);
    return $result;
}


