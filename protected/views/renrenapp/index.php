<a href="<?php echo aurl('renrenapp/xinxian', array('xn_sig_session_key'=>$_GET['xn_sig_session_key']));?>">发布新鲜事</a>
<hr />
<script type="text/javascript">
function sendFeed(){
	var feedSettings = {
		"template_bundle_id": 1,
		"template_data": {
			"feedtype": "我是freetype",
            "content": "[支持校内，情系人人！]",
            "actor": "[我是actor]",
            "images": [{
                "src": "http://fmn028.xnimg.cn/fmn028/pic001/20090330/20/25/head_isr3_91558j206097.jpg",
                "href": 'http://www.24beta.com/'
            }, {
                "src": "http://www.favorpretty.com/upload/20098792556297.jpg",
                "href": 'http://www.24beta.com/'
            }]
		},
		"body_general": "here body_general",
		"callback": function(ok){console.log('here in feed callback!!'+ok);},
		"user_message_prompt": "here user_message_prompt",
		"user_message": "here user_message"
	};
	XN.Connect.showFeedDialog(feedSettings);
}

function display(){
    var friends = document.getElementById('friends');//根据id获取div
    var friendframe = document.getElementById('friendframe');//根据id获取iframe
    friends.style.display = "block";//显示弹层
    friendframe.src="http://www.52wm.com/renrenapp/invite";//将iframe的src属性置为好友选择器的页面
}

function closediv(){
    var friends = document.getElementById('friends');//根据id获取div
    friends.style.display = "none";//将弹层置为隐藏
}

</script>
<a href="#" onclick="sendFeed();return false;">发送自定义新鲜事</a>
<a href="#" onclick="display();return false;">邀请好友</a>

<div id="friends" style="display:none; width:770px; height:680px;">
	<iframe id="friendframe" style="width:770px; height:650px; border:0px;" frameborder="0" />
</div>