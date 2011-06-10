<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px al">
		<th>
<?php foreach ((array)$citylist as $key=>$val):?>
		<a href="<?php echo url('super/searchlog/statistics', array('id'=>$key));?>"><?php echo $val?></a>
<?php endforeach;?>
		</th>
	</tr>
</table>

<?php if ($searchs) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
	<tr>
		<td colspan="2" class="al"><h3><?php echo $citylist[$_GET['id']]?></h3></td>
	</tr>
    <tr class="title f14px">
        <th class="al" width="120">关键字</th>
        <th class="al" width="120">查询次数</th>
    </tr>
<?php foreach ((array)$searchs as $key=>$val) :?>
	<tr>
		<td><?php echo $val[keywords]?></td>
		<td><?php echo $val[c]?></td>
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
  <div>没有符合条件的查询结果</div>
<?php endif;?>
<?php echo user()->getFlash('errorSummary'); ?>