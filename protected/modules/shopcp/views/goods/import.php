<?php echo CHtml::beginForm(url('shopcp/goods/import'), 'post', array('enctype'=>'multipart/form-data'));?>
<table  class="tabcolor list-tbl" width="100%">
	<tr>
		<td>选择导入文件：</td>
		<td><?php echo CHtml::fileField('postimport');?></td>
	</tr>
</table>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
<?php CHtml::endForm();?>
<div class="ma-t10px tline">
	<span class="fb">导入文本格式介绍：</span><br>
	<span>
	1. 菜品分类和菜品都以换行来分割<br />
	2. 菜品分类单独一行<br />
	3. 该分类下的菜品要放到当前分类行下录入<br />
	4. 菜品是以：菜品名称，价格，描述做为一行之间以逗号分割<br />
	</span>
	<br>
	<span class="fb">导入文本格式如下：</span><br>
	<span>
	木片便当类<br />
	木片秘制鸡腿便当,12,描述可有可无<br />
	木片回锅肉便当,10,<br />
	木片番茄蛋便当,10,<br />
	点心<br />
	香芋地瓜丸,7,<br />
	芋丝南瓜酥,10,<br />
	套餐<br />
	10元套餐,10,三菜一汤<br />
	15元套餐,15,五菜一汤<br />
	</span>
</div>