<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<style>
.left{width:630px;}

.left .search{}
.search .search-box{background:url("<?php echo resBu('images/newindex/search_box.jpg');?>"); height:31px; width:430px; margin-left:10px;}
.search-box input{border:0px; height:24px; line-height:24px; width:410px; font-size:14px; margin-top:4px; margin-left:10px; color:#828282; background:url("<?php echo resBu('images/newindex/search_box_bg.jpg'); ?>") repeat-x 0 -4px;}
.search .button{background:url("<?php echo resBu('images/newindex/search_buttom.jpg');?>"); height:31px; width:78px;}
.search .select-city{background:url("<?php echo resBu('images/newindex/search_select_city.jpg');?>"); height:31px; width:95px; text-align:center; line-height:31px;}
.district {height:20px;}
.district a {float:left; width:60px; height:17px; line-height:17px; font-weight:bold; text-align:center; cursor:pointer; display:block;}
.district a:hover{background:#EB5D03; color:#fff;}
.district a.selected {background:#EB5D03; color:#fff;}
.error{background:url("<?php echo resBu('images/newindex/search-error.png');?>") no-repeat 0 5px; margin-left:105px; padding-left:25px; height:30px; line-height:30px; margin-top:5px; color:red;}
.left .history{margin-top:5px; margin-left:105px; height:30px; line-height:30px;}

#map {border:1px solid #CDCDCD;}
.right{width:160px;}
.right-top{background:url("<?php echo resBu('images/newindex/search_left_top_bg.jpg');?>"); height:9px; font-size:0px;}
.right-bottom{background:url("<?php echo resBu('images/newindex/search_left_bottom_bg.jpg');?>"); height:9px; font-size:0px;}

.right-up{height:28px; width:28px; background:url("<?php echo resBu('images/newindex/up_down_btn.jpg');?>") 0 0 no-repeat; float:left; margin-left:48px;}
.right-down{height:28px; width:28px; background:url("<?php echo resBu('images/newindex/up_down_btn.jpg');?>") 0 -30px no-repeat; float:left; margin-left:10px;}
.right-up-show{background-position: 0 -60px;}
.right-down-show{background-position: 0 -91px;}
div.right-up-show-select {background-postion: 0 -120px;}
div.right-down-show-select {background-postion: 0 -150px;}

.right-title{background:#E6E6E6; border-bottom:1px solid #A7A7A7}
.right-content{position:absolute; z-index:0;}
</style>
</head>
<body onload="initialize()">
<div>
	<div class="fl left">
		<div class="search">
		<?php echo CHtml::beginForm(url('ditu/search'), 'get', array('id'=>'postform'));?>
			<div class="fl select-city fb f14px cursor">济南市</div>
			<div class="fl search-box"><input type="text" name="kw" value="<?php echo $kw ? $kw : '输入地点，如数码港大厦';?>" id="searchBox" /></div>
			<div class="fl button fb f14px lh30px ac" onclick="postform()">查&nbsp;询</div>
			<div class="clear"></div>
		<?php echo CHtml::endForm();?>
		</div>
		<?php if($error):?>
		<div class="error"><?php echo $error;?></div>
		<?php else:?>
		<div class="history cgreen"> <span class="cblack">历史地点：</span> <?php $this->renderDynamic('getUserSearchLocationHistory');?></div>
		<?php endif;?>
		<div class="district ma-t5px">
			<?php foreach ($district as $d):?>
			<a mapx="<?php echo $d->map_x;?>" mapy="<?php echo $d->map_y;?>"><?php echo $d->name;?></a>
			<?php endforeach;?>
		</div>
		<div id="map" class="view" style="width:100%;height:400px;"></div>
	</div>
	<div class="fl right ma-l10px">
		<div class="right-top"></div>
		<div class="fb right-title f14px pa-l10px pa-b5px"><?php if($kw):?>地址列表<?php else:?>使用帮助<?php endif;?></div>
		<div style="height:425px; overflow-y:hidden; position:relative; background:#E6E6E6; border-top:1px solid #ffffff;" id="right_bContent">
			<div class="right-content">
			<?php if($kw):?>
				<?php if($data):?>
				<?php foreach ($data as $k=>$v):?>
				<div class="ma-t5px lh20px ma-l10px cursor address-list" lang="<?php echo $k;?>">
					<div class="fl ma-t5px"><?php echo CHtml::image(resBu('images/ditu/map_point_'.$k.'.png'), '', array('width'=>'12'));?></div>
					<div class="fl ma-l5px">
						<h4 title="<?php echo $v['name'];?>" class="f12px"><?php echo mb_substr($v['name'], 0, 10);?></h4>
						<div title="<?php echo $v['address'];?>"><?php echo mb_substr($v['address'], 0, 10);?></div>
					</div>
					<div class="clear"></div>
				</div>
				<?php endforeach;?>
				<?php else:?>
				<div class="ma-t10px lh24px ma-l10px">没有地址列表!</div>
				<?php endif;?>
			<?php else:?>
				<div class="ma-t5px lh24px ma-l10px ma-r10px">
					1. 在搜索框输入地址进行定位查找 <br />
					2. 也可以直接在电子地图上标注位置进行查找店铺
				</div>
			<?php endif;?>
			</div>
		</div>
		<div style="height:28px; background:#E6E6E6;">
		<?php if($kw):?>
			<div class="right-up cursor"></div>
			<div class="right-down cursor"></div>
		<?php endif;?>
		</div>
		<div class="right-bottom"></div>
	</div>
	<div class="clear"></div>
</div>
<?php if($miaosha):?>
<div class="none" id="openInfoHtml">
	<div style="width:300px; height:90px; line-height:20px; font-size:12px;">
		<div>如果这里是您的位置，请点击设置位置</div>
		<div>如果这里不是您的位置，请先移动标注标志位置</div>
		<div class="ac"><input type='button' value=' 设置为我的位置 ' onclick='gotoShopList()'></div>
	</div>
</div>
<?php else:?>
<div class="none" id="openInfoHtml">
	<div style="width:300px; height:50px; line-height:20px; font-size:12px;">
		<div>如果这里不是您的位置，请先移动标注标志位置</div>
		<div class="ac"><input type='button' value=' 搜索此位置的店铺 ' onclick='gotoShopList()'></div>
	</div>
</div>
<?php endif;?>
<script type="text/javascript" src=" http://ditu.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var shopUrl = '<?php echo aurl('shop/list', array('cid'=>$cid)); ?>';
var addusernumsUrl = '<?php echo aurl('at/addusenums'); ?>';
var map_x = <?php echo $this->city['map_x'];?>;
var map_y = <?php echo $this->city['map_y'];?>;
var map = null;
var select_address = null;
var address = new Array();
function initialize() {
	var myLatlng = new google.maps.LatLng(map_y, map_x);
	var myOptions = {
		zoom: 14,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map"), myOptions);

	// 我的位置
	var marker = new google.maps.Marker({
        position: new google.maps.LatLng(0, 0),
        map: map,
        icon: new google.maps.MarkerImage('<?php echo resBu("images/ditu/map_point_green.png");?>'),
        title:"我的位置",
        draggable: true
    });
	var openInfoHtml = $('#openInfoHtml').html();
	var infowindow = new google.maps.InfoWindow({
		content: openInfoHtml
	});
	//infowindow.open(map,marker);
	
	// 生成地址的点
	<?php foreach ((array)$data as $k=>$v):?>
	address[<?php echo $k;?>] = <?php echo json_encode($v);?>;
	<?php if($k==0):?>
	map_x = <?php echo $v['map_x']?>;
	map_y = <?php echo $v['map_y']?>;
	var myLatlng = new google.maps.LatLng(map_y, map_x);
	//map.panTo(myLatlng);
	map.setCenter(myLatlng);
	marker.setPosition(myLatlng);
	infowindow.open(map,marker);
	select_address = 0;
	<?php endif;?>
	var marker<?php echo $k;?> = new google.maps.Marker({
        position: new google.maps.LatLng(<?php echo $v['map_y']?>, <?php echo $v['map_x']?>),
        icon: new google.maps.MarkerImage('<?php echo resBu("images/ditu/map_point_" . $k . ".png");?>'),
        map: map,
        title:"<?php echo $v['name'];?>",
        draggable: false
    });
	// google地图事件
	google.maps.event.addListener(marker<?php echo $k;?>, 'click', function(e) {
		select_address = <?php echo $k;?>;
		map_x = <?php echo $v['map_x']?>;
		map_y = <?php echo $v['map_y']?>;
		myLatlng = new google.maps.LatLng(map_y, map_x);
		marker.setPosition(myLatlng);
		infowindow.open(map,marker);
	});
	<?php endforeach;?>
	
	// google地图事件
	google.maps.event.addListener(marker, 'click', function(){
		infowindow.open(map,marker);
	});
	google.maps.event.addListener(marker, 'dragend', function(e) {
		select_address = null;
		map_x = e.latLng.lng();
		map_y = e.latLng.lat();
		infowindow.open(map,marker);
	});
	google.maps.event.addListener(marker, 'dragstart', function() {
		infowindow.close();
	});
	google.maps.event.addListener(map, 'dragend', function(){
		var latlngCenter = map.getCenter();
		var newBounds = map.getBounds();
		var northEast = newBounds.getNorthEast();
		var southWest = newBounds.getSouthWest();
		var isChanger = false;
		if(map_x > northEast.lng() || map_x < southWest.lng()) {
			isChanger = true;
		}
		if(map_y > northEast.lat() || map_y < southWest.lat()) {
			isChanger = true;
		}
		if(isChanger) {
			select_address = null;
			map_x = latlngCenter.lng();
			map_y = latlngCenter.lat();
			marker.setPosition(latlngCenter);
		}
	});
	google.maps.event.addListener(map, 'click', function(e) {
		select_address = null;
		map_x = e.latLng.lng();
		map_y = e.latLng.lat();
		marker.setPosition(e.latLng);
		infowindow.open(map,marker);
	});

	// 行政区域
	$('.district a').click(function(){
		$('.district a').removeClass('selected');
		$(this).addClass('selected');
		var lng = $(this).attr('mapx');
		var lat = $(this).attr('mapy');
		map_x = lng;
		map_y = lat;
		var latlng = new google.maps.LatLng(lat,lng);
		map.setCenter(latlng);
		marker.setPosition(latlng);
	});

	// 地址点击处理
	$(".address-list").click(function(){
		var i = parseInt($(this).attr('lang'));
		select_address = i;
		map_x = address[i].map_x;
		map_y = address[i].map_y;
		myLatlng = new google.maps.LatLng(map_y, map_x);
		map.panTo(myLatlng);
		marker.setPosition(myLatlng);
		infowindow.open(map,marker);
	});
	
	// 搜索框处理
	$("#searchBox").focus(function(){
		$(this).addClass('cblack');
		if($(this).val() == '输入地点，如数码港大厦') {
			$(this).val('');
		}
	});
	$("#searchBox").blur(function(){
		$(this).removeClass('cblack');
		if($(this).val() == '') {
			$(this).val('输入地点，如数码港大厦');
		}
	});

	// 地址栏效果
	var aHeight = $('.right-content').height();
	var bHeight = $('#right_bContent').height();
	if(aHeight > bHeight) {
		$(".right-down").addClass('right-down-show');
	}
	$('.right-down-show').live('click', function(){
		var h = bHeight - aHeight;
		var top = parseInt($('.right-content').css("top"));
		if(!top) {top = 0;}
		if(h < top) {
			var m = top - 400;
			if(m < h) {
				m = h;
			}
			$('.right-content').animate({top: m}, 1000, function(){
				if(m==h) {$(".right-down").removeClass('right-down-show');}
				$(".right-up").removeClass('right-up-show');
				$(".right-up").addClass('right-up-show');
			});
		} else {
			$(".right-down").removeClass('right-down-show');
		}
	});
	$('.right-up-show').live('click', function(){
		var top = parseInt($('.right-content').css("top"));
		if (top < 0) {
			var m = top + 400;
			if(m > 0) {
				m = 0;
			}
			$('.right-content').animate({top: m}, 1000, function(){
				if(m==0) {$(".right-up").removeClass('right-up-show');}
				$(".right-down").removeClass('right-down-show');
				$(".right-down").addClass('right-down-show');
			});
		}
	});
}

//查看商铺列表
<?php if($miaosha):?>
function gotoShopList() {
	if(select_address === null) {
		top.closeLocationMap(shopUrl + '?lat=' + map_x + '&lon=' + map_y);
	} else {
		if(address[select_address].id) {
			// 已存在地址
			var url = addusernumsUrl + '?id=' + address[select_address].id;
			$.ajax({
				type: 'post',
				url: url,
				dataType: 'html',
				cache: false,
				success: function(data){
					top.closeLocationMap(shopUrl + '?atid=' + address[select_address].id);
				}
			});
		} else {
			// 不存在地址
			var addresss = address[select_address].address;
			var map_xs = address[select_address].map_x;
			var map_ys = address[select_address].map_y;
			var name = address[select_address].name;
			$.post('<?php echo url('at/postSearchLocation');?>', {address:addresss, map_x:map_xs, map_y:map_ys, name:name}, function(data){
				if(data) {
					top.closeLocationMap(data);
				}
			});
		}
	}
}

function showMiaoshaArea() {
	top.showMiaoshaArea();
}
<?php else:?>
function gotoShopList() {
	if(select_address === null) {
		<?php if($kw): ?>
		$.post('<?php echo url('at/postSearchLocation');?>', {address:'', map_x:map_x, map_y:map_y, name:'<?php echo $kw;?>'});
		<?php endif;?>
		top.location.href = shopUrl + '?lat=' + map_x + '&lon=' + map_y;
	} else {
		if(address[select_address].id) {
			// 已存在地址
			var url = addusernumsUrl + '?id=' + address[select_address].id;
			$.ajax({
				type: 'post',
				url: url,
				dataType: 'html',
				cache: false,
				success: function(data){}
			});
			top.location.href = shopUrl + '?atid=' + address[select_address].id;
		} else {
			// 不存在地址
			var addresss = address[select_address].address;
			var map_xs = address[select_address].map_x;
			var map_ys = address[select_address].map_y;
			var name = address[select_address].name;
			$.post('<?php echo url('at/postSearchLocation');?>', {address:addresss, map_x:map_xs, map_y:map_ys, name:name}, function(data){
				if(data) {
					top.location.href = data;
				}
			});
		}
	}
}
<?php endif;?>
function postform() {
	$('#postform').submit();
}

KISSY.ready(function(S) {
	var dataUrl = '<?php echo aurl('at/suggest');?>';
	var sug = new S.Suggest('#searchBox', dataUrl, {
		containerCls: 'suggest-container',
		//resultFormat: '',
		charset: 'utf-8',
		queryName: 'kw',
		callbackFn: 'get52WmKeyWords'
	});
	sug.on('dataReturn', function() {
		this.returnedData = this.returnedData || [];
	});
});
</script>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/kissy-min.js'), CClientScript::POS_HEAD);
cs()->registerScriptFile(resBu('scripts/suggest-pkg-min.js'), CClientScript::POS_END);
?>
