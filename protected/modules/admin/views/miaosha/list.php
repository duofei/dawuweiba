<?php if ($miaosha) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">秒杀介绍</th>
        <th class="al">商铺名称</th>
        <th width="120">秒杀时间</th>
        <th width="60">秒杀数量</th>
        <th width="120">创建时间</th>
        <th width="40">状态</th>
        <th width="60">操作</th>
    </tr>
<?php foreach ($miaosha as $key=>$val) :?>
	<tr>
		<td><?php echo $val->desc;?></td>
		<td><?php echo $val->shop->getNameLinkHtml(0, '_blank');?></td>
		<td><?php echo date(param('formatShortDateTime'), $val->active_time);?></td>
		<td class="ac"><?php echo $val->active_num;?></td>
		<td><?php echo $val->shortCreateDateTimeText;?></td>
		<td class="ac"><?php echo $val->active_time < time() ? '已结束' : $val->stateText;?></td>
		<td class="ac">
			<?php //if($val->active_time > time()):?>
			<a href="<?php echo url('admin/miaosha/edit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
			<?php //endif;?>
			<a href="<?php echo url('admin/miaosha/result', array('id'=>$val->id));?>">结果</a>
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
  <div>目前没有商铺</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>