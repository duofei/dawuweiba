<?php echo CHtml::beginForm(url('admin/shopinside/create'),'post',array('name'=>'create'));?>
<table  class="list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
    	<th class="al" width="70">管理人员</th>
        <th class="al" width="">店铺名称</th>
        <th class="al" width="">起送条件</th>
        <th class="al" width="">送餐时间</th>
        <th width="100">配送范围</th>
        <th width="60">状态</th>
        <th width="130">添加时间</th>
        <th width="110">操作</th>
    </tr>
    <tr>
    	<td></td>
		<td class="ac"><?php echo CHtml::activeTextField($model, 'shop_name', array('class'=>'txt', 'style'=>'width:98%'));?></td>
		<td class="ac"><?php echo CHtml::activeTextField($model, 'transport_condition', array('class'=>'txt', 'style'=>'width:98%'));?></td>
		<td class="ac"><?php echo CHtml::activeTextField($model, 'transport_time', array('class'=>'txt', 'style'=>'width:98%'));?></td>
		<td class="ac">
			<?php echo l('设置范围', 'javascript:void(0);', array('id'=>'showMapRegion')); ?>
    		<?php echo CHtml::activeHiddenField($model, 'map_region', array('id'=>'region'));?>
		</td>
		<td class="ac"></td>
		<td class="ac"><?php echo date("Y-m-d H:i");?></td>
		<td class="ac">
			<?php echo CHtml::submitButton('保存');?>
		</td>
    </tr>
    <tr><td style="font-size:0px; height:1px; padding:0px" colspan="8"></td></tr>
<?php if ($shopinside) :?>
	<?php foreach ($shopinside as $key=>$val) :?>
	<tr>
		<td><?php echo $val->user->username;?></td>
		<td><?php echo $val->shop_name;?></td>
		<td><?php echo $val->transport_condition;?></td>
		<td><?php echo $val->transport_time;?></td>
		<td class="ac"><?php echo $val->map_region ? '<span class="cgreen">已设置</span>' : '<span class="color999">未设置</span>';?></td>
		<td class="ac"><?php echo $val->state==STATE_ENABLED ? '<span class="cgreen">已提交</span>' : '<span class="color999">未提交</span>';?></td>
		<td class="ac"><?php echo $val->shortCreateDateTimeText;?></td>
		<td class="ac">
			<?php if($val->state==STATE_DISABLED && $val->user_id==user()->id): ?>
			<a href="<?php echo url('admin/shopinside/create', array('id'=>$val->id))?>">编辑</a>
			<a href="<?php echo url('admin/shopinside/post', array('id'=>$val->id))?>">提交审核</a>
			<a href="<?php echo url('admin/shopinside/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach;?>
<?php endif;?>
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
<?php echo CHtml::endForm(); ?>


<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在电子地图上标注您的位置',
        'autoOpen'=>false,
		'width' => 820,
		'height' => 530,
		'modal' => true,
		'draggable' => true,
		'resizable' => false
    ),
));
?>
<iframe id="ShowMapIframe" src="#" width="100%" height="480" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">

function setRegion(json) {
	$("#ShowMap").dialog("close");
	$("#region").val(json);
}

$(function(){
	$("#showMapRegion").click(function(){
		$("#ShowMap").dialog("open");
		var region = $('#region').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion')); ?>' + '?region=' + region);
	});
});
</script>