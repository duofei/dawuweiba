<div style="background:url(<?php echo resBu('images/newindex/mainbg.gif');?>) repeat-x #d70a00">
<div class="main">
	<div align="center">
	<div id=Layer1 style="Z-INDEX: 1; RIGHT: 0px;  position:absolute;">
		<DIV id=flash1></DIV>
		<script type=text/javascript>
		var so = new SWFObject("<?php echo resBu('images/newindex/shujiao20110508.swf');?>", "mymovie", "250", "250", "7", "#336699");
		so.addParam("quality", "high");
		so.addParam("wmode", "transparent");
		so.addParam("menu", "false");
		so.addParam("scale", "noscale");
		so.addParam("flashVars", document.location.search.substr(1));
		so.write("flash1");
		</script>
	</div>
	</div>
	<div class="spaceline"></div>
	<div class="" id="meishi"><img src="<?php echo resBu('images/newindex/datu.jpg');?>" border="0" usemap="#Map" />
	    <map name="Map" id="Map">
	        <area shape="poly" coords="496,149,639,6,661,6,959,304,959,326,906,379,601,379,647,333,652,315,648,301,572,225"  href="javascript:void(0);" onmouseover="showDangao()" />
	        <area shape="poly" coords="410,302,524,302,524,287,566,320,524,351,524,337,410,337" href="javascript:void(0);" onclick="meishiMapSearch()" />
	    </map>
	</div>
	<div class="guan" id="dangao"><img src="<?php echo resBu('images/newindex/dangao.jpg');?>" border="0" usemap="#Map2" />
	    <map name="Map2" id="Map2">
	        <area shape="poly" coords="483,149,343,9,330,5,317,9,21,305,19,319,24,332,72,380,381,380,335,335,327,315,332,302,343,290" href="javascript:void(0);" onmouseover="showMeishi()" />
	        <area shape="poly" coords="569,302,569,337,455,337,455,350,413,320,454,288,454,302" href="javascript:void(0);" onclick="dangaoMapSearch()" />
	    </map>
	</div>
    <div><img src="<?php echo resBu('images/newindex/liucheng.gif');?>" /></div>
</div>
</div>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowCake',
    'options'=>array(
        'title'=>'◎很抱歉，蛋糕外卖还没有开通哦！',
        'autoOpen'=>false,
		'width' => 830,
		'height' => 500,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
		'dialogClass' => 'cake-bg'
    ),
));
?>
<div style="margin-top:140px; text-align:center;"><img src="<?php echo resBu('images/newindex/cake-sorry.jpg');?>" /></div>
<div style="margin-top:20px; color:#ffffff; height:45px; line-height:45px; font-size:22px; background:#e60000; text-align:center; font-family:'黑体', Arial;">7月份再来抢<span class="fb">好利来、Ali、稻香园</span>的折扣蛋糕吧。</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- 地图处理 -->

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在电子地图上查询您的位置',
        'autoOpen'=>false,
		'width' => 830,
		'height' => 540,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
		'dialogClass' => 'meishi-show'
    ),
));
?>
<iframe id="ShowMapIframe" src="about:black" width="840" height="498" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
function meishiMapSearch() {
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/search', array('cid'=>ShopCategory::CATEGORY_FOOD)); ?>');
	$("#ShowMap").dialog("open");
}
function dangaoMapSearch() {
	$("#ShowCake").dialog('open');
	//alert('很抱歉，蛋糕外卖还没开通哦。7月份再来抢好利来，Ali，稻香园的折扣蛋糕吧。');
}
function showMeishi() {
	$('#meishi').attr('class', '');
	$('#dangao').attr('class', 'guan');
}
function showDangao() {
	$('#meishi').attr('class', 'guan');
	$('#dangao').attr('class', '');
}
</script>
<?php
cs()->registerCssFile(resBu("styles/newmain.css"), 'screen');
cs()->registerScriptFile(resBu('scripts/swfobject.js'), CClientScript::POS_HEAD);
cs()->registerScriptFile(resBu('scripts/AC_RunActiveContent.js'), CClientScript::POS_HEAD);
?>