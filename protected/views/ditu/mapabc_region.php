<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
</head>
<body style="margin:0px; padding:0px" onload="bodyOnload()">
<div class="f12px">
	<div id="map" class="view fl" style="width:90%;height:480px;"></div>
	<div style="width:9%;" class="fr lh24px" id="defaultSet">
		<div><input type="checkbox" id="showBuilding" /> 显示楼宇</div>
		<div class="lh24px tline bline fb">常用区域</div>
		<?php foreach ($mapregion as $m):?>
		<div><a href="javascript:void(0);" region="<?php echo $m->region?>"><?php echo $m->name?></a></div>
		<?php endforeach;?>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript" src="http://app.mapabc.com/apis?&t=flashmap&v=2.3.4&key=<?php echo param('mapABCKey');?>"></script>
<script type="text/javascript">
var mapObj;
function bodyOnload() {
    var mapoption = new MMapOptions();
    mapoption.toolbar = ROUND; //设置地图初始化工具条，ROUND:新版圆工具条
    mapoption.overviewMap = HIDE; //设置鹰眼地图的状态，SHOW:显示，HIDE:隐藏（默认）
    mapoption.scale = SHOW; //设置地图初始化比例尺状态，SHOW:显示（默认），HIDE:隐藏。
    mapoption.zoom = 13;//要加载的地图的缩放级别
//    mapoption.center = new MLngLat(117.009115,36.680328);//要加载的地图的中心点经纬度坐标
    mapoption.language = MAP_CN;//设置地图类型，MAP_CN:中文地图（默认），MAP_EN:英文地图
    mapoption.fullScreenButton = SHOW;//设置是否显示全屏按钮，SHOW:显示（默认），HIDE:隐藏
    mapoption.centerCross = SHOW;//设置是否在地图上显示中心十字,SHOW:显示（默认），HIDE:隐藏
    mapoption.toolbarPos=new MPoint(20,20); //设置工具条在地图上的显示位置
    mapObj = new MMap("map", mapoption); //地图初始化
    mapObj.addEventListener(mapObj,DRAG_END,showBuilding);
    mapObj.addEventListener(mapObj,ZOOM_END,showBuilding);
    addMPolygon(true);
}

function addMPolygon(original) {
//	mapObj.removeAllOverlays();	//删除地图对象上所有覆盖物
	var arr = new Array(); 		//由多边形顶点组成的经纬度数组
	if(original) {
	<?php if($regions) :
	foreach (json_decode($regions) as $key=>$val) :?>
		arr.push(new MLngLat("<?php echo $val[0]?>","<?php echo $val[1]?>"));
	<?php endforeach;
	else:?>
		arr.push(new MLngLat("117.009094","36.684062"));
		arr.push(new MLngLat("117.005382","36.678435"));
		arr.push(new MLngLat("117.012892","36.678521"));
	<?php endif;?>
	} else{
		arr = region_arr;
	}

	var tipOption = new MTipOptions();
	tipOption.title="搜索的范围";
	tipOption.content="请用鼠标拖动到您的范围并点击保存范围按钮<input type='button' value='保存范围' onclick='submitPoints()'> <input type='button' onclick='javascript:addMPolygon(\"original\");' value='还原多边形'/>";//信息窗口内容
	tipOption.tipType=HTML_BUBBLE_TIP;
	tipOption.hasShadow=true;
	var areopt = new MAreaOptions();
	areopt.tipOption=tipOption;
	areopt.canShowTip = true;
	areopt.isEditable=true;
	polygonAPI = new MPolygon(arr,areopt);
	polygonAPI.id="polygon101";
	mapObj.addOverlay(polygonAPI,true);
}

function addMarker(lat, lon, name, id){
	var tipOption = new MTipOptions();
	tipOption.title = '楼宇名称';
	tipOption.content = name;

	tipOption.tipType=HTML_BUBBLE_TIP;
	tipOption.tipWidth = 200;

	var markerOption=new MMarkerOptions();
	markerOption.imageUrl="http://api.mapabc.com/flashmap/2.0/marker.png";
	markerOption.imageAlign = BOTTOM_CENTER;
	markerOption.tipOption = tipOption;
    markerOption.canShowTip = true;
    markerOption.isBounce = true;
    markerOption.isEditable = false;
    markerOption.picAgent = false;

    Marker = new MMarker(new MLngLat(lat,lon),markerOption);
    Marker.id="MyMarkId" + id;
    mapObj.addOverlay(Marker);
}

function showBuilding()
{
	if($('#showBuilding').attr('checked')) {
		var bounds = mapObj.getLngLatBounds();
		$.get('<?php echo url('ditu/getBuilding');?>', 'minx=' + bounds.southWest.lngX + '&miny=' + bounds.southWest.latY + '&maxx=' + bounds.northEast.lngX + '&maxy=' + bounds.northEast.latY, function(data){
			if(data && data.length > 0) {
				for(i=0; i<data.length; i++) {
					if(data[i].id) {
						if(!mapObj.getOverlayById("MyMarkId" + data[i].id)){
							addMarker(data[i].map_x,data[i].map_y, data[i].name, data[i].id);
						}
					}
				}
			}
		}, 'json');
	}
}

function setPolygonArr() {
    var object=mapObj.getOverlayById("polygon101");
    var arr = new Array();
    for (var i=0;i<object.lnglatArr.length ;i++ ) {
		arr.push(new MLngLat(object.lnglatArr[i].lngX,object.lnglatArr[i].latY));
    }
    region_arr = arr;
}

function submitPoints()
{
	var coordinates = mapObj.expOverlay("polygon101").coordinates;
	var temp = new Array();
	for (var i in coordinates) {
		temp[i] = '' + coordinates[i].x + ',' + coordinates[i].y;
	}
	var points = temp.join('|');
	parent.<?php echo $callback;?>(points);
}

$(function(){
	$('#showBuilding').click(function(){
		if($(this).attr('checked')) {
			showBuilding();
		} else {
			setPolygonArr();
			mapObj.removeAllOverlays();
			addMPolygon(false);
		}
	});
	$('#defaultSet a').click(function(){
		var region = $(this).attr('region');
		var temp_region = region.split('|');
		var temp;
		var arr = new Array();
		for(var i=0; i<temp_region.length; i++) {
			if(temp_region[i]) {
				temp = temp_region[i].split(',');
				arr.push(new MLngLat(temp[0], temp[1]));
			}
		}
		region_arr = arr;
		mapObj.removeOverlayById('polygon101');
		addMPolygon(false);
	});
});
</script>
</body>
<?php
cs()->registerCoreScript('jquery');
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
?>
</html>