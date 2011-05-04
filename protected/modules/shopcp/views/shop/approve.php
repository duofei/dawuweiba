<?php echo CHtml::beginForm(url('shopcp/shop/approve'),'post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
<h3>商家认证</h3>
<table  class="tabcolor list-tbl" width="100%">
  <tr>
    <td width="120">营业执照复印件:</td>
    <td><?php if (!$shop_info->commercial_instrument) { echo CHtml::fileField('commercial_instrument', ''); } else { if (!$shop_info->is_commercial_approve) { echo '您的营业执照认证申请已经提交，请耐心等待审核！'; }else { echo '您的营业执照认证已通过审核！'; } }?></td>
  </tr>
  <tr>
    <td width="120">卫生许可证复印件:</td>
    <td><?php if (!$shop_info->sanitary_license) { echo CHtml::fileField('sanitary_license', ''); } else { if (!$shop_info->is_commercial_approve) { echo '您的卫生许可证认证申请已经提交，请耐心等待审核！'; }else { echo '您的卫生许可证认证已通过审核！'; } }?></td>
  </tr>
</table>
<?php
if (!$shop_info->commercial_instrument || !$shop_info->sanitary_license) 
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
<?php echo user()->getFlash('errorSummary'); ?>
<?php echo CHtml::endForm();?>
