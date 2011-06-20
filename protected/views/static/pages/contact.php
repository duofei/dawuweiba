<?php
	/**
	 * 设置面包屑导航
	 */
	$this->breadcrumbs = array(
		'联系我们' => url('static/service'),
	);
	$this->pageTitle = $location->name . '外卖';
?>
	<?php echo $this->renderPartial('pageleft', array('contact' => 'select'));?>
	<div class="page-right fl pa-20px lh24px indent-p f14px">
        <h4 class="f16px cred">联系我们</h4>
        <p>&nbsp;</p>
        <p><strong>公司地址：</strong>山东济南新泺大街康桥颐东5-1701</p>
        <p><strong>联系电话：</strong>0531-55500071</p>
        <p><strong>邮　　箱：</strong>contact@52wm.com</p>
        <p id="map" style="width:100%; height:450px;" class="ma-t10px"></p>
    </div>
    <div class="clear"></div>

<script type="text/javascript" src=" http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
$(function(){
	var myLatlng = new google.maps.LatLng(36.67204431617357,117.11989995602414);
	var myOptions = {
		zoom: 15,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map"), myOptions);

	var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title:"我们的位置",
        draggable: false
    });
	var openInfoHtml = "<div class='f12px lh20px'>山东省济南市新泺大街<br />康桥颐东5-1701</div>";
	var infowindow = new google.maps.InfoWindow({
		content: openInfoHtml
	});
	infowindow.open(map,marker);
});
</script>