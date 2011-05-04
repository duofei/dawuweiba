	<a href="<?php echo url('shopcp/statistics/sales', array('type'=>'7'))?>">7天内</a>&nbsp;&nbsp;
	<a href="<?php echo url('shopcp/statistics/sales', array('type'=>'30'))?>">30天内</a>&nbsp;&nbsp;
	<a href="<?php echo url('shopcp/statistics/sales', array('type'=>'180'))?>">半年内</a>&nbsp;&nbsp;
 <?php echo CHtml::beginForm(url('shopcp/statistics/sales'),'get',array('name'=>'edit'));?>
	选择日期&nbsp;&nbsp;
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Order[create_time_start]',
    'value' => $order_get['create_time_start']?$order_get['create_time_start']:date(param('formatDate'), strtotime('last Week')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt'),
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
    'htmlOptions' => array('class'=>'txt'),
));
?>&nbsp;&nbsp;
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '搜 索',
	)
);
?>
 <?php echo CHtml::endForm();?>
	<div class="line30 line ma-t10px">
	<h3>营业额统计：</h3>
	今日 营业额：<?php echo $statistics['1Amount']?>元，订单数：<?php echo $statistics['1Num']?>。   <br />
	7天内 营业额：<?php echo $statistics['7Amount']?>元，订单数：<?php echo $statistics['7Num']?>。<br />
	30天内 营业额：<?php echo $statistics['30Amount']?>元，订单数：<?php echo $statistics['30Num']?>。 
<!--	导出数据-->
	</div>
	<div class="line1px ma-b5px ma-t5px"></div>
	
	<div class="line30 line ma-t10px">
	<h3>热销菜统计：</h3>
	<?php echo $typetext?>天内最热销的食物:<span class="ma-r20px"><?php echo $statistics[$type.'goodsAmountMax']?></span>
	</div>
	<div class="line1px ma-b5px ma-t5px"></div>
	
	<div class="line30 line ma-t10px">
	<h3>商品销售统计：</h3><?php echo $typetext?>天内销售分布
<table  class="tabcolor list-tbl" width="100%">
    <tr class="title">
        <th class="al">商品名称</th>
        <th class="al">销售量</th>
        <th class="al">销售额（元）</th>
        <th class="al">百分比（销售额）</th>
    </tr>
    <?php $i=1; if ($statistics[$type.'goodsNum']) { foreach ($statistics[$type.'goodsNum'] as $key=>$val) {?>
    <tr <?php if ($i%2 == 0) { echo 'class="divbg1"'; } $i++;?>>
        <td><?php echo $key?></td>
        <td><?php echo $val?></td>
        <td><?php echo $statistics[$type.'goodsAmount'][$key]?></td>
        <td><?php echo round($statistics[$type.'goodsAmount'][$key]/$statistics[$type.'Amount']*100)?>%</td>
    </tr>
    <?php }}?>
</table>
<div class="line1px ma-b5px ma-t5px"></div>