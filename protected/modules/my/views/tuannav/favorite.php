<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/tuannav/favorite");?>">团购收藏</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/tuannav/tuansecond");?>">发布的二手</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<table class="list">
	  <tr>
	    <th class="al" width="120">团购图片</th>
	    <th class="al">团购名称</th>
	    <th width="60">操作</th>
	  </tr>
	<?php if(!$tuanfavorite):?>
	  <tr>
	    <td class="ac" colspan="5">没有收藏列表!</td>
	    </tr>
	<?php endif;?>
	<?php foreach ((array)$tuanfavorite as $row): ?>
	  <tr>
	    <td class="al"><?php echo $row->tuannav->imageLinkHtml;?></td>
	    <td class="al"><a href="<?php echo $row->tuannav->absoluteUrl?>" target="_blank" title="<?php echo $row->tuannav->title?>"><?php echo $row->tuannav->title;?></a></td>
	    <td class="ac"><a href="<?php echo url('my/tuannav/favoriteDelete', array('fid'=>$row->id,'type'=>'tuan'));?>" onclick="javascript: return confirm('真要删除这条收藏吗？')">删除</a></td>
	  </tr>
	<?php endforeach;?>
	  <tr>
	    <td class="pages ar pa-t10px pa-b10px" colspan="5">
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
	</table>
</div>