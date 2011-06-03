<!-- 商铺资料 -->
<div class="fl main ma-b10px">
    <?php $this->beginWidget('WmCornerBox', array(
        'htmlOptions' => array('class'=>'corner-gray shop-detail'),
    ));?>
	<div class="fl ma-r10px ma-b10px shop-pic"><?php echo $shop->logoHtml;?></div>
	<ul class="fl lh20px shop-profile">
		<li>
		    <h1 class="f16px cblack ma-b5px fl"><?php echo $shop->nameLinkHtml;?>&nbsp;</h1>
		    <?php echo $shop->isNewIcon;?>
    	    <?php echo $shop->isSanitaryIcon;?>
    	    <?php echo $shop->isCommercialIcon;?>
		    <?php //echo l('查看地图', '#', array('class'=>'f14px cred'));?>
		    <div class="clear"></div>
		</li>
		<?php if ($shop->buy_type != Shop::BUYTYPE_TELPHONE):?><li>电话：<?php echo $shop->telphone;?></li><?php endif;?>
		<li>地址：<?php echo $shop->address;?></li>
		<?php if(Location::getLastVisit()):?>
		<?php if($shop->distanceText):?><li>距离您：<span style="color:#40a8ca;"><?php echo $shop->distanceText;?></span></li><?php endif;?>
		<?php else: ?>
		距离您：您还没有设置您的位置，<a href="javascript:meishiMapSearch();">设置我的位置</a>。
		<?php endif;?>
		<li>状态：<?php echo $shop->isOpeningText;?></li>
		<li></li>
	</ul>
	<ul class="fr lh20px ma-t5px ma-r20px shop-rating">
		<li>
			<div class="fl">服务：</div>
			<div class="fl star-big-gray ma-t5px"><div class="star-big-color" style="width:<?php echo $shop->serviceStarWidth;?>px;"></div></div>
			<div class="clear"></div>
		</li>
		<li>
			<div class="fl">口味：</div>
			<div class="fl star-big-gray ma-t5px"><div class="star-big-color" style="width:<?php echo $shop->tasteStarWidth;?>px;"></div></div>
			<div class="clear"></div>
		</li>
		<li><a href="<?php echo url('shop/show', array('shopid'=>$shop->id, 'tab'=>'rating'));?>">共有<?php echo $shop->taste_mark_nums;?>个点评</a></li>
	</ul>
	<div class="clear"></div>
	<ul class="lh24px">
		<?php if ($shop->transport_time):?><li>送餐时间：<?php echo $shop->transport_time;?></li><?php endif;?>
		<?php if ($shop->matchTransportCondition):?><li>起送条件：<?php echo $shop->matchTransportCondition;?></li><?php endif;?>
		<?php if ($shop->desc):?><li>店铺简介：<?php echo $shop->desc;?></li><?php endif;?>
		<li>
		    <?php echo l('收藏该店铺', url('shop/favorite', array('shopid'=>$shop->id)), array('class'=>'ma-r10px shop-favorite'));?>&nbsp;|&nbsp;
		    <span class="cgray">分享到：
		    	<a class="share2renren" href="javascript:void(0);" title="分享到人人网" share_type="link" share_link="<?php echo $shop->absoluteUrl;?>" share_title="<?php echo sprintf(param('share_title'), $shop->shop_name);?>" share_description="<?php echo sprintf(param('share_description'), $shop->desc, $shop->absoluteUrl);?>">
		    		<?php echo CHtml::image(resBu('images/pixel.gif'), '分享到人人网', array('class'=>'bg-icon icon-renren', 'align'=>'texttop'))?>
		    	</a>
		    	<a class="share2kaixin001" href="javascript:void(0);" title="分享到开心网" share_link="<?php echo $shop->absoluteUrl;?>" share_title="<?php echo sprintf(param('share_title'), $shop->shop_name);?>" share_description="<?php echo sprintf(param('share_description'), $shop->desc, $shop->absoluteUrl);?>">
		    		<?php echo CHtml::image(resBu('images/pixel.gif'), '分享到开心网', array('class'=>'bg-icon icon-kaixin001', 'align'=>'texttop'))?>
		    	</a>
		    	<a class="share2sinat" href="javascript:void(0);" title="分享到新浪微博" appkey="<?php echo param('sinaApiKey');?>" share_link="<?php echo $shop->absoluteUrl;?>" share_title="<?php echo sprintf(param('share_title'), $shop->shop_name) . sprintf(param('share_description'), $shop->desc, null);?>" share_pic="<?php echo $shop->logoUrl;?>">
		    		<?php echo CHtml::image(resBu('images/pixel.gif'), '分享到新浪微博', array('class'=>'bg-icon icon-sinat', 'align'=>'texttop'))?>
		    	</a>
		    	<a class="share2qqt" href="javascript:void(0);" title="分享到腾讯微博" share_link="<?php echo $shop->absoluteUrl;?>" share_title="<?php echo sprintf(param('share_title'), $shop->shop_name) . sprintf(param('share_description'), $shop->desc, null);?>" share_pic="<?php echo $shop->logoUrl;?>">
		    		<?php echo CHtml::image(resBu('images/pixel.gif'), '分享到腾讯微博', array('class'=>'bg-icon icon-qqt', 'align'=>'texttop'))?>
		    	</a>
		    </span>
		</li>
	</ul>
	<?php if ($shop->buy_type == Shop::BUYTYPE_TELPHONE):?><p class="f16px cred fb ma-t10px">该店铺当前只能进行电话预订，选择美食后可以查看店铺电话号码！</p><?php endif;?>
    <?php $this->endWidget();?>
    
	<?php if($groupon):?>
    <?php $this->beginWidget('WmCornerBox', array(
        'htmlOptions' => array('class'=>'corner-gray shop-detail ma-t10px'),
    ));?>
    		<p>当前楼宇：<span class="cred"><?php echo $location->name;?></span></p>
                
            <div class="ma-t20px ma-l30px groupon-price1">
            	<div class="fl groupon-price2" style="width:<?php echo $groupon->priceWidth;?>px;"></div>
                <div class="fl groupon-price3"></div><div class="clear"></div>
        	</div>
            <p class="ma-t10px ma-l30px">
            	达成条件：订单总额达到<span class="cred"><?php echo $groupon->shop_group_price;?>元</span>。目前订餐总额：<span class="cred"><?php echo $groupon->amountPrice;?>元</span>。
            	<?php if($groupon->amountPrice > $groupon->shop_group_price):?>
                <span>已经达成同楼订餐，还可以继续订餐</span>
                <?php else:?>
                <span>还未达成同楼订餐</span>
            	<?php endif?>
            </p>
    <?php $this->endWidget();?>
	<?php endif;?>
</div>
<div class="fr sidebar">
<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'corner-gray-title shop-announcement'),
    'title' => '店铺公告',
));?>
<div class="lh20px m10px"><?php echo $shop->announcementText;?></div>
<?php $this->endWidget();?>
</div>
<div class="clear"></div>
<!-- 菜单 -->
<div class="fl main">
	<!-- 电话预订商家开始 -->
	<?php if($shop->buy_type==Shop::BUYTYPE_TELPHONE):?>
	
	<?php $this->beginWidget('WmCornerBox', array(
	    'htmlOptions' => array('class'=>'corner-gray-title ma-b10px phone-order none'),
	    'title' => '电话预订',
		'id' => 'phoneOrderCornerBox'
	));?>
		<div class="bg-pic fl ma-l10px"></div>
     	<div class="fl ma-l20px w660px">
         	<h4 class="f16px bline lh30px" >请拨打店铺电话<span class="f20px cred"><?php echo $shop->telphone;?></span>进行预订</h4>
         	<div id="phoneOrderCornerContent"></div>
		 	<div class="lh30px f14px">您与店铺核对金额，如果发现店铺或网站的错误，请您<?php echo l('告诉我们', url('correction/create'));?>，谢谢！</div>
        </div>
        <div class="clear"></div>
	<?php $this->endWidget();?>
	
	<?php endif;?>
	<!-- 电话预订商家结束 -->
	<?php $this->beginWidget('WmCornerBox', array(
        'htmlOptions' => array('class'=>'corner-gray-title shop-menu'),
    ));?>
    <ul class="tab-bg-gray cred bline pa-l10px tab subfl">
		<li class="ac ma-r10px f14px fb <?php echo $tab_menu;?>"><?php echo l('在线菜单', url('shop/show', array('shopid'=>$shop->id)));?></li>
		<li class="ac ma-r10px f14px fb <?php echo $tab_rating;?>"><?php echo l('热门点评', url('shop/show', array('shopid'=>$shop->id, 'tab'=>'rating')));?></li>
		<li class="ac ma-r10px f14px fb <?php echo $tab_comment;?>"><?php echo l('用户留言', url('shop/show', array('shopid'=>$shop->id, 'tab'=>'comment')));?></li>
		<li class="ac ma-r10px f14px fb <?php echo $tab_promotion;?>"><?php echo l('优惠信息', url('shop/show', array('shopid'=>$shop->id, 'tab'=>'promotion')));?></li>
		<?php if($shop->is_voucher):?>
		<li class="ac ma-r10px f14px fb <?php echo $tab_voucher;?>"><?php echo l('优惠券', url('shop/show', array('shopid'=>$shop->id, 'tab'=>'voucher')));?></li>
		<?php endif;?>
	</ul>
    <?php echo $this->renderPartial($tab, $$tab);?>
    <?php $this->endWidget();?>
</div>
<div class="fr sidebar">
<?php if ($shop->is_group):?>
	<?php $this->beginWidget('WmCornerBox', array(
		'htmlOptions' => array('class'=>'corner-gray-title ma-b10px'),
	    'title' => '同楼订餐时间',
	));?>
	<div class="cgray lh24px ma-t10px pa-l10px">
		<p>送餐时间：11:30-12:00</p>
     	<p>截止时间：10:00</p>
    </div>
    <p class=" ac ma-t10px cgray ">距离结束时间还有</p>
    <p class="clock ma-t5px">
    	<span id="hour" style=""><?php echo $remaintime['hours'];?></span>
        <span id="minute" style="margin-left:32px;"><?php echo $remaintime['minutes'];?></span>
        <span id="second" style="margin-left:25px;"><?php echo $remaintime['seconds'];?></span>
    </p>
	<?php $this->endWidget();?>
<?php endif;?>
    <div id="cart" view="small_cart"><?php $this->widget('SidebarCart');?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
$(function(){
	<?php if (!user()->isGuest):?>
	loadRenrenLoader();
	$('.shop-favorite').click(favoriteShop);
	$('.goods-favorite').click(favoriteGoods);
	<?php endif;?>

	$('.btn-buy').live('click', buyOneGoods);
	$('.share2renren').click(share2Renren);
	$('.share2kaixin001').click(share2Kaixin001);
	$('.share2sinat').click(share2Sinat);
	$('.share2qqt').click(share2Qqt);
	setInterval(remainTimeClock, 1000);
});
function remainTimeClock()
{
	var h = parseInt($('#hour').text());
	var m = parseInt($('#minute').text());
	var s = parseInt($('#second').text());
	var tmp = new Date(2010,1,1,h,m,s,0);
	var ms = tmp.getTime() - 1000;
	var d = new Date(ms);
	$('#hour').text(d.getHours());
	$('#minute').text(d.getMinutes());
	$('#second').text(d.getSeconds());
}
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>