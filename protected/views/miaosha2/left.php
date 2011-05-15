<div class="cblack ma-l20px">您的位置：<?php echo l('首页', app()->homeUrl);?> > <?php echo l('一元秒杀', url('miaosha/index'));?></div>
<div class="today-shop"></div>
<!-- 商铺LOGO -->
<div class="shop-logo">
	<?php foreach ($todayShops as $shop):?>
	<div class="s-logo" title="<?php echo $shop->shop_name;?>"><img src="<?php echo $shop->logoUrl;?>" width="100" height="100" alt="<?php echo $shop->shop_name;?>" /></div>
	<?php endforeach;?>
	<div class="clear"></div>
</div>

<!-- 活动区域 -->
<div class="shop-area ac fb f16px cgray lh30px pa-t5px">活动区域</div>
<div class="shop-maps" id="maps"></div>
<?php $i=0;?>
<?php foreach ($todayShops as $shop):?>
<div class="shop-maps-color">
	<div class="fl" style="background:<?php echo $colors[$i];?>; width:50px; height:14px;"></div>
	<div class="fl ma-l5px"><?php echo $shop->shop_name;?></div>
	<div class="clear"></div>
</div>
<?php $i++;?>
<?php endforeach;?>

<!-- 关注新浪微博 -->
<div class="ac ma-t20px">
	<a href="http://weibo.com/my52wm" target="_blank"><?php echo CHtml::image(resBu('miaosha2/images/gz52wm_sina.gif'));?></a>
</div>

<!-- 意见反馈 -->
<div class="shop-box-top"></div>
<div class="shop-box">
	<div class="pa-t10px pa-l10px"><?php echo CHtml::image(resBu('miaosha2/images/yjfk.gif'));?></div>
	<div class="pa-l10px lh30px">请<?php echo l('点击这里', url('feedback/index'));?>提交意见反馈</div>
</div>
<div class="shop-box-bottom"></div>

<!-- 邮件订阅 -->
<!--
<div class="shop-box-top"></div>
<div class="shop-box">
	<div class="pa-t10px pa-l10px"><?php echo CHtml::image(resBu('miaosha2/images/smshhd.gif'));?></div>
	<div class="ma-t5px ma-b5px pa-l10px">
		<div class="fl"><input type="text" style="width:140px; height:20px; border:1px solid #b43700;" /></div>
		<div class="fl ma-l5px"><input type="image" src="<?php echo resBu('miaosha2/images/2_r13_c8.gif');?>" /></div>
		<div class="clear"></div>
	</div>
	<div class="pa-l10px lh20px pa-r10px">我们会通过邮件在第一时间通知您最新的活动(随时可以取消)。</div>
</div>
<div class="shop-box-bottom"></div>
-->

<!-- 讨论区-->
<!--
<div class="shop-box-top"></div>
<div class="shop-box">
	<div class="pa-t10px pa-l5px"><?php echo CHtml::image(resBu('miaosha2/images/taolq.gif'));?></div>
</div>
<div class="shop-box-bottom"></div>
-->

<script type="text/javascript">
function showMap() {
	var latlng = new google.maps.LatLng(<?php echo $center['lat'];?>, <?php echo $center['lng'];?>);
    var myOptions = {
        zoom: 13,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: false,
        streetViewControl: false
    };
    map = new google.maps.Map(document.getElementById('maps'), myOptions);
	<?php $key = 0;?>
	<?php foreach ($todayShops as $shop):?>
		var croods<?php echo $shop->id;?> = new Array();
    	<?php foreach ($shop->mapRegion as $k=>$r):?>
			croods<?php echo $shop->id;?>[<?php echo $k;?>] = new google.maps.LatLng(<?php echo $r[1];?>, <?php echo $r[0];?>);
    	<?php endforeach;?>
    	var polygon<?php echo $shop->id;?> = new google.maps.Polygon({
            paths: croods<?php echo $shop->id;?>,
            map: map,
            strokeColor: '<?php echo $colors[$key];?>',
            strokeOpacity: 0.1,
            strokeWeight: 1,
            fillColor: '<?php echo $colors[$key];?>',
            fillOpacity: 0.4
        });
    <?php $key++;?>
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
</script>