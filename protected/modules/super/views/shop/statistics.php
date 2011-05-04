 <?php echo CHtml::beginForm(url('super/shop/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
<!--        <td width="120" class="ar">店铺分类：</td>-->
<!--        <td><?php echo CHtml::radioButtonList('Shop[category_id]', $shop['category_id'], ShopCategory::$categorys, array('separator'=>''))?></td>-->
        <td width="120" class="ar">营业状态：</td>
        <td width="300"><?php echo CHtml::radioButtonList('Shop[business_state]', $shop['business_state'], Shop::$business_states, array('separator'=>''))?></td>   
        <td width="120" class="ar">订餐方式：</td>
        <td><?php echo CHtml::radioButtonList('Shop[buy_type]', $shop['buy_type'], Shop::$buytype, array('separator'=>''))?></td>  
    </tr>
    <tr>  
        <td width="120" class="ar">营业执照审核：</td>
        <td><?php echo CHtml::radioButtonList('Shop[is_commercial_approve]', $shop['is_commercial_approve'], Shop::$approve, array('separator'=>''))?></td>
        <td width="120" class="ar">卫生许可证审核：</td>
        <td><?php echo CHtml::radioButtonList('Shop[is_sanitary_approve]', $shop['is_sanitary_approve'], Shop::$approve, array('separator'=>''))?></td> 
    </tr>
    <tr>  
    	<td width="120" class="ar">城市：</td>
        <td colspan="3"><?php echo CHtml::radioButtonList('Shop[city_id]', $shop['city_id'], $citylist, array('separator'=>''))?></td>
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>
 
  <?php if ($shopcount) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title">
        <th class="al" width="100">城市</th>
        <th class="al" width="100">美食餐厅总数</th>
        <th class="al" width="">蛋糕餐厅总数</th>
    </tr>
  <?php foreach ($shopcount as $key=>$val) :?>
	  <tr>
	    <td><?php echo $key?></td>
	    <td><?php echo $val['foodcount']?></td>
	    <td><?php echo $val['cakecount']?></td>
	  </tr>
  <?php endforeach;?>
</table>
 <?php else:?>
  <div>没有符合条件的订单</div>
  <?php endif;?>