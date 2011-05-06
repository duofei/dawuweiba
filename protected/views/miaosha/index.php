<div class="main">
	<div id="tbody">
	    <img src="<?php echo resBu('miaosha/images/goleft.gif');?>" width="19" height="32" id="goleft" />
	    <img src="<?php echo resBu('miaosha/images/goright.gif');?>" width="19" height="32" id="goright" />
	    <div id="photos">
	    	<?php $temp = array(); $isComCount = 0;?>
	    	<?php foreach ($miaosha as $m):?>
	<?php if(!$temp[date("Y-m-d",$m->active_time)] || !in_array($m->shop_id, $temp[date("Y-m-d",$m->active_time)])):?>
	    	<?php if ($m->active_time > mktime(0,0,0,date('m'),date('d'),date('Y'))):?>
	    	<div class="xq<?php echo date('w',$m->active_time);?>">
	    	<?php else:?>
	    	<div class="xq8">
	    	<?php $isComCount++;?>
	    	<?php endif;?>
	        	<div class="riqi"><?php echo date('m月d日',$m->active_time);?></div>
	            <div class="zhouqi"><?php echo $m->activeTimeWeek;?></div>
	            <div class="xqtu"><?php echo $m->shop->logoHtml;?></div>
	     	</div>
	<?php $temp[date("Y-m-d",$m->active_time)][] = $m->shop_id;?>
	<?php endif;?>
	     	<?php endforeach;?>
	    </div>
	</div>
	<div><img src="<?php echo resBu('miaosha/images/titjr.jpg');?>" /></div>
    <div class="main02">
    	<div class="fl" style="width:397px; overflow:hidden">
        	<div class="column01">
            	<div class="tit f14px"><strong>商家列表</strong></div>
                <div class="cont">
                	<?php foreach ($miaoshalist as $m):?>
                	<div class="box">
                    	<div style="padding-top:5px;border-bottom:1px dashed #b7cbda; padding-bottom:5px;">
                        	<div class="fl"><img src="<?php echo $m->shop->logoUrl;?>" /></div>
                            <div class="fr lh20" style="width:290px;">
                            	<h1 class="cd60a01 f14px"><?php echo l($m->shop->shop_name, url('shop/show', array('shopid'=>$m->shop->id)))?></h1>
                                <p>送餐时间：<?php echo $m->shop->transport_time;?> </p>
                                <p>起送条件：<?php echo $m->shop->transport_condition;?></p>
                                <p>店铺简介：<?php echo $m->shop->desc;?> </p>
                            </div>
                            <div class=" clear"></div>
                        </div>
                    </div>
                    <div class="spaceline"></div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="spaceline"></div>
            <div class="column01">
            	<div class="tit f14px"><strong>送餐范围</strong><a name="AREA"></a></div>
                <div class="cont">
                   <div style="border:1px solid #cccccc; width:380px; height:200px; margin:10px auto; overflow:hidden;" id="gmap"></div>
                   <div>
                   	<div class="fl lh20">图例：</div>
                   	<?php foreach ($miaoshalist as $key=>$m):?>
                   	<div class="ditu-tuli" style="background:<?php echo $colors[$key];?>;"></div>
                   	<div class="fl lh20"><?php echo $m->shop->shop_name;?></div>
                   	<?php endforeach;?>
                   </div>
                </div>
            </div>
        </div>
        <div class="fr" style="width:405px;">
        	<div class="biao"><h1 id="time">秒杀倒计时</h1></div>
            <div class="column02" style="position:absolute;">
            	<div class="notice"><img src="<?php echo resBu('miaosha/images/notice.gif');?>" /></div>
            	<div class="tit"></div>
                <div class="cont">
                <?php echo CHtml::beginForm(url('miaosha/post'), 'post', array('id'=>'postform'));?>
                	<?php foreach ($miaoshalist as $m):?>
                	<div class="cdtit"><?php echo l($m->shop->shop_name, url('shop/show', array('shopid'=>$m->shop->id)));?><?php if($shopInArea[$m->shop->id]=='disabled') echo '(不在配送范围之内)'?></div>
                    <div class="list02">
                    	<ul>
                    		<?php foreach ($m->miaoshaGoods as $g):?>
                        	<li><input type="radio" name="goodsid" value="<?php echo $g->goods_id;?>" mid="<?php echo $g->miaosha_id;?>" <?php echo $shopInArea[$m->shop->id];?> /> <?php echo $g->goods->name;?>(原价<?php echo $g->goods->wmPrice;?>元)</li>
                        	<?php endforeach;?>
                            <div class="clear"></div>
                        </ul>
                    </div>
                    <div class="spaceline"></div>
                    <?php endforeach;?>
                    <?php echo CHtml::hiddenField('miaoshaid', 0);?>
                    <?php if($error_flash):?>
                    <div class="error"><?php echo $error_flash;?></div>
                    <?php endif;?>
                    <div align="center"><a class="queding-wait" id="submit">确定</a></div>
                    <div class="spaceline"></div>
                <?php echo CHtml::endForm();?>
                </div>
                <div class="bot"></div>
                <?php if($error):?>
                <div class="shade">
                	<div class="shade-top"></div>
                	<div class="shade-content"></div>
                	<div class="shade-bottom"></div>
                	<div class="box">
                		<?php if(user()->isGuest):?>
                		<div class="f26px fb ac ma-t20px">您还未登陆</div>
						<div class="f26px fb ac ma-t5px">请先<?php echo l('登陆', url('site/login', array('referer'=>aurl('miaosha/index'))), array('class'=>'cred'));?>或<?php echo l('马上注册', url('site/signup'), array('class'=>'cred'));?></div>
                		<?php elseif($lastLatLng[1]):?>
                		<div class="f26px ac ma-t20px" style="font-family:'雅黑', '黑体'; font-weight:100;"><span class="cred">您的位置</span>不在活动范围之内</div>
                		<div class="box-btn" onclick="showLocationMap()">重新设置位置</div>
                		<div class="box-btn" onclick="javascript:location.href='<?php echo url('shop/list');?>'">查看其它店铺</div>
                		<div class="clear"></div>
                		<?php else:?>
                		<div class="f26px fb ac ma-t20px" style="font-family:'雅黑', '黑体'; font-weight:100;">请先确定你的位置</div>
                		<div class="box-btn" onclick="showLocationMap()">设置您的位置</div>
                		<div class="box-btn" onclick="showMiaoshaArea()">查看活动范围</div>
                		<div class="clear"></div>
                		<?php endif;?>
                	</div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="spaceline"></div>
        <div align="center"><a href="<?php echo url('my/default/inviteurl')?>" target="_blank"><img src="<?php echo resBu('miaosha/images/pic03.jpg');?>" /></a></div>
        <div class="spaceline"></div>
        <div class="clear"></div>
    </div>
    <div><img src="<?php echo resBu('miaosha/images/bg04.jpg');?>" /></div>
</div>

<!-- 地图位置处理 -->
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
    ),
));
?>
<iframe id="ShowMapIframe" src="<?php echo aurl('ditu/search', array('other'=>'miaosha'));?>" width="100%" height="495" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<!--main end-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script language="javascript" type="text/javascript">
var activeTime = <?php echo $miaoshalist[0]->active_time - time();?>;
//activeTime = -50;
var interval;
var map = null;
$(function(){
	interval = setInterval("showStartTime()", 1000);
	$("input[type='radio']").click(function(){
		var mid = $(this).attr('mid');
		$('#miaoshaid').val(mid);
	});
	var length = $("#photos").children().size() - 1;
	var showi = 0;
	$("#goleft").hide();
	$("#goright").click(function(){
		$("#photos > div").first().appendTo("#photos");
		$("#goleft").show();
		showi++;
		if(showi>=(length-5)) {
			$("#goright").hide();
		}
	});
	$("#goleft").click(function(){
		$("#photos > div").last().prependTo("#photos");
		$("#goright").show();
		showi--;
		if(showi<=0) {
			$("#goleft").hide();
		}
	});
	$('#submit').click(function(){
		if($(this).attr('class') == 'queding') {
			$('#postform').submit();
		}
	});
	

	var isComCount = <?php echo $isComCount;?>;
	var moveNum = 0;
	if(isComCount > 0) {
		if((length-6) > isComCount) {
			moveNum = isComCount;
		} else {
			moveNum = length-6;
		}
		for(var i=0; i<=moveNum; i++) {
			$("#goright").click();
		}
	}
	
	showMap();
	
	$(".box-btn").mouseover(function(){
		$(this).addClass('select');
	});
	$(".box-btn").mouseout(function(){
		$(this).removeClass('select');
	});
	<?php if($error):?>
	$(".shade-content").height($(".column02").height()-24);
	$(".box").css('top',($(".column02").height()-111)/2);
	<?php else:?>
	$(".notice").show();
	$(".notice").click(function(){ $(this).hide(); });
	<?php endif;?>
	$("input[type='radio']").click(function(){
		$(".notice").hide();
	});
});
function showStartTime(){
	if(activeTime < 0) {
		$('#submit').attr('class', 'queding');
		$('#time').html('00:00:00');
		clearInterval(interval);
		setTimeout("location.reload();", (60-Math.abs(activeTime)) * 1000);
	} else {
		var html = '';
		var s = activeTime%60;
		var m = parseInt(activeTime/60)%60;
		var h = parseInt(activeTime/3600);
		if(s < 10) s = '0'+s;
		if(m < 10) m = '0'+m;
		if(h < 10) h = '0'+h;
		html = h + ':' + m + ':' + s + '';
		$('#time').html(html);
		activeTime--;
	}
}
function showMap() {
	var latlng = new google.maps.LatLng(<?php echo $center['lat'];?>, <?php echo $center['lng'];?>);
    var myOptions = {
        zoom: 13,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: false
    };
    map = new google.maps.Map(document.getElementById('gmap'), myOptions);

	<?php foreach ($miaoshalist as $key=>$m):?>
		var croods<?php echo $m->shop_id;?> = new Array();
    	<?php foreach ($m->shop->maxMapRegion as $k=>$r):?>
			croods<?php echo $m->shop_id;?>[<?php echo $k;?>] = new google.maps.LatLng(<?php echo $r[1];?>, <?php echo $r[0];?>);
    	<?php endforeach;?>
    	var polygon<?php echo $m->shop_id;?> = new google.maps.Polygon({
            paths: croods<?php echo $m->shop_id;?>,
            map: map,
            strokeColor: '<?php echo $colors[$key];?>',
            strokeOpacity: 0.1,
            strokeWeight: 1,
            fillColor: '<?php echo $colors[$key];?>',
            fillOpacity: 0.4
        });
    <?php endforeach;?>

    <?php if($lastLatLng[1]):?>
    var lastLatLng = new google.maps.LatLng(<?php echo $lastLatLng[1];?>, <?php echo $lastLatLng[0];?>);
    var marker = new google.maps.Marker({
        position: lastLatLng,
        map: map,
		draggable: false,
		title: '我的位置'
    });
    <?php endif;?>
}

function closeLocationMap(url) {
	$("#ShowMapIframe").attr('src', url);
	$("#ShowMap").dialog("close");
	setTimeout("location.reload()", 1000);
}
function showLocationMap() {
	$("#ShowMap").dialog("open");
}
function showMiaoshaArea() {
	var url = location.href.replace(/#AREA/, '');
	location.href = url + '#AREA';
}
</script>