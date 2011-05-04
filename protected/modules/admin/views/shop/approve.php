<?php if ($shop) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="150">店铺名称</th>
        <th class="al" width="60">店铺类型</th>
        <th class="al" width="100">联系电话</th>
        <th class="al" width="60">订餐方式</th>
        <th class="al">详细地址</th>
        <th class="al" width="120">营业执照</th>
        <th class="al" width="135">卫生许可证</th>
    </tr>
<?php foreach ($shop as $key=>$val) :?>
	<tr>
		<td><?php echo $val->getNameLinkHtml(0, '_blank')?></td>
		<td><?php echo $val->categoryText?></td>
		<td><?php echo $val->telphone?></td>
		<td><?php echo $val->buyTypeText?></td>
		<td><?php echo $val->district->city->name . ' ' . $val->district->name . ' ' . $val->address?></td>
		<td>
		    <?php if ($val->commercial_instrument) :?><a href="<?php echo sbu($val->commercial_instrument)?>" target="_blank">查看</a><?php endif;?>
		    <?php if ($val->is_commercial_approve == '1') : echo '已通过'; else : if ($val->commercial_instrument) :?><a href="<?php echo url('admin/shop/approve', array('id'=>$val->id, 'is'=>'1', 'type'=>'commercial'))?>" onclick="return confirm('确定要通过吗？');"><span class="color">通过</span></a>|<a href="<?php echo url('admin/shop/approve', array('id'=>$val->id, 'is'=>'0', 'type'=>'commercial'))?>" onclick="return confirm('确定要拒绝吗？');"><span class="color">拒绝</span></a><?php endif;endif;?>
		</td>
		<td>
		    <?php if ($val->sanitary_license) :?><a href="<?php echo sbu($val->sanitary_license)?>" target="_blank">查看</a><?php endif;?>
		    <?php if ($val->is_sanitary_approve == '1') : echo '已通过'; else : if ($val->sanitary_license) :?><a href="<?php echo url('admin/shop/approve', array('id'=>$val->id, 'is'=>'1', 'type'=>'sanitary'))?>" onclick="return confirm('确定要通过吗？');"><span class="color">通过</span></a>|<a href="<?php echo url('admin/shop/approve', array('id'=>$val->id, 'is'=>'0', 'type'=>'sanitary'))?>" onclick="return confirm('确定要拒绝吗？');"><span class="color">拒绝</span></a><?php endif;endif;?>
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