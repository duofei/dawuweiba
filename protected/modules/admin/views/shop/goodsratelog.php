<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th>商品名称</th>
        <th>用户名</th>
        <th width="55">评分值</th>
        <th>评论内容</th>
        <th width="120">评论时间</th>
    </tr>
	<?php foreach ($goodsrate as $k=>$v):?>
	<tr>
		<td><?php echo $v->goods->nameLinkHtml;?></td>
		<td><?php echo l($v->user->screenName, url('admin/user/info', array('id'=>$v->user_id)));?></td>
		<td class="ac"><div class="star-small-gray ma-t5px ma-r5px fl"><div class="star-small-color" style="width:<?php echo $v->rateStarWidth;?>px;"></div></div></td>
		<td><?php echo trim(h($v->content));?></td>
		<td><?php echo $v->shortCreateDateTimeText;?></td>
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