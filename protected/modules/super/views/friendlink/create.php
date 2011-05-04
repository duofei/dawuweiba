<?php echo CHtml::form(null, 'post', array('name'=>'theform', 'enctype'=>'multipart/form-data'));?>
<table border="0" width="100%" class="frm-tbl list-tbl" cellspacing="1" cellpadding="0">
	<tr><th class="al f14px indent10px" colspan="2">添加友情链接</th></tr>
	<tr><td class="item-title ar">链接名称：</td><td><?php echo CHtml::activeTextField($friendlink, 'name', array('class'=>'txt subject'));?></td></tr>
	<tr><td class="item-title ar">网站地址：</td><td><?php echo CHtml::activeTextField($friendlink, 'homepage', array('class'=>'txt subject'));?></td></tr>
	<tr><td class="item-title ar">Logo：</td><td><?php echo CHtml::activeTextField($friendlink, 'logo', array('class'=>'txt subject'));?></td></tr>
	<tr><td class="item-title ar">分站城市：</td><td><?php echo CHtml::activeDropDownList($friendlink, 'city_id', $city, array('empty'=>'全站'));?></td></tr>
	<tr><td class="item-title ar">描述：</td><td><?php echo CHtml::activeTextArea($friendlink, 'desc', array('class'=>'remark'));?></td></tr>
	<tr><td class="item-title ar">立即启用：</td><td><?php echo CHtml::activeCheckBox($friendlink, 'isvalid', array('class'=>'checkbox'));?></td></tr>
	<tr><td>&nbsp;</td><td><?php echo CHtml::submitButton('添加友情链接', array('class'=>'btn'));?></td></tr>
</table>
<?php echo CHtml::endForm();?>

<div id="note-list"><?php echo CHtml::errorSummary($friendlink);?></div>