<!-- 搜索 begin -->
<?php echo CHtml::form(aurl('bdapp/goodsSearch'), 'get');?>
<div class="sousuo2">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input02" value="<?php echo $_GET['kw'];?>" />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索美食" class="chaxun2" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<!-- 搜索 end -->

<div class="no-location">
	<h1 class="f14px lh30px cc61819">没有找到与“<?php echo $_GET['kw'];?>”相关的美食</h1>
	<p class="f12px lh20px">建议您适当删减或更改搜索关键词”</p>
</div>