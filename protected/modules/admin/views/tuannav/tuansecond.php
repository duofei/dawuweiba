<?php if ($tuansecond) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="60">交易类别</th>
        <th class="al" width="60">分类</th>
        <th class="al">标题</th>
        <th class="al" width="60">数量</th>
        <th class="al" width="60">价格</th>
        <th class="al" width="60">联系电话</th>
        <th class="al" width="150">添加时间</th>
        <th class="al" width="60">状态</th>
        <th class="al" width="60">操作</th>
    </tr>
<?php foreach ($tuansecond as $key=>$val) :?>
	<tr>
		<td><?php echo $val->tradeSortText?></td>
		<td><?php echo $val->category->name?></td>
		<td><?php echo $val->title?></td>
		<td><?php echo $val->nums?></td>
		<td><?php echo $val->price?></td>
		<td><?php echo $val->mobile?></td>
		<td><?php echo $val->shortCreateDateTimeText?></td>
		<td><?php echo $val->stateText?></td>
		<td><a href="<?php echo url('admin/tuannav/secondDelete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
		</td>
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
  <div>目前没有二手信息</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>