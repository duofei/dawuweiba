<?php if ($tuanRecommend) :?>
<table  class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="300">网址</th>
        <th class="al" width="60">推荐次数</th>
        <th class="al" width="60">状态</th>
        <th class="al">操作</th>
    </tr>
<?php foreach ($tuanRecommend as $key=>$val) :?>
	<tr>
		<td><a href="<?php echo $val->url?>" target="_blank"><?php echo $val->url?></a></td>
		<td><?php echo $val->nums?></td>
		<td><?php echo $val->stateText?></td>
		<td>
		<a href="<?php echo url('admin/tuannav/recommendstate', array('id'=>$val->id, 'state'=>TuanRecommend::STATE_PASS))?>"><span class="color">通过</span></a>
		<a href="<?php echo url('admin/tuannav/recommendstate', array('id'=>$val->id, 'state'=>TuanRecommend::STATE_IGNORE))?>"><span class="color">忽略</span></a>
		<a href="<?php echo url('admin/tuannav/recommenddelete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
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
  <div>目前没有团购</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>