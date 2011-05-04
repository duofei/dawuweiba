<div id="searchResultBegin" class="ma-t10px none fb f14px" style="color:#0370DA">以下是来自地图搜索的结果</div>
<ul class="search-location-list ma-t10px" id="searchMapResult"></ul>
<div id="loading" class="ma-t10px">
	<div class="fl ma-l10px"><img src="<?php echo resBu('images/loading2.gif')?>" /></div>
	<div class="fl fb f14px ma-l5px ma-t5px" style="color:#0370DA">正在加载来自地图上的搜索。。。</div>
	<div class="clear"></div>
</div>
<div id="mapsearch" class="none" style="width:1px;height:1px;"></div>
<script type="text/javascript" src="http://app.mapabc.com/apis?&t=flashmap&v=2.3.4&key=<?php echo param('mapABCKey');?>"></script>
<script type="text/javascript">
var keyArray = new Array();
var nodata = true;
<?php
if($data) {
	$str = 'keyArray = [';
	$dot = '';
	foreach ($data as $d) {
		$str .= $dot."'".$d->name."'";
		$dot = ',';
	}
	$str .= '];';
	echo $str;
	echo "nodata = false;\n";
}
?>
var pageNum = 1;
var resultHtml = '';
var lnglat = new MLngLat(<?php echo $this->city['map_x'];?>,<?php echo $this->city['map_y'];?>);
$(function(){
	var mapoption = new MMapOptions();
    mapoption.toolbar = ROUND; //设置地图初始化工具条，ROUND:新版圆工具条
    mapoption.toolbarPos=new MPoint(20,20); //设置工具条在地图上的显示位置
    mapoption.overviewMap = SHOW; //设置鹰眼地图的状态，SHOW:显示，HIDE:隐藏（默认）
    mapoption.scale = SHOW; //设置地图初始化比例尺状态，SHOW:显示（默认），HIDE:隐藏。
    mapoption.zoom = 15;//要加载的地图的缩放级别
    mapoption.center = lnglat;//要加载的地图的中心点经纬度坐标
    mapoption.language = MAP_CN;//设置地图类型，MAP_CN:中文地图（默认），MAP_EN:英文地图
    mapoption.fullScreenButton = SHOW;//设置是否显示全屏按钮，SHOW:显示（默认），HIDE:隐藏
    mapoption.centerCross = SHOW;//设置是否在地图上显示中心十字,SHOW:显示（默认），HIDE:隐藏
    mapoption.mapComButton = 0;//设置地图右上角的组件是否显示，SHOW:显示（默认），HIDE:隐藏。
    mapoption.requestNum=100;//设置地图切片请求并发数。默认100。
    mapObj = new MMap("mapsearch", mapoption); //地图初始化
    keywordSearch();
    $('#searchMapResult a').live('click',function(){
		var address = $(this).attr('address');
		var map_x = $(this).attr('map_x');
		var map_y = $(this).attr('map_y');
		var name = $(this).html();
		$.post('<?php echo url('at/postSearchLocation');?>', {address:address, map_x:map_x, map_y:map_y, name:name}, function(data){
			if(data) {
				location.href = data;
			}
		});
    });
});

function keywordSearch() {
	$('#loading').show();
	var keywords = '<?php echo $kw;?>';	//关键字
    var city = '<?php echo $this->city['name'];?>';	//城市
  	var MSearch = new MPoiSearch();
 	var opt = new MPoiSearchOptions();
   	opt.recordsPerPage = 20;//每页返回数据量，默认为10
  	opt.pageNum = pageNum;//当前页数。
   	opt.dataType = "";//数据类别，该处为分词查询，只需要相关行业关键字即可
  	opt.dataSources = DS_BASE_ENPOI;//数据源，基础+企业地标数据库（默认）
   	opt.range = 30000;//查询范围，单位为米，默认值为3000
  	opt.naviFlag = 0;//周边查询返回结果是否按导航距离排序,0，不按导航距离排序（默认）1，按导航距离排序。
  	MSearch.setCallbackFunction(keywordSearch_CallBack);
  	MSearch.poiSearchByCenPoi(lnglat,keywords,city,opt);
}
function keywordSearch_CallBack(data) {
    if(data.error_message == null){
        if(data.message == 'ok') {
            if(data.poilist.length > 0){
				var line;
				for (i=0; i<data.poilist.length; i++) {
					if(jQuery.inArray(data.poilist[i].name, keyArray) == -1) {
						line = '<li class="bline pa-t10px bg-pic"><h2 class="f16px fb"><a href="javascript:void(0)" map_x="'+data.poilist[i].x+'" map_y="'+data.poilist[i].y+'" address="'+data.poilist[i].address+'">' + data.poilist[i].name + '</a></h2><p class="cgray f12px">' + data.poilist[i].address + '</p></li>';
                		resultHtml += line;
					}
				}
            }
        }
    }
    if(resultHtml) {
        $('#searchResultBegin').show();
    } else {
        if(nodata)
		location.href = '<?php echo url('at/searchNoResult', array('kw'=>$kw));?>';
    }
    $('#searchMapResult').html(resultHtml);
    $('#loading').hide();
    
    if(data.poilist.length == 20) {
    	pageNum++;
    	if(pageNum > 5) return ;
    	keywordSearch();
    }
}
</script>