<?php echo CHtml::beginForm(url('admin/miaosha/edit', array('id'=>$miaosha->id)),'post',array('name'=>'miaosha'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="200" class="ar">秒杀介绍：</td>
        <td>
			<?php echo CHtml::activeTextArea($miaosha, 'desc', array('style'=>'width:500px;height:100px'));?>
		</td>
    </tr>
    <tr>
        <td class="ar">秒杀时间：</td>
        <td>
        	<?php echo CHtml::textField('y', date("Y", $miaosha->active_time), array('class'=>'txt ac', 'style'=>'width:30px'));?> -
        	<?php echo CHtml::textField('m', date("m", $miaosha->active_time), array('class'=>'txt ac', 'style'=>'width:15px'));?> -
        	<?php echo CHtml::textField('d', date("d", $miaosha->active_time), array('class'=>'txt ac', 'style'=>'width:15px'));?>
        	　<?php echo CHtml::textField('h', date("H", $miaosha->active_time), array('class'=>'txt ac', 'style'=>'width:15px'));?> :
        	<?php echo CHtml::textField('i', date("i", $miaosha->active_time), array('class'=>'txt ac', 'style'=>'width:15px'));?>
        </td>
    </tr>
    <tr>
        <td class="ar">秒杀数量：</td>
        <td><?php echo CHtml::activeTextField($miaosha, 'active_num', array('class'=>'txt', 'style'=>'width:50px'));?></td>
    </tr>
    <tr>
        <td class="ar">虚假数量：</td>
        <td><?php echo CHtml::activeTextField($miaosha, 'untrue_num', array('class'=>'txt', 'style'=>'width:50px'));?> 要小于秒杀数量</td>
    </tr>
    <tr>
        <td class="ar">选择商铺：</td>
        <td>
        	<div>
	        	<?php echo CHtml::activeHiddenField($miaosha, 'shop_id');?>
	        	<?php echo CHtml::textField('shop_name', '', array('class'=>'txt')); ?>
	        	<?php echo CHtml::button('查询', array('id'=>'search', 'url'=>url('admin/shop/searchforbind')));?>
        	</div>
        	<div id="shoplist" class="ma-t10px">
        	<?php if($miaosha->shop):?>
        		<?php $this->renderPartial('/shop/searchforbind', array('shops'=>array($miaosha->shop)));?>
        	<?php else:?>
        		请先查询商铺
        	<?php endif;?>
        	</div>
        </td>
    </tr>
    <tr>
        <td class="ar">选择商品：</td>
        <td id="goodslist">请先选择商铺</td>
    </tr>
    <tr>
        <td class="ar">状态：</td>
        <td><?php echo CHtml::activeRadioButtonList($miaosha, 'state', Miaosha::$states, array('separator'=> '&nbsp;'))?></td>
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
<script type="text/javascript">
$(function(){
	$('#search').click(function(){
		var shop_name = $('#shop_name').val();
		var url = $(this).attr('url');
		$.get(url,{'kw':shop_name}, function(data){
			if(data) {
				$('#shoplist').html(data);
				$('#goodslist').html('请先选择商铺');
			}
		});
	});
	$('input[name="shopid"]').live('click', function(){
		var shopId = $(this).val();
		var miaoshaId = <?php echo intval($miaosha->id);?>;
		$('#Miaosha_shop_id').val(shopId);
		var url = "<?php echo url('admin/miaosha/searchgoods');?>";
		$.get(url, {shopid:shopId, miaoshaid:miaoshaId}, function(data){
			if(data) {
				$('#goodslist').html(data);
			}
		});
	});
	$('input[name="shopid"]').click();
});
</script>
<div class="errorsummary">
<?php echo CHtml::errorSummary($miaosha);?>
</div>