<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/favorite");?>">商品收藏</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/favorite/shoplist");?>">商铺收藏</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<table class="list">
	  <tr>
	    <th class="al">商品名称</th>
	    <th class="al">商铺名称</th>
	    <th width="60">价格</th>
	    <th width="130">收藏时间</th>
	    <th width="60">操作</th>
	  </tr>
	<?php if(!$goodsfavorite):?>
	  <tr>
	    <td class="ac" colspan="5">没有收藏列表!</td>
	    </tr>
	<?php endif;?>
	<?php foreach ($goodsfavorite as $row): ?>
	  <tr>
	    <td class="al"><?php echo $row->nameLinkHtml;?></td>
	    <td class="al"><?php echo $row->goods->shop->nameLinkHtml;?></td>
	    <td class="ac"><?php if($row->goods_price > 0) echo $row->goods_price;?></td>
	    <td class="ac"><?php echo $row->shortCreateDateTimeText;?></td>
	    <td class="ac"><a href="<?php echo url('my/favorite/delete', array('fid'=>$row->id,'type'=>'goods'));?>" onclick="javascript: return confirm('真要删除这条收藏吗？')">删除</a></td>
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