<?php if ($result) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">秒杀介绍</th>
        <th class="al">用户名</th>
        <th>商品名称</th>
        <th width="120">订单id</th>
        <th width="120">秒杀时间</th>
        <th width="120">状态</th>
    </tr>
<?php foreach ($result as $key=>$val) :?>
	<tr>
		<td><?php echo $val->miaosha->desc;?></td>
		<td><a href="<?php echo url('admin/user/info', array('id'=>$val->user->id))?>"><?php echo $val->user->username;?></a></td>
		<td><?php echo $val->goods->name;?></td>
		<td class="ac"><?php echo $val->order->orderSn;?></td>
		<td><?php echo $val->shortCreateDateTimeText;?></td>
		<td class="ac"><?php echo $val->order_id ? '成功' : '失败';?></td>
	</tr>
<?php endforeach;?>
</table>
 	<div class="pages ar">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '翻页',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
 <?php else:?>
  <div>目前没有商铺</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>