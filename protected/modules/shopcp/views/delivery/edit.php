<?php echo CHtml::beginForm(url('shopcp/delivery/edit'),'post',array('name'=>'add'));?>
<input type="hidden" name="id" value="<?php echo $deliveryMan->id?>">
<table  class="tabcolor list-tbl" width="100%">
    <tr>
        <td width="50">姓名：</td>
        <td width="100"><?php echo $deliveryMan->name; ?></td>
        <td width="50">&nbsp;&nbsp;手机：</td>
        <td width="200"><?php echo CHtml::activeTextField($deliveryMan, 'mobile', array('class'=>'txt')); ?></td>
        <td>&nbsp;&nbsp;
        <?php $this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'submit',
				'caption' => '提 交',
			)
		);?></td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
  <?php echo user()->getFlash('errorSummary'); ?>
