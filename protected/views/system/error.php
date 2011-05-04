<?php
$this->pageTitle = '很抱歉您访问的页面出错啦！';
$this->setPageKeyWords($this->pageTitle);
$this->setPageDescription($this->pageTitle);
?>
<div class="fl icon-404">&nbsp;</div>
<div  class="fl text-404">
	<h3 class="f20px ma-b20px">很抱歉您访问的页面出现了错误......</h3>
	<p class="f14px lh24px indent28px" rel="nofollow">
   	出现这个问题，也许是因为您访问了不正确的链接地址，也可能是由于我们对程序做出了更新，没有即时通知您所造成的。如果对<?php echo l('我爱外卖网', app()->homeUrl, array('class'=>'f16px fb'));?>感兴趣，可以<a class="f16px fb" href="<?php echo url('site/signup');?>">注册</a>成为我爱外卖网会员，我们可以为您提供即时的信息通知服务。我爱外卖网现在的美食已经有数千种之多，点<a class="f16px fb" href="<?php echo app()->homeUrl;?>">这儿</a>就可以来挑选我们为您准备的各种美食。
	</p>
</div>
<div class="clear"></div>

<div style="display:none;" rel="nofollow">
    <p><?php echo $error['message'];?></p>
    <pre><?php echo $error['trace'];?></pre>
</div>