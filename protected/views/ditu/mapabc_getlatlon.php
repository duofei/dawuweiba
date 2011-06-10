<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
</head>
<body style="margin:0px; padding:0px" onload="bodyOnload()">
<div id="map" class="view" style="width:100%;height:480px;"></div>
</body>
</html>
<script type="text/javascript" src="http://app.mapabc.com/apis?&t=flashmap&v=2.3.4&key=<?php echo param('mapABCKey');?>"></script>
<script type="text/javascript">
var map_x, map_y;

function bodyOnload() {
	var tipOption = new MTipOptions();
	tipOption.title="我的位置";
	tipOption.content="请用鼠标拖动到您的位置并点击保存位置按钮<input type='button' value='保存位置' onclick='parent.<?php echo $get['callback'];?>(map_x,map_y)'>";
	
	tipOption.tipType=HTML_BUBBLE_TIP;
	tipOption.tipWidth=200;

	var markerOption=new MMarkerOptions();
	markerOption.imageUrl="http://api.mapabc.com/flashmap/2.0/marker.png";
	markerOption.imageAlign = BOTTOM_CENTER;
	markerOption.tipOption = tipOption;
    markerOption.canShowTip = true;
    markerOption.isBounce = true;
    markerOption.isEditable = true;
    markerOption.picAgent = false;
	
	/* 鼠标点击事件 */
	var setClickMarker = function(param){
		map_x = param.eventX;
		map_y = param.eventY;
		mapObj.clearMap();
		mapObj.removeAllOverlays();

	    var Marker = new MMarker(new MLngLat(param.eventX,param.eventY),markerOption);
	    Marker.id="MyMarkId";
	    mapObj.addOverlay(Marker);
	}

	/* 打开提示层 */
	var addOverlayEvent = function(param){
	    mapObj.openOverlayTip(param.overlayId);
	}

	/* 设置移动完成事件 */
	var setMoveMarker = function(param) {
		var Marker = mapObj.getOverlayById("MyMarkId");
		// 判断是否移动了标注点
		if(map_x == Marker.lnglat.lngX && map_y == Marker.lnglat.latY) {
			var bounds=mapObj.getLngLatBounds(); // 获取西南和东北角点的经纬度坐标
			if(Marker.lnglat.lngX < bounds.southWest.lngX ||Marker.lnglat.lngX > bounds.northEast.lngX || Marker.lnglat.latY < bounds.southWest.latY || Marker.lnglat.latY > bounds.northEast.latY) {
				// 如果标注点被移出了当前地图范围，自动定位到当前地图的中心点
				var center=mapObj.getCenter();
			    map_x = center.lngX;
			    map_y = center.latY;
				var Marker = new MMarker(new MLngLat(map_x,map_y),markerOption);
			    Marker.id="MyMarkId";
			    mapObj.addOverlay(Marker);
			}
		} else {
			map_x = Marker.lnglat.lngX;
			map_y = Marker.lnglat.latY;
			mapObj.openOverlayTip("MyMarkId");
		}
	}
	
	var mapOptions = new MMapOptions(); //设置地图初始化参数对象
	mapOptions.zoom = 14;
	mapOptions.center = new MLngLat(<?php echo $get['map_x'] ? $get['map_x'] : $city->map_x;?>, <?php echo $get['map_y'] ? $get['map_y'] : $city->map_y;?>);
	mapOptions.toolbar = ROUND; //工具条
	mapOptions.centerCross = SHOW;
	var mapObj = new MMap("map", mapOptions); //创建地图对象
	mapObj.addEventListener(mapObj,MOUSE_CLICK,setClickMarker); //鼠标点击事件
	mapObj.addEventListener(mapObj,ADD_OVERLAY,addOverlayEvent);
	mapObj.addEventListener(mapObj,DRAG_END,setMoveMarker);

	/* 取的地图中心点坐标 */
	var center=mapObj.getCenter();
    map_x = center.lngX;
    map_y = center.latY;

	/* 显示标注点 */
	var Marker = new MMarker(new MLngLat(map_x, map_y), markerOption);
    Marker.id = "MyMarkId";
    mapObj.addOverlay(Marker);
}
</script>

