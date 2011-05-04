<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/tuannav/favorite");?>">团购收藏</a></li>
	  <li class="select corner-top cgray"><a href="<?php echo url("my/tuannav/tuansecond");?>">发布的二手</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
<?php if ($tuansecond) :?>
<table class="list">
    <tr>
        <th class="al" width="60">交易类别</th>
        <th class="al" width="40">分类</th>
        <th class="al">标题</th>
        <th class="al" width="40">数量</th>
        <th class="al" width="60">价格</th>
        <th class="al" width="100">联系电话</th>
        <th class="al" width="130">添加时间</th>
        <th class="al" width="40">状态</th>
        <th class="al" width="40">操作</th>
    </tr>
<?php foreach ($tuansecond as $key=>$val) :?>
	<tr>
		<td><?php echo $val->tradeSortText?></td>
		<td><?php echo $val->category->name?></td>
		<td><?php echo $val->titleSub?></td>
		<td><?php echo $val->nums?></td>
		<td>&yen;<?php echo $val->price?></td>
		<td><?php echo $val->mobile?></td>
		<td><?php echo $val->shortCreateDateTimeText?></td>
		<td><?php echo $val->stateText?></td>
		<td><?php if ($val->state == STATE_DISABLED) :?><a href="<?php echo url('my/tuannav/secondState', array('id'=>$val->id))?>" onclick="return confirm('确定要设置为成交吗？');"><span class="color">成交</span></a><?php endif;?>
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
</div>
  <?php echo user()->getFlash('errorSummary'); ?>