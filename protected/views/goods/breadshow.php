<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail ma-b10px'),
));?>

<?php if ($goods->pic):?><div class="fl ma-l10px shop-pic"><?php echo $goods->picLinkHtml;?></div><?php endif;?>
<ul class="fl lh20px goods-profile ma-l10px">
	<li>
	    <h1 class="f16px cblack ma-b5px"><?php echo $goods->fullName;?>&nbsp;</h1>
	</li>
	<!-- <li><?php echo ShopCategory::$storeNames[$goods->shop->category_id]?>：<?php echo $goods->shop->nameLinkHtml;?>&nbsp;<span class="cgrya">(<?php echo $goods->shop->buyTypeText;?>)</span> -->
	<?php if ($goods->tagsHtml):?><li>标签：<?php echo $goods->tagsHtml;?></li><?php endif;?>
	<li>甜度：<?php echo $goods->goodsModel->saccharinitysText;?> &nbsp;&nbsp; 是否无糖：<?php echo $goods->goodsModel->sugarText;?></li>
	<li>
	<?php if ($goods->goodsModel->taste):?>口味：<?php echo $goods->goodsModel->taste;?> &nbsp;&nbsp;<?php endif;?>
	<?php if ($goods->goodsModel->fresh_condition):?> 保鲜条件：<?php echo $goods->goodsModel->fresh_condition;?></li><?php endif;?>
	<?php if ($goods->goodsModel->label):?><li>小语：<?php echo $goods->goodsModel->label;?></li><?php endif;?>
	<?php if ($goods->goodsModel->buy_advice):?><li>购买建议：<?php echo $goods->goodsModel->buy_advice;?></li><?php endif;?>
	<?php if ($goods->goodsModel->stuff):?><li>材料：<?php echo $goods->goodsModel->stuff;?></li><?php endif;?>
	<?php if ($goods->goodsModel->pack):?><li>包装：<?php echo $goods->goodsModel->pack;?></li><?php endif;?>
	<li>
		<div class="fl lh20px">市场价：<span class="market-price f14px fb"><?php echo $goods->marketPrice;?></span>元&nbsp;&nbsp;</div>
		<div class="fl lh20px">外卖价：<span class="f18px fb cred"><?php echo $goods->wmPrice;?></span>元&nbsp;&nbsp;</div>
		<?php echo $goods->buyBtn;?>
	</li>
</ul>
<ul class="fr lh20px goods-rating ma-r10px">
	<li>
		<div class="fl">口味：</div>
		<div class="fl star-big-gray ma-t5px"><div class="star-big-color" style="width:<?php echo $goods->rateStarWidth;?>px;"></div></div>
		<div class="clear"></div>
	</li>
	<li>共有<?php echo $goods->goodsRateCount;?>个点评</li>
</ul>
<div class="clear"></div>
<?php $this->endWidget();?>

<div class="fl main">
<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail ma-b10px'),
    'title' => '文字点评<span class="cgray f12px fn">(共' . count($textDianping['logs']) . '条)</span>',
    'decorationCssClass' => 'cblack',
));?>
    <table class="ma-l5px lh20px list-tbl" width="98%">
        <tr class="bg-gray">
        	<th class="al" width="120">评价</th>
        	<th class="al">点评</th>
        	<th class="al" width="100">食客</th>
        </tr>
        <?php foreach ($textDianping['logs'] as $k => $v):?>
        <tr <?php echo ($k%2==0) ? null : 'class="bg-gray"';?>>
        	<td><div class="star-small-gray"><div class="star-small-color" style="width:<?php echo $v->rateStarWidth;?>px"></div></div></td>
        	<td><p><?php echo h($v->content);?></p><p class="cgray f10px">[<?php echo $v->shortCreateDateTimeText;?>]</p></td>
        	<td class="cgray"><?php echo $v->user->screenName;?></td>
        </tr>
        <?php endforeach;?>
        <?php if (count($textDianping['logs']) == 0):?>
        <tr><td colspan="3">暂时没有点评</td></tr>
        <?php endif;?>
    </table>
<?php $this->endWidget();?>

<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail'),
    'title' => '最近点评<span class="cgray f12px fn">(共' . count($dianping['logs']) . '条)</span>',
    'decorationCssClass' => 'cblack',
));?>
    <table class="ma-l5px lh20px list-tbl" width="98%">
        <tr class="bg-gray">
        	<th class="al" width="120">评价</th>
        	<th class="al">点评</th>
        	<th class="al" width="100">食客</th>
        </tr>
        <?php foreach ($dianping['logs'] as $k => $v):?>
        <tr <?php echo ($k%2==0) ? null : 'class="bg-gray"';?>>
        	<td><div class="star-small-gray"><div class="star-small-color" style="width:<?php echo $v->rateStarWidth;?>px"></div></div></td>
        	<td><p><?php echo $v->starText;?></p><p class="cgray f10px">[<?php echo $v->shortCreateDateTimeText;?>]</p></td>
        	<td class="cgray"><?php echo $v->user->screenName;?></td>
        </tr>
        <?php endforeach;?>
        <?php if (count($dianping['logs']) == 0):?>
        <tr><td colspan="3">暂时没有点评</td></tr>
        <?php endif;?>
    </table>
<?php $this->endWidget();?>
<div class="pages">
	<?php $this->widget('CLinkPager', array(
		'pages' => $dianping['pages'],
	    'header' => '',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
</div>
</div>
<div class="fr sidebar">
    <div id="cart" view="small_cart"><?php $this->widget('SidebarCart');?></div>
</div>
<script type="text/javascript">
$(function(){
	$('.btn-buy').live('click', buyOneGoods);
});
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>
