<?php echo CHtml::beginForm('', 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="100" class="ar">打印机编号：</td>
        <td><?php echo $model->code; ?> <span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">商铺查询：</td>
        <td>
            <?php echo CHtml::textField('phone', '', array('class'=>'txt shop-kw')); ?>
            <?php echo CHtml::Button('查询', array('id'=>'searchshop'));?>
        </td>
    </tr>
    <tr>
        <td class="ar">绑定商铺：</td>
        <td>
            <?php echo $model->shop ? $model->shop->getNameLinkHtml(0, '_blank') : '';?>
            <select name="selectedshop" id="selected-shop"></select>
        </td>
    </tr>
    <tr>
    	<td class="ar">&nbsp;</td>
    	<td class="al">
    		<?php echo CHtml::submitButton('绑定商铺');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<?php echo CHtml::errorSummary($model);?>

<script type="text/javascript">
$(function(){
	$('#searchshop').click(function(){
		var url = '<?php echo aurl('admin/printer/searchshop');?>' + '?kw=' + $('.shop-kw').val();
		var markup = '<option value="${id}">${shop_name}</option>';
		
		$.getJSON(url, function(data){
			$('#selected-shop').html('');
			$.template('tpl', markup);
			$.tmpl('tpl', data).appendTo('#selected-shop');
		});
	});
});
</script>