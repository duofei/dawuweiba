<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="150">商铺名称</th>
        <th class="al">商铺地址</th>
        <th class="al" width="120">用户邮箱</th>
        <th class="al" width="60">用户电话</th>
        <th class="al">留言内容</th>
        <th class="al">备注</th>
        <th class="al" width="80">操作</th>
    </tr>
<?php if ($suggest) :?>
<?php foreach ((array)$suggest as $s) :?>
	<tr>
		<td><?php echo $s->shop_name;?></td>
		<td><?php echo $s->shop_address;?></td>
		<td><?php echo $s->email;?></td>
		<td><?php echo $s->telphone;?></td>
		<td><?php echo $s->comment?></td>
		<td id="remark_<?php echo $s->id;?>"><?php echo $s->remark?></td>
		<td>
			<?php echo l('删除', url('admin/shop/shopsuggestdel', array('id'=>$s->id)));?>
			<?php echo l('修改备注', 'javascript:void(0)', array('class'=>'change-remark', 'id'=>$s->id));?>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr><td colspan="8">没有用户推荐商铺信息</td></tr>
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
<script type="text/javascript">
var remarkValue;
$(function(){
	$('.change-remark').click(function(){
		var id = $(this).attr('id');
		var remark = $('#remark_' + id);
		remarkValue = remark.text();
		if(!remarkValue) {
			remarkValue = remark.find('input').val();
		}
		var textfiled = '<input type="text" class="txt" value="'+ remarkValue +'" style="width:98%" id="textfield'+ id +'" onblur="saveRemark(' + id + ')">';
		remark.html(textfiled);
		remark.find('input').focus();
	});
});
function saveRemark(id) {
	var url = "<?php echo url('admin/shop/shopsuggestsave');?>";
	var value = $('#textfield'+id).val();
	$.ajax({
		url:url,
		type:'post',
		dataType:'html',
		data:'v=' + value + '&id=' + id,
		success: function(data){
			if(data == 1) {
				$('#remark_' + id).html(value);
			} else {
				$('#remark_' + id).html(remarkValue);
			}
		}
	});
}
</script>