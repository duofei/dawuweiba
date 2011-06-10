<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/shop/ditu");?>">地图查询商铺</a></li>
	</ul>
	<div id="index" class="ui-tabs-panel">
	<?php echo CHtml::beginForm(url('shopcp/shop/ditu'), 'post');?>
		<div class="bline ma-b5px">
			<span><?php echo l('在地图上画范围 ', 'javascript:voild(0);', array('id'=>'showMapRegion'));?><?php echo CHtml::hiddenField('map_region', $map_region);?></span>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<span><?php echo CHtml::submitButton('查询');?></span>
		</div>
	<?php echo CHtml::endForm();?>
	<?php if ($shops) :?>
		<table  class="tabcolor list-tbl ma-b5px" width="100%">
	    <tr class="title">
	        <th class="al">商铺名称</th>
	        <th class="al">商铺地址</th>
	        <th width="60">订餐方式</th>
	        <th width="130">添加时间</th>
	        <th width="50">状态</th>
	        <th width="60">操作</th>
	    </tr>
	  	<?php foreach ($shops as $key=>$shop) :?>
		<tr>
		    <td><?php echo $shop->shop_name;?></td>
		    <td><?php echo $shop->address;?></td>
		    <td class="ac"><?php echo $shop->buyTypeText;?></td>
		    <td class="ac"><?php echo $shop->shortCreateDateTimeText;?></td>
		    <td class="ac"><?php echo $shop->state ? '已通过' : '未通过';?></td>
		    <td class="cred">
		    	<?php if(!$shop->state && $shop->yewu_id==user()->id): ?>
		    		<?php echo l('商铺管理', url('shopcp/shop/setSession', array('id'=>$shop->id)));?>
		    	<?php endif;?>
		    </td>
		</tr>
		<?php endforeach;?>
		</table>
	<?php else:?>
	  	<div>没有商铺列表</div>
	<?php endif;?>
	   	<div class="pages ar">
		<?php $this->widget('CLinkPager', array(
			'pages' => $pages,
		    'header' => '',
		    'firstPageLabel' => '首页',
		    'lastPageLabel' => '末页',
		    'nextPageLabel' => '下一页',
		    'prevPageLabel' => '上一页',
		));?>
		</div>
		<div class="bline ma-b5px"></div>
  	</div>
  	
		
  	<style>
	.district {height:20px;}
	.district a {float:left; width:60px; height:17px; line-height:17px; font-weight:bold; text-align:center; cursor:pointer; display:block;}
	.district a:hover{background:#EB5D03; color:#fff;}
	.district a.selected {background:#EB5D03; color:#fff;}
	</style>
  	<div class="district">
	<?php foreach ($district as $d):?>
	<a mapx="<?php echo $d->map_x;?>" mapy="<?php echo $d->map_y;?>"><?php echo $d->name;?></a>
	<?php endforeach;?>
	</div>
	<div id="gmap" style="width:99%;height:480px; margin:0px auto;"></div>
</div>

<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
    'options'=>array(
        'title'=>'◎请在地图上标注',
        'autoOpen'=>false,
		'width' => 820,
		'height' => 530,
		'modal' => true,
		'draggable' => true,
		'resizable' => false
    ),
));
?>
<iframe id="ShowMapIframe" src="#" width="100%" height="480" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
$(function(){
	$("#showMapRegion").click(function(){
		$("#ShowMap").dialog("open");
		var region = $('#map_region').val();
		$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/region', array('callback'=>'setRegion')); ?>' + '?region=' + region);
	});
});

function setRegion(position) {
	$("#ShowMap").dialog("close");
	$("#map_region").val(position);
}
</script>





<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var map = null;
var count=0;
var markers = new Array();
<?php
$dot = '';
echo "var shoplist = [";
foreach ($shoplist as $shop):
if($shop->map_x):
echo $dot . '{"shop_name":"' . $shop->shop_name . '", "map_x":"' . $shop->map_x . '", "map_y":"' . $shop->map_y . '", "address":"' . $shop->address . '"}';
$dot = ',';
endif;
endforeach;
echo "];";
?>
$(function(){
	var latlng = new google.maps.LatLng(<?php echo $this->city['map_y'];?>, <?php echo $this->city['map_x'];?>);
    var myOptions = {
        zoom: 14,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById('gmap'), myOptions);
	showShop();
	$('.district a').click(function(){
		$('.district a').removeClass('selected');
		$(this).addClass('selected');
		var lng = $(this).attr('mapx');
		var lat = $(this).attr('mapy');
		var latlng = new google.maps.LatLng(lat,lng);
		map.setCenter(latlng);
	});
});
function showShop()
{
	for(var i=0; i<shoplist.length; i++) {
		var latlng = new google.maps.LatLng(shoplist[i].map_y, shoplist[i].map_x);
		drawMarker(latlng, shoplist[i].shop_name, shoplist[i].address);
	}
}
function drawMarker(latlng, title, address)
{
	var marker = new google.maps.Marker({
        position: latlng,
        map: map,
		draggable: false,
		title: title
    });
	markers[count] = marker;
	var openInfoHtml = "<div class='f12px lh20px'>店铺名称：" + title + "<br />地址：" + address + "</div>";
	var infowindow = new google.maps.InfoWindow({
		content: openInfoHtml
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
	count++;
}
</script>
