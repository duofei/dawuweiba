<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
			't' => $t
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-content">
		<div class="miaosha-info pa-t20px cgray">
			<div style="width:450px; margin:0px auto;">
				<div class="ac"><?php echo CHtml::image(resBu('miaosha2/images/dd_r1_c1.gif'));?></div>
				<div class="ma-t20px bgeee lh30px pa-l10px pa-r10px">
					<span class="fr ac f14px" style="width:70px">价格</span>
					<span class="fr ac f14px" style="width:70px">数量</span>
					店名：<?php echo $cart[0]->goods->shop->shop_name;?>
				</div>
				<?php foreach ($cart as $c):?>
				<div class="lh30px pa-l10px pa-r10px f14px">
					<span class="fr ac" style="width:70px">￥<?php echo $cart[0]->goods_price;?></span>
					<span class="fr ac" style="width:70px"><?php echo $cart[0]->goods_nums;?></span>
					<?php echo $cart[0]->goods_name;?>
				</div>
				<?php endforeach;?>
				<div class="mline1px ma-t20px ma-b10px"></div>
				<div class="ar lh30px f14px">总计：￥1.0元 货到付款</div>
				<div class="ar lh30px f14px">一元秒杀活动</div>
				<div class="mline1px ma-t10px"></div>
				<!-- 配送信息 -->
				<div class="ma-t10px pa-l10px f14px">配送信息：</div>
				<?php if($address):?>
				<!-- 现有配送地址 -->
				<ul class="lh24px pa-l10px ma-t5px">
				<?php foreach ((array)$address as $v):?>
					<li>
						<input type="radio" name="address-item" value="<?php echo $v->id;?>" id="address<?php echo $v->id;?>" <?php if($v->id == $address_default->id) {echo 'checked';}?> />&nbsp;<?php echo $v->address;?>
						<input type="hidden" name="consignee" value="<?php echo $v->consignee;?>" />
						<input type="hidden" name="address" value="<?php echo $v->address;?>" />
						<input type="hidden" name="telphone" value="<?php echo $v->telphone;?>" />
						<input type="hidden" name="city_id" value="<?php echo $v->city_id;?>" />
					</li>
				<?php endforeach;?>
				</ul>
				<?php endif;?>
				<!-- 填写送餐地址 -->
				<?php echo CHtml::beginForm(url('miaosha2/checkout', array('miaosha_id'=>$miaosha_id)), 'post', array('id'=>'postform'));?>
				<ul class="lh30px pa-l10px ma-t10px f14px">
					<li class="lh30px">姓名：<input type="text" value="<?php echo $address_default->consignee;?>" id="consignee" name="UserAddress[consignee]" tabindex="1" class="txt" style="width:240px;"> <span id="consignee_msg" class="cred"></span></li>
					<li class="lh30px ma-t5px">手机：<input type="text" value="<?php echo $address_default->telphone;?>" id="telphone" name="UserAddress[telphone]" tabindex="2" class="txt" style="width:240px;"> <span id="telphone_msg" class="cred"></span></li>
					<li class="lh30px ma-t5px">地址：<input type="text" value="<?php echo $address_default->address;?>" id="address" name="UserAddress[address]" tabindex="3" class="txt" style="width:240px;"> <span id="address_msg" class="cred"></span></li>
					<li class="lh30px ma-t5px">
						<div class="fl">备注：<textarea name="UserAddress[message]" id="message" style="width:240px; height:65px;" tabindex="4"><?php echo $message;?></textarea></div>
						<div class="fl messagespan f12px ma-l5px">
							<span>么零钱</span><span>不吃辣</span><br />
							<span>辣一点</span><span>多加米</span><br />
							<span>自取</span><span>谢谢:)</span>
						</div>
						<div class="clear"></div>
					</li>
				</ul>
				<?php echo CHtml::endForm();?>
				<div class="mline1px ma-t10px ma-b10px"></div>
				<div class="ar">
					<span class="cursor" id="submit"><?php echo CHtml::image(resBu('miaosha2/images/dd_r3_c2.gif'));?></span>
				</div>
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
	$(".messagespan span").click(function(){
		$("#message").val($("#message").val() + ' ' + $(this).html());
	});
	$(':radio[name=address-item]').click(function(){
		var consignee = $(this).siblings(':hidden[name=consignee]').val();
		var address = $(this).siblings(':hidden[name=address]').val();
		var telphone = $(this).siblings(':hidden[name=telphone]').val();
		var mobile = $(this).siblings(':hidden[name=mobile]').val();
		var addressid = $(this).siblings(':hidden[name=aid]').val();
		var cityid = $(this).siblings(':hidden[name=city_id]').val();
		$('#consignee').val(consignee);
		$('#address').val(address);
		$('#telphone').val(telphone);

		if($('#cityid')) {
			$('#cityid').val(cityid);
		}
		if($(this).attr('class')=='edit-address') {
			$('#editAddress').val(1);
			$(this).siblings(':radio[name=address-item]').attr('checked', true);
		} else {
			$('#editAddress').val(0);
		}
	});
	$('#submit').click(function(){
		var status = true;
		var consignee = $('#consignee').val();
		if(consignee=='') {
			$('#consignee_msg').html('* 姓名不能为空');
			status = false;
		}
		var address = $('#address').val();
		if(address=='') {
			$('#address_msg').html('* 地址不能为空');
			status = false;
		}
		var telphone = $('#telphone').val();
		if(telphone=='') {
			$('#telphone_msg').html('* 手机不能为空');
			status = false;
		}
		var phone_re = /^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/;
		var telphone_re = /^(0[1-9][0-9]{1,2}-?)?[1-9][0-9]{6,7}$/;
		if(!telphone.match(phone_re) && !telphone.match(telphone_re)) {
			$('#telphone_msg').html('* 格式填写不正确');
			return ;
		}
		if(status) {
			$('#postform').submit();
		}
	});
});
</script>