<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/handleno");?>">未加工订单</a></li>
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/order/handleing");?>">加工中订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/dispatching");?>">配送中订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/finish");?>">已完成订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/cancelstate");?>">申请取消订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/cancel");?>">已取消订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/list");?>">全部订单</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
 <?php echo CHtml::beginForm(url('shopcp/order/state'),'post',array('name'=>'edit'));?>
 <?php if ($order) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%">
    <tr class="title">
        <th class="al" width="100">订单号</th>
        <th class="al" width="60"></th>
        <th class="al" width="60">收货人</th>
        <th class="al" width="90">电话号码</th>
        <th class="al">送餐地址</th>
        <th class="al" width="90">订单总价</th>
        <th class="al" width="130">操作</th>
    </tr>
</table>
  <?php foreach ($order as $key=>$val) :?>
  <div class="order-look">
  <table class="list-tbl bline order-title" width="100%">
	  <tr>
	    <td width="100"><?php echo $val->orderSn?>&nbsp;&nbsp;</td>
		<td width="60"><a href=""><span class="look color  f14px">查看订单</span></a></td>
	    <td width="60"><?php echo h($val->consignee)?></td>
	    <td width="90"><?php echo h($val->telphone)?></td>
	    <td class="pa-r10px" ><?php if($val->groupon_id):?><span class="cred">(<?php echo $val->building->name;?>)</span><?php endif;?><?php echo h($val->address)?></td>
	    <td width="90">&yen;<?php echo $val->amountPrice?></td>
	    <td width="130">
	    <?php echo CHtml::dropDownList('delivery', '', CHtml::listData($deliveryMan, 'id', 'name'), array('separator'=>' '))?>
	    <a href="<?php echo url('shopcp/order/state', array(type=>Order::STATUS_DELIVERING, id=>$val->id));?>" class="submit-man"><span class="color">配送</span></a></td>
	  </tr>
 </table>
  <div class="look none">
  <table class="ma-t10px ma-b10px ma-l10px" width="90%">
        <tr class="fb">
            <td width="160">商品名称</td>
            <td width="50">数量</td>
            <td width="70">单价</td>
            <td width="60">小计</td>
            <?php if ($_SESSION['shop']->category_id == ShopCategory::CATEGORY_CAKE):?>
            <td width="200">蛋糕祝福语</td>
            <td width="200">贺卡祝福语</td>
            <?php endif;?>
        </tr>
        <?php if ($val->orderGoods) : foreach ($val->orderGoods as $k=>$v) :?>
        <tr>
            <td><?php echo $v->goods_name?></td>
            <td><?php echo $v->goods_nums?></td>
            <td><?php echo $v->goodsPrice?></td>
            <td><?php echo $v->goodsAmount?></td>
            <?php if ($_SESSION['shop']->category_id == ShopCategory::CATEGORY_CAKE):?>
            <td><?php $remark = explode('||',$v->remark);echo $remark['0'];?></td>
            <td><?php $remark = explode('||',$v->remark);echo $remark['1'];?></td>
            <?php endif;?>
        </tr>
        <?php endforeach;endif;?>
    </table>
    <div class="pa-l10px tline divbg1 lh20px">
    	<span class="ma-r5px">下单时间:<?php echo $val->shortCreateDateTimeText;?></span>|
    	<span class="ma-r5px">备注:<?php echo $val->message;?></span><br />
    	<span class="ma-r5px">送餐时间：<?php echo $val->deliver_time;?></span>
    </div>
    </div><!--end look-->
  </div>
  <?php endforeach;?>
 <?php else:?>
  <div>您目前没有加工中订单</div>
  <?php endif;?>
 <?php echo CHtml::endForm();?>
 	</div>
</div>
<script type="text/javascript">
$(function() {
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
	$("a.submit-man").click(function(e){
		var manid = $(this).prev('select').val();
		var url = $(this).attr('href') + '?manid=' + manid;
		$(this).attr('href', url);
		
	});
});
</script>
