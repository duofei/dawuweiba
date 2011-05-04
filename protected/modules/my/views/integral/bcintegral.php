<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/integral/bcintegral");?>">白吃点使用记录</a></li>
	  <li class="corner-top cgray"><a href="<?php echo url("my/integral/change");?>">积分换白吃点</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<table class="list">
	  <tr>
	    <th class="ac" width="130">使用名称</th>
	    <th class="ac" width="50">点数</th>
	    <th>使用时间</th>
	  </tr>
	<?php if($bclog):?>
	<?php foreach ((array)$bclog as $row): ?>
	  <tr>
	    <td class="ac"><?php echo $row->sourceText;?></td>
	    <td class="ac"><?php echo $row->integralText;?></td>
	    <td class="ac"><?php echo $row->shortCreateDateTimeText;?></td>
	  </tr>
	<?php endforeach;?>
	  <tr>
	    <td class="pages ar pa-t10px pa-b10px" colspan="3">
		<?php $this->widget('CLinkPager', array(
			'pages' => $pages,
		    'header' => '',
		    'firstPageLabel' => '首页',
		    'lastPageLabel' => '末页',
		    'nextPageLabel' => '下一页',
		    'prevPageLabel' => '上一页',
		));?>
	    </td>
	  </tr>
	<?php else:?>
	  <tr>
	    <td class="ac" colspan="3">没有收藏列表!</td>
	  </tr>
	<?php endif;?>
	</table>
</div>