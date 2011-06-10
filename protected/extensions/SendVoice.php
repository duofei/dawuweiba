<?php
class SendVoice
{
	/**
	 * 调用短信接口发送短信
	 * @param string $to 接受手机号或电话号码
	 * @param integer $content 验证码内容
	 * @return string api接口返回内容
	 */
	public static function send_voice($to)
	{
	    if (empty($to))
	        return false;

	    /*
	     * 通过http方式调用接口
	     */
	    $url = 'http://221.194.44.133/outcallInterface.php';	// 语音
	    //$url = 'http://221.194.44.133/SMSInterface.php';		// 短信
	    /*
	     * http://yzt.136u.com
	     */
	    $userid = param('yzt_userid');
		$userkey = param('yzt_key');
	    
	    $md5 = md5($userid . "0" . $to . $userkey);
		$curlPost ='UserId='. $userid . '&phone=' . $to . '&Md5Str=' . $md5;

		$client = new CdCurl();
	    $data = $client->post($url, $curlPost)->rawData();
	    return $data;
	}
	
	/**
	 * 过滤电话，只保留手机号
	 * @param string $n 待过滤字符串
	 * @return string|null 如果是手机号则返回电话号码，如果不是返回null
	 */
	public static function filter_phone($n)
	{
	    /*
	     * 粗略过滤
	     */
	    // $p = '/^1(3|5|8)\d{9}$/';
	    
	    /*
	     * 精确过滤，注意及时更新号段
	     * 以1开头符号号段并且是11位的数字
	     */
	    $p1 = '/^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/';
	    $p2 = '/^0[0-9]{2,3}[0-9]{7,8}$/';
	    if (preg_match($p1, $n) || preg_match($p2, $n))
	        return $n;
	    else
	        return null;
	}
}