<?php echo CHtml::beginForm(url('admin/user/setmanager', array('id'=>$user->id)),'post',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo $user->username?></td>     
    </tr>
    <?php if($authItem->name == 'CityAdmin'):?>
    <tr><td colspan="2">此会员已是分站管理员不能再进行设置！</td></tr>
    <?php else:?>
    <tr>
        <td class="ar">选择角色：</td>
        <td><?php echo CHtml::dropDownList('role', $authItem->name, $roles);?></td>     
    </tr>
    <tr><td colspan="2"><input type="submit" value="设置角色" /></td></tr>
    <?php endif;?>
</table>
<input type="hidden" value="<?php echo $user->id;?>" name="id" />
<?php echo CHtml::endForm();?>
<div class="clear"></div>