<div class="main fl">
	
	<!-- 过滤条件 -->
	<?php $this->beginWidget('WmCornerBox', array(
		'htmlOptions' => array('class'=>'corner-gray-title ma-b10px'),
		'title'=>'挑选您喜欢的美食口味',
	));?>
    	<ul class="shop-filter subfl ma-l10px lh30px fl ma-r20px fb">
    		<?php foreach ((array)$filters as $v):?>
    		<li class="bg-icon filter_btn pa-l20px"><?php echo $v;?></li>
    		<?php endforeach;?>
    		<div class="clear"></div>
    	</ul>
    	<?php echo CHtml::beginForm(url('shop/search'), 'get');?>
    	<input type="hidden" name="cid" value="<?php echo (int)$_GET['cid'] ? (int)$_GET['cid'] : ShopCategory::CATEGORY_FOOD;?>" />
    	<div class="fl ma-t20px goods-search">
    		<input type="text" name="kw" class="txt lh24px cgray search-goods" value="搜索美食" id="goods-search" />
    		<input type="submit" class="btn-two cred" value="搜 索" />
    	</div>
    	<?php echo CHtml::endForm();?>
    	<div class="clear"></div>
  	<?php $this->endWidget();?>
 	<!-- 商铺列表 -->
 	<div class="corner corner-gray-title shop-list">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content">
			<h3 class="f12px cgray fn lh20px bline">
				<span class="cred fl indent10px f14px">为您找到&nbsp;<span id="shop-nums"><?php echo count($shops);?></span>&nbsp;家<?php echo ShopCategory::getStoreName($_GET['cid']);?></span>
       			<span class="bg-pic sort-btn <?php echo $serviceSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('service_avg', '服务', array('class'=>'block lh24px'));?></span>
       			<span class="bg-pic sort-btn <?php echo $orderSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('order_nums', '人气', array('class'=>'block lh24px'));?></span>
	   			<span class="bg-pic sort-btn <?php echo $tasteSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('taste_avg', '口味', array('class'=>'block lh24px'));?></span>
	   			<div class="clear"></div>
			</h3>
			<div class="wm-shop-list">
			<?php if (count($shops) == 0):?>
				<?php $this->renderPartial('/shop/shop_nolist');?>
			<?php else:?>
				<?php $this->renderPartial('/shop/shop_list', array('shops'=>$shops));?>
			<?php endif;?>
			</div>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
 	<?php if (count($shops) == 0):?>
 	<div class="corner corner-gray-title shop-list ma-t10px">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content">
			<h3 class="f12px cgray fn lh20px bline">
				<span class="cred fl indent10px f14px">目前服务范围</span>
	   			<div class="clear"></div>
			</h3>
			<div class="wm-shop-list">
			<?php $this->renderPartial('/shop/shop_maparea', array('center'=>$center, 'maxMin'=>$maxMin));?>
			</div>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
 	<?php endif;?>
</div>

<div class="sidebar fr">
	<div class="corner corner-gray-title location-promotion ma-b10px">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content lh24px">
			<h3 class="indent10px lh20px bline">
				<span class="fl f14px cred">优惠信息</span>
				<?php echo l('更多&gt;&gt;', url('promotion'), array('target'=>'_blank', 'class'=>'fr ma-r10px cgray f12px fn'))?>
			</h3>
			<?php if (count($promotions) > 0):?>
			<ul class="ma-l5px lh20px">
			<?php foreach ((array)$promotions as $v):?>
				<li><?php echo $v->shopNameGroupText;?></li>
			<?php endforeach;?>
			</ul>
			<?php else:?>
			<div class="m10px">当前地区没有优惠信息</div>
			<?php endif;?>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
 	
 	<div class="corner corner-gray-title hot-goods ma-b10px">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content lh24px">
			<h3 class="indent10px lh20px bline">
				<span class="fl f14px cred">热卖商品</span>
				<?php echo l('更多&gt;&gt;', url('goods/top', array('cid'=>$cid)), array('target'=>'_blank', 'class'=>'fr ma-r10px cgray f12px fn'))?>
			</h3>
			<ul class="ma-l5px lh20px ma-t10px">
			<?php foreach ((array)$goods as $v):?>
				<li class="ma-b5px">
					<div class="goods-name fl"><?php echo $v->getNameLinkHtml(12);?></div>
					<div class="star-small-gray fr ma-r5px ma-t5px"><div class="star-small-color" style="width:<?php echo $v->rateStarWidth;?>px"></div></div>
					<div class="wm-price fr ma-r10px"><?php echo $v->foodGoods->wmPrice;?>元</div>
					<div class="clr cgray"><?php echo $v->shop->getNameLinkHtml(24);?></div>
				</li>
			<?php endforeach;?>
			</ul>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
</div>

<script type="text/javascript">
$(function(){
	$('.shop-filter li.filter_btn').click(function(){
		$(this).toggleClass('filter_btn_checked');
		var selector = '.wm-shop:hidden';
		$('.filter_btn_checked').each(function(){
			var tag = $(this).text();
			selector += '[tags*=' + tag + ']';
		});
		$('.wm-shop').hide();
		$(selector).show();
		var shopNums = $('.wm-shop:visible').length;
		$('#shop-nums').text(shopNums);
	});
	
	// 搜索框文字处理
	var location_search_input = $("#goods-search").val();
	$("#goods-search").blur(function(){
		if($(this).val()=='') {
			$(this).val(location_search_input);
		}
		$(this).removeClass('cblack');
	});
	$("#goods-search").focus(function(){
		if($(this).val()==location_search_input) {
			$(this).val('');
		}
		$(this).addClass('cblack');
	});
});
</script>