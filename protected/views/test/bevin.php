<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>选择送餐范围</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map = null;
var croods = new Array();
var croods2 = new Array();
var markers = new Array();
var buildingMarkers = new Array();
var polygon;
var polygon2;
var count = 0;
var buildingCount = 0;
var infoWin;
var changeMapZoom = true;
function initMap()
{
    var latlng = new google.maps.LatLng(36.68498551897609,117.0300566828613);
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById('gmap'), myOptions);
    var regions = <?php echo $regions;?>;
    initPolygon(regions);
    var regions2 = <?php echo $regions2;?>;
    initPolygon2(regions2);

	var marker = new google.maps.Marker({
        position: new google.maps.LatLng(36.68849578901217,117.0300566828613),
        map: map,
		draggable: true,
		title: '济南东站'
    });
}





function initPolygon(regions)
{
	count = 0;
	for (var i in regions) {
		croods[count] = new google.maps.LatLng(regions[i][1], regions[i][0]);
		count++;
	}
	drawPolygon(croods);
}

function drawPolygon(croods)
{
	//if (polygon) {
		//polygon.setMap(null);
	//}
	
	var color = '#0000ee';

    polygon = new google.maps.Polygon({
        paths: croods,
        map: map,
        strokeColor: '#0000FF',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: color,
        fillOpacity: 0.35
    });
}

function initPolygon2(regions)
{
	count = 0;
	for (var i in regions) {
		croods2[count] = new google.maps.LatLng(regions[i][1], regions[i][0]);
		count++;
	}
	drawPolygon2(croods2);
}

function drawPolygon2(croods)
{
	//if (polygon2) {
		//polygon2.setMap(null);
	//}
	
	var color = '#00eeee';

    polygon2 = new google.maps.Polygon({
        paths: croods,
        map: map,
        strokeColor: '#0000FF',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: color,
        fillOpacity: 0.35
    });
}
</script>
</head>
<body onload="initMap();">
<div class="f12px">
	<div id="gmap" class="view fl" style="width:90%;height:480px;"></div>
</div>
</body>
</html>
<?php
cs()->registerCoreScript('jquery');
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
?>
