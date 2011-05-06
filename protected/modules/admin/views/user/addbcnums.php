<?php echo CHtml::beginForm('','post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户姓名：</td>
        <td><?php echo $user->username;?></td>
    </tr>
    <tr>
        <td class="ar">白吃点：</td>
        <td><?php echo $user->bcnums;?></td>
    </tr>
    <tr>
        <td class="ar">增加白吃点：</td>
        <td><?php echo CHtml::textField('bcnums', '0', array('class'=>'txt', 'style'=>'width:50px;'));?></td>
    </tr>
	<tr>
		<td colspan="2" class="ac"><?php echo CHtml::submitButton(' 增加积分 ');?></td>
	</tr>
</table>
<?php echo CHtml::endForm();?>