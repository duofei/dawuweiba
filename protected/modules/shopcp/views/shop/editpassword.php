<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/shop/editpassword");?>">修改密码</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
    <?php echo CHtml::beginForm('', 'post');?>
		<table  class="tabcolor list-tbl" width="600">
		    <tr>
		        <td width="100">旧　密码：</td>
		        <td><?php echo CHtml::passwordField('oldpassword', '', array('class'=>'txt')); ?></td>
		    </tr>
		    <tr>
		        <td >新　密码：</td>
		        <td><?php echo CHtml::passwordField('newpassword', '', array('class'=>'txt')); ?></td>
		    </tr>
		    <tr>
		        <td>确认密码：</td>
		        <td><?php echo CHtml::passwordField('repassword', '', array('class'=>'txt')); ?></td>
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
	<?php if($errorSummary): ?>
	<div class="errorSummary pa-10px ma-t10px"><?php echo $errorSummary;?></div>
	<?php endif;?>
	</div>
</div>