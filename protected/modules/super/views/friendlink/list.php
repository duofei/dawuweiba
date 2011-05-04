<?php echo CHtml::beginForm(url('super/friendlink/friend'),'get');?>
<div class="ma-t5px ma-b5px">
<?php echo CHtml::dropDownList("city_id", intval($_GET['city_id']), $city, array('empty'=>'全站'));?>
&nbsp;
<?php echo CHtml::textField('keyword', trim($_GET['keyword']), array('class'=>'txt', 'style'=>'height:20px'));?>
&nbsp;
<?php echo CHtml::submitButton('搜索');?>
</div>
<?php echo CHtml::endForm()?>
<?php echo CHtml::form(CHtml::normalizeUrl(url('super/friendlink/update')), 'post', array('name'=>'theform'));?>
<table border="0" cellspacing="1" cellpadding="0" width="100%" class="list-tbl">
	<tr>
		<th width="40">顺序</th>
		<th width="60">城市</th>
		<th width="150">网站名称</th>
		<th width="190">网址</th>
		<th width="190">Logo</th>
		<th>描述</th>
		<th width="50">状态</th>
		<th width="140">添加时间</th>
		<th width="40">操作</th>
	</tr>
<?php foreach ((array)$links as $l):?>
	<tr>
		<td><?php echo CHtml::textField("order_id[{$l->id}]", $l->order_id, array('class'=>'txt txt-order'));?></td>
		<td class="ac"><?php echo CHtml::dropDownList("city_id[{$l->id}]", $l->city_id, $city, array('empty'=>'全站'));?></td>
		<td><?php echo CHtml::textField("name[{$l->id}]", $l->name, array('class'=>'txt'));?></td>
		<td><?php echo CHtml::textField("homepage[{$l->id}]", $l->homepage, array('class'=>'txt txt-url'));?></td>
		<td><?php echo CHtml::textField("logo[{$l->id}]", $l->logo, array('class'=>'txt txt-url'));?></td>
		<td class="ac"><?php echo CHtml::textField("desc[{$l->id}]", $l->desc, array('class'=>'txt', 'style'=>'width:95%'));?></td>
		<td class="ac">
		<?php if($l->isvalid): ?>
			<span class="cgreen">已审核</span>
		<?php else:?>
			<span class="cgray">未审核</span>
		<?php endif;?>
		</td>
		<td title="<?php echo $l->create_ip;?>"><?php echo $l->createDateTimeText;?></td>
		<td class="ac">
			<?php if($l->isvalid): ?>
				<?php echo l('未审核', url('super/friendlink/valid', array('fid'=>$l->id)));?>
			<?php else:?>
				<?php echo l('审核', url('super/friendlink/valid', array('fid'=>$l->id)));?>
			<?php endif;?>
			<br />
			<a href="<?php echo url('super/friendlink/delete', array('fid'=>$l->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
</table>
<?php if (count($links) > 0):?>
<div class="btnblock">
<?php echo CHtml::submitButton('更新友情链接', array('class'=>'btn'));?>
</div>
<?php endif;?>
<?php echo CHtml::endForm();?>

<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	$('.list-tbl tr').mouseover(trMouseOver);
	$('.list-tbl tr').mouseout(trMouseOut);
});
/*]]>*/
</script>