<?php echo CHtml::beginForm(url('super/shop/categoryEdit'),'post',array('name'=>'add'));?>
<input type="hidden" name="id" value="<?php echo $category->id?>">
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="50">名称：</td>
        <td width="200"><?php echo CHtml::activeTextField($category, 'name', array('class'=>'txt')); ?></td>
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
  <?php echo user()->getFlash('errorSummary'); ?>