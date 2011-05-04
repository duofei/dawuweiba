<?php echo CHtml::beginForm(url('shopcp/delivery/create'),'post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl" width="100%">
    <tr>
        <td width="50">姓名：</td>
        <td width="200"><?php echo CHtml::textField('Delivery[name]', '', array('class'=>'txt')); ?></td>
        <td width="50">&nbsp;&nbsp;手机：</td>
        <td width="200"><?php echo CHtml::textField('Delivery[mobile]', '', array('class'=>'txt')); ?></td>
        <td>&nbsp;&nbsp;<?php 
	        $this->widget('zii.widgets.jui.CJuiButton',
				array(
					'name' => 'submit',
					'caption' => '提 交',
				)
			);
		?></td>
    </tr>

</table>
<?php echo CHtml::endForm();?>
  <?php echo user()->getFlash('errorSummary'); ?>