<div class="buildlist">
<?php if($data):?>
	<?php foreach($data as $building):?>
	<a alt="<?php echo $building->id;?>" map_x="<?php echo $building->map_x; ?>" map_y="<?php echo $building->map_y; ?>" building_name="<?php echo $building->name;?>">
		<span><?php echo $building->food_nums;?>家商家</span><?php echo $building->name;?>
	</a>
	<?php endforeach;?>
<?php else:?>
	<a>没有结果！</a>
<?php endif;?>
	<div class="clear"></div>
</div>
<div class="pages ac pa-t10px">
<?php $this->widget('CLinkPager', array(
	'pages' => $pages,
    'header' => '',
    'firstPageLabel' => '首页',
    'lastPageLabel' => '末页',
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
));?>
</div>