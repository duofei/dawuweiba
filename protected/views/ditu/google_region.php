<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>选择送餐范围</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map = null;
var croods = new Array();
var markers = new Array();
var buildingMarkers = new Array();
var polygon;
var count = 0;
var buildingCount = 0;
var infoWin;
var changeMapZoom = true;
function initMap()
{
    var latlng = new google.maps.LatLng(<?php echo $center['map_y'];?>, <?php echo $center['map_x'];?>);
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById('gmap'), myOptions);
    var regions = <?php echo $regions;?>;
    initPolygon(regions);
	google.maps.event.addListener(map, 'rightclick', function(event){
		drawMarker(count, event.latLng);
		count++;
	});
	google.maps.event.addListener(map, 'dragend', function(){
		showBuilding();
	});
	google.maps.event.addListener(map, 'dragstart', function(){
		changeMapZoom = false;
	});
	google.maps.event.addListener(map, 'tilesloaded', function(){
		resetMapZoom();
	});
}

function resetMapZoom() {
	var maxMin = <?php echo $maxMin; ?>;
	if(maxMin) {
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
			if(zoom < 14)
				changeMapZoom = false;
		}
	}
}

function drawMarker(count, latlng)
{
	var marker = new google.maps.Marker({
        position: latlng,
        map: map,
		draggable: true,
		title: count.toString()
    });
	croods[count] = latlng;
	drawPolygon(croods);
	markers[count] = marker;
	
	google.maps.event.addListener(marker, 'dragend', function(event){
		var key = this.getTitle();
		croods[key] = event.latLng;
		drawPolygon(croods);
	});
}

function initPolygon(regions)
{
	for (var i in regions) {
		croods[count] = new google.maps.LatLng(regions[i][1], regions[i][0]);
		drawMarker(count, croods[count]);
		count++;
	}
	drawPolygon(croods);
}

function drawPolygon(croods)
{
	if (polygon) {
		polygon.setMap(null);
	}
    polygon = new google.maps.Polygon({
        paths: croods,
        map: map,
        strokeColor: '#0000FF',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#0000FF',
        fillOpacity: 0.35
    });
	google.maps.event.addListener(polygon, 'click', function(event){
		var html = $('#submitPoints').html();
		infoWin = null;
	    infoWin = new google.maps.InfoWindow({
			content: html,
			disableAutoPan: true,
			position:event.latLng
		});
		infoWin.open(map);
	});
}
function clearOverlay()
{
	polygon.setMap(null);
    polygon = null;
    for (var i in markers) markers[i].setMap(null);
    markers.length = 0;
    croods.length = 0;
    count = 0;
    infoWin.close();
}
function submitPoints()
{
	//var points = (croods.join('|')).replace(/[\(\)\s]*/g,'');
	var temp = new Array();
	for (var i in croods) {
		temp[i] = '' + croods[i].lng() + ',' + croods[i].lat();
	}
	var points = temp.join('|');
	parent.<?php echo $callback;?>(points);
}

function showBuilding()
{
	if($('#showBuilding').attr('checked')) {
		var bounds = map.getBounds();
		var northEast = bounds.getNorthEast();
		var southWest = bounds.getSouthWest();
		$.get('<?php echo url('ditu/getBuilding');?>', 'minx=' + southWest.lng() + '&miny=' + southWest.lat() + '&maxx=' + northEast.lng() + '&maxy=' + northEast.lat(), function(data){
			if(data && data.length > 0) {
				clearBuilding();
				for(i=0; i<data.length; i++) {
					if(data[i].id) {
						
						buildingMarkers[buildingCount] = new google.maps.Marker({
					        position: new google.maps.LatLng(data[i].map_y, data[i].map_x),
					        map: map,
					        //icon: http://www.baidu.com/icon.jpg,
							title: data[i].name
					    });
						
						buildingCount++;
					}
				}
			}
		}, 'json');
	}
}
function clearBuilding()
{
	for (var i in buildingMarkers) buildingMarkers[i].setMap(null);
	buildingMarkers.length = 0;
	buildingCount = 0;
}
$(function(){
	$('#defaultSet a').click(function(){
		for (var i in markers) markers[i].setMap(null);
		markers.length = 0;
	    croods.length = 0;
	    count = 0;
		markers.length = 0;
		var region = $(this).attr('region');
		var temp_region = region.split('|');
		var temp;
		var arr = new Array();
		for(var i=0; i<temp_region.length; i++) {
			if(temp_region[i]) {
				temp = temp_region[i].split(',');
				arr.push(new google.maps.LatLng(temp[1], temp[0]));
			}
			drawMarker(count, new google.maps.LatLng(temp[1], temp[0]));
			count++;
		}
	});
	$('#showBuilding').click(function(){
		if($(this).attr('checked')) {
			showBuilding();
		} else {
			clearBuilding();
		}
	});
	$('#drawAreaId').click(function(){
		var region = $("#regionId").val();
		var url = '<?php echo aurl("/ditu/octagon", array('x'=>$shopLocation['map_x'], 'y'=>$shopLocation['map_y'])); ?>';
		$.ajax({
			type: 'get',
			url: url,
			data: 'region=' + region,
			dataType: 'json',
			success: function(data){
				if(data.length > 0) {
					for (var i in markers) markers[i].setMap(null);
					markers.length = 0;
				    croods.length = 0;
				    count = 0;
					markers.length = 0;
					initPolygon(data);
				}
			}
		});
	});
});
</script>
</head>
<body onload="initMap();">
<div class="f12px">
	<div id="gmap" class="view fl" style="width:90%;height:480px;"></div>
	<div style="width:9%;" class="fr lh24px" id="defaultSet">
		<div><input type="checkbox" id="showBuilding" /> 显示楼宇</div>
		<div class="lh24px tline bline fb">常用区域</div>
		<?php foreach ($mapregion as $m):?>
		<div><a href="javascript:void(0);" region="<?php echo $m->region?>"><?php echo $m->name?></a></div>
		<?php endforeach;?>
		<div class="lh24px tline bline fb ma-t30px">区域范围</div>
		<div class="ma-t5px"><?php echo CHtml::textField('region', '', array('class'=>'txt', 'style'=>'width:40px', 'id'=>'regionId'));?>米</div>
		<div><?php echo CHtml::button('画出范围', array('id'=>'drawAreaId'));?></div>
	</div>
	<div class="clear"></div>
	<div id="submitPoints">
    	<input type="button" id="submit" value="提交送餐范围" onclick="submitPoints();" />
    	<input type="button" id="clear" value="清除送餐范围" onclick="clearOverlay();" />
    </div>
</div>
</body>
</html>
<?php
cs()->registerCoreScript('jquery');
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
?>
