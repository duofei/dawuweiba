
<?php if ($tuanreport) :?>
<table  class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="240">被举报团购</th>
        <th class="al" width="70">举报原因</th>
        <th class="al" width="140">邮箱</th>
        <th class="al" width="">举报说明</th>
        <th class="al" width="150">添加时间</th>
    </tr>
<?php foreach ($tuanreport as $key=>$val) :?>
	<tr>
		<td><a href="<?php echo $val->tuannav->absoluteUrl?>" target="_blank"><?php echo $val->tuannav->titleSub?></a></td>
		<td><?php echo $val->typeText?></td>
		<td><?php echo $val->email?></td>
		<td><?php echo $val->content?></td>
		<td><?php echo $val->createTimeText?></td>
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
  <div>目前没有举报</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>