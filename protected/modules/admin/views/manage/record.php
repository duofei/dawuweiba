<?php echo CHtml::beginForm(url('admin/manage/record'), 'get');?>
<div class="ma-b5px lh30px">
	<div class="fl">管理人员：<?php echo CHtml::dropDownList('user_id', $_GET['user_id'], CHtml::listData($user, 'id', 'username'), array('empty'=>'选择管理人员'));?></div>
	<div class="fl ma-l10px">选楼时间：
	<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
	    'name'=>'begin_time',
	    'value' => $_GET['begin_time'],
	    'language' => 'zh',
	    'options'=>array(
			'dateFormat' => 'yy-mm-dd',
	        'showAnim'=>'fold',
	        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
	    ),
	    'htmlOptions' => array('class'=>'txt', 'style'=>'width:100px;height:20px', 'readOnly'=>true),
	));
	?> - <?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
	    'name'=>'end_time',
	    'value' => $_GET['end_time'],
	    'language' => 'zh',
	    'options'=>array(
			'dateFormat' => 'yy-mm-dd',
	        'showAnim'=>'fold',
	        'defaultDate' => date(param('formatDate')),
	    ),
	    'htmlOptions' => array('class'=>'txt', 'style'=>'width:100px;height:20px', 'readOnly'=>true),
	));
	?>
	</div>
	<div class="fl ma-l10px"><?php echo CHtml::submitButton('查询');?></div>
	<div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th width="100">管理人员名称</th>
        <th class="al">管理内容</th>
        <th width="150">管理时间</th>
        <th width="90">管理ip</th>
    </tr>
<?php if ($log):?>
<?php foreach ($log as $l) :?>
	<tr>
		<td class="ar"><?php echo $l->id;?>.</td>
		<td class="ac"><?php echo $l->user->username;?></td>
		<td><?php echo $l->content;?></td>
		<td class="ac"><?php echo $l->createDateTimeText;?></td>
		<td class="ac"><?php echo $l->create_ip;?></td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="5" class="ac">没有管理记录信息</td>
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