<?php if ($deliveryMan):?>
<table  class="tabcolor list-tbl" width="100%">
    <tr class="title">
        <th class="al">姓名</th>
        <th class="al">手机</th>
        <th class="al" width="150">加入时间</th>
        <th class="al" width="100">状态</th>
        <th class="al" width="100">操作</th>
    </tr>
  <?php foreach ($deliveryMan as $key=>$val):?>
    <tr <?php if ($key%2 != 0) { echo 'class="divbg1"'; } ?>>
        <td><?php if($val->state == 0){ echo "<strike>".$val->name."</strike>"; }else{ echo $val->name;}?></td>
        <td><?php if($val->state == 0){ echo "<strike>".$val->mobile."</strike>"; }else{ echo $val->mobile;}?></td>
        <td><?php echo $val->createDateText;?></td>
        <td><?php echo $val->stateText;?></td>
	    <td>
	    <?php if($val->state == 1){ ?>
	    	<a href="<?php echo url('shopcp/delivery/edit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>&nbsp;&nbsp;
	    	<a href="<?php echo url('shopcp/delivery/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
	    <?php }?>
	    </td>
    </tr>
  <?php endforeach;?>
</table>
<?php else:?>
<div>您目前没有送餐员</div>
<?php endif;?>
