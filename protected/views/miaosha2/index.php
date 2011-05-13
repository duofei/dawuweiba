<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-conten">
		<div class="miaosha-info">
			<!-- 邀请好友 -->
			<div class="invite">
				<div class="fl ma-t10px ma-l10px"><?php echo CHtml::image(resBu('miaosha2/images/new.gif'));?></div>
				<div class="fl ma-l5px">邀请好友有奖：成功邀请好友，同获10元返利(无上限)。　邀请好友:</div>
				<div class="fl ma-t5px"><?php echo l(CHtml::image(resBu('miaosha2/images/invite_icon.jpg')), url('my/default/inviteurl'));?></div>
				<div class="fr ma-r5px ma-t10px cursor"><?php echo CHtml::image(resBu('miaosha2/images/close.gif'), '关闭', array('id'=>'inviteClose'));?></div>
				<div class="clear"></div>
			</div>
			<!-- 秒杀信息 -->
			<div class="m-info ma-t10px">
				<div class="fl  fb">价格:</div>
				<div class="fl ma-l5px"><?php echo CHtml::image(resBu('miaosha2/images/1yuan.gif'));?></div>
				<div class="fl ma-l20px "><span class="fb">总计:</span><?php echo $todayCountOrderNum;?>单(<a href="#">已抢<?php echo $todayCompleteOrderNum;?>单</a>)</div>
				<div class="clear"></div>
			</div>
			<!-- 秒杀商铺菜品信息 -->
			<?php echo CHtml::beginForm(url('miaosha/post'), 'post', array('id'=>'postform'));?>
			<input type="hidden" name="miaoshaid" id="miaoshaid" value="0" />
			<input type="hidden" name="goodsid" id="goodsid" value="0" />
			<?php $i=0;?>
			<?php foreach ($miaoshalist as $m):?>
			<div class="goods-list ma-t5px">
				<div class="fl  fb lh30px"><?php if($i===0){ echo '菜品:';} else {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}?></div>
				<div class="fl" style="width:575px">
					<?php if($m->state==Miaosha::STATE_OVER || $shopInArea[$m->shop->id]=='disabled'):?>
					<div class="ma-l5px ma-b5px lh24px cblack pa-l10px" style="border:1px solid #E8BC23; height:24px; background:#FFF9D6;">
						<?php if($m->state==Miaosha::STATE_OVER):?>
						温馨提示: <?php echo $m->shop->shop_name;?>的菜太火爆了，已经被抢光了，请您选择另一家进行秒杀。
						<?php elseif($shopInArea[$m->shop->id]=='disabled'):?>
						温馨提示: <?php echo $m->shop->shop_name;?>不在您的配送范围内，无法参与秒杀。请<span class="cred"><a href="<?php echo app()->homeUrl;?>">正常点餐</a></span>或<span class="cred cursor showMapClick">更改您的地址</span>
						<?php endif;?>
					</div>
					<?php endif;?>
					<ul class="subfl ma-l5px" style="width:560px">
						<li class="shop-name pa-l10px fb cred" title="<?php if($shopInArea[$m->shop->id]=='disabled') echo '(不在配送范围之内)'?>"><?php echo $m->shop->shop_name;?></li>
						<li class="ma-r5px"><?php echo CHtml::image(resBu('miaosha2/images/M_r11_c34.jpg'));?></li>
						<?php foreach ($m->miaoshaGoods as $g):?>
						<li class="goods <?php if($m->state==Miaosha::STATE_OVER || $shopInArea[$m->shop->id]=='disabled') echo 'disabled';?>" gid="<?php echo $g->goods_id;?>" mid="<?php echo $g->miaosha_id;?>">
						<?php echo $g->goods->name;?>(原价<?php echo $g->goods->wmPrice;?>元)</li>
						<?php endforeach;?>
						<li class="clear" style="font-size:0px; height:0px;"></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<?php $i++;?>
			<?php endforeach;?>
			<div class="pa-b5px ma-t10px">
				<div class="fl  fb lh30px">秒杀:</div>
				<div class="fl showtime">
					<span id="timeH">23</span>
					<span id="timeI">59</span>
					<span id="timeS">59</span>
					<div class="clear"></div>
				</div>
				<div class="fl ma-l10px">
					<?php if($myTodayMiaosha>0):?>
					<div>过</div>
					<?php endif;?>
					<div class="" id="showGoodsName"><span class="fb">请选择：</span>秒杀午餐</div>
					<div class="ma-t5px"><?php echo CHtml::image(resBu('miaosha2/images/btn1.jpg'), '提交', array('id'=>'submit'));?></div>
				</div>
				<div class="clear"></div>
			</div>
			<?php echo CHtml::endForm();?>
			<div class="mline1px"></div>
			<div class="ma-t10px fb">秒杀流程</div>
			<div class="ma-t5px ma-b20px" style="height:27px;">
				<?php if(user()->isGuest):?>
				<div class="fl mslc cred"><?php echo l('请登陆', url('site/login', array('referer'=>aurl('miaosha/index'))));?></div>
				<?php else:?>
				<div class="fl mslc">已登陆</div>
				<?php endif;?>
				<div class="fl jiantou"></div>
				<?php if($lastLatLng[0]):?>
				<div class="fl mslc">设置您的位置(已设置)</div>
				<?php else:?>
				<div class="fl mslc cred"><?php echo l('设置您的位置(未设置)', 'javascript:void(0);', array('class'=>'showMapClick'));?></div>
				<?php endif;?>
				<div class="fl jiantou"></div>
				<?php if($userAddressCount > 0):?>
                <div class="fl mslc">填写送餐地址(已填写)</div>
                <?php else:?>
                <div class="fl mslc cred"><?php echo l('填写送餐地址(未填写)', url('my/address/list'));?></div>
                <?php endif;?>
				<div class="fl jiantou"></div>
				<?php if($user->approve_state == User::APPROVE_STATE_VERIFY):?>
                <div class="fl mslc">用户认证(已认证)</div>
                <?php else:?>
                <div class="fl mslc"><?php echo l('用户认证(未认证)', url('my/default/approve'), array('class'=>'cred'));?></div>
                <?php endif;?>
				<div class="fl jiantou"></div>
				<div class="fl mslc">等待秒杀</div>
<?php if(user()->isGuest):?>
	<?php echo CHtml::image(resBu('miaosha2/images/tip.gif'), '', array('style'=>'position:relative; top:-60px; left:-35px;'));?>
<?php elseif (!$lastLatLng[0]):?>
	<?php echo CHtml::image(resBu('miaosha2/images/tip.gif'), '', array('style'=>'position:relative; top:-60px; left:80px;'));?>
<?php elseif ($userAddressCount==0):?>
	<?php echo CHtml::image(resBu('miaosha2/images/tip.gif'), '', array('style'=>'position:relative; top:-60px; left:240px;'));?>
<?php elseif ($user->approve_state != User::APPROVE_STATE_VERIFY):?>
	<?php echo CHtml::image(resBu('miaosha2/images/tip.gif'), '', array('style'=>'position:relative; top:-60px; left:400px;'));?>
<?php endif;?>
				<div class="clear"></div>
			</div>
			<div class="mline1px"></div>
			<div class="ma-t10px fb">秒杀技巧</div>
			<div class="ma-t5px">我爱外卖抢购午餐是以下定单最快为抢购成功，所以要在抢购前准备好4步，首先注册用户，其次要选择自己所在的地址，再次要填写好送餐的地址，最后一定要进行用户认证。</div>
			<div class="ma-t10px"><?php echo l(CHtml::image(resBu('miaosha2/images/yaoqing.png')), url('my/default/inviteurl'));?></div>
			<div class="ma-t10px" style="height:390px">
				<iframe scrolling="no" frameborder="0" src="http://www.connect.renren.com/widget/liveWidget?api_key=49e422d84b694b69ba5e3c5809db4102&url=http%3A%2F%2Fwww.52wm.com%2Fmiaosha&desp=%E4%B8%80%E5%85%83%E5%8D%88%E9%A4%90%E7%81%AB%E7%83%AD%E8%BF%9B%E8%A1%8C%E4%B8%AD" style="width:610px;height:390px;"></iframe>
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<!-- 地图位置处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
	'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'◎请在电子地图上查询您的位置',
        'autoOpen'=>false,
		'width' => 830,
		'height' => 540,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
    ),
));
?>
<iframe id="ShowMapIframe" src="<?php echo aurl('ditu/search', array('other'=>'miaosha'));?>" width="100%" height="495" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script type="text/javascript">
var activeTime = <?php echo $miaoshalist[0]->active_time - time();?>;
var interval;
var btn2 = "<?php echo resBu('miaosha2/images/btn1.jpg'); ?>";
$(function(){
	interval = setInterval("showStartTime()", 1000);
	
	/* 邀请好友 */
	$('#inviteClose').click(function(){
		$('.invite').hide();
	});
	
	/* 设置左右高 */
	setLeftRightHeight(1080);

	/* 菜品处理 */
	$('.goods').hover(function(){
		if(!$(this).hasClass('disabled') && !$(this).hasClass('goods-select'))
			$(this).addClass('goods-hover');
	}, function(){
		$(this).removeClass('goods-hover');
	});
	$('.goods').click(function(){
		if(!$(this).hasClass('disabled') && !$(this).hasClass('goods-select')) {
			var miaoshaid = $(this).attr('mid');
			var goodsid = $(this).attr('gid');
			$('#miaoshaid').val(miaoshaid);
			$('#goodsid').val(goodsid);
			$('.goods').removeClass('goods-select');
			$(this).addClass('goods-select');
			$('#showGoodsName').html('<span class="fb">已选择：</span>' + $(this).html());
		}
	});

	/* 显示位置选择 */
	$('.showMapClick').click(function(){
		$("#ShowMap").dialog("open");
	});

	/* 提交表单 */
	$('#submit').click(function(){
		if($(this).attr('src') == btn2) {
			$('#postform').submit();
		}
	});
});
function showStartTime(){
	if(activeTime < 0) {
		$('#submit').attr('src', btn2);
		$('#timeH').html('00');
		$('#timeI').html('00');
		$('#timeS').html('00');
		clearInterval(interval);
	} else {
		var html = '';
		var s = activeTime%60;
		var m = parseInt(activeTime/60)%60;
		var h = parseInt(activeTime/3600);
		if(s < 10) s = 0 + s;
		if(m < 10) m = 0 + m;
		if(h < 10) h = 0 + h;
		$('#timeH').html(h);
		$('#timeI').html(m);
		$('#timeS').html(s);
		activeTime--;
	}
}
function closeLocationMap(url) {
	$("#ShowMapIframe").attr('src', url);
	$("#ShowMap").dialog("close");
	setTimeout("location.reload()", 1000);
}
</script>