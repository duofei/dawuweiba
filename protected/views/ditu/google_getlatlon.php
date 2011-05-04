<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<style>
.district {height:20px;}
.district a {float:left; width:60px; height:17px; line-height:17px; font-weight:bold; text-align:center; cursor:pointer; display:block;}
.district a:hover{background:#EB5D03; color:#fff;}
.district a.selected {background:#EB5D03; color:#fff;}
</style>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<script type="text/javascript" src=" http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map_x = '<?php echo $get['map_x'] ? $get['map_x'] : $city->map_x;?>';
var map_y = '<?php echo $get['map_y'] ? $get['map_y'] : $city->map_y;?>';

function initialize() {
	var myLatlng = new google.maps.LatLng(map_y, map_x);
	var myOptions = {
		zoom: 13,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map"), myOptions);

	var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title:"我的位置",
        draggable: true
    });
	var openInfoHtml = "请用鼠标拖动到您的位置<br>并点击保存位置按钮<br><input type='button' value='保存位置' onclick='parent.<?php echo $get['callback'];?>(map_x,map_y)'>";
	var infowindow = new google.maps.InfoWindow({
		content: openInfoHtml
	});
	infowindow.open(map,marker);
	google.maps.event.addListener(marker, 'dragend', function(e) {
		var latLng = marker.getPosition();
		map_x = latLng.lng();
		map_y = latLng.lat();
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
			map_x = latlngCenter.lng();
			map_y = latlngCenter.lat();
			marker.setPosition(latlngCenter);
		}
	});
	google.maps.event.addListener(map, 'click', function(e) {
		map_x = e.latLng.lng();
		map_y = e.latLng.lat();
		marker.setPosition(e.latLng);
	});
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
}
</script>
</head>
<body style="margin:0px; padding:0px" onload="initialize()">
<div class="district">
	<?php foreach ($district as $d):?>
	<a mapx="<?php echo $d->map_x;?>" mapy="<?php echo $d->map_y;?>"><?php echo $d->name;?></a>
	<?php endforeach;?>
</div>
<div id="map" class="view" style="width:100%;height:480px;"></div>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCoreScript('jquery');
?>