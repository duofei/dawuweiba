<?php echo CHtml::form(aurl('bdapp/locationSearch'), 'get');?>
<div class="sousuo">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input01" value="请输入您所在的地址" />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索" class="chaxun" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<div class="redian c333">历时地点： <?php echo $history;?></div>

<div class="no-location">
	<h1 class="f14px lh30px cc61819">报抱歉，你选择的地点周边没有可以外送的餐馆。</h1>
</div>

<?php cs()->registerCoreScript('jquery');?>

<script type="text/javascript">
$(function(){
	$(':text[name=kw]').focus(kwfocus);
	$(':text[name=kw]').blur(kwblur);
});

var kwfocus = function(event) {
	if ($(this).val() == '请输入您所在的地址')
		$(this).val('');
}

var kwblur = function(event) {
	if ($(this).val() == '')
		$(this).val('请输入您所在的地址');
}
</script>