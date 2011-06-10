<div><img class="ma-t10px ma-b10px" src="<?php echo resBu('images/shop-step1.jpg');?>" width="600" height="50" /></div>
<div class="pa-l10px f14px fl pa-r10px shop-checkin-left">
	<?php echo CHtml::beginForm('', 'post', array('id'=>'register-form'));?>
	<h4 class="f18px ma-t10px ma-b10px">我要开店　店铺注册</h4>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>店主姓名：</label></div>
		<div class="fl"><?php echo CHtml::activeTextField($shop, 'owner_name', array('class'=>'txt'));?></div>
		<div class="fl ma-l5px"> </div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>身份证号：</label></div>
		<div class="fl"><?php echo CHtml::activeTextField($shop, 'owner_card', array('class'=>'txt'));?></div>
		<div class="fl ma-l5px"> </div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>店铺名称：</label></div>
		<div class="fl"><?php echo CHtml::activeTextField($shop, 'shop_name', array('class'=>'txt'));?></div>
		<div class="fl ma-l5px"> </div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>订购方式：</label></div>
		<div class="fl"><span><?php echo CHtml::activeRadioButtonList($shop, 'buy_type', Shop::$buytype, array('separator'=>'</span> &nbsp; <span>'));?></span></div>
		<div class="fl ma-l5px"></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>店铺分类：</label></div>
		<div class="fl" id="shopCategory"><input id="Shop_category_id_0" value="1" checked="checked" type="radio" name="Shop[category_id]"> 美食
			<!-- <span><?php //echo CHtml::activeRadioButtonList($shop, 'category_id', ShopCategory::$categorys, array('separator'=>'</span> &nbsp; <span>'));?></span> -->
		</div>
		<div class="fl ma-l5px"></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px" id="shopTag">
		<div class="fl"><label>餐厅分类：</label></div>
		<div class="fl" style="width:250px;"><span><?php echo CHtml::CheckBoxList('tags', '', $shopTag, array('separator'=>'</span> &nbsp; <span>'));?></span></div>
		<div class="fl ma-l5px"></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>所属地区：</label></div>
		<div class="fl">
			<?php echo CHtml::DropDownList('city_id', $city_id,$city);?>
			<?php echo CHtml::activeDropDownList($shop, 'district_id', $district);?>
		</div>
		<div class="fl ma-l5px"></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>详细地址：</label></div>
		<div class="fl"><?php echo CHtml::activeTextField($shop, 'address', array('class'=>'txt'));?></div>
		<div class="fl ma-l5px"></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl"><label>联系电话：</label></div>
		<div class="fl"><?php echo CHtml::activeTextField($shop, 'telphone', array('class'=>'txt'));?></div>
		<div class="fl ma-l5px"> </div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="fl ma-l5px"><?php echo CHtml::checkBox('pact', true, array('id'=>'agreement'));?></div>
		<div class="fl ma-l5px"><label for="agreement">我已认真阅读并完全同意<?php echo l('开店协议', url('static/service'));?>中所有条款</label></div>
		<div class="clear"></div>
	</div>
	<div class="lh30px ma-t10px ma-b10px">
		<div class="ma-l5px"><?php echo CHtml::submitButton('提交申请', array('class'=>'btn-four cred'));?></div>
	</div>
	<?php echo CHtml::endForm();?>
	<div class="lh30px ma-t10px ma-b10px">
		<?php echo $errorSummary;?>
	</div>
</div>
<div class="fl pa-10px f14px cgray lh30px shop-checkin-right">
	<p style="height:15px"></p>
	<p class="ma-t10px ma-l20px">您也可以拨打我爱外卖信息发布市场热线<?php echo param('servicePhone');?> 直接与我们取得联系。我们会协助您完成注册和开店的准备工作。</p>
	<p>&nbsp;</p>
	<p class="cred ma-l20px">冒用他人信息或发布其他虚假信息我们将向公安部门进行举报! </p>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
	$("#city_id").change(function(){
		var getDistrictUrl = '<?php echo url('at/district'); ?>';
		var city_id = $("#city_id").val();
		$.get(getDistrictUrl,{city_id:city_id},function(data){
			var html = '';
			for(var i=0; i<data.length; i++) {
				html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
			}
			$("#Shop_district_id").html(html);
		},'json');
	});
	$("#shopCategory span").click(function(){
		var value = $("input[name='Shop[category_id]']:checked").val();
		if(value==1) {
			$('#shopTag').show();
		} else {
			$('#shopTag').hide();
			$('#shopTag input').each(function(){
				$(this).attr('checked','');
			});
		}
	});
});
</script>