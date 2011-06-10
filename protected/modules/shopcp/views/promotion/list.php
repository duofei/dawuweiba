<?php if ($promotion) : ?>
<table  class="tabcolor list-tbl" width="100%">
    <tr class="title">
        <th class="al">优惠信息</th>
        <th class="al" width="80">截止时间</th>
        <th class="al" width="80">添加时间</th>
        <th class="ac" width="80">操作</th>
    </tr>
  <?php foreach ($promotion as $key=>$val):?>
    <tr <?php if ($key%2 != 0) { echo 'class="divbg1"'; } ?>>
        <td><?php echo $val->content?></td>
        <td class="cred"><?php echo $val->endDateText?></td>
        <td><?php echo $val->createDateText;?></td>
	    <td class="ar"><a href="<?php echo url('shopcp/promotion/edit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
	    	<a href="<?php echo url('shopcp/promotion/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
	    </td>
    </tr>
  <?php endforeach;?>
    <?php else:?>
    	<div>您目前没有发布优惠信息</div>
    <?php endif;?>
</table>