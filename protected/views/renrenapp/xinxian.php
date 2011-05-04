<script type="text/javascript">
function sendFeed(){
	var feedSettings = {
		"template_bundle_id": 8,
		"template_data":{
             "feedtype":"我是 feedtype",
             "content":"支持校内，情系人人！",
             "actor":"我是actor"
		},
		"body_general": "here body_general",
		"callback": function(ok){console.log('here in feed callback!!'+ok);},
		"user_message_prompt": "here user_message_prompt",
		"user_message": "here user_message"
	};
	XN.Connect.showFeedDialog(feedSettings);
}

</script>
<a href="#" onclick="sendFeed();return false;">发送自定义新鲜事</a>