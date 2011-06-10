<ul class="search-location-list ma-t30px">
<?php foreach ((array)$newdata as $v):?>
    <li class="bline pa-t10px bg-pic">
    	<h2 class="f16px fb">
    		<?php if($v['map']):?>
    	    <?php echo l($v['name'], 'javascript:void(0);', array('class'=>'location-link mapsearch', 'map_x'=>$v['map_x'], 'map_y'=>$v['map_y'], 'address'=>$v['address']));?>
    	    <?php else :?>
    	    <?php echo l($v['name'], $v['foodShopListUrl'], array('class'=>'location-link', 'url'=>url('at/addusenums', array('id'=>$v['id']))));?>
    	    <?php endif;?>
    	    <?php if(0):?>
    	    <span class="cgray">(<?php echo l("餐馆{$v['food_nums']}家", $v['foodShopListUrl']);?>, <?php echo l("蛋糕店{$v['cake_nums']}家", $v['cakeShopListUrl']);?>)</span>
    	    <?php endif;?>
    	</h2>
    	<p class="cgray f12px"><?php echo $v['address'];?></p>
    </li>
<?php endforeach;?>
</ul>

<?php //$this->renderPartial('search_googlemap', array('kw'=>$kw, 'data'=>$data));?>

<?php echo CHtml::beginForm(url('at/search'), 'get');?>
<div class="ma-t10px ma-b20px search-no-text f14px">
    <p>
    	没找到结果？可以提供再详细点的地址信息:
    	<input class="txt" name="kw" type="text" value="<?php echo $kw;?>" />
    	<input type="submit" class="cursor" value="搜索" />
    	您也可以在<a href="javascript:void(0);" onclick="openDialog()">电子地图上标注您的位置</a>
    </p>
</div>
<?php echo CHtml::endForm();?>


<!-- 地图处理 -->
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMap',
	'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'◎请在电子地图上标注您的位置',
        'autoOpen'=>false,
		'width' => 820,
		'height' => 530,
		'modal' => true,
		'draggable' => true,
		'resizable' => false
    ),
));
?>
<iframe id="ShowMapIframe" src="about:blank" width="100%" height="480" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
function openDialog() {
	$("#ShowMap").dialog("open");
	$("#ShowMapIframe").attr('src', '<?php echo aurl('ditu/location'); ?>');
}
//增加地址使用次数
function addLocationUseNums()
{
	var url = $(this).attr('url');
	$.ajax({
		type: 'post',
		url: url,
		dataType: 'html',
		cache: false,
		success: function(data){}
	});
}
$(function(){
	$('.location-link').click(addLocationUseNums);
	$('.mapsearch').click(function(){
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
</script>