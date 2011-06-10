<?php echo CHtml::beginForm(url('admin/building/edit', array('id'=>$building->id)), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="100" class="ar">行政区域：</td>
        <td><?php echo CHtml::activeDropDownList($building, 'district_id', District::getDistrictArray($_SESSION['manage_city_id'])); ?> <span class="cred">*</span></td>
    </tr>
    <?php echo CHtml::activeHiddenField($building, 'type');?>
    <tr>
        <td class="ar">楼宇名称：</td>
        <td><?php echo CHtml::activeTextField($building, 'name', array('class'=>'txt', 'style'=>'width:300px;')); ?> <span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">地址：</td>
        <td><?php echo CHtml::activeTextField($building, 'address', array('class'=>'txt', 'style'=>'width:300px;')); ?></td>
    </tr>
    <tr>
        <td class="ar">地图坐标：</td>
        <td>
        	<span id="showMapXY"><?php echo $building->map_x;?> , <?php echo $building->map_y;?></span> <?php echo l('修改', 'javascript:void(0)', array('onclick'=>'openDialog()'));?>
        	<?php echo CHtml::activeHiddenField($building, 'map_x'); ?><?php echo CHtml::activeHiddenField($building, 'map_y'); ?>
        </td>
    </tr>
    <tr>
        <td class="ar">状态：</td>
        <td class="f14px"><span><?php echo CHtml::activeRadioButtonList($building, 'state', Location::$states, array('separator'=>'</span> <span>'));?></span></td>
    </tr>
    <tr>
    	<td class="ac" colspan="2">
    		<?php echo CHtml::activeHiddenField($building, 'id'); ?>
    		<?php echo CHtml::HiddenField('url', $url); ?>
    		<?php echo CHtml::submitButton('提交信息');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($building);?>
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
	$('#Location_map_x').val(x);
	$('#Location_map_y').val(y);
	$("#ShowMap").dialog("close");
}
function openDialog() {
	$("#ShowMap").dialog("open");
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('map_x'=>$building->map_x, 'map_y'=>$building->map_y, 'callback'=>'setMapXY', 'city_id'=>$_SESSION['manage_city_id'])); ?>');
}
</script>