 <?php echo CHtml::beginForm(url('admin/searchlog/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">关键字：</td>
        <td width="400"><?php echo CHtml::textField('Search[keywords]', $search['keywords'], array('class'=>'txt')); ?></td>        
        <td width="120" class="ar">搜索时间：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Search[create_time_start]',
    'value' => $search['create_time_start']?$search['create_time_start']:date(param('formatDate'), strtotime('last Week')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt '),
));
?>-<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Search[create_time_end]',
    'value' => $search['create_time_end']?$search['create_time_end']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt '),
));
?></td>       
    </tr>
    <tr>  
        <td width="120" class="ar">按用户id：</td>
        <td><?php echo CHtml::textField('Search[user_id]', $search['user_id'], array('class'=>'txt')); ?></td> 
        <td width="120" class="ar">按用户ip：</td>
        <td><?php echo CHtml::textField('Search[create_ip]', $search['create_ip'], array('class'=>'txt')); ?></td>  
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>

<?php if ($searchs) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="120">关键字</th>
        <th class="al" width="120">用户名</th>
        <th class="al" width="100">用户ip</th>
        <th class="al" width="60">查询时间</th>
        <th class="al" width="60">城市</th>
    </tr>
<?php foreach ($searchs as $key=>$val) :?>
	<tr>
		<td><?php echo $val->keywords?></td>
		<td><?php echo $val->user->username?></td>
		<td><?php echo $val->create_ip?></td>
		<td><?php echo $val->shortCreateDateTimeText?></td>
		<td><?php echo $val->city->name?></td>
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
  <div>没有符合条件的查询结果</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>