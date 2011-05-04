<p class="f14px lh24px pa-l20px">选择已经保存的收货地址</p>
<ul class="f14px lh24px address-list pa-l20px">
<?php foreach ((array)$address as $v):?>
	<li>
		<input type="radio" name="address-item" value="<?php echo $v->id;?>" id="address<?php echo $v->id;?>" <?php if($v->id == $checkid) {echo 'checked';}?> />&nbsp;<label for="address<?php echo $v->id;?>"><?php echo $v->address;?></label>
		<input type="hidden" name="consignee" value="<?php echo $v->consignee;?>" />
		<input type="hidden" name="address" value="<?php echo $v->address;?>" />
		<input type="hidden" name="telphone" value="<?php echo $v->telphone;?>" />
		<input type="hidden" name="mobile" value="<?php echo $v->mobile;?>" />
		<input type="hidden" name="aid" value="<?php echo $v->id;?>" />
		<input type="hidden" name="city_id" value="<?php echo $v->city_id;?>" />
		<br /><a href="javascript:void(0)" class="edit-address">[编辑这个地址]</a>
	</li>
<?php endforeach;?>
	<li class="ma-t10px">或者 <a href="javascript:void(0)" class="new-address">使用新地址</a></li>
</ul>
<script type="text/javascript">
$(function(){
	$(':radio[name=address-item]').click(selectUserAddress);
	$('a.edit-address').click(selectUserAddress);
	$('a.new-address').click(selectUserAddress);
});
</script>