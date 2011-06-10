<?php
echo CHtml::form(null, 'post', array('name' => 'theform'));
?>
<table border="0" width="100%" class="frm-tbl list-tbl" cellspacing="1" cellpadding="0">
	<tr>
		<th class="al f14px indent10px" colspan="2">添加禁用IP</th>
	</tr>
	<tr>
		<td class="item-title ar"><?php echo CHtml::activeLabel($model, 'ip_start');?>：</td>
		<td><?php echo CHtml::activeTextField($model, 'ip_start', array('class' => 'txt'));?></td>
	</tr>
	<tr>
		<td class="item-title ar"><?php echo CHtml::activeLabel($model, 'ip_end');?>：</td>
		<td>
		    <?php echo CHtml::activeTextField($model, 'ip_end', array('class' => 'txt'));?>
		    <span class="cred">如果要禁用某个特定的ip，结束IP填写与起始IP一样</span>
		</td>
	</tr>
	<tr>
		<td class="item-title ar"><?php echo CHtml::activeLabel($model, 'user_id');?>：</td>
		<td>
		    <?php echo CHtml::activeTextField($model, 'user_id', array('class' => 'txt'));?>
		    <span class="cred">可选，如果不清楚是哪个用户，可不填写</span>
		</td>
	</tr>
	<tr>
		<td class="item-title ar"><?php echo CHtml::activeLabel($model, 'type');?>：</td>
		<td>
		    <?php echo CHtml::activeRadioButtonList($model, 'type', DenyIp::$types, array('separator'=>'&nbsp;', 'class' => 'txt'));?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo CHtml::submitButton('提交禁用IP', array('class' => 'btn' , 'name' => 'submit'));?></td>
	</tr>
</table>
<?php echo CHtml::endForm();?>

<div id="note-list"><?php echo CHtml::errorSummary($model);?></div>
