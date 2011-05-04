<h2 class="pa-l10px cred f16px bline lh30px">我要提交楼宇</h2>

<?php echo CHtml::beginForm(url('building/create'), 'post');?>
<div class="m10px f14px">
	<div>如果发现您搜索的楼宇不存在，请您告诉我们，谢谢！</div>
	<?php if($success):?>
	<h2 class="ma-t20px ma-b20px lh30px f20px"><?php echo $success;?></h2>
	<?php else:?>
	<div class="ma-t10px">
		楼宇名称：<?php echo CHtml::activeTextField($building, 'name', array('class' => 'txt'));?>
	</div>
	<div class="ma-t10px">
		行政区域：<?php echo CHtml::activeDropDownList($building, 'district_id', District::getDistrictArray($this->city[id]));?>
	</div>
	<div class="ma-t10px">
		详细地址：<?php echo CHtml::activeTextField($building, 'address', array('class' => 'txt'));?>
	</div>
	<div class="ma-t10px">
		<?php echo CHtml::activeHiddenField($building, 'map_x');?>
		<?php echo CHtml::activeHiddenField($building, 'map_y');?>
		地图标注：<?php echo l('开始标注', 'javascript:void(0);', array('onclick'=>'openDialog()'));?>
		<span id="showMapXY"><?php echo $building->map_x;?>, <?php echo $building->map_y;?></span>
	</div>
	<div class="ma-t10px">
	验证　码：
	<?php echo CHtml::activetextField($building ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'tabindex'=>3, 'maxlength'=>4))?>
	<?php $this->widget('CCaptcha',array(
		'captchaAction' => 'captcha',
		'showRefreshButton' => true,
		'buttonLabel' => '换一个',
		'clickableImage' => true,
		'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
	));?>
	</div>
	<div class="ma-t10px"><?php echo CHtml::submitButton('提 交', array('class'=>'fb cred btn-two'));?></div>
	<div class="ma-t10px">
		<?php echo $errorSummary; ?>
	</div>
	<?php endif;?>
</div>
<?php echo CHtml::endForm();?>

<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
	'htmlOptions' => array('class'=>'none'),
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
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/getLatLon', array('map_x'=>$location->map_x, 'map_y'=>$location->map_y, 'callback'=>'setMapXY')); ?>');
}
</script>