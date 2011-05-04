<?php echo CHtml::beginForm('', 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="160" class="ar">隐藏码：</td>
        <td><?php echo CHtml::activeTextField($model, 'hcode', array('class'=>'txt')); ?> <span class="cred">*</span>
        <?php echo CHtml::button(' 随机生成 ', array('id'=>'randomCode'));?></td>
    </tr>
    <tr>
        <td class="ar">白吃点数：</td>
        <td><?php echo CHtml::activeTextField($model, 'integral', array('class'=>'txt')); ?> <span class="cred">*</span> 6-10之间</td>
    </tr>
    <tr>
        <td class="ar">状态：</td>
        <td><?php echo CHtml::activeRadioButtonList($model, 'state', UserInviterHideCode::$states, array('separator'=>'&nbsp;'));?></td>
    </tr>
    <tr>
    	<td class="al" colspan="2">
    		<?php echo CHtml::submitButton(' 提 交 ');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($model);?>
<script type="text/javascript">
$(function(){
	$('#randomCode').click(function(){
		var  x="0123456789ABCDEFGHIGKLMNOPQRSTUVWXYZ";
		var  tmp="";
		for(var i=0;i<8;i++) {
			tmp += x.charAt(Math.ceil(Math.random()*100000000)%x.length);
		}
		$('#UserInviterHideCode_hcode').val(tmp);
	});
});
</script>