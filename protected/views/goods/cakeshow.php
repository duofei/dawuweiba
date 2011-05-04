<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail ma-b10px'),
));?>
<div class="fl cake-big-pic"><?php echo CHtml::image(sbu($goods->goodsModel->big_pic));?></div>
<div class="fl ma-l10px cake-w338px">
	<div class="cake-small-pic"><?php echo CHtml::image(sbu($goods->goodsModel->small_pic));?></div>
	<div class="ma-t5px">
		<div class="fl cred f16px fb lh30px"><?php echo $goods->fullName; ?></div>
		<div class="fr star-big-gray ma-t5px"><div class="star-big-color" style="width:<?php echo $goods->rateStarWidth;?>px;"></div></div>
		<div class="fr lh24px">口味：</div>
		<div class="clear"></div>
	</div>
	<?php if ($goods->tagsHtml):?><div>标签：<?php echo $goods->tagsHtml;?></div><?php endif;?>
	<div class="ma-t10px f14px lh24px"><strong>蛋糕小语：</strong><?php echo h($goods->goodsModel->label);?></div>
	<div class="ma-t10px f14px lh24px"><strong>购买建议：</strong><?php echo h($goods->goodsModel->buy_advice);?></div>
</div>
<div class="clear"></div>
<?php $this->endWidget();?>
<div class="fl main">
<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail ma-b10px'),
	'title' => '蛋糕价格',
    'decorationCssClass' => 'cblack',
));?>
	<table class="ma-l5px lh20px list-tbl" width="98%">
        <tr class="bg-gray">
        	<th width="20"></th>
        	<th class="al" width="150">尺寸(适合人数) </th>
        	<th class="al" width="70">门市价</th>
        	<th class="al" width="70">外卖价</th>
        	<th class="al">描述</th>
        </tr>
        <?php foreach ($goods->goodsModel->cakePrices as $k => $v):?>
        <tr <?php echo ($k%2==0) ? null : 'class="bg-gray"';?>>
        	<td class="ac"><input type="radio" name="cakeprice" <?php if($k==0){echo 'checked';}?> value="<?php echo $v->id;?>" /></td>
        	<td><?php echo $v->sizeExplanation;?></td>
        	<td><span class="market-price f14px"><?php echo $v->marketPrice;?></span> 元</td>
        	<td><span class="f14px fb cred"><?php echo $v->wmPrice;?></span> 元</td>
        	<td><?php echo $v->desc;?></td>
        </tr>
        <?php endforeach;?>
        <tr><td colspan="5">
        	<?php echo $goods->getBuyBtn(null, $goods->goodsModel->cakePrices[0]->id);?>
        </td></tr>
    </table>
<?php $this->endWidget();?>

<?php $this->beginWidget('WmCornerBox', array(
    'htmlOptions' => array('class'=>'goods-detail ma-b10px'),
	'title' => '蛋糕详情',
    'decorationCssClass' => 'cblack',
));?>
	<table class="ma-l5px lh20px list-tbl" width="98%">
        <tr class="bg-gray">
        	<td width="50%">甜度：<?php echo $goods->goodsModel->saccharinitysText;?></td>
        	<td>是否无糖：<?php echo $goods->goodsModel->sugarText;?></td>
        </tr>
        <tr>
        	<td>口味：<?php echo $goods->goodsModel->taste;?></td>
        	<td>保鲜条件：<?php echo $goods->goodsModel->fresh_condition;?></td>
        </tr>
        <tr class="bg-gray">
        	<td>材料：<?php echo $goods->goodsModel->stuff;?></td>
        	<td></td>
        </tr>
    </table>
<?php $this->endWidget();?>

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
	$("input[name='cakeprice']").click(changeCakePriceId);
});
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>
