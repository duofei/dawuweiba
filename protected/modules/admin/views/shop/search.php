 <?php echo CHtml::beginForm(url('admin/shop/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="150" class="ar">店铺名称：</td>
        <td width="400"><?php echo CHtml::textField('Shop[shop_name]', $shop['shop_name'], array('class'=>'txt')); ?></td>
        <td width="120" class="ar">店铺分类：</td>
        <td><?php echo CHtml::radioButtonList('Shop[category_id]', $shop['category_id'], ShopCategory::$categorys, array('separator'=>''))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">营业状态：</td>
        <td><?php echo CHtml::radioButtonList('Shop[business_state]', $shop['business_state'], Shop::$business_states, array('separator'=>''))?></td>
        <td width="120" class="ar">订餐方式：</td>
        <td><?php echo CHtml::radioButtonList('Shop[buy_type]', $shop['buy_type'], Shop::$buytype, array('separator'=>''))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">商铺状态：</td>
        <td><?php echo CHtml::radioButtonList('Shop[state]', $shop['state'], Shop::$states, array('separator'=>''))?></td>
        <td width="120" class="ar">业务员：</td>
        <td><?php echo CHtml::dropDownList('Shop[yewu_id]', $shop['yewu_id'], $yewu, array('empty'=>'业务员选择'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">营业执照审核：</td>
        <td><?php echo CHtml::radioButtonList('Shop[is_commercial_approve]', $shop['is_commercial_approve'], Shop::$approve, array('separator'=>''))?></td>
        <td width="120" class="ar">卫生许可证审核：</td>
        <td><?php echo CHtml::radioButtonList('Shop[is_sanitary_approve]', $shop['is_sanitary_approve'], Shop::$approve, array('separator'=>''))?></td>
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>

<?php if ($shops) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="120">店铺名称</th>
        <th class="al" width="50">打印机</th>
        <th class="al" width="120">分类-主营</th>
        <th class="al" width="100">联系电话</th>
        <th width="40">营业</th>
        <th width="80">订餐方式</th>
        <th class="al" width="*">详细地址</th>
        <th width="50">状态</th>
        <th class="al" width="120">操作</th>
    </tr>
<?php foreach ($shops as $key=>$val) :?>
	<tr>
		<td>
		<?php echo $val->getNameLinkHtml(0, '_blank')?>
		<?php if ($val->yewuyuan) echo '(' . l($val->yewuyuan->username, url('admin/user/info', array('id'=>$val->yewuyuan->id)), array('target'=>'_blank')) . ')';?>
		</td>
		<td><?php echo $val->printer->code;?></td>
		<td><?php echo $val->categoryText?>-<?php foreach (CHtml::listData($val->tags, 'id', 'name') as $k=>$v) : echo $v.' '; endforeach;?></td>
		<td><?php echo $val->telphone?></td>
		<td><?php echo $val->businessStateText?></td>
		<td class="ac"><?php echo $val->buyTypeText?></td>
		<td><?php echo $val->district->city->name . ' ' . $val->district->name . ' ' . $val->address?></td>
		<td class="ac"><?php echo $val->stateText;?></td>
		<td class="ac">
			<a href="<?php echo url('admin/shop/profile', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
			<a href="<?php echo url('admin/shop/info', array('id'=>$val->id))?>"><span class="color">查看</span></a>
			<a href="<?php echo url('admin/shop/setSession', array('id'=>$val->id));?>" target="_blank">管理商铺</a>
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
  <div>没有符合条件的商铺</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>