 <?php if ($shop) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="150">店铺名称</th>
        <th class="al" width="185">店主-身份证</th>
        <th class="al" width="120">分类-主营</th>
        <th class="al" width="*">详细地址</th>
        <th class="al" width="120">联系电话</th>
        <th class="al" width="60">订购方式</th>
        <th class="al" width="120">申请时间</th>
        <th class="al" width="140">操作</th>
    </tr>
<?php foreach ($shop as $key=>$val) :?>
	<tr>
		<td>
		    <?php echo l($val->shop_name, url('shop/show', array('shopid'=>$val->id)), array('target'=>'_blank'));?>
		    <?php if ($val->yewuyuan) echo '(' . l($val->yewuyuan->username, url('admin/user/info', array('id'=>$val->yewuyuan->id)), array('target'=>'_blank')) . ')';?>
		</td>
		<td><?php echo $val->owner_name . '-' . $val->owner_card;?></td>
		<td><?php echo $val->categoryText;?>-<?php foreach (CHtml::listData($val->tags, 'id', 'name') as $k=>$v) : echo $v.' '; endforeach;?></td>
		<td><?php echo $val->district->city->name . ' ' . $val->district->name . ' ' . $val->address?></td>
		<td><?php echo $val->telphone?></td>
		<td><?php echo $val->buyTypeText?></td>
		<td><?php echo $val->shortCreateDateTimeText?></td>
		<td>
		<a href="<?php echo url('admin/shop/state', array('id'=>$val->id, 'state'=>Shop::STATE_VERIFY));?>" onclick="return confirm('确定要通过吗？');"><span class="color">通过</span></a>
		<a href="<?php echo url('admin/shop/state', array('id'=>$val->id, 'state'=>Shop::STATE_PSEUDO));?>" onclick="return confirm('确定要打回吗？');"><span class="color">打回</span></a>
		<a href="<?php echo url('admin/shop/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
		<a href="<?php echo url('admin/shop/setSession', array('id'=>$val->id));?>" target="_blank">管理商铺</a></a>
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
  <div>目前没有待审商铺</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>