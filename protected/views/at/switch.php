<style>
.meishi-show .ui-dialog-content{overflow:hidden;}
</style>
<div class="pa-l20px f14px lh24px location-switch">
<?php foreach($location as $r):?>
	<div class="cblack bg-icon ma-t10px"><?php echo l($r->name, $r->shopListUrl);?></div>
<?php endforeach;?>
	<p class="ma-t10px bg-icon"><?php echo l('搜索新地址', 'javascript:meishiMapSearch();');?></p>
</div>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowMeishiMap',
	'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'◎请在电子地图上查询您的位置',
        'autoOpen'=>false,
		'width' => 830,
		'height' => 540,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
		'dialogClass' => 'meishi-show'
    ),
));
?>
<iframe id="ShowMeishiMapIframe" src="about:black" width="840" height="498" frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
function meishiMapSearch() {
	$("#ShowMeishiMapIframe").attr('src', '<?php echo aurl('ditu/search', array('cid'=>ShopCategory::CATEGORY_FOOD)); ?>');
	$("#ShowMeishiMap").dialog("open");
}
</script>