<?php echo CHtml::form(aurl('bdapp/locationSearch'), 'get');?>
<div class="sousuo">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input01" value="请输入您所在的地址" />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索" class="chaxun" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<div class="redian c333">历时地点：<?php echo $history;?></div>
<!-- 搜索 end -->
<div style="width:493px; margin:0 auto;">
    <div>
        <div style="top:0px; margin-bottom:0px; height:39px; width:493px; overflow:hidden;">
        <ul class="sheqian">
            <li><span class="s01">热门搜索</span></li>
            <?php $i=1; foreach ($hot as $k=>$v): $i++;?>
            <li<?php echo $i==2 ? ' class="current"' : '';?>><a href="javascript:void(0);" class="district s0<?php echo $i;?>"><?php echo $k;?></a></li>
            <?php endforeach;?>
            <li><a class="s07"></a></li>
        </ul>
        </div>
    </div>
    <div class="index-location-list">
        <div class="list01">
        <?php reset($hot); $i=0; foreach ($hot as $k=>$v):?>
        	<ul district="<?php echo $k;?>" class="<?php echo $i ? 'hide' : '';?>">
        	<?php foreach ($v as $kk=>$vv):?>
            	<li><a href="<?php echo aurl('bdapp/shopSearch', array('locid'=>$kk));?>"><?php echo $vv;?></a></li>
            <?php endforeach;?>
                <div style="clear:both"></div>
            </ul>
        <?php $i++; endforeach;?>
        </div>
    </div>
    <div class="bottom-line"><img src="<?php echo resBu('baidu/img/pic12.png');?>" /></div>
</div>

<?php cs()->registerCoreScript('jquery');?>


<script type="text/javascript">
$(function(){
	$('.district').click(district_tab);
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
var district_tab = function(event) {
	$('.sheqian li').removeClass('current');
	$(this).parents('li').addClass('current');
	
	var district = $(this).text();
	$('ul:visible[district]').hide();
	$('ul[district=' + district + ']').show();
}
</script>