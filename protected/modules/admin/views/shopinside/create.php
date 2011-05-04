<?php
$iss = array(
	'1' => '是',
	'0' => '否',
);
?>
<?php echo CHtml::beginForm(url('admin/shopinside/create', array('id'=>$shopinside->id)),'post',array('name'=>'create'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar ">店铺名称：</td>
        <td class=""><?php echo CHtml::activeTextField($shopinside, 'shop_name', array('class'=>'txt', 'style'=>'width:350px')); ?></td>
    </tr>
    <tr>
    	<td class="ar ">配送范围：</td>
    	<td class="">
    		<?php echo l('设置配送范围', 'javascript:void(0);', array('id'=>'showMapRegion')); ?>
    		<?php echo CHtml::activeHiddenField($shopinside, 'map_region', array('id'=>'region'));?>
    	</td>
    </tr>
    <tr>
    	<td class="ar ">起送条件：</td>
    	<td class=""><?php echo CHtml::activeTextField($shopinside, 'transport_condition', array('class'=>'txt', 'style'=>'width:350px'));?></td>
    </tr>
    <tr>
    	<td class="ar ">送餐时间：</td>
    	<td class=""><?php echo CHtml::activeTextField($shopinside, 'transport_time', array('class'=>'txt'));?></td>
    </tr>
	<tr><td colspan="2">
		<div class="f14px ma-l20px lh30px"><a href="#" id="showMoreInfo">填写更多信息</a></div>
		<table class="list-tbl none" width="100%" cellspacing="1" id="moreInfo">
			<tr>
		        <td width="120" class="ar">店铺分类：</td>
		        <td><?php echo CHtml::activeRadioButtonList($shopinside, 'category_id', ShopCategory::$categorys, array('separator'=>'&nbsp;'));?></td>
		    </tr>
		    <tr>
		        <td class="ar">所属地区：</td>
		        <td><?php echo CHtml::activeDropDownList($shopinside, 'district_id', $district);?></td>
		    </tr>
		    <tr>
		    	<td class="ar">详细地址：</td>
		    	<td><?php echo CHtml::activeTextField($shopinside, 'address', array('class'=>'txt', 'style'=>'width:350px'));?></td>
		    </tr>
		    <tr>
		        <td class="ar">是否支持网络订餐：</td>
		        <td><?php echo CHtml::activeRadioButtonList($shopinside, 'buy_type', Shop::$buytype, array('separator'=>'&nbsp;')); ?></td>
		    </tr>
		    <tr>
		    	<td class="ar">店铺位置：</td>
		    	<td>
		    		<span id="showMapXY"><?php echo $shopinside->map_x;?> , <?php echo $shopinside->map_y;?></span> <?php echo l('地图标注', 'javascript:void(0)', array('onclick'=>'openDialog()'));?>
		        	<?php echo CHtml::activeHiddenField($shopinside, 'map_x'); ?><?php echo CHtml::activeHiddenField($shopinside, 'map_y'); ?>
		    	</td>
		    </tr>
		    <tr>
		    	<td class="ar">预订时间限制：</td>
		    	<td>最短可提前<?php echo CHtml::activeTextField($shopinside, 'reserve_hour', array('class'=>'txt', 'style'=>'width:50px'));?>小时预订。</td>
		    </tr>
		    <tr>
		    	<td class="ar">营业时间：</td>
		    	<td><?php echo CHtml::activeTextField($shopinside, 'business_time', array('class'=>'txt'));?></td>
		    </tr>
		    <tr>
		    	<td class="ar">联系电话：</td>
		    	<td><?php echo CHtml::activeTextField($shopinside, 'telphone', array('class'=>'txt'));?></td>
		    </tr>
		    <tr>
		    	<td class="ar">店主手机：</td>
		    	<td><?php echo CHtml::activeTextField($shopinside, 'mobile', array('class'=>'txt'));?></td>
		    </tr>
		    
		    <tr>
		    	<td class="ar">联系QQ：</td>
		    	<td><?php echo CHtml::activeTextField($shopinside, 'qq', array('class'=>'txt'));?></td>
		    </tr>
		   
		    <tr>
		    	<td class="ar">是否清真：</td>
		    	<td><?php echo CHtml::activeRadioButtonList($shopinside, 'is_muslim', $iss, array('separator'=>'&nbsp;')); ?></td>
		    </tr>
		    <tr>
		    	<td class="ar">是否接受团购预订：</td>
		    	<td><?php echo CHtml::activeRadioButtonList($shopinside, 'is_group', $iss, array('separator'=>'&nbsp;')); ?></td>
		    </tr>
		    <tr>
		    	<td class="ar">店铺简介：</td>
		    	<td><?php echo CHtml::activeTextArea($shopinside, 'desc', array('style'=>'width:350px'));?></td>
		    </tr>
		    <tr>
		    	<td class="ar">店铺公告：</td>
		    	<td><?php echo CHtml::activeTextArea($shopinside, 'announcement', array('style'=>'width:350px'));?></td>
		    </tr>
		    
		    <tr>
		        <td class="ar">是否支持在线支付：</td>
		        <td><?php echo CHtml::activeRadioButtonList($shopinside, 'pay_type', $iss, array('separator'=>'&nbsp;')); ?></td>
		    </tr>
		    <tr>
		        <td class="ar">店主姓名：</td>
		        <td><?php echo CHtml::activeTextField($shopinside, 'owner_name', array('class'=>'txt')); ?></td>
		    </tr>
		    <tr>
		        <td class="ar">身份证号：</td>
		        <td><?php echo CHtml::activeTextField($shopinside, 'owner_card', array('class'=>'txt')); ?></td>
		    </tr>
		</table>
	</td></tr>
</table>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
<?php echo CHtml::endForm();?>
<?php
echo CHtml::errorSummary($shopinside);
?>

<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在电子地图上标注您的位置',
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
function setMapXY(x, y) {
	$('#showMapXY').html(x + ',' + y);
	$('#ShopInside_map_x').val(x);
	$('#ShopInside_map_y').val(y);
	$("#ShowMap").dialog("close");
}

function setRegion(points) {
	$("#ShowMap").dialog("close");
	$("#region").val(points);
}

function openDialog() {
	$("#ShowMap").dialog("open");
	var map_x = $('#ShopInside_map_x').val();
	var map_y = $('#ShopInside_map_y').val();
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('callback'=>'setMapXY', 'city_id'=>$_SESSION['manage_city_id'])); ?>' + '?map_x=' + map_x + '&map_y=' + map_y);
}
$(function(){
	$("#showMapRegion").click(function(){
		$("#ShowMap").dialog("open");
		var region = $('#region').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion')); ?>' + '?region=' + region);
	});
	$("#showMoreInfo").click(function(){
		$('#moreInfo').toggle();
	});
});
</script>
