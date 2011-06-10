<?php echo CHtml::beginForm(url('super/cityadmin/addmanager', array('id'=>$city->id)), 'post');?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td class="ar" width="150">选择代理城市：</td>
        <td><?php echo CHtml::dropDownList('city_id', '', $city);?></td>
    </tr>
    <tr>
        <td class="ar">选择会员：</td>
        <td>
        	<span id="userId"><select name="user_id">
        		<option value="0">选择会员</option>
        	</select></span>
        	<input type="text" id="userName" class="txt" />
        	<input type="button" id="searchName" value="搜索" url="<?php echo url('super/cityadmin/searchuser');?>" />
        </td>
    </tr>
    <tr>
        <td class="ar">选择会员：</td>
        <td><?php echo CHtml::dropDownList('role', '', $roles);?></td>
    </tr>
    <tr>
    	<td class="ac" colspan="2">
    		<?php echo CHtml::submitButton('设为分站管理员');?>
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<script type="text/javascript">
$(function(){
	$('#searchName').click(function(){
		var url = $(this).attr('url');
		$.ajax({
			type: 'post',
			data: "name=" + $('#userName').val(),
			url: url,
			dataType: 'html',
			success: function(data){
				$('#userId').html(data);
			}
		});
	});
});
</script>