<?php echo CHtml::beginForm('','post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">礼品名称：</td>
        <td><?php echo $model->gift->name;?></td>
    </tr>
    <tr>
        <td width="120" class="ar">兑换用户：</td>
        <td><?php echo $model->user->username;?></td>
    </tr>
    <tr>
        <td width="120" class="ar">兑换时间：</td>
        <td><?php echo $model->createDateTimeText;?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收货人：</td>
        <td><?php echo CHtml::activeTextField($model, 'consignee', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收货地址：</td>
        <td><?php echo CHtml::activeTextField($model, 'address', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收货人电话：</td>
        <td><?php echo CHtml::activeTextField($model, 'telphone', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">备用电话：</td>
        <td><?php echo CHtml::activeTextField($model, 'mobile', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">状态：</td>
        <td><?php echo CHtml::activeDropDownList($model, 'state', GiftExchangeLog::$states);?></td>
    </tr>
    <tr>
        <td width="120" class="ar">备注：</td>
        <td><?php echo CHtml::activeTextArea($model, 'message', array('cols'=>65, 'rows'=>5));?></td>
    </tr><tr>
        <td width="120" class="ar">&nbsp;</td>
        <td><?php echo CHtml::submitButton('提交处理');?></td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<div id="note-list"><?php echo CHtml::errorSummary($model);?></div>