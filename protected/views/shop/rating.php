<div class="shop-rating m10px">
<?php if($goodsratelog):?>
<table>
	<tr>
		<th width="70">评价</th>
		<th>点评 </th>
		<th>食客 </th>
        <th>美食</th>
    	<th>操作</th>
    </tr>
    <?php foreach((array)$goodsratelog as $row):?>
	<tr>
		<td><div class="star-small-gray ma-l10px ma-r10px"><div class="star-small-color" style="width:<?php echo $row->rateStarWidth;?>px;"></div></div></td>
		<td>
			<?php echo $row->contentText;?><br />
			<span class="cgray f10px">[<?php echo $row->createDateTimeText;?>]</span>
		</td>
      	<td class="ac"><?php echo $row->user->screenName; ?></td>
      	<?php if($row->goods->shop->category_id == ShopCategory::CATEGORY_CAKE && $row->goods->goodsModel->category_id == CakeGoods::CATEGROY_CAKE):?>
       	<td class="ac"><?php echo $row->orderGoods->goods_name;?></td>
       	<td width="90">
       			<div class="fr">
       				<?php if($row->goods):?>
	       			<?php echo $row->goods->getBuyBtn(null, null, true);?>
	       			<?php endif;?>
	       		</div>
	       		<div class="fr ma-r5px lh20px"><?php echo $row->orderGoods->goodsPrice;?>元</div>
       		<div class="clear"></div>
    	</td>
    	<?php else:?>
    	<td class="ac"><?php echo $row->goods->name;?></td>
       	<td width="90">
	       		<div class="fr">
	       			<?php if($row->goods):?>
	       			<?php echo $row->goods->getBuyBtn();?>
	       			<?php endif;?>
	       		</div>
	       		<div class="fr ma-r5px lh20px"><?php echo $row->goods->goodsModel->wmPrice;?>元</div>
       		
       		<div class="clear"></div>
    	</td>
    	<?php endif;?>
    	<!--
    	<td width="95">
       		<div class="fr ma-r5px">
       			<?php //echo l(CHtml::image(resBu('images/pixel.gif'), '团购', array('class'=>'bg-pic buy-group')), url('cart/create', array('goodsid'=>$row->goods_id)), array('title'=>'团购', 'class'=>'btn-buy'));?>
       		</div>
       		<div class="fr ma-r5px lh20px"><?php //echo $row->goods->foodGoods->groupPrice;?>元</div>
       		<div class="clear"></div>
    	</td>
    	 -->
	</tr>
	<?php endforeach;?>
</table>
<div class="pages">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
</div>
<?php else:?>
<div class="ac m10px f14px">目前还没有点评信息！</div>
<?php endif;?>
</div>