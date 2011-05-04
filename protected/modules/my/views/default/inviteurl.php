<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/default/inviteurl");?>">邀请好友</a></li>
	</ul>
</div>
<div id="invite" style="background:#fff;">
	<div class="title"><a class="btn" href="<?php echo url('my/integral/bcintegral');?>"></a>邀请好友 <?php echo CHtml::image(resBu('images/invite_tip.png'));?></div>
	<div class="firstlist">
		<div class="box" title="分享到人人网" onclick="invite2Renren()">
			<div class="icon_renren"></div>
			<span>人人网</span>
		</div>
		<div class="box" title="分享到新浪微博" onclick="invite2SinaWeibo()">
			<div class="icon_sinaweibo"></div>
			<span>新浪微博</span>
		</div>
		<div class="box" title="分享到腾讯微博" onclick="invite2QqWeibo()">
			<div class="icon_qqweibo"></div>
			<span>腾讯微博</span>
		</div>
		<div class="box" title="分享到开心网 " onclick="invite2Kaixin001()">
			<div class="icon_kaixin001"></div>
			<span>开心网</span>
		</div>
	</div>
	<div class="list none"></div>
	<div class="lh24px f14px ma-b20px ma-t20px">
      	<p>上面没有您要想要邀请方式，您还可以直接复制下面链接地址发给您的好友。</p>
      	<p class="lh30px ma-t10px">
      		<?php echo CHtml::textField('inviteUrl', aurl('site/signup', array('invite'=>$invite)), array('class'=>'txt', 'id'=>'inviteUrl', 'style'=>'width:600px;'));?>
      		<br />
      		<input class="cwhite fb" type="button" value="复制链接地址" id="copyUrl" style="width:120px; height:28px; background:#c8181b; "/>
       	</p>
	</div>
</div>
<script type="text/javascript">
$(function(){
	loadRenrenLoader();
	$("#copyUrl").click(function(){
		var url = $('#inviteUrl').val();
		if (document.all){
			window.clipboardData.setData('text', url);
			alert("复制成功，赶快把链接发给你的好友吧！");
		} else {
			alert("您的浏览器不支持剪贴板操作，请自行复制。");
	  	}
	});
	$(".box").mouseover(function(){
		$(this).addClass('selectbox');
	});
	$(".box").mouseout(function(){
		$(this).removeClass('selectbox');
	});
});

var inviteUrl = '<?php echo aurl("site/signup", array("invite"=>$invite));?>';
var inviteTitle = '我爱外卖网邀请好友双向获取白吃点啦！1白吃点等于一元钱哦！';
var inviteDesc = '赶快通过我的链接注册吧，我们都将获取10点白吃点。';
var inviteText = inviteTitle + inviteDesc;

function invite2Renren()
{
	var shareInfo = {
		'medium': 'link',
		'title': inviteText + inviteUrl,
		'link': inviteUrl,
		'image_src': '',
		'message': '',
		'description': ''
	};
	XN.Connect.showShareDialog(shareInfo);
}

function invite2SinaWeibo()
{
	var title = inviteText;
	var appkey = "<?php echo param('sinaApiKey');?>";
	var shareUrl = inviteUrl;
	var picUrl = '';
	var url = 'http://v.t.sina.com.cn/share/share.php?appkey=' + appkey + '&title=' + title + '&url=' + shareUrl + '&pic=' + picUrl;
	wopen(url, 'shareSinaWeibo', 700, 400);
}

function invite2Kaixin001()
{
	var title = inviteTitle;
	var desc = inviteDesc + inviteUrl;
	var link = inviteUrl;
	var url = 'http://www.kaixin001.com/repaste/bshare.php?rtitle=' + title + '&rcontent=' + desc + '&rurl=' + link;
	wopen(url, 'shareKaixin001', 700, 400);
}

function invite2QqWeibo(e)
{
	var title = inviteText;
	var shareUrl = inviteUrl;
	var picUrl = ''
	var url = 'http://v.t.qq.com/share/share.php?title=' + title + '&url=' + shareUrl + '&pic=' + picUrl;
	wopen(url, 'shareQqt', 700, 400);
}
</script>
