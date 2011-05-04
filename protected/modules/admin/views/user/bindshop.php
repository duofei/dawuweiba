<?php echo CHtml::beginForm(url('admin/user/bindshop', array('id'=>$user->id)),'post',array('name'=>'bindshop', 'id'=>'bindshop'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo $user->username;?></td>
    </tr>
    <tr>
    	<td class="ar">已绑定的商铺：</td>
    	<td>
    	<?php foreach((array)$user->shops as $shop):?>
    		<?php echo $shop->getNameLinkHtml(0, '_blank');?>
    	<?php endforeach;?>
    	</td>
    </tr>
    <tr>
        <td width="120" class="ar">商铺：</td>
        <td>
        	<?php echo CHtml::textField('shop_name', '', array('class'=>'txt')); ?>
        	<?php echo CHtml::button('查询', array('id'=>'search', 'url'=>url('admin/shop/searchforbind')));?>
        </td>
    </tr>
    <tr>
		<td></td>
        <td id="shoplist">请先查询商铺</td>
    </tr>
</table>
<?php echo CHtml::hiddenField('userid', $user->id);?>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'bind',
		'caption' => '绑定商铺',
	)
);?>
<?php echo CHtml::endForm();?>
<script type="text/javascript">
$(function(){
	$('#search').click(function(){
		var shop_name = $('#shop_name').val();
		var url = $(this).attr('url');
		$.get(url,{'kw':shop_name}, function(data){
			if(data) {
				$('#shoplist').html(data);
			}
		});
	});
	$('#bindshop').submit(function(){
		return confirm('确定要把用户绑定到此商铺吗？');
	});
});
</script>
<div class="lh30px"><?php echo $message;?></div>