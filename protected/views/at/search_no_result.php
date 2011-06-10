<?php echo CHtml::beginForm(url('at/search'), 'get');?>
<div class="fl ma-t40px search-no-icon bg-pic"></div>
<div class="fl ma-t40px search-no-text lh30px f14px">
    <h3 class="ma-b20px f18px">对不起，本站没有找到与<span class="cred"><?php echo $kw;?></span>相关的地址</h3>
    <p>
    	建议您适当删减或更改搜索关键词&nbsp;或者&nbsp;输入门牌号例如“山大路47号”
    	<br /><input class="txt" name="kw" type="text" value="<?php echo $kw;?>" />
    	<input type="submit" class="cursor" value="搜索" />
    </p>
    <p>您也可以在<a href="javascript:void(0);" onclick="openDialog()">电子地图上标注您的位置</a></p>
    <p><?php echo l('我的地址不存在， 我要提交地址!', url('location/create'));?></p>
</div>
<?php echo CHtml::endForm();?>
<div class="clear"></div>

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
function openDialog() {
	$("#ShowMap").dialog("open");
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/location'); ?>');
}
</script>


