<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
		<li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/shop/list");?>">商铺列表</a></li>
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/shop/create");?>">创建商铺</a></li>
	</ul>
	<div id="index" class="ui-tabs-panel">
	<?php echo CHtml::beginForm(url('shopcp/shop/create'));?>
	<table  class="tabcolor list-tbl" width="600">
    <tr>
        <td width="120">店铺名称：</td>
        <td><?php echo CHtml::activeTextField($shop, 'shop_name', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td width="120">店铺分类：</td>
        <td><span><?php echo CHtml::CheckBoxList('tags', '', $shopTag, array('separator'=>'</span> &nbsp; <span>'));?></span></td>
    </tr>
    <tr>
    	<td>行政区域：</td>
    	<td><?php echo CHtml::activeDropDownList($shop, 'district_id', District::getDistrictArray($this->city['id']));?></td>
    </tr>
    <tr>
        <td>详细地址：</td>
        <td><?php echo CHtml::activeTextField($shop, 'address', array('class'=>'txt'));?></td>
    </tr>
    <tr>
        <td>订餐电话：</td>
        <td><?php echo CHtml::activeTextField($shop, 'telphone', array('class'=>'txt'));?></td>
    </tr>
	</table>
	<br />
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'submit',
			'caption' => '提 交',
		)
	);
	?>
	<?php echo CHtml::endForm();?>
  	</div>
<?php echo CHtml::errorSummary($shop);?>
</div>