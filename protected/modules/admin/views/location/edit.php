<?php echo CHtml::beginForm(url('admin/location/edit', array('id'=>$location->id)), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td class="ar">地址名称：</td>
        <td><?php echo CHtml::activeTextField($location, 'name', array('class'=>'txt', 'style'=>'width:300px;')); ?> <span class="cred">*</span></td>     
    </tr>
    <tr>
        <td class="ar">详细地址：</td>
        <td><?php echo CHtml::activeTextField($location, 'address', array('class'=>'txt', 'style'=>'width:300px;')); ?></td>     
    </tr>
    <tr>
        <td class="ar">地图坐标：</td>
        <td>
        	<span id="showMapXY"><?php echo $location->map_x;?> , <?php echo $location->map_y;?></span> <?php echo l('修改', 'javascript:void(0)', array('onclick'=>'openDialog()'));?>
        	<?php echo CHtml::activeHiddenField($location, 'map_x'); ?><?php echo CHtml::activeHiddenField($location, 'map_y'); ?>
        </td>
    </tr>
    <tr>
        <td class="ar">使用次数：</td>
        <td><?php echo CHtml::activeTextField($location, 'use_nums', array('class'=>'txt', 'style'=>'width:50px;')); ?></td>
    </tr>
    <tr>
        <td class="ar">状态：</td>
        <td class="f14px"><span><?php echo CHtml::activeRadioButtonList($location, 'state', Location::$states, array('separator'=>'</span> <span>'));?></span></td>     
    </tr>
    <tr>
    	<td class="ac" colspan="2">
    		<?php echo CHtml::activeHiddenField($location, 'id'); ?>
    		<?php echo CHtml::HiddenField('url', $url);?>
    		<?php echo CHtml::submitButton('提交信息');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($location);?>
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
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('map_x'=>$location->map_x, 'map_y'=>$location->map_y, 'callback'=>'setMapXY', 'city_id'=>$_SESSION['manage_city_id'])); ?>');
}
</script>