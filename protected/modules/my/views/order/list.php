<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray" id="uncomplete"><a href="<?php echo url('my/order/uncomplete');?>">未完成订单</a></li>
	  <li class="normal corner-top cgray" id="norating"><a href="<?php echo url('my/order/norating');?>">未点评订单</a></li>
	  <li class="normal corner-top cgray" id="online"><a href="<?php echo url('my/order/online');?>">网络订单</a></li>
	  <!-- <li class="normal corner-top cgray" id="groupon"><a href="<?php echo url('my/order/groupon');?>">同楼订餐</a></li> -->
	  <li class="normal corner-top cgray" id="telphone"><a href="<?php echo url('my/order/telphone');?>">电话订单</a></li>
	  <li class="normal corner-top cgray" id="list"><a href="<?php echo url('my/order/list');?>">所有订单</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<?php if($data):?>
	<?php foreach($data as $order):?>
	<div class="order ma-b20px">
		<?php if($orderIsNoRating[$order->id]):?>
		<div class=" bg bg-order"></div>
		<?php endif;?>
		<div class="divbg1 pa-l10px lh20px f12px">
		  	<span class="ma-r10px">下单时间：<?php echo $order->pastCreateTimeText;?></span>
		  	<span class="ma-r10px">订单号：<?php echo $order->orderSn;?></span>
		  	<span class="ma-r10px cred"><?php if($order->shop) echo $order->shop->getNameLinkHtml(0, '_blank');?></span>
		  	<span class="ma-r10px">店铺电话：<?php if($order->shop) echo $order->shop->telphone ? $order->shop->telphone : $order->shop->mobile; ?></span>
	 	</div>
	 	<?php if($order->groupon):?>
	 	<div class="divbg1 pa-l10px lh20px f12px tline">
	 		<span class="ma-r30px">楼宇：<?php echo $order->building->name;?></span>
	 		<?php if ($order->status==Order::STATUS_UNDISPOSED):?>
	 			<span class="ma-r30px cred">状态：同楼订餐中</span>
	 		<?php elseif ($order->status==Order::STATUS_CANCEL):?>
	 			<span class="ma-r30px cred">状态：已取消</span>
	 		<?php else:?>
		 		<?php if($order->groupon->amount > $order->groupon->shop_group_price):?>
		   		<span class="ma-r30px cred">状态：已达成(将会按同楼价配送)</span>
		   		<?php else :?>
		   		<span class="ma-r30px cred">状态：未达成(将会按正常价配送)</span>
		   		<?php endif;?>
	   		<?php endif;?>
	 		<span class="ma-r10px">所需金额：<?php echo $order->groupon->shop_group_price;?></span>
	 		<span class="ma-r10px">已达成金额：<?php echo $order->groupon->amount;?></span>
	 	</div>
	 	<?php endif;?>
	 	<div class="clear tline ma-b10px"></div>
	 	<div class="left-dp">
	 	<?php foreach($order->orderGoods as $ordergoods):?>
	    	<p> <?php echo l($ordergoods->goods_name, url('goods/show', array('goodsid'=>$ordergoods->goods_id)), array('target'=>'_blank'));?><span class="left200px"><?php echo $ordergoods->goods_nums;?> × <?php echo $ordergoods->goodsPrice;?>元 </span> <span class="left280px"><span class="goods-amount"><?php echo $ordergoods->goodsAmount;?></span>元</span></p>
			<?php
			if($order->status==Order::STATUS_COMPLETE || $order->status==Order::STATUS_DELIVERING):
				if($ordergoods->goodsRateLog->goods_id):
					$this->widget('CStarRating', array(
						'name'=>'rating_' . $ordergoods->id,
						'allowEmpty' => false,
						'maxRating' => 5,
						'minRating' => 1,
						'starCount' => 5,
						'titles' => GoodsRateLog::$stars,
						'readOnly' => true,
						'value' => $ordergoods->goodsRateLog->mark,
					));
			?>
					<div class="fl lh20px"><?php echo $ordergoods->goodsRateLog->starText; ?></div>
					<div class="clear"></div>
					<div class="ma-b10px">
			       		<?php echo $ordergoods->goodsRateLog->content; ?>
		    		</div>
			<?php
				else:
					$this->widget('CStarRating', array(
						'name'=>'rating_' . $ordergoods->id,
						'allowEmpty' => false,
						'maxRating' => 5,
						'minRating' => 1,
						'starCount' => 5,
						'titles' => GoodsRateLog::$stars,
						'focus' => 'js:rateShowTitle',
						'blur' => 'js:rateShowNone',
						'callback' => 'js:rateComment',
						'htmlOptions' => array(
							'mark' => $order->buy_type==Shop::BUYTYPE_TELPHONE ? param('markUserGradeGoods') : intval($ordergoods->goodsAmount),
						),
					));  ?>
					<div class="fl lh20px cred" id="mark_rating_<?php echo $ordergoods->id;?>">还未打分</div>
				    <div class="clear"></div>
					<div class="ma-b10px" id="content_rating_<?php echo $ordergoods->id;?>"></div>
	    	<?php
	    		endif;
	    	endif; ?>
		<?php endforeach;?>
	 	</div>
	 	<div class="right-dp">
	 		<?php if($order->groupon):?>
	 			<?php if($order->groupon->amount > $order->groupon->shop_group_price):?>
	 			<div class="fl width80px">
	 				<del>共计<?php echo $order->amountPrice;?>元</del><br />
	 				<span>同楼价<?php echo $order->groupAmountPrice;?>元</span>
	 			</div>
	 			<?php else :?>
	 			<div class="fl width80px">
	 				<span>共计<?php echo $order->amountPrice;?>元</span><br />
	 				<del>同楼价<?php echo $order->groupAmountPrice;?>元</del>
	 			</div>
	   			<?php endif;?>
	 		<?php else:?>
	   		<div class="fl width80px">
		   		<?php if($order->dispatchingAmountPrice > 0):?>
		   		费送费<?php echo $order->dispatchingAmountPrice;?>元 <br />
		   		<?php endif;?>
		   		共计<?php echo $order->amountPrice;?>元 <br />
		   		<?php if($order->paidAmountPrice):?>
		   		已付款<?php echo $order->paidAmountPrice;?>元 <br />
		   		<?php endif;?>
		   		应付款<?php echo $order->dueAmountPrice;?>元
	   		</div>
	   		<?php endif;?>
	   		<div class="fl ma-t10px ma-l20px">
	   		<span class="cred">
	   		<?php if($order->groupon):?>
	   			同楼订餐
	   		<?php elseif($order->buy_type == Shop::BUYTYPE_NETWORK || $order->buy_type == Shop::BUYTYPE_PRINTER):?>
	   			<?php echo $order->statusText; ?>
	   		<?php else:?>
		  		<?php echo $order->buyTypeText; ?>
		  	<?php endif;?>
		  	</span>
		  	<!-- <span><?php //echo $order->orderLogs[0] ? $order->orderLogs[0]->shortCreateDateTimeText : $order->shortCreateDateTimeText; ?></span> -->
		  	<br />
		  	<?php if($order->status==Order::STATUS_DELIVERING):?>
		  		配送员(<?php echo $order->deliveryMan->name; ?>,<?php echo $order->deliveryMan->mobile; ?>) <br />
		  	<?php endif;?>
	   		<?php if($order->status==Order::STATUS_COMPLETE || $order->status==Order::STATUS_DELIVERING):?>服务：
		   		<?php if(!$order->shopCreditLogs->id): ?>
		   			<span id="service_<?php echo $order->id;?>"><input name="radio" type="button" value="好评" onclick="serviceComment(<?php echo $order->id; ?>,1,<?php if($order->buy_type==Shop::BUYTYPE_TELPHONE) echo param('markShopGradeGoods'); else echo intval($order->amount);?>)" />&nbsp;&nbsp;<input name="radio" type="button" value="差评" onclick="serviceComment(<?php echo $order->id; ?>,0,<?php if($order->buy_type==Shop::BUYTYPE_TELPHONE) echo param('markShopGradeGoods'); else echo intval($order->amount);?>)" /></span>
		   		<?php else: ?>
		   			<?php echo $order->shopCreditLogs->evaluatesText; ?>
		   		<?php endif; ?>
			<?php endif; ?>
	   		</div><div class="clear"></div>
	 	</div>
	 	<div class="clear"></div>
	 	<div class="ar">
	 	<?php if($order->groupon):?>
	 		<?php if($order->status==Order::STATUS_UNDISPOSED):?>
	 		<span class="ma-r10px"><a href="javascript:void(0);" onclick="sendCancel(<?php echo $order->id;?>, this)">取消订单</a></span>
	 		<?php elseif ($order->status==Order::STATUS_CANCEL):?>
	 		<span class="ma-r10px">订单已取消</span>
	 		<?php endif;?>
	 	<?php elseif($order->buy_type==Shop::BUYTYPE_NETWORK && $order->status!=Order::STATUS_INVAIN):?>
	 		<?php if($order->status!=Order::STATUS_CANCEL && $order->status!=Order::STATUS_COMPLETE):?>
		 		<?php if($order->pay_type==Shop::PAYTYPE_ONLINE && $order->is_pay==0):?>
				<span class="ma-r10px"><?php echo l('在线付款',url('alipay/pay', array('orderid'=>$order->id)));?></span>
				<?php endif;?>
				<?php if($order->cancel_state==STATE_ENABLED):?>
		    	<span class="ma-r10px">已取消(等待商家确认)</span>
		    	<?php else:?>
		    	<span class="ma-r10px"><a href="javascript:void(0);" onclick="sendCancel(<?php echo $order->id;?>, this)">取消订单</a></span>
		    	<?php endif;?>
		    	<span class="ma-r10px"><a href="javascript:void(0);" onclick="sendQuestion(<?php echo $order->id;?>)">订单咨询</a></span>
		    <?php endif;?>
	 		<!-- <span class="ma-r10px"><a class="clickme">查看订单</a></span> -->
	    <?php endif;?>
	 	</div>
	</div>
	<?php endforeach;?>
	<div class="pages ar">
	<?php echo $norationPages;?>
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
	没有该类下的订单！
	<?php endif;?>
</div>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'send_question',
    'htmlOptions' => array('class'=>'none'),
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'订单咨询',
        'autoOpen'=>false,
		'width' => 400,
		'height' => 280,
    ),
));
?>
<div class="lh20px">
	快速咨询内容：
	<ul class="question-list">
		<li class="bg-icon">麻烦我的订单快一点！</li>
		<li class="bg-icon">我的订单口味要辣一些！</li>
		<li class="bg-icon">我的订单口味不要辣！</li>
	</ul>
	<?php echo CHtml::textArea('question_content', '', array('style'=>'width:340px;height:68px;', 'class'=>'ma-t10px'));?>
	<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'post_question',
				'buttonType' => 'button',
				'caption' => '我要咨询',
				'htmlOptions' => array('class'=>'ma-t10px')
			)
		);
		?>
	<div id="question_error"></div>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'send_cancel',
    'htmlOptions' => array('class'=>'none'),
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'订单取消',
        'autoOpen'=>false,
		'width' => 400,
		'height' => 280,
    ),
));
?>
<div class="lh20px">
	申请取消订单的理由：
	<?php echo CHtml::textArea('cancel_content', '', array('style'=>'width:340px;height:68px;', 'class'=>'ma-t10px'));?>
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'post_cancel',
			'buttonType' => 'button',
			'caption' => '取消订单',
			'htmlOptions' => array('class'=>'ma-t10px')
		)
	);
	?>
	<div id="cancel_error"></div>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script language="JavaScript">
//取消订单
function sendCancel(order_id, obj) {
	var url = '<?php echo url('my/order/cancel');?>';
	$("#send_cancel").dialog("open");
	$("#post_cancel").click(function(){
		var content = $("#cancel_content").val();
		if(!content) {
			$("#cancel_error").html('申请取消订单的理由不能为空！');
			return;
		}
		$.post(url, {order_id:order_id,content:content}, function(data){
			if(data == 2) {
				$(obj).parent().html('已取消(等待商家确认)');
				$("#send_cancel").dialog("close");
			} else if (data == 1) {
				$(obj).parent().html('已取消');
				$("#send_cancel").dialog("close");
			} else {
				$("#cancel_error").html(data);
			}
		});
	});
}

// 订单咨询
function sendQuestion(order_id) {
	var url = '<?php echo url('my/question/create');?>';
	$("#send_question").dialog("open");
	$("#question_error").html('');
	var textarea = $("#question_content");
	$("#send_question li").click(function(){
		textarea.val($(this).html());
	});
	$("#post_question").click(function(){
		var content = $("#question_content").val();
		$.post(url, {order_id:order_id,content:content}, function(data){
			if(data) {
				$("#question_error").html(data);
			} else {
				$("#question_error").html('咨询发送成功！');
				textarea.val('');
				$("#send_question").dialog("close");
			}
		});
		$("#post_question").unbind();
	});
	return false;
}

// 服务评分 shop_id,order_id,evaluate
function serviceComment(order_id, evaluate, mark) {
	var url = '<?php echo url('my/order/postService');?>';
	$.post(url,{order_id:order_id, evaluate:evaluate},function(data){
		if(data) {
			alert(data);
		} else {
			if(evaluate==1) {
				$("#service_" + order_id).html('好评');
			} else {
				$("#service_" + order_id).html('差评');
			}
			showIntegral(mark);
		}
	});
}

// 口味评分
function rateComment(e) {
	var inputname = $(this).attr('name');
	var orderGoodsId = inputname.replace('rating_','');
	var rate_value = $(this).val();
	var content_id = '#content_' + inputname;
	var mark_id = '#mark_' + inputname;
	var url = '<?php echo url('my/order/postRate');?>';

	$.post(url, {ordergoodsid:orderGoodsId, value:rate_value }, function(data){
		if(data) {
			rateContentComment(data, content_id);
			$(mark_id).html($(this).title);
			$(mark_id).attr('id','');
			$('#' + inputname + " .star-rating").unbind();
			showIntegral($('#' + inputname).attr('mark'));
		}
	});
}

// 口味点评内容
function rateContentComment(rateid, content_id) {
	var content_html = '<div class="fl"><textarea id="content_' + rateid + '" class="dianping f14px"></textarea></div><div class="fl"><input id="button_' + rateid + '" type="button" value="点评" /></div><div class="clear"></div>';
	$(content_id).html(content_html);
	
	$('#content_' + rateid).focus(function(){
		$(this).css('height', '40px');
	});
	$('#content_' + rateid).blur(function(){
		$(this).css('height', '20px');
	});
	$('#button_' + rateid).click(function(){
		var content = $('#content_' + rateid).val();
		var url = '<?php echo url('my/order/postRateContent');?>';
		$.post(url, {id:rateid, content:content}, function(data){
			if(data) {
				alert(data);
			} else {
				$(content_id).html(content);
			}
		});
	});
}

// tab选中操作
$(function(){
	$("#<?php echo $id;?>").removeClass('normal');
	$("#<?php echo $id;?>").addClass('select');
});
</script>