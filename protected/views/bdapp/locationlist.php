<!-- 搜索 begin -->
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
<!-- 搜索 end -->
<div style="width:493px; margin:0 auto;">
    <div class="location-search"></div>
    <div class="index-location-list">
        <div class="list02">
        	<ul>
        	<?php foreach ((array)$data as $v):?>
                <?php if ($v['map']):?>
                <li><a href="<?php echo aurl('bdapp/shopsearch', array('lat'=>$v['map_x'], 'lon'=>$v['map_y']));?>" target="_self"><?php echo $v['name'];?></a><span><?php if ($v['address']) echo "&nbsp;&nbsp;地址：{$v['address']}";?></span></li>
                <?php else:?>
                <li><a href="<?php echo aurl('bdapp/shopsearch', array('locid'=>$v['id']));?>" target="_self"><?php echo $v['name'];?></a><span><?php if ($v['address']) echo "&nbsp;&nbsp;地址：{$v['address']}";?></span></li>
                <?php endif;?>
            <?php endforeach;?>
            </ul>
        </div>
        <!-- fanye begin -->
        <div class="megas512"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
        <!-- fanye end -->
    </div>
    <div class="bottom-line"><img src="<?php echo resBu('baidu/img/pic12.png');?>" /></div>
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