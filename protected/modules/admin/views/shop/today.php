<?php if ($shop) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="150">店铺名称</th>
        <th class="al" width="120">分类-主营</th>
        <th class="al" width="100">联系电话</th>
        <th class="al" width="60">营业状态</th>
        <th class="al" width="60">订餐方式</th>
        <th class="al">详细地址</th>
        <th class="al" width="70">操作</th>
    </tr>
<?php foreach ($shop as $key=>$val) :?>
	<tr>
		<td><?php echo $val->getNameLinkHtml(0, '_blank')?></td>
		<td><?php echo $val->categoryText;?>-<?php foreach (CHtml::listData($val->tags, 'id', 'name') as $k=>$v) : echo $v.' '; endforeach;?></td>
		<td><?php echo $val->telphone?></td>
		<td><?php echo $val->businessStateText?></td>
		<td><?php echo $val->buyTypeText?></td>
		<td><?php echo $val->district->city->name . ' ' . $val->district->name . ' ' . $val->address?></td>
		<td><a href="<?php echo url('admin/shop/profile', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
		<a href="<?php echo url('admin/shop/info', array('id'=>$val->id))?>"><span class="color">查看</span></a></td>
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
  <div>7天内没有商铺加盟</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>