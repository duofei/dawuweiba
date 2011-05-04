<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/order/handleno");?>">未加工订单</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/order/handleing");?>">加工中订单</a></li>
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
        <th class="al" width="60">&nbsp;</th>
        <th class="al" width="60">收货人</th>
        <th class="al" width="90">电话号码</th>
        <th class="al">送餐地址</th>
        <th class="al" width="70">订单总价</th>
        <th class="al" width="70">操作</th>
    </tr>
</table>
  <?php foreach ($order as $key=>$val):?>
  <div class="order-look">
	  <table class="list-tbl bline order-title" width="100%">
		  <tr>
		    <td width="100" class="pa-l10px"><?php echo $val->orderSn?>&nbsp;</td>
			<td width="60"><a href=""><span class="look color  f14px">订单详情</span></a></td>
		    <td width="60"><?php echo h($val->consignee)?></td>
		    <td width="90"><?php echo h($val->telphone)?></td>
		    <td><?php if($val->groupon_id):?><span class="cred">(<?php echo $val->building->name;?>)</span><?php endif;?><?php echo h($val->address)?></td>
		    <td width="70">&yen;<?php echo $val->amountPrice?></td>
		    <td width="70">
		    <a href="<?php echo url('shopcp/order/state', array(type=>Order::STATUS_PROCESS, id=>$val->id))?>"><span class=" f14px color">加工</span></a>
		    <a href="javascript:void(0);" onclick="sendCancel(<?php echo $val->id;?>, this)"><span class=" f14px color">无效</span></a></td>
		  </tr>
	 </table>
  	<div class="look none">
	  	<table class="ma-t10px ma-b10px ma-l10px" width="90%">
	        <tr class="fb">
	            <td>商品名称</td>
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
	    <div class="pa-l10px tline divbg1 lh20px h20px">
	    	<span class="ma-r5px">下单时间：<?php echo $val->shortCreateDateTimeText;?></span>|
	    	<span class="ma-r5px">备注:<?php echo $val->message;?></span>
		</div>
		<div class="pa-l10px divbg1 lh20px h20px">
			<span class="ma-r10px cred fr"><a href="<?php echo url('shopcp/order/print', array(id=>$val->id));?>" class="post-print" id="<?php echo $val->id;?>">提交打印</a></span>
	    	<?php if($val->user):?>
	    	<span class="ma-r5px">用户:<?php echo $val->user->username;?> - 好评:<?php echo intval($val->user->credit);?> 差评:<?php echo intval($val->user->credit_nums - $val->user->credit);?></span>|
	    	<?php else:?>
	    	<span class="ma-r5px">用户:匿名用户</span>
	    	<?php endif;?>
	    	<span class="ma-r5px">送餐时间：<?php echo $val->deliver_time;?></span>
	    	<?php if($val->groupon->amount > $val->groupon->shop_group_price):?>
	    	<span class="ma-r5px cgreen">同楼订餐已达成 &nbsp; 同楼订餐价：&yen;<?php echo $val->groupAmountPrice;?></span>
	    	<?php else:?>
	    	<span class="ma-r5px cred">同楼订餐未达成</span>
	    	<?php endif;?>
	    </div>
	</div><!--end look-->
  </div>
  <?php endforeach;?>
 <?php else:?>
  <div>您目前没有未加工订单</div>
  <?php endif;?>
 <?php echo CHtml::endForm();?>
  	</div>
</div>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'send_cancel',
    'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'无效订单',
        'autoOpen'=>false,
		'width' => 400,
		'height' => 280,
    ),
));
?>
<div class="lh20px">
	提交无效订单的理由：
	<?php echo CHtml::textArea('cancel_content', '', array('class'=>'invalid-order ma-t10px'));?>
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'post_cancel',
			'buttonType' => 'button',
			'caption' => '提 交',
			'htmlOptions' => array('class'=>'ma-t10px')
		)
	);
	?>
	<div id="cancel_error"></div>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script type="text/javascript">
function sendCancel(order_id, obj) {
	var url = '<?php echo url('shopcp/order/invain');?>';
	$("#send_cancel").dialog("open");
	$("#post_cancel").click(function(){
		var content = $("#cancel_content").val();
		if(!content) {
			$("#cancel_error").html('申请取消订单的理由不能为空！');
			return;
		}
		$.post(url, {id:order_id,content:content}, function(data){
			if (data == 1) {
				$(obj).parent().html('已无效');
				$("#send_cancel").dialog("close");
			} else {
				$("#cancel_error").html(data);
			}
		});
	});
}

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
	$(".post-print").click(function(e){
		e.preventDefault();
		var url = $(this).attr('href');
		var id = $(this).attr('id');
		window.open(url, 'postprint'+id,'width=200,height=500,top=100,left=500');
	});
});
</script>
