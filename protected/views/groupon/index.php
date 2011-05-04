<div class="main fl">
	
	<!-- 过滤条件 -->
	<?php $this->beginWidget('WmCornerBox', array(
		'htmlOptions' => array('class'=>'corner-gray-title ma-b10px'),
		'title'=>'同楼订餐',
	));?>
	<div style="padding: 5px 20px 0px">
    	<div class="cgray lh30px cred open-note ma-t10px f14px">同楼订餐说明</div>
    	<ul class="lh40px  cgray  none about pa-l30px">
            <li><span class="fb ma-r10px">同楼订餐：</span>是指同一座楼上的网友在规定的时间段内，共同在同一家商铺订餐。目前同楼订餐只支持中午订餐。</li>
            <li><span class="fb ma-r10px">达成条件：</span>同楼所有网友的订单总额达到商铺的规定的价格，就能享受同楼订餐服务。若未达到只能享受正常订餐服务。</li>
            <li><span class="fb ma-r10px">同楼订餐截止时间：</span>每天上午10：00，可以提前一天预订。10点以后下的订单，第二天中午才能收到。</li>
            <li><span class="fb ma-r10px">商铺送餐时间：</span>每天中午11：30-12：00。</li>
            <li><span class="fb ma-r10px">优点：</span>价格比正常订餐便宜，商铺送餐及时、准时。</li>
        </ul>
        
        <ul class="lh40px">
            <li>您目前的订餐地址是<span class="cred"><?php echo $currentLocation->name;?></span>，要想更换地址请点击<a href="javascript:void(0);" onclick="selectBuilding(setBuilding, <?php echo $this->city['id'];?>);" lang="">更改订餐楼宇地址</a></li>
        </ul>
	</div>
	<?php $this->endWidget();?>
	
	<!-- 商铺列表 -->
 	<div class="corner corner-gray-title shop-list">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content">
			<h3 class="f12px cgray fn lh20px bline">
				<span class="cred fl indent10px f14px">为您找到&nbsp;<span id="shop-nums"><?php echo count($shops);?></span>&nbsp;家<?php echo ShopCategory::getStoreName($_GET['cid']);?></span>
       			<span class="bg-pic sort-btn <?php echo $serviceSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('service_avg', '服务', array('class'=>'block lh24px'));?></span>
       			<span class="bg-pic sort-btn <?php echo $orderSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('order_nums', '人气', array('class'=>'block lh24px'));?></span>
	   			<span class="bg-pic sort-btn <?php echo $tasteSort;?> block fr ma-r5px indent10px"><?php echo $sort->link('taste_avg', '口味', array('class'=>'block lh24px'));?></span>
	   			<div class="clear"></div>
			</h3>
			<div class="wm-shop-list">
				<?php $this->renderPartial('/shop/shop_list', array('shops'=>$shops, 'groupon'=>true, 'building'=>$currentLocation));?>
			</div>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
</div>

<div class="sidebar fr">
	<?php $this->beginWidget('WmCornerBox', array(
		'htmlOptions' => array('class'=>'corner-gray-title ma-b10px'),
	    'title' => '订餐时间',
	));?>
    <p class=" ac ma-t10px cgray ">距离结束时间还有</p>
    <p class="clock ma-t10px">
    	<span id="hour" style=""><?php echo $remaintime['hours'];?></span>
        <span id="minute" style="margin-left:32px;"><?php echo $remaintime['minutes'];?></span>
        <span id="second" style="margin-left:25px;"><?php echo $remaintime['seconds'];?></span>
    </p>
	<?php $this->endWidget();?>
	
	<div class="corner corner-gray-title hot-goods ma-b10px">
  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
   		<div class="content lh24px">
			<h3 class="indent10px lh20px bline">
				<span class="fl f14px cred">热卖商品</span>
				<?php echo l('更多&gt;&gt;', url('goods/top', array('cid'=>$cid)), array('target'=>'_blank', 'class'=>'fr ma-r10px cgray f12px fn'))?>
			</h3>
			<ul class="ma-l5px lh20px ma-t10px">
			<?php foreach ((array)$goods as $v):?>
				<li class="ma-b5px">
					<div class="goods-name fl"><?php echo $v->getNameLinkHtml(12);?></div>
					<div class="star-small-gray fr ma-r5px ma-t5px"><div class="star-small-color" style="width:<?php echo $v->rateStarWidth;?>px"></div></div>
					<div class="wm-price fr ma-r10px"><?php echo $v->foodGoods->wmPrice;?>元</div>
					<div class="clr cgray"><?php echo $v->shop->getNameLinkHtml(24);?></div>
				</li>
			<?php endforeach;?>
			</ul>
  		</div>
  		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
 	</div>
</div>

<script type="text/javascript">
$(function(){
	setInterval(remainTimeClock, 1000);
	$(".open-note").toggle(function(){
		$(".about").show()
	  },
		function(){
		$(".about").hide()
	});
});
function remainTimeClock()
{
	var h = parseInt($('#hour').text());
	var m = parseInt($('#minute').text());
	var s = parseInt($('#second').text());
	var tmp = new Date(2010,1,1,h,m,s,0);
	var ms = tmp.getTime() - 1000;
	var d = new Date(ms);
	$('#hour').text(d.getHours());
	$('#minute').text(d.getMinutes());
	$('#second').text(d.getSeconds());

}
</script>

<script type="text/javascript">
function setBuilding(name, building_id, map_x, map_y)
{
	location.href = '<?php echo abu('at/setLocation');?>' + '?atid=' + building_id + '&referer=' + '<?php echo aurl('groupon');?>';
}
</script>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowBuildingDialog',
	'htmlOptions' => array('class'=>'nonoe'),
    'options'=>array(
        'title'=>'◎请选择小区/大厦',
        'autoOpen'=>$building ? false : true,
		'width' => 870,
		'height' => 500,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
		'beforeClose' => 'js:function(){$("select").css("visibility","inherit");}',
    ),
));
?>
<?php if ($offices):?>
<p class="cgray f12px">您可能使用的楼宇：</p>
<div class="lh24px cred ma-b10px">
<?php foreach ((array)$offices as $v):?>
<?php echo l($v->name, aurl('at/setLocation', array('atid'=>$v->id)) . '?referer=' . abu(request()->url), array('title'=>$v->name));?>&nbsp;&nbsp;
<?php endforeach;?>
</div>
<?php endif;?>

<div class="district">
	<a>行政区域</a>
	<div class="clear"></div>
</div>
<div class="search">
	<label>搜索小区/大厦：</label>
	<input type="text" id="building_key" class="txt" />
	<input type="button" id="building_button" value="搜索" />
	<label><?php echo l('如果没有找到我的小区/大厦？ 请点击这里', url('building/create'));?></label>
</div>
<div class="letter">
	<a>A</a><a>B</a><a>C</a><a>D</a><a>E</a><a>F</a><a>G</a><a>H</a><a>J</a><a>K</a><a>L</a><a>M</a><a>N</a><a>O</a><a>P</a><a>Q</a><a>R</a><a>S</a><a>T</a><a>W</a><a>X</a><a>Y</a><a>Z</a>
	<div class="clear"></div>
</div>
<div class="result">
	<div class="buildlist">
		<a><span>100家商家</span>小区/大厦</a>
		<div class="clear"></div>
	</div>
	<div class="pages"></div>
</div>
<div class="loading">正在加载中，请稍候...</div>

<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
cs()->registerCssFile(resBu("styles/my.css"), 'screen');
cs()->registerScriptFile(resBu('scripts/my.js'), CClientScript::POS_END);
?>

<script type="text/javascript">
$(function(){
	if (<?php echo (int)!$building;?>) selectBuilding(setBuilding, <?php echo $this->city['id'];?>);
});
</script>
