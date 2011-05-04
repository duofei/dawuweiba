<?php echo CHtml::beginForm(url('super/cityadmin/addcity', array('id'=>$city->id)), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td class="ar">城市名称：</td>
        <td><?php echo CHtml::activeTextField($city, 'name', array('class'=>'txt')); ?> <span class="cred">*</span></td>     
    </tr>
    <tr>
        <td class="ar">城市中心坐标：</td>
        <td>
        	<span id="showMapXY"><?php echo $city->map_x;?> , <?php echo $city->map_y;?></span> <?php echo l('修改', 'javascript:void(0)', array('onclick'=>'openDialog()'));?>
        	<?php echo CHtml::activeHiddenField($city, 'map_x'); ?><?php echo CHtml::activeHiddenField($city, 'map_y'); ?>
        </td>
    </tr>
    <tr>
    	<td class="ac" colspan="2">
    		<?php echo CHtml::activeHiddenField($city, 'id'); ?>
    		<?php echo CHtml::submitButton('提 交');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<div><?php echo CHtml::errorSummary($city);?></div>
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
	$('#City_map_x').val(x);
	$('#City_map_y').val(y);
	$("#ShowMap").dialog("close");
}
function openDialog() {
	$("#ShowMap").dialog("open");
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('map_x'=>$location->map_x, 'map_y'=>$location->map_y, 'callback'=>'setMapXY')); ?>');
}
</script>