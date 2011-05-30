<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/voucher/list");?>">优惠券列表</a></li>
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/voucher/create");?>">添加优惠券</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
		<?php echo CHtml::beginForm(url('shopcp/voucher/create'),'post',array('name'=>'add'));?>
		<table class="lh30px" width="730px">
		    <tr>
		        <td width="100" class="ar">选择商品：</td>
		        <td>
		        	<?php foreach ((array)$goods_list as $goods):?>
		        	<div class="fl lh24px ma-r20px" style="width:270px;">
		        		<input type="radio" name="goodsid" value="<?php echo $goods->id;?>" <?php if ($goods->id==$post['goods_id']) echo 'checked';?> />
		        		<?php echo $goods->name;?>(<?php echo $goods->wmPrice?>元)
		        	</div>
		        	<?php endforeach;?>
		        	<div class="clear"></div>
		        </td>
			</tr>
			<tr>
				<td class="ar">优惠价：</td>
				<td><input type="text" name="price" value="<?php echo $post['price'];?>" class="txt" style="width:60px" /></td>
			</tr>
	        <tr>
		        <td class="ar">有效期限：</td>
		        <td>
		        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				    'name'=>'end_time',
					'language' => 'zh',
				    'options'=>array(
						'dateFormat' => 'yy-mm-dd',
				        'showAnim'=>'fold',
				    ),
				    'htmlOptions'=>array(
				        'class'=>'txt'
				    ),
				    'value' => $_POST['end_time']
				)); ?></td>
			</tr>
			<tr>
		        <td><?php
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
		<?php if($error):?>
		<div class="lh24px pa-l20px ma-t20px" style="border:1px solid #CCCCCC; background:#FFFFCC;">
			<div class="cred">错误提示：</div>
			<?php foreach ($error as $e):?>
			<div><?php echo $e;?></div>
			<?php endforeach;?>
		</div>
		<?php endif;?>
  	</div>
</div>
