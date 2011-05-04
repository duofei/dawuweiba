	<?php if($shop->is_dailymenu == Shop::DAILYMENU_SUPPORT):?>
	<ul class="pa-l10px subfl lh30px bline h30px cblack">
		<li class="ma-r10px">每日菜单：</li>
		<?php foreach((array)DayList::$weeks as $k=>$v):?>
    	<li class="ma-r10px"><?php echo l($v,url('shop/show', array('shopid'=>$shop->id, 'week'=>$k)), array('class'=>$weekdayClass[$k]));?></li>
    	<?php endforeach;?>
    	<div class="clear"></div>
    </ul>
    <?php endif;?>
	<ul class="subfl goods-filter pa-l10px bline lh30px h30px cblack">
		<li class="ma-r10px">美食分类：</li>
    	<?php foreach ((array)$shop->goodsCategories as $v):?>
    	<li class="ma-r10px"><?php echo $v->nameLinkHtml;?></li>
    	<?php endforeach;?>
    	<li class="ma-r10px"><?php echo l('所有美食', '#goodsall', array('id'=>'goodsall', 'class'=>'link-selected'))?></li>
    	<div class="clear"></div>
    </ul>
    <div class="goods-search pa-l10px pa-r5px pa-t5px bline lh30px h30px">
    	<div class="fl">快速搜索：<input type="text" class="txt" name="quick_kw" id="quick_search" /></div>
    	<div class="fr lh24px" style="margin-top:7px;">
			<span class="bg-pic sort-btn <?php echo $sortclass['wm_price'];?> block fr ma-r5px indent10px"><?php echo $sort->link('wm_price', '价格', array('class'=>'block'));?></span>
			<span class="bg-pic sort-btn <?php echo $sortclass['favorite_nums'];?> block fr ma-r5px indent10px"><?php echo $sort->link('favorite_nums', '人气', array('class'=>'block'));?></span>
			<span class="bg-pic sort-btn <?php echo $sortclass['rate_avg'];?> block fr ma-r5px indent10px"><?php echo $sort->link('rate_avg', '口味', array('class'=>'block'));?></span>
			<div class="clear"></div>
    	</div>
    	<div class="clear"></div>
    </div>
    <?php if($goods):?>
    <?php foreach ((array)$goods as $k => $v):?>
	<div class="goods-list pa-t10px pa-b10px bline" category="<?php echo $k;?>">
   		<h2 class="f16px cred indent10px h30px"><?php echo $k;?></h2>
   		<ul>
		<?php foreach ($v as $k => $s):?>
			<?php if ($s->pic):?>
			<li class="fl goods-pic-list-li" gid="<?php echo $s->id;?>">
		   		<div><?php echo $s->PicLinkHtml;?></div>
		      	<div class="fl pa-l5px lh24px pa-t5px cblack goods-pic-fullname"><?php echo $s->fullName;?></div>
		      	<div class="fr pa-r5px goods-pic-favorite"><?php echo $s->favoriteHtml;?></div>
		       	<div class="fr ma-r5px buy-confirm" style="margin-top:7px">
					<?php if (in_array($s->id, $cartGoods)):?>
					<img src="<?php echo resBu('images/pixel.gif');?>" class="bg-icon cart-ok" />
					<?php endif;?>
				</div>
		       	<div class="clear"></div>
		       	<div class="fl pa-5px"><div class="star-small-gray"><div class="star-small-color" style="width:<?php echo $s->rateStarWidth;?>px"></div></div></div>
		       	<div class="fl pa-b5px">
		       		<div class="fl ar lh20px goods-pic-price"><?php echo $s->wmPrice;?>元&nbsp;</div>
		      		<div class="fl ar goods-pic-btn"><?php echo $s->getBuyBtn($week);?></div>
		       	</div>
		       	<?php if ($shop->is_group):?>
		       	<div class="clear"></div>
				<div class="fr ar goods-pic-btn ma-r5px"><?php echo $s->getGroupBtn($week);?></div>
				<div class="fr ar lh20px"><?php echo $s->groupPrice;?>元&nbsp;</div>
				<?php endif;?>
		       	<div class="clear"></div>
			</li>
			<?php endif;?>
		<?php endforeach;?>
		<?php foreach ($v as $k => $s):?>
			<?php if (!$s->pic):?>
			<li class="pa-10px lhnormal goods-item clr li-bg<?php echo $k%2;?>" gid="<?php echo $s->id;?>">
			    <div class="fl shop-desc cblack"><?php echo $s->fullName;?><br /><span class="cgray"><?php echo $s->desc;?></span></div>
			    <div class="fl ma-l10px buy-confirm">
			    <?php if (in_array($s->id, $cartGoods)):?>
			    	<img src="<?php echo resBu('images/pixel.gif');?>" class="bg-icon cart-ok" />
			    <?php endif;?>
			    </div>
			    <div class="fr"><?php echo $s->favoriteHtml;?></div>
			    <div class="fr ma-r10px"><div class="star-small-gray ma-t5px"><div class="star-small-color" style="width:<?php echo $s->rateStarWidth;?>px"></div></div></div>
			    <?php if ($shop->is_group):?>
			    <div class="fr ma-r10px"><?php echo $s->getGroupBtn($week);?></div>
		       	<div class="fr goods-price lh20px"><?php echo $s->groupPrice;?>元&nbsp;</div>
				<?php endif;?>
			    <div class="fr ma-r5px">
			    <?php echo $s->getBuyBtn($week);?>
			    </div>
			    <div class="fr goods-price lh20px"><?php echo $s->wmPrice;?>元&nbsp;</div>
			    <div class="clear"></div>
			</li>
			<?php endif;?>
		<?php endforeach;?>
		</ul>
		<div class="clear"></div>
    </div>
	<?php endforeach;?>
	<?php else:?>
	<div class="pa-t10px pa-b10px bline ac f14px">
		暂无商品！
	</div>
	<?php endif;?>
<script type="text/javascript">
$(function(){
	$('.goods-filter li a').click(filterGoods);
	$('#quick_search').keyup(goodsQuickSearch);

	<?php if($cartConflict && !$_SESSION['super_admin'] && !$_SESSION['manage_city_id'] && (!$_SESSION['shop'] || $_SESSION['shop']->id!=$shop->id)): ?>
	showOverlayBox($("#overlayBox").attr('cart'));
	<?php endif;?>
	<?php if($locationConflict && $_GET['s']!='subdomain' && !$_SESSION['super_admin'] && !$_SESSION['manage_city_id'] && (!$_SESSION['shop'] || $_SESSION['shop']->id!=$shop->id)): ?>
	showOverlayBox($("#overlayBox").attr('location'));
	<?php endif;?>
});


//获取当前鼠标的top,left值
var x,y;
$(document).mousemove(function(e){
	x = e.pageX;
	y = e.pageY;
});
</script>
