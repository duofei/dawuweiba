<?php echo CHtml::beginForm(url('admin/order/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">订单号：</td>
        <td width=""><?php echo CHtml::textField('Order[order_sn]', $order_get['order_sn'], array('class'=>'txt ')); ?></td>
        <td width="120" class="ar">商铺名称：</td>
        <td><?php echo CHtml::textField('Order[shop_name]', $order_get['shop_name'], array('class'=>'txt ')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收货人：</td>
        <td><?php echo CHtml::textField('Order[consignee]', $order_get['consignee'], array('class'=>'txt w100')); ?></td>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo CHtml::textField('Order[username]', $order_get['username'], array('class'=>'txt w100')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">下单时间：</td>
        <td colspan="3"><?php
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
        <td colspan="3"><?php echo CHtml::radioButtonList('Order[status]', $order_get['status'], Order::$states, array('separator'=>''))?></td>
    </tr>
    <tr>
    	<td width="120" class="ar">店铺分类：</td>
        <td colspan="3"><?php echo CHtml::radioButtonList('Order[category_id]', $order_get['category_id'], ShopCategory::$categorys, array('separator'=>''))?></td>
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>
 <?php if ($order) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="0">
    <tr class="title">
        <th class="al" width="200">订单号</th>
        <th class="al" width="60"></th>
        <th class="al" width="60">收货人</th>
        <th class="al" width="90">电话号码</th>
        <th class="al">送餐地址</th>
        <th class="al" width="50">配送费</th>
        <th class="al" width="60">订单总价</th>
        <th class="al" width="50">已付款</th>
        <th class="al" width="50">应收款</th>
        <th class="al" width="70">餐厅类型</th>
        <th class="al" width="70">订单状态</th>
    </tr>
</table>
  <?php foreach ($order as $key=>$val) :?>
  <div class="order-look <?php if ($key==0){ echo 'border-black ma-b10px'; }?>">
  <table class="list-tbl bline <?php if ($key==0){ echo 'line divbg1 order-title'; }?>" width="100%" cellspacing="0">
	  <tr>
	    <td width="200"><?php echo $val->orderSn?>-<span class="cgray"><?php echo $val->shop->shop_name;?></span></td>
		<td width="60"><a href=""><span class="look color  f14px">查看订单</span></a></td>
	    <td width="60"><?php echo l(h($val->consignee ? $val->consignee : $val->user->username), url('admin/user/info', array('id'=>$val->user->id)));?></td>
	    <td width="90"><?php echo h($val->mobile)?></td>
	    <td class="pa-r10px" >
	    	<?php if(preg_match("/^([0-9\.]+),([0-9\.]+)$/", $val->address, $match)):?>
	    	<?php echo l(h($val->address), url('shop/list', array('lat'=>$match[1], 'lon'=>$match[2])), array('target'=>'_blank')); ?>
	    	<?php else:?>
	    	<?php echo h($val->address)?>
	    	<?php endif;?>
	    </td>
	    <td width="50">&yen;<?php echo $val->dispatchingAmountPrice;?></td>
	    <td width="60">&yen;<?php echo $val->amountPrice;?></td>
	    <td width="50">&yen;<?php echo $val->paidAmountPrice;?></td>
	    <td width="50">&yen;<?php echo $val->dueAmountPrice;?></td>
	    <td width="70"><?php echo $val->shop->categoryText?></td>
	    <td width="70"><?php echo $val->statusText?></td>
	  </tr>
 </table>
  <div class="look <?php if ($key!=0){ echo 'none'; }?>">
  <table class="ma-t10px ma-b10px ma-l10px" width="90%" >
        <tr class="fb">
            <td width="160">商品名称</td>
            <td width="50">数量</td>
            <td width="70">单价</td>
            <td width="300">小计</td>
        </tr>
        <?php if ($val->orderGoods) : foreach ($val->orderGoods as $k=>$v) :?>
        <tr>
            <td><?php echo $v->goods_name?></td>
            <td><?php echo $v->goods_nums?></td>
            <td><?php echo $v->goodsPrice?></td>
            <td><?php echo $v->goodsAmount?></td>
        </tr>
        <?php endforeach;endif;?>
    </table>
    <div class="pa-l10px tline divbg1 order-title lh30px">
    <span class="ma-r20px">下单时间:<?php echo $val->shortCreateDateTimeText;?></span>
    <span class="ma-r10px">备注:<?php echo $val->message;?></span>
    </div>
    </div><!--end look-->
  </div>
  <?php endforeach;?>
 <?php else:?>
  <div>没有符合条件的订单</div>
  <?php endif;?>
 	<div class="pages ar">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '翻页',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
<script type="text/javascript">
$(function() {
    $("span.look").toggle(function(){
	 	$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").toggleClass("line divbg1 order-title");
	 	$(this).parents("div.order-look").toggleClass("border-black ma-b10px");
	},function(){
		$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").toggleClass("line divbg1 order-title");
	 	$(this).parents("div.order-look").toggleClass("border-black ma-b10px");
	})
});
</script>