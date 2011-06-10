 <?php echo CHtml::beginForm(url('super/user/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo CHtml::textField('User[username]', $user['username'], array('class'=>'txt')); ?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">注册时间：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'User[create_time_start]',
    'value' => $user['create_time_start']?$user['create_time_start']:date(param('formatDate'), strtotime('last Week')),
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
    'name'=>'User[create_time_end]',
    'value' => $user['create_time_end']?$user['create_time_end']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">状态：</td>
        <td><?php echo CHtml::radioButtonList('User[state]', $user['state'], User::$states, array('separator'=>' '))?></td>     
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>
<?php if ($users) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="110">用户名</th>
        <th class="al" width="150">注册时间</th>
        <th class="al" width="150">最后登录时间</th>
        <th class="al">积分</th>
        <th class="al">信用</th>
        <th class="al">评价次数</th>
        <th class="al">白吃点</th>
        <th class="al">状态</th>
        <th class="al" width="220">操作</th>
    </tr>
<?php foreach ($users as $key=>$val) :?>
	<tr>
		<td><?php echo $val->username?></td>
		<td><?php echo $val->createTimeText?></td>
		<td><?php echo $val->lastLoginTimeText?></td>
		<td><?php echo $val->integral?></td>
		<td><?php echo $val->credit?></td>
		<td><?php echo $val->credit_nums?></td>
		<td><?php echo $val->bcnums?></td>
		<td><?php echo $val->stateText?></td>
		<td><a href="<?php echo url('super/user/profile', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
		<a href="<?php echo url('super/user/info', array('id'=>$val->id))?>"><span class="color">查看</span></a>
		<?php if ($val->state==1) :?>
		<a href="<?php echo url('super/user/state', array('id'=>$val->id, 'state'=>STATE_DISABLED))?>" onclick="return confirm('确定要禁用吗？');"><span class="color">禁用</span></a>
		<?php else:?>
		<a href="<?php echo url('super/user/state', array('id'=>$val->id, 'state'=>STATE_ENABLED))?>" onclick="return confirm('确定要启用吗？');"><span class="color">启用</span></a>
		<?php endif;?>
		<a href="<?php echo url('super/user/integral', array('id'=>$val->id))?>"><span class="color">积分管理</span></a>
		<a href="<?php echo url('super/user/setmanager', array('id'=>$val->id));?>">设为管理人员</a>
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
  <div>目前没有符合搜索条件的用户</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>