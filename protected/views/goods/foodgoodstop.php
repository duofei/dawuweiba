<div class="fl main">
<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'corner-gray-title ma-b10px'),
));?>

<h3 class="f12px cgray fn lh20px bline">
	<span class="cred fl indent10px f14px"><b>热卖美食</b></span>
	<span class="bg-pic sort-btn <?php echo $wm_price;?> block fr ma-r5px indent10px"><?php echo $sort->link('wm_price', '价格', array('class'=>'block'));?></span>
	<span class="bg-pic sort-btn <?php echo $favorite_nums;?> block fr ma-r5px indent10px"><?php echo $sort->link('favorite_nums', '人气', array('class'=>'block'));?></span>
	<span class="bg-pic sort-btn <?php echo $rate_avg;?> block fr ma-r5px indent10px"><?php echo $sort->link('rate_avg', '口味', array('class'=>'block'));?></span>
	<div class="clear"></div>
</h3>


<div class="goods-list m10px">
  	<ul>
 	<?php foreach ($goods as $k=>$g):?>
    	<li class="pa-10px lhnormal goods-item clr li-bg<?php echo $k%2;?>" gid="<?php echo $g->id;?>">
    		<div class="fl f14px"><?php echo $g->shop->nameLinkHtml;?></div>
		    <div class="fl ma-l10px shop-desc cblack"><?php echo $g->fullName;?><br /><span class="cgray"><?php echo $g->desc;?></span></div>
		    <div class="fl ma-l10px buy-confirm">
		    <?php if (in_array($g->id, $cartGoods)):?>
		    	<img src="<?php echo resBu('images/pixel.gif');?>" class="bg-icon cart-ok" />
		    <?php endif;?>
		    </div>
		    <div class="fr"><?php echo $g->favoriteHtml;?></div>
		    <div class="fr ma-r10px"><div class="star-small-gray ma-t5px"><div class="star-small-color"></div></div></div>
		    <!-- <div class="fr ma-r10px"><?php //echo l(CHtml::image(resBu('images/pixel.gif'), '团购', array('class'=>'bg-pic buy-group')), url('cart/create', array('goodsid'=>$g->id)), array('title'=>'团购', 'class'=>'btn-buy'));?></div> -->
		    <!-- <div class="fr ma-r10px goods-price lh20px"><?php //echo $g->groupPrice;?>元</div> -->
		    <div class="fr ma-r10px"><?php echo $g->buyBtn;?></div>
		    <div class="fr ma-r10px goods-price lh20px"><?php echo $g->wmPrice;?>元</div>
		    <div class="clear"></div>
		</li>
   	<?php endforeach;?>
    </ul>
</div>

<?php $this->endWidget();?>
</div>
<div class="fr sidebar">
	<div id="cart" view="small_cart"><?php $this->widget('SidebarCart');?></div>
</div>
<script type="text/javascript">
$(function(){
	<?php if (!user()->isGuest):?>
	$('.goods-favorite').click(favoriteGoods);
	<?php endif;?>
	$('.btn-buy').live('click', buyOneGoods);
});

//获取当前鼠标的top,left值
var x,y;
$(document).mousemove(function(e){ 
	x = e.pageX;
	y = e.pageY;
});
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>