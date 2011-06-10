<?php echo CHtml::beginForm(url('super/shop/categoryCreate'),'post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="50">名称：</td>
        <td width="200"><?php echo CHtml::textField('Category[name]', $category_post['name'], array('class'=>'txt')); ?></td>
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
  
<?php if ($category):?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title">
        <th class="al" width="100">分类名称</th>
        <th class="al">状态</th>
        <th class="al" width="100">操作</th>
    </tr>
  <?php foreach ($category as $key=>$val):?>
    <tr <?php if ($key%2 != 0) { echo 'class="divbg1"'; } ?>>
        <td><?php if($val->state == 0){ echo "<strike>".$val->name."</strike>"; }else{ echo $val->name;}?></td>
        <td><?php echo $val->stateText;?></td>
	    <td>
	    <?php if($val->state == 1){ ?>
	    	<a href="<?php echo url('super/shop/categoryEdit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>&nbsp;&nbsp;
	    	<a href="<?php echo url('super/shop/categoryDelete', array('id'=>$val->id))?>" onclick="return confirm('确定要禁用吗？');"><span class="color">禁用</span></a>
	    <?php }?>
	    </td>
    </tr>
  <?php endforeach;?>
</table>
<?php else:?>
<div>目前没有店铺分类</div>
<?php endif;?>
  