<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/address/list");?>">地址列表</a></li>
	</ul>
</div>
<div class="pa-10px">
	<table class="list lh30px">
	  <tr class="bline">
	    <th width="50">联系人</th>
	    <th>详细地址</th>
	    <th width="110">联系电话</th>
	    <th width="110">备选电话</th>
	    <th width="50"></th>
	    <th width="70">操作</th>
	  </tr>
	<?php foreach ($address as $row): ?>
	  <tr id="list_<?php echo $row->id;?>" class="bline">
	    <td class="ac"><?php echo $row->consignee;?></td>
	    <td><?php echo $row->city->name;?> <?php echo $row->district->name;?> <?php echo $row->address;?></td>
	    <td class="ac"><?php echo $row->telphone;?></td>
	    <td class="ac"><?php echo $row->mobile;?></td>
	    <td class="ac cgray">
	    	<?php if($row->is_default):?>
	    	默认地址
	    	<?php else:?>
	    	<?php echo l('设为默认',url('my/address/setDefault', array('id'=>$row->id)),array('class'=>'set-default'));?>
	    	<?php endif;?>
	    </td>
	    <td class="ac"><a href="<?php echo url('my/address/list',array('id'=>$row->id));?>">修改</a> <a href="javascript:void(0);" onclick="del_address(<?php echo $row->id;?>)">删除</a></td>
	  </tr>
	<?php endforeach;?>
	</table>
	<?php echo CHtml::beginForm(url('my/address/list', array('id'=>$model->id)),'post', array('name'=>'address_form', 'id'=>'address_form'));?>
	<table class="ma-t10px lh30px ma-l10px">
	  <tr>
	    <td class="ar" width="60">选择地址：</td>
	    <td>
	    	<?php echo CHtml::activeDropDownList($model, 'city_id', $cityarray, array('id'=>'city_id'));?>
	    	<?php echo CHtml::activeDropDownList($model, 'district_id', $districtarray, array('id'=>'district_id'));?>
	    </td>
	  </tr>
	  <tr>
	    <td class="ar">选择楼宇：</td>
	    <td>
	    	<?php echo CHtml::textField('building_name', $model->building->name, array('id'=>'building_name', 'class'=>'txt building', 'readonly'=>true, 'lang'=>'', 'onclick'=>"selectBuilding(setBuilding, this.lang)"));?>
		    <a href="javascript:void(0);" onclick="selectBuilding(setBuilding, this.lang)" lang="" id="selectBuildingLink">选择楼宇</a>
		    <?php echo CHtml::activeHiddenField($model, 'building_id', array('id'=>'building_id'));?>
		    <?php echo CHtml::activeHiddenField($model, 'map_x', array('id'=>'map_x'));?>
		    <?php echo CHtml::activeHiddenField($model, 'map_y', array('id'=>'map_y'));?>
	    </td>
	  </tr>
	  <tr>
	    <td class="ar">详细地址：</td>
	    <td><?php echo CHtml::activeTextField($model, 'address', array('class'=>'txt'));?></td>
	  </tr>
	  <tr>
	    <td class="ar" width="60">联系人：</td>
	    <td><?php echo CHtml::activeTextField($model, 'consignee', array('class'=>'txt'));?></td>
	  </tr>
	  <tr>
	    <td class="ar">联系电话：</td>
	    <td><?php echo CHtml::activeTextField($model, 'telphone', array('class'=>'txt'));?></td>
	  </tr>
	  <tr>
	    <td class="ar">备选电话：</td>
	    <td><?php echo CHtml::activeTextField($model, 'mobile', array('class'=>'txt'));?></td>
	  </tr>
	  <tr><td colspan="2">
		<?php echo CHtml::activeHiddenField($model, 'id'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'address_btn',
				'caption' => $caption,
			)
		);
		?>
	  </td></tr>
	</table>
	<?php echo CHtml::endForm();?>
	<div id="create_error_message" class="ma-t10px"></div>
	<?php echo user()->getFlash('errorSummary'); ?>
</div>
<script language="JavaScript">
// 选择building， callback的方法
function setBuilding(name, building_id, map_x, map_y) {
	$("#building_id").val(building_id);
	$("#map_x").val(map_x);
	$("#map_y").val(map_y);
	$("#building_name").val(name);
}

function del_address(id) {
	var postUrl = '<?php echo url('my/address/delete'); ?>';
	var create_error_message = $('#create_error_message');
	if(confirm("确定要删除这条地址吗？")) {
		$.post(postUrl,{id:id},function(data){
			if(data) {
				create_error_message.html(data);
			} else {
				$("#list_" + id).hide();
			}
		});
	}
}
$(function(){
	$(".list tr").hover(function(){
		$(".list tr").removeClass('selected');
		$(this).addClass('selected');
	},function(){
		$(".list tr").removeClass('selected');
	});
	
	$('#address_form').submit(function(){
		var state = true;
		var consignee = $('#UserAddress_consignee').val();
		var address = $('#UserAddress_address').val();
		var mobile = $('#UserAddress_mobile').val();
		var telphone = $('#UserAddress_telphone').val();
		var create_error_message = $('#create_error_message');
		var error_message = '';
		
		create_error_message.html(error_message);
		return state;
	});
	$("#city_id").change(function(){
		var getDistrictUrl = '<?php echo url('at/district'); ?>';
		var city_id = $("#city_id").val();
		$("#selectBuildingLink").attr('lang',city_id);
		$("#building_name").attr('lang',$("#city_id").val());
		$.get(getDistrictUrl,{city_id:city_id},function(data){
			var html = '';
			for(var i=0; i<data.length; i++) {
				html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
			}
			$("#district_id").html(html);
		},'json');
	});
	
	$("#selectBuildingLink").attr('lang',$("#city_id").val());
	$("#building_name").attr('lang',$("#city_id").val());
});
</script>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'ShowBuildingDialog',
	'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'◎请选择小区/大厦',
        'autoOpen'=>false,
		'width' => 870,
		'height' => 450,
		'modal' => true,
		'draggable' => true,
		'resizable' => false,
		'beforeClose' => 'js:function(){$("select").css("visibility","inherit");}',
    ),
));
?>
<div class="district">
	<a>行政区域</a>
	<div class="clear"></div>
</div>
<div class="search">
	<label>搜索小区/大厦：</label>
	<input type="text" id="building_key" class="txt" />
	<input type="button" id="building_button" value="搜索" /> 
	<label><?php echo l('如果没有找到我的小区/大厦？ 请点击这里', url('building/create'));?></label>
</div>
<div class="letter">
	<a>A</a><a>B</a><a>C</a><a>D</a><a>E</a><a>F</a><a>G</a><a>H</a><a>J</a><a>K</a><a>L</a><a>M</a><a>N</a><a>O</a><a>P</a><a>Q</a><a>R</a><a>S</a><a>T</a><a>W</a><a>X</a><a>Y</a><a>Z</a>
	<div class="clear"></div>
</div>
<div class="result">
	<div class="buildlist">
		<a><span>100家商家</span>小区/大厦</a>
		<div class="clear"></div>
	</div>
	<div class="pages"></div>
</div>
<div class="loading">正在加载中，请稍候...</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>