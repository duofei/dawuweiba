 <?php if ($list): echo CHtml::beginForm(url('admin/tuannav/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">添加时间：</td>
        <td width="400"><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Tuannav[create_time_start]',
    'value' => $tuannav_get['create_time_start']?$tuannav_get['create_time_start']:date(param('formatDate'), strtotime('last Week')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?>-<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Tuannav[create_time_end]',
    'value' => $tuannav_get['create_time_end']?$tuannav_get['create_time_end']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?></td>
<td><?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?></td>
    </tr>
</table>

 <?php echo CHtml::endForm(); endif;?>

<?php if ($tuannav) :?>
<table  class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="60">分类</th>
        <th class="al">标题</th>
        <th class="al" width="60">来源</th>
        <th class="al" width="60">团购价</th>
        <th class="al" width="60">折扣</th>
        <th class="al" width="60">原价</th>
        <th class="al" width="100">截至日期</th>
        <th class="al" width="40">购买</th>
        <th class="al" width="150">添加时间</th>
        <th class="al" width="150">操作</th>
    </tr>
<?php foreach ($tuannav as $key=>$val) :?>
	<tr>
		<td><?php echo $val->category->name?></td>
		<td class="<?php echo $val->state ? 'cgreen' : 'cred';?>"><a href="<?php echo $val->absoluteUrl?>" target="_blank" title="<?php echo $val->title;?>"><?php echo $val->titleSub?></a></td>
		<td><?php echo $val->tuandata->name?></td>
		<td><?php echo $val->group_price?></td>
		<td><?php echo $val->discount==0 ? '无折扣' : $val->discount;?></td>
		<td><?php echo $val->original_price?></td>
		<td><?php echo $val->effectiveTime?></td>
		<td><?php echo $val->buy_num?></td>
		<td><?php echo $val->createTimeText?></td>
		<td><a href="<?php echo url('admin/tuannav/edit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
		<a href="<?php echo url('admin/tuannav/info', array('id'=>$val->id))?>"><span class="color">查看</span></a>
		<a href="<?php echo url('admin/tuannav/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
		<a href="<?php echo url('admin/tuannav/comment', array('id'=>$val->id))?>"><span class="color">管理评论</span></a>
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
  <div>目前没有团购</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>