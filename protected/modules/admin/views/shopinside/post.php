<?php echo CHtml::beginForm(url('admin/shopinside/post', array('id'=>$shopinside->id)),'post',array('name'=>'create'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar ">店铺名称：</td>
        <td class=""><?php echo $shopinside->shop_name;?></td>
    </tr>
    <tr>
    	<td class="ar">用户名：</td>
    	<td class=""><?php echo CHtml::activeTextField($user, 'username', array('class'=>'txt', 'style'=>'width:300px'));?></td>
    </tr>
    <tr>
    	<td class="ar ">新密码：</td>
    	<td class=""><?php echo CHtml::activeTextField($user, 'password', array('class'=>'txt', 'style'=>'width:300px'));?></td>
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
<?php echo CHtml::endForm();?>
<?php
echo CHtml::errorSummary($user);
?>
