<?php echo CHtml::beginForm('','get',array('name'=>'add'));?>
<p class="line30 pa-5px">选择配送人员：
<?php echo CHtml::dropDownList('Order[delivery]', $order_get['delivery'], CHtml::listData($deliveryMan, 'id', 'name'), array('separator'=>' '))?>
</p>
<p class="line30 pa-5px">选择起止时间：
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
?></p>
<p class="line30 pa-5px">
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提交查询',
	)
);
?></p>
<?php echo CHtml::endForm();?>
<?php $order_num=0; $amount=0; if ($order) :?>
   <table  class="tabcolor list-tbl ma-b5px" width="100%">
    <tr class="title">
        <th class="al" width="30">编号</th>
        <th class="al" width="45">送货员</th>
        <th class="al">&nbsp;</th>
        <th class="al" width="90">订单号</th>
        <th class="al" width="125">提交配送时间</th>
        <th class="al" width="125">返回核算时间</th>
        <th class="ar" width="60">应付金额</th>
        <th class="ar" width="60">实收金额</th>
    </tr>
</table>
  <?php foreach ($order as $key=>$val):?>
  <div class="order-look">
  <table class="list-tbl bline order-title" width="100%">
	  <tr>
	    <td width="30" class="pa-l10px"><?php echo $val->delivery_id?>&nbsp;</td>
		<td width="45"><?php echo $val->deliveryMan->name?></td>
	    <td><a href="javascript:void(0);"><span class="look color f14px">订单详情</span></a></td>
	    <td width="90"><?php echo $val->orderSn;?></td>
	    <td width="125"><?php echo $val->orderDeliveringLog->shortCreateDateTimeText;?></td>
	    <td width="125"><?php echo $val->orderCompleteLog->shortCreateDateTimeText;?></td>
	    <td width="60" class="ar"><?php echo $val->amountPrice;?>元</td>
	    <td width="60" class="ar"><?php echo $val->actualMoney;?>元</td>
	  </tr>
 </table>
 
  <div class="look none">
  <table class="ma-t10px ma-b10px ma-l10px" width="90%">
        <tr class="fb">
            <td width="160">商品名称</td>
            <td width="50">数量</td>
            <td width="70">单价</td>
            <td width="300">小计</td>
        </tr>
        <?php if ($val->orderGoods) : foreach ($val->orderGoods as $k=>$v):?>
        <tr>
            <td><?php echo $v->goods_name?></td>
            <td><?php echo $v->goods_nums?></td>
            <td><?php echo $v->goods_price?></td>
            <td><?php echo $v->goods_amount?></td>
        </tr>
        <?php endforeach;endif;?>
    </table>
    </div><!--end look-->
  </div>
  <?php $order_num++; $amount+=$val->amount; $actual_money+=$val->actual_money; endforeach;?>
  <?php endif;?>
  <div>订单合计：共 <?php echo $order_num?> 个订单，应付总额 <?php echo $amount;?> 元，实收金额 <?php echo $actual_money?> 元</div>
<script type="text/javascript">
$(function() {
    $("span.look").toggle(function(){
	 	$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").addClass("line");
	  	$(this).parents("table").addClass("divbg1");
	 	$(this).parents("div.order-look").addClass("border-black ma-b10px");
	},function(){
		$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").removeClass("line");
	  	$(this).parents("table").removeClass("divbg1");
	 	$(this).parents("div.order-look").removeClass("border-black ma-b10px");
	})
});
</script> 
