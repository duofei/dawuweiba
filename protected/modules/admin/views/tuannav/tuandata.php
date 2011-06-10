<?php if ($tuandata) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">团购网名称</th>
        <th class="al">创办者</th>
        <th class="al" width="120">电话</th>
        <th class="al" width="60">QQ</th>
        <th class="al" width="160">邮箱</th>
        <th width="80">API类型</th>
        <th width="90">上线时间</th>
        <th width="60">排序值</th>
        <th width="80">操作</th>
    </tr>
<?php foreach ($tuandata as $key=>$val) :?>
	<tr>
		<td><a href="<?php echo aurl('tuannav/info', array('source_id' => $val->id))?>" target="_blank"><?php echo $val->name?></a></td>
		<td><?php echo $val->create?></td>
		<td><?php echo $val->mobile?></td>
		<td><?php echo $val->QQ?></td>
		<td><?php echo $val->email?></td>
		<td class="ac"><?php echo $val->apitype ? $val->apiTypeText : '';?></td>
		<td class="ac"><?php echo substr($val->online_time, 0, 10);?></td>
		<td class="ac"><?php echo $val->orderid;?></td>
		<td class="ac"><a href="<?php echo url('admin/tuannav/tuanEdit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
		<a href="<?php echo url('admin/tuannav/tuanDelete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
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
  <div>目前没有团购网</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>