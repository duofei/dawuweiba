
<?php echo CHtml::beginForm(url('super/user/profile'),'post',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'username', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">邮箱：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'email', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">密码：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'clear_password', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">真实姓名：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'realname', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">性别：</td>
        <td><?php echo CHtml::activeRadioButtonList($user_info, 'gender', User::$genders, array('separator'=>''));?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">生日：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'User[birthday]',
    'value' => $user_info->birthday,
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt'),
));
?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">电话：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'telphone', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">手机号：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'mobile', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">qq：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'qq', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">msn：</td>
        <td><?php echo CHtml::activeTextField($user_info, 'msn', array('class'=>'txt')); ?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">行政区域：</td>
        <td><?php echo $user_info->city->name . '|' . $user_info->district->name?> 
			<?php echo CHtml::activeDropDownList($user_info, 'district_id', $district);?><span class="cred">*</span></td>     
    </tr>
    <tr>
        <td width="120" class="ar">状态：</td>
        <td><?php echo CHtml::activeRadioButtonList($user_info, 'state', User::$states, array('separator'=>''));?><span class="cred">*</span></td>     
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