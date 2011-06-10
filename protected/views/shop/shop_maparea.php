<div style="width:98%; margin:10px auto;">
	<div>
		<span class="block fl" style="width:50px; height:14px; font-size:0px; background-color:#FAA75E"></span>
		<span class="block fl ma-l10px">本月之前已有服务范围</span>
		<div class="clear"></div>
	</div>
	<div class="ma-t5px">
		<span class="block fl" style="width:50px; height:14px; font-size:0px; background-color:#F7D7A8"></span>
		<span class="block fl ma-l10px">本月新增</span>
		<div class="clear"></div>
	</div>
</div>
<div id="gmaps" style="height:600px; width:98%; margin:0px auto;"></div>
<script type="text/javascript" src="http://ditu.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
$(function(){
	initMap();
});
var map = null;
var croods = new Array();
var polygon;
var infoWin;
var changeMapZoom = true;
function initMap()
{
    var latlng = new google.maps.LatLng(<?php echo $center['map_y'];?>, <?php echo $center['map_x'];?>);
    var myOptions = {
        zoom: 11,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById('gmaps'), myOptions);
    
	var imageBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(<?php echo $maxMin['min']['y'];?>, <?php echo $maxMin['min']['x'];?>),
		new google.maps.LatLng(<?php echo $maxMin['max']['y'];?>, <?php echo $maxMin['max']['x'];?>)
	);

	var newmap = new google.maps.GroundOverlay("<?php echo sbu('ditu/ditu_now.png') . '?t=' . time();?>", imageBounds);
	newmap.setMap(map);
	var oldmap = new google.maps.GroundOverlay("<?php echo sbu('ditu/ditu_monthago.png') . '?t=' . time();?>", imageBounds);
	oldmap.setMap(map);

	google.maps.event.addListener(map, 'dragstart', function(){
		changeMapZoom = false;
	});
//	google.maps.event.addListener(map, 'tilesloaded', function(){
//		resetMapZoom();
//	});
//	setTimeout("setChangeMapZoom(false)", 1500);
}

function setChangeMapZoom(state) {
	changeMapZoom = false;
}

function resetMapZoom() {
	var maxMin = <?php echo json_encode($maxMin); ?>;
	var newBounds = map.getBounds();
	var northEast = newBounds.getNorthEast();
	var southWest = newBounds.getSouthWest();
	var isChanger = false;
	if(maxMin.max.x > northEast.lng() || maxMin.min.x < southWest.lng()) {
		isChanger = true;
	}
	if(maxMin.max.y > northEast.lat() || maxMin.min.y < southWest.lat()) {
		isChanger = true;
	}
	if(isChanger && changeMapZoom) {
		var zoom = map.getZoom();
		map.setZoom(zoom-1);
		if(zoom < 10)
			changeMapZoom = false;
	}
}
</script>