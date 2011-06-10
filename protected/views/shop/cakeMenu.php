<ul	class="subfl cake-filter pa-l10px bline	lh30px h30px cblack">
	<li	class="pa-l10px">按商品分类:</li>
	<li	class="pa-l10px"><?php echo l('全部', 'javascript:void(0);',	array('class'=>'link-selected', 'type'=>'category'))?></li>
	<?php foreach ((array)$arrayCategory as	$k=>$c):?>
	<li	class="pa-l10px	keep-all"><?php	echo l($c, 'javascript:void(0);', array('sid'=>$k, 'type'=>'category'))?></li>
	<?php endforeach;?>
	<div class="clear"></div>
</ul>
<div class="none" id="cake-type">
	<ul	class="subfl cake-filter pa-l10px lh30px cblack">
		<li	class="pa-l10px">按用途分类:</li>
		<li	class="pa-l10px"><?php echo l('全部', 'javascript:void(0);',	array('class'=>'link-selected', 'type'=>'purpose'))?></li>
		<?php foreach ((array)$arrayPurpose as	$k=>$v):?>
		<li	class="pa-l10px	keep-all"><?php	echo l($v, 'javascript:void(0);', array('sid'=>$k, 'type'=>'purpose'))?></li>
		<?php endforeach;?>
		<div class="clear"></div>
	</ul>
	<ul	class="subfl cake-filter pa-l10px lh30px cblack">
		<li	class="pa-l10px">按品种分类:</li>
		<li	class="pa-l10px"><?php echo l('全部', 'javascript:void(0);',	array('class'=>'link-selected', 'type'=>'variety'))?></li>
		<?php foreach ((array)$arrayVariety as	$k=>$v):?>
		<li	class="pa-l10px	keep-all"><?php	echo l($v, 'javascript:void(0);', array('sid'=>$k, 'type'=>'variety'))?></li>
		<?php endforeach;?>
		<div class="clear"></div>
	</ul>
	<ul	class="subfl cake-filter pa-l10px bline	lh30px h30px cblack">
		<li	class="pa-l10px">按造型分类:</li>
		<li	class="pa-l10px"><?php echo l('全部', 'javascript:void(0);',	array('class'=>'link-selected', 'type'=>'shape'))?></li>
		<?php foreach ((array)$arrayShape as	$k=>$v):?>
		<li	class="pa-l10px"><?php echo	l($v, 'javascript:void(0);', array('sid'=>$k, 'type'=>'shape'))?></li>
		<?php endforeach;?>
		<div class="clear"></div>
	</ul>
</div>
<div class="goods-search pa-l10px pa-r5px pa-t5px bline	lh30px h30px ">
	<div class="fl">快速搜索：<input type="text" class="txt" name="quick_kw" id="quick_search" /></div>
	<div class="fr lh20px" style="margin-top:7px;">
		<span class="bg-pic	sort-btn <?php echo	$sortclass['favorite_nums'];?> block fr	ma-r5px	indent10px"><?php echo $sort->link('favorite_nums',	'人气',	array('class'=>'block'));?></span>
		<span class="bg-pic	sort-btn <?php echo	$sortclass['rate_avg'];?> block	fr ma-r5px indent10px"><?php echo $sort->link('rate_avg', '口味', array('class'=>'block'));?></span>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<?php if($goods):?>
<?php foreach ((array)$arrayCategory as	$k=>$c):?>
<div class="bline cake-category-list" category="<?php echo $k;?>">
<h2 class="lh30px h30px fb cred pa-l10px f16px"><?php echo $c;?></h2>
	<?php foreach ((array)$goods as	$g):?>
	<?php if($g->goodsModel->category_id == $k):?>
	<div class="cake-list pa-b10px" name="<?php echo h(strip_tags($g->name));?>" <?php if($k==CakeGoods::CATEGROY_CAKE):?> shape="<?php echo $g->goodsModel->shape_id;?>" purpose=",<?php foreach($g->goodsModel->Purposes as $p): echo $p->id . ','; endforeach;?>" variety=",<?php foreach($g->goodsModel->Varietys as $v): echo $v->id . ','; endforeach;?>" <?php endif;?>>
		<div class="fl cake-img	ma-l10px"><?php	echo $g->PicLinkHtml;?></div>
		<div  class="fl	ma-l10px cake-content pa-r10px">
			<div class="fb f14px cred lh20px">
				<div class="fl"><?php echo $g->fullName; ?></div>
				<div class="star-big-gray ma-t5px fl ma-l5px"><div class="star-big-color" style="width:<?php echo $g->rateStarWidth;?>px"></div></div>
				<div class="fr"><?php echo $g->favoriteHtml;?></div>
				<div class="clear"></div>
			</div>
			<div class="ma-t10px lh20px">蛋糕小语：<?php echo h($g->goodsModel->getLabelText(104));	?></div>
			<div class="lh20px">材料：<?php	echo h($g->goodsModel->stuff); ?></div>
			<div class="lh20px">口味：<?php	echo h($g->goodsModel->taste); ?></div>
		</div>
		<div class="fl cake-buys ma-l10px lh24px">
			<?php if($g->goodsModel->category_id==CakeGoods::CATEGROY_CAKE): ?>
			<div>请选择尺寸大小：</div>
			<div>
				<select>
					<?php foreach($g->goodsModel->cakePrices as	$p):?>
					<option value="<?php echo $p->id;?>" size="<?php echo $p->size;?>" wmprice="<?php echo $p->wmPrice;?>" marketprice="<?php echo $p->marketPrice;?>"><?php echo $p->sizeExplanation;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div>市场价：<span class="market-price fb f14px"><?php echo	$g->goodsModel->cakePrices[0]->marketPrice;?></span> 元</div>
			<div>外卖价：<span class="wm-price cred	fb f16px"><?php	echo $g->goodsModel->cakePrices[0]->wmPrice;?></span> 元 </div>
			<div class="ma-t10px">
				<?php echo $g->getBuyBtn(null, $g->goodsModel->cakePrices[0]->id);?>
			</div>
			<?php else:?>
			<div>市场价：<span class="market-price fb f14px"><?php echo	$g->marketPrice;?></span> 元</div>
			<div>外卖价：<span class="wm-price cred	fb f16px"><?php	echo $g->wmPrice;?></span> 元 </div>
			<div class="ma-t10px">
				 <?php echo $g->getBuyBtn();?>
			</div>
			<?php endif;?>
		</div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php endforeach;?>
</div>
<?php endforeach;?>
<?php else:?>
<div class="pa-t10px pa-b10px bline	ac f14px">
	暂无商品！
</div>
<?php endif;?>
<script	type="text/javascript">
$(function(){
	$('.cake-filter	li a').click(filterCakeGoods);
	$('#quick_search').keyup(filterCakeGoods);
	$('.cake-buys select').change(changeCakePrice);
	
	<?php if($cartConflict): ?>
	showOverlayBox($("#overlayBox").attr('cart'));
	<?php endif;?>
	<?php if($locationConflict): ?>
	showOverlayBox($("#overlayBox").attr('location'));
	<?php endif;?>
});

//获取当前鼠标的top,left值
var	x,y;
$(document).mousemove(function(e){
	x =	e.pageX;
	y =	e.pageY;
});
</script>
