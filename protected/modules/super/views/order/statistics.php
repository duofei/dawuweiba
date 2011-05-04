<?php echo CHtml::beginForm(url('super/order/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">下单时间：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Order[create_time_start]',
    'value' => $order_get['create_time_start']?$order_get['create_time_start']:date(param('formatDate'), strtotime('last Week')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?>-<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Order[create_time_end]',
    'value' => $order_get['create_time_end']?$order_get['create_time_end']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">订单状态：</td>
        <td><?php echo CHtml::radioButtonList('Order[status]', $order_get['status'], Order::$states, array('separator'=>''))?></td>     
    </tr>
    <tr>
    	<td width="120" class="ar">店铺分类：</td>
        <td><?php echo CHtml::radioButtonList('Order[category_id]', $order_get['category_id'], ShopCategory::$categorys, array('separator'=>''))?></td>
    </tr>
    <tr>
    	<td width="120" class="ar">城市：</td>
        <td><?php echo CHtml::radioButtonList('Order[city_id]', $order_get['city_id'], $citylist, array('separator'=>''))?></td>
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>
  <?php if ($ordercount) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title">
        <th class="al" width="100">城市</th>
        <th class="al" width="100">订单总数</th>
        <th class="al" width="*">订单总额</th>
    </tr>
  <?php foreach ((array)$ordercount as $key=>$val) :?>
	  <tr>
	    <td><?php echo $key?></td>
	    <td><?php echo $val['count']?></td>
	    <td><?php echo $val['amount']['0']?$val['amount']['0']:0?>元</td>
	  </tr>
  <?php endforeach;?>
</table>
 <?php else:?>
  <div>没有符合条件的订单</div>
  <?php endif;?>