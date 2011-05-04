<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">标题：</td>
        <td><?php echo $tuannav->title ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">内容：</td>
        <td><?php echo $tuannav->content ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">分类：</td>
        <td><?php echo $tuannav->category->name?></td>
    </tr>
    <tr>
        <td width="120" class="ar">来源：</td>
        <td><?php echo $tuannav->tuandata->name ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">团购价：</td>
        <td><?php echo $tuannav->group_price ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">折扣：</td>
        <td><?php echo $tuannav->discount ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">原价：</td>
        <td><?php echo $tuannav->original_price ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">售出件数：</td>
        <td><?php echo $tuannav->sell_num ?></td>
    </tr>
    --><tr>
        <td width="120" class="ar">截至日期：</td>
        <td><?php echo $tuannav->effective_time?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收藏：</td>
        <td><?php echo $tuannav->favorite_num?></td>
    </tr>
    <tr>
        <td width="120" class="ar">添加时间：</td>
        <td><?php echo $tuannav->createTimeText?></td>
    </tr>
    <tr>
        <td width="120" class="ar">添加ip：</td>
        <td><?php echo $tuannav->create_ip?></td>
    </tr>
</table>

<?php if ($tuanComment) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="200">评价内容</th>
        <th class="al" width="60">评价人</th>
        <th class="al" width="160">评价时间</th>
        <th class="al" width="60">评价IP</th>
        <th class="al" width="">操作</th>
    </tr>
<?php foreach ($tuanComment as $key=>$val) :?>
	<tr>
		<td><?php echo $val->content?></td>
		<td><?php echo $val->user->username?></td>
		<td><?php echo $val->createTimeText?></td>
		<td><?php echo $val->create_ip?></td>
		<td><a href="<?php echo url('admin/tuannav/commentDelete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
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
  <div>目前没有评论</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>