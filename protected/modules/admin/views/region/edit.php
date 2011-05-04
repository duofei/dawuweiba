<?php echo CHtml::beginForm(url('admin/region/edit', array('id'=>$region->id)), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td class="ar">地图区域名称：</td>
        <td><?php echo CHtml::activeTextField($region, 'name', array('class'=>'txt', 'style'=>'width:300px;')); ?> <span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">地图范围坐标：</td>
        <td>
        	<span id="showMapXY" class="block" style="width:800px; overflow:hidden;"><?php echo $region->region;?></span> <?php echo l('修改', 'javascript:void(0)', array('onclick'=>'openDialog()'));?>
        	<?php echo CHtml::activeHiddenField($region, 'region', array('id'=>'region_hidden')); ?>
        </td>
    </tr>
    <tr>
    	<td class="ac" colspan="2">
    		<?php echo CHtml::activeHiddenField($region, 'id'); ?>
    		<?php echo CHtml::HiddenField('url', $url); ?>
    		<?php echo CHtml::submitButton('提交信息');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($region);?>
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

function setRegion(json) {
	$("#ShowMap").dialog("close");
	var length=json.coordinates.length;
	var lineString = '';
	for (i=0;i<length ;i++ ){
	    lineString += json.coordinates[i].x + "," + json.coordinates[i].y + "|";
	}
	$("#region_hidden").val(lineString);
	$("#showMapXY").html(lineString);
}
function openDialog() {
	$("#ShowMap").dialog("open");
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('region'=>$region->region, 'callback'=>'setRegion', 'region'=>$region->region)); ?>');
}
</script>