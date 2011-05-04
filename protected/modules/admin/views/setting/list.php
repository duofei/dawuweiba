<?php echo CHtml::beginForm('','post',array('name'=>'setting'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
	<tr><th width="200">参数</th><th>内容值</th></tr>
    <tr>
        <td class="ar">待审核订单处理：</td>
        <td>
			<?php echo CHtml::radioButtonList('Setting[' . param('s_orderApprove') . ']', $setting[param('s_orderApprove')], array('0'=>'关闭', '1'=>'开启'), array('separator'=> '&nbsp;'));?>
		</td>
    </tr>
    <tr>
        <td class="ar">待审核订单自动关闭处理：</td>
        <td><?php echo CHtml::textField('Setting[' . param('s_orderApproveCloseTime') . ']', $setting[param('s_orderApproveCloseTime')], array('class'=>'txt'));?></td>
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
<div class="errorsummary">
<?php //echo CHtml::errorSummary($miaosha);?>
</div>