<?php
class SendSms
{
	/**
	 * 调用短信接口发送短信
	 * @param string|array $to 接受手机号，可以是字符串（多个手机号中间有英文半角逗号分隔），也可以是数组
	 * @param string $content 短信内容，必须为gb2312编码
	 * @param string $time 发送时间，如果为空则为立即发送
	 * @return string api接口返回内容
	 */
	public static function send_sms($to, $content, $time = '')
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
	    $userid = param('winic_userid');
	    $passwd = param('winic_passwd');
	    
	    /*
	     * 通过http方式调用接口
	     */
	    $url = 'http://service.winic.org:8009/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=%s';
	    $url = sprintf($url, $userid, $passwd, trim($to), trim($content), trim($time));
	     
	    $client = new CdCurl();
	    $data = $client->get($url)->rawData();
	    return $data;
	}
	
	/**
	 * 过滤电话，只保留手机号
	 * @param string $n 待过滤字符串
	 * @return string|null 如果是手机号则返回手机号，如果不是返回null
	 */
	public static function filter_mobile($n)
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
}