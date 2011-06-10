
<?php echo CHtml::beginForm(url('super/user/integral'),'post',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo $user_info->username?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">用户积分：</td>
        <td><?php echo $user_info->integral?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">增减积分数：</td>
        <td><?php echo CHtml::textField('integral', '', array('class'=>'txt')); ?><span class="cred">*</span>正数为增加积分，负数为减少积分</td>     
    </tr>
    <tr>
        <td width="120" class="ar">操作备注：</td>
        <td><?php echo CHtml::textArea('remark', '', array('cols'=>'55', 'rows'=>'3')); ?><span class="cred">*</span></td>     
    </tr>
</table>
<input type="hidden" value="<?php echo $user_info->id;?>" name="id">
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
 <?php echo CHtml::endForm();?>
 <div class="clear"></div>
 <?php echo user()->getFlash('errorSummary'); ?>