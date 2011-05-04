<?php echo CHtml::beginForm(url('admin/building/search'), 'get');?>
<!--
<div class="ma-b5px lh30px">
	<div class="fl ma-l10px"><?php echo CHtml::submitButton('查询');?></div>
	<div class="clear"></div>
</div>
 -->
<?php echo CHtml::endForm();?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">商铺名称</th>
        <th>地址</th>
        <th>联系电话</th>
        <th width="50">起送价</th>
        <th>配送时间</th>
        <th>起送条件</th>
        <th width="50">配送价</th>
        <th width="40">操作</th>
    </tr>
<?php if ($shops):?>
<?php foreach ((array)$shops as $b) :?>
	<tr>
		<td><?php echo $b->shop_name;?></td>
		<td><?php echo $b->address;?></td>
		<td class="ac"><?php echo $b->telphone;?></td>
		<td><?php echo $b->transport_amount + 0;?>元</td>
		<td class="ac"><?php echo $b->transport_time;?></td>
		<td><?php echo $b->transport_condition;?></td>
		<td><?php echo $b->dispatching_amount + 0;?>元</td>
		<td class="ac">
		 	<a href="<?php echo url('admin/caiji/delete', array('id'=>$b->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="8" class="ac">没有采集数据</td>
	</tr>
<?php endif;?>
</table>
<div class="pages ar">
<?php $this->widget('CLinkPager', array(
	'pages' => $pages,
    'header' => '',
    'firstPageLabel' => '首页',
    'lastPageLabel' => '末页',
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
));?>
</div>