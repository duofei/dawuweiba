<?php $categorys = ShopCategory::$categorys;
$iss = array(
	'1' => '是',
	'0' => '否',
);
?>

 <div class="fl pa-t10px">
 <p><?php echo $shop_info->logoHtml; ?></p>
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'changer_logo',
			'buttonType' => 'button',
			'caption' => '更改logo',
			'onclick'=>'js:function(){$("#logo").dialog("open");}',
		)
	);
	?>
	<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	    'id'=>'logo',
	    'options'=>array(
	        'title'=>'更改logo',
	        'autoOpen'=>false,
	        'width' => 350,
	    ),
	    'htmlOptions' => array('class'=>'none'),
	));
	echo CHtml::beginForm(url('shopcp/shop/logo'),'post',array('name'=>'logo_edit', 'enctype'=>'multipart/form-data', 'target'=>'logo-ifarme'));
	?>
	<div class="logo-dialog">
		<?php echo CHtml::activeFileField($shop_info, 'logo');?>
		<br /><br />
		<?php
		$this->widget('zii.widgets.jui.CJuiButton', array(
			'name' => 'upload_logo',
			'caption' => '上 传',
		));
		?>
	</div>
	<iframe name="logo-ifarme" class="logo-ifarme"></iframe>
	<?php
	echo CHtml::endForm();
	$this->endWidget('zii.widgets.jui.CJuiDialog');
	?>
 </div>
 
 <?php echo CHtml::beginForm(url('shopcp/shop/profile'),'post',array('name'=>'edit', 'id'=>'profileForm'));?>
 <div class="fl ma-l20px">
<table  class="tabcolor list-tbl" width="600">
    <tr>
        <td width="120">店铺名称：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'shop_name', array('class'=>'txt')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td >店铺分类：</td>
        <td ><?php echo $shop_info->categoryText; ?></td>
    </tr>
    <tr>
        <td>详细地址：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'address', array('class'=>'txt'));?><span style="color:red">*</span></td>
    </tr>
    <tr>
        <td>地图坐标：</td>
        <td class="cred">
			<span id="showMapXY"><?php echo $shop_info->mapPosition;?></span>
			<?php echo CHtml::activeHiddenField($shop_info, 'map_x');?>
			<?php echo CHtml::activeHiddenField($shop_info, 'map_y');?>
			<a id="showMapClick" href="javascript:void(0);"><?php echo $shop_info->mapPosition ? '重新标注' : '标注地图';?></a>
        </td>
    </tr>
    <tr>
        <td>联系电话：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'telphone', array('class'=>'txt')); ?><span class="cred">*</span></td>
	</tr>
	<tr>
        <td>店主手机：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'mobile', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td>联系QQ：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'qq', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td>营业时间：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'business_time', array('class'=>'txt')); ?><span class="cred">*</span> 格式：9:00-21:00</td>
    </tr>
    <tr>
        <td>送餐时间：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'transport_time', array('class'=>'txt')); ?></td>
    </tr>
    <!-- 最小配送范围 -->
    <?php if($shop_info->transport_condition):?>
    <tr class="tline">
        <td class="ar">起送条件：</td>
        <td><?php echo $shop_info->transport_condition;?></td>
    </tr>
    <?php endif;?>
    <tr>
        <td class="ar">起送价：</td>
        <td>
        	<?php echo CHtml::activeTextField($shop_info, 'transport_amount', array('class'=>'txt', 'style'=>'width:100px'))?>
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	送餐费：<?php echo CHtml::activeTextField($shop_info, 'dispatching_amount', array('class'=>'txt', 'style'=>'width:100px'))?>
        </td>
    </tr>
    <tr>
        <td class="ar">最小配送范围：</td>
        <td>
        	<a id="showMapRegion" href="javascript:void(0);" style="color:#f69626"><?php echo $shop_info->map_region ? '重新绘制商铺的配送范围' : '点击打开地图绘制商铺的配送范围';?></a>
        	&nbsp;&nbsp;&nbsp;&nbsp;<a id="clearMapRegion" href="javascript:void(0);">清楚配送范围</a>
        	<?php echo CHtml::activeHiddenField($shop_info, 'map_region');?>
        </td>
	</tr>
	<!-- 适中配送范围 -->
	<?php if($shop_info->transport_condition2):?>
	<tr class="tcondition_mregion none divbg1">
		<td class="ar">起送条件：</td>
        <td><?php echo $shop_info->transport_condition2;?></td>
	</tr>
	<?php endif;?>
	<tr class="tcondition_mregion none divbg1">
        <td class="ar">起送价：</td>
        <td>
        	<?php echo CHtml::activeTextField($shop_info, 'transport_amount2', array('class'=>'txt', 'style'=>'width:100px'))?>
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	送餐费：<?php echo CHtml::activeTextField($shop_info, 'dispatching_amount2', array('class'=>'txt', 'style'=>'width:100px'))?>
        </td>
    </tr>
	<tr class="tcondition_mregion none divbg1">
        <td class="ar">适中配送范围：</td>
        <td>
        	<a id="showMapRegion2" href="javascript:void(0);" style="color:#f69626"><?php echo $shop_info->map_region2 ? '重新绘制商铺的配送范围' : '点击打开地图绘制商铺的配送范围';?></a>
        	&nbsp;&nbsp;&nbsp;&nbsp;<a id="clearMapRegion2" href="javascript:void(0);">清楚配送范围</a>
        	<?php echo CHtml::activeHiddenField($shop_info, 'map_region2');?>
        </td>
	</tr>
	<!-- 最大配送范围 -->
	<?php if($shop_info->transport_condition3):?>
	<tr class="tcondition_mregion none">
		<td class="ar">起送条件：</td>
        <td><?php echo $shop_info->transport_condition3;?></td>
	</tr>
	<?php endif;?>
	<tr class="tcondition_mregion none">
        <td class="ar">起送价：</td>
        <td>
        	<?php echo CHtml::activeTextField($shop_info, 'transport_amount3', array('class'=>'txt', 'style'=>'width:100px'))?>
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	送餐费：<?php echo CHtml::activeTextField($shop_info, 'dispatching_amount3', array('class'=>'txt', 'style'=>'width:100px'))?>
        </td>
    </tr>
	<tr class="tcondition_mregion none">
        <td class="ar">最大配送范围：</td>
        <td>
        	<a id="showMapRegion3" href="javascript:void(0);" style="color:#f69626"><?php echo $shop_info->map_region3 ? '重新绘制商铺的配送范围' : '点击打开地图绘制商铺的配送范围';?></a>
        	&nbsp;&nbsp;&nbsp;&nbsp;<a id="clearMapRegion3" href="javascript:void(0);">清楚配送范围</a>
        	<?php echo CHtml::activeHiddenField($shop_info, 'map_region3');?>
        </td>
	</tr>
	<tr class="bline">
		<td colspan="2" class="cred ar"><a href="javascript:void(0);" onclick="tmToggle()">更多起送条件和配送范围设置</a></td>
	</tr>
	<tr>
		<td>是否支持在线支付：</td>
		<td><?php echo $iss[$shop_info->buy_type]; ?></td>
    </tr>
    <?php if ($_SESSION['shop']->category_id == ShopCategory::CATEGORY_FOOD) :?>
	<tr>
		<td>是否清真：</td>
		<td><?php echo CHtml::activeRadioButtonList($shop_info, 'is_muslim', $iss, array('separator'=>' ')); ?></td>
    </tr>
    <tr>
    	<td>是否每日菜单不同：</td>
    	<td><?php echo CHtml::activeRadioButtonList($shop_info, 'is_dailymenu', $iss, array('separator'=>' ')); ?> (如选择是，请立即设置<a href="<?php echo url('shopcp/goods/daylist')?>" class="fb cred">每日菜单</a>，以方便前台显示菜品)</td>
    </tr>
    <!--
	<tr>
    	<td>是否接受同楼订餐:</td>
    	<td><?php echo CHtml::activeRadioButtonList($shop_info, 'is_group', $iss, array('separator'=>' ')); ?></td>
    </tr>
    -->
    <tr>
    	<td>同楼订餐成功金额：</td>
    	<td><?php echo CHtml::activeTextField($shop_info, 'group_success_price', array('class'=>'txt', 'style'=>'width:100px'));?>(如果您的商铺支持同楼订餐，请填写同楼订餐成功的金额)</td>
    </tr>
    <?php endif;?>
    <tr>
        <td>预订时间限制：</td>
        <td>最短可提前<?php echo CHtml::activeTextField($shop_info, 'reserve_hour', array('class'=>'txt', 'style'=>'width:50px')); ?>小时预订。</td>
	</tr>
	<tr>
		 <td>店铺简介：</td>
		 <td><?php echo CHtml::activeTextArea($shop_info, 'desc', array('cols'=>'55', 'rows'=>'3'))?></td>
	</tr>
	<tr>
 		<td>店铺公告：</td>
  		<td><?php echo CHtml::activeTextArea($shop_info, 'announcement', array('cols'=>'55', 'rows'=>'3'))?></td>
	</tr>
</table>
  <br />
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
 </div>
 <?php echo CHtml::endForm();?>
 <div class="clear"></div>
 <?php echo user()->getFlash('errorSummary'); ?>

<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在地图上标注',
        'autoOpen'=>false,
		'width' => 820,
		'height' => 530,
		'modal' => true,
		'draggable' => true,
		'resizable' => false
    ),
));
?>
<iframe id="ShowMapIframe" src="#" width="100%" height="480" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
$(function(){
	$("#showMapClick").click(function(){
		$("#ShowMap").dialog("open");
		var map_x = $('#Shop_map_x').val();
		var map_y = $('#Shop_map_y').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('callback'=>'setMapXY', 'city_id'=>$shop_info->district->city_id)); ?>' + '?map_x=' + map_x + '&map_y=' + map_y);
	});

	$("#clearMapRegion").click(function(){
		if(confirm('确定要清除此范围吗？')) {
			$('#Shop_map_region').val('');
			$('#Shop_transport_amount').val('0');
			$('#Shop_dispatching_amount').val('0');
			alert('范围已清除，请提交完成！');
		}
		return false;
	});
	$("#clearMapRegion2").click(function(){
		if(confirm('确定要清除此范围吗？')) {
			$('#Shop_map_region2').val('');
			$('#Shop_transport_amount2').val('0');
			$('#Shop_dispatching_amount2').val('0');
			alert('范围已清除，请提交完成！');
		}
		return false;
	});
	$("#clearMapRegion3").click(function(){
		if(confirm('确定要清除此范围吗？')) {
			$('#Shop_map_region3').val('');
			$('#Shop_transport_amount3').val('0');
			$('#Shop_dispatching_amount3').val('0');
			alert('范围已清除，请提交完成！');
		}
		return false;
	});
	$("#showMapRegion").click(function(){
		$("#ShowMap").dialog("open");
		var map_x = $('#Shop_map_x').val();
		var map_y = $('#Shop_map_y').val();
		var region = $('#Shop_map_region').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion')); ?>' + '?region=' + region + '&map_x=' + map_x + '&map_y=' + map_y);
	});
	$("#showMapRegion2").click(function(){
		$("#ShowMap").dialog("open");
		var map_x = $('#Shop_map_x').val();
		var map_y = $('#Shop_map_y').val();
		var region = $('#Shop_map_region2').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion2')); ?>' + '?region=' + region + '&map_x=' + map_x + '&map_y=' + map_y);
	});
	$("#showMapRegion3").click(function(){
		$("#ShowMap").dialog("open");
		var map_x = $('#Shop_map_x').val();
		var map_y = $('#Shop_map_y').val();
		var region = $('#Shop_map_region3').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion3')); ?>' + '?region=' + region + '&map_x=' + map_x + '&map_y=' + map_y);
	});
});

function setMapXY(x, y) {
	$('#showMapXY').html(x + ',' + y);
	$('#Shop_map_x').val(x);
	$('#Shop_map_y').val(y);
	$("#ShowMap").dialog("close");
	var url = '<?php echo aurl("shopcp/ditu/setShopLocation"); ?>';
	$.ajax({
		type: 'get',
		url: url,
		data: 'map_x=' + x + '&map_y=' + y,
		dataType: 'html',
		success: function(data){
		}
	});
}

function setRegion(position) {
	$("#ShowMap").dialog("close");
	$("#Shop_map_region").val(position);
}
function setRegion2(position) {
	$("#ShowMap").dialog("close");
	$("#Shop_map_region2").val(position);
}
function setRegion3(position) {
	$("#ShowMap").dialog("close");
	$("#Shop_map_region3").val(position);
}

function tmToggle() {
	$(".tcondition_mregion").toggle();
}
</script>


