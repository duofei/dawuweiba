<?php echo CHtml::beginForm(url('admin/location/search'), 'get');?>
<div class="ma-b5px lh30px">
	<div class="fl">关键字：<?php echo CHtml::textField('k', trim($_GET['k']), array('class'=>'txt', 'style'=>'height:20px'));?></div>
	<div class="fl ma-l10px">地址：<?php echo CHtml::textField('address', trim($_GET['address']), array('class'=>'txt', 'style'=>'height:20px'))?></div>
	<div class="fl ma-l10px">标签：<?php echo CHtml::textField('category', trim($_GET['category']), array('class'=>'txt', 'style'=>'height:20px'))?></div>
	<div class="fl ma-l10px"><?php echo l('画地图范围', 'javascript:void(0);', array('id'=>'showMapRegion')); ?>：<?php echo CHtml::textField('region', trim($_GET['region']), array('readonly'=>true, 'class'=>'txt', 'style'=>'height:20px; width:200px; font-size:10px', 'id'=>'region'));?></div>
	<div class="fl ma-l10px"><?php echo CHtml::submitButton('查询');?></div>
	<div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<?php echo CHtml::beginForm(url('admin/location/delete'), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40"><?php echo CHtml::checkBox('checkAll', false, array('id'=>'checkAll'));?></th>
        <th class="al">地址名称</th>
        <th width="200">地图坐标</th>
        <th class="al">详细地址</th>
        <th width="60">来源</th>
        <th width="60">使用</th>
        <th width="60">状态</th>
        <th width="80">操作</th>
    </tr>
<?php if ($location):?>
<?php foreach ((array)$location as $b) :?>
	<tr>
		<td class="ac"><?php echo CHtml::checkBox('postid[]', false, array('value'=>$b->id)); ?></td>
		<td <?php echo $b->type==Location::TYPE_OFFICE ? 'class="cred"' : '';?>><?php echo $b->name;?></td>
		<td class="ac"><?php echo $b->map_x;?> , <?php echo $b->map_y;?></td>
		<td><?php echo $b->address;?></td>
		<td class="ac"><?php echo $b->sourceText;?></td>
		<td class="ac"><?php echo $b->use_nums;?></td>
		<td class="ac <?php echo $b->state ? 'cgreen' : 'cred';?>"><?php echo $b->stateText;?></td>
		<td class="ac">
			<a href="<?php echo url('admin/location/edit', array('id'=>$b->id))?>">修改</a>
		 	<a href="<?php echo url('admin/location/delete', array('id'=>$b->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="8" class="ac">没有地址信息</td>
	</tr>
<?php endif;?>
</table>
<div><?php echo CHtml::submitButton('批量删除', array('onclick'=>'return confirm("确定要删除吗？");'));?></div>
<?php echo CHtml::endForm();?>
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

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在地图上标注',
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
	$("#checkAll").click(function(){
		if($(this).attr('checked')) {
			$("input[type='checkbox']").attr('checked','true');
		} else {
			$("input[type='checkbox']").attr('checked','');
		}
	});
	
	var showDialog = function(){
		$("#ShowMap").dialog("open");
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion', 'region'=>trim($_GET['region']))); ?>');
	}
	
	$("#showMapRegion").click(showDialog);
	$("#region").click(showDialog);
	
});
</script>