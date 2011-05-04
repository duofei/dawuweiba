<?php echo CHtml::form(aurl('bdapp/locationSearch'), 'get');?>
<div class="sousuo">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input01" value="<?php echo $_GET['kw'];?>" />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索" class="chaxun" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<div class="redian c333">历时地点： <?php echo $history;?></div>

<div class="no-location">
	<h1 class="f14px lh30px cc61819">没有找到与“<?php echo $_GET['kw'];?>”相关的地址</h1>
	<p class="f12px lh20px">建议您适当删减或更改搜索关键词，或者输入门牌号例如“山大路47号”</p>
</div>