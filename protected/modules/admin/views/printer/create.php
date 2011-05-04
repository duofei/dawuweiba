<?php echo CHtml::beginForm('', 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="100" class="ar">编号：</td>
        <td><?php echo CHtml::activeTextField($model, 'code', array('class'=>'txt')); ?> <span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">手机号：</td>
        <td><?php echo CHtml::activeTextField($model, 'phone', array('class'=>'txt')); ?> <span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">绑定商铺：</td>
        <td><?php echo $model->shop ? $model->shop->getNameLinkHtml(0, '_blank') : '';?></td>
    </tr>
    <tr>
        <td class="ar">备注：</td>
        <td><?php echo CHtml::activeTextArea($model, 'remark', array('class'=>'f12px', 'rows'=>3, 'cols'=>80)); ?></td>
    </tr>
    <tr>
    	<td class="al" colspan="2">
    		<?php echo CHtml::submitButton('提交信息');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($model);?>