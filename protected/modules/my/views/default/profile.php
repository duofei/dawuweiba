<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/default/profile");?>">基本资料</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/email");?>">修改邮箱</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/passwd");?>">修改密码</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px" style="width:720px">
<div class="fl ma-r20px ma-t10px" style="width:120px">
	<p><?php echo $user->portraitHtml; ?></p>
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'changer_portrait',
			'buttonType' => 'button',
			'caption' => '更改头像',
			'onclick'=>'js:function(){$("#portrait").dialog("open");}',
		)
	);
	?>
	<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	    'id'=>'portrait',
	    'htmlOptions' => array('class'=>'none'),
	    'options'=>array(
	        'title'=>'更改头像',
	        'autoOpen'=>false,
	        'width' => 350,
	    ),
	));
	echo CHtml::beginForm(url('my/default/portrait'),'post',array('name'=>'portrait_edit', 'enctype'=>'multipart/form-data', 'target'=>'portrait-ifarme'));
	?>
	<div class="portrait-dialog">
		<?php echo CHtml::activeFileField($user, 'portrait');?>
		<br /><br />
		<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'upload_portrait',
				'caption' => '上 传',
			)
		);
		?>
	</div>
	<iframe name="portrait-ifarme" class="portrait-ifarme" frameborder="no" border="0"></iframe>
	<?php
	echo CHtml::endForm();
	$this->endWidget('zii.widgets.jui.CJuiDialog');
	?>
    <div class="clear"></div>
</div>
<div class="fl" >
<?php echo CHtml::beginForm(url('my/default/profile'),'post',array('name'=>'profile_edit')); ?>
	<table width="500" class="lh40px">
	  <tr>
	    <td width="90" class="ar">用户名：</td>
	    <td><?php echo $user->username; ?> <span class="cred">(<?php echo $user->getApproveStateText();?>) <?php if($user->approve_state == User::APPROVE_STATE_UNSETTLED) echo l('马上认证', url('my/default/approve'));?></span></td>
	  </tr>
	  <tr>
	    <td class="ar">电子邮箱：</td>
	    <td id="show_email"><?php echo $user->email; ?> <?php echo CHtml::link('修改邮箱', url('my/default/email'));?></td>
	  </tr>
	  <tr>
	    <td class="ar">真实姓名：</td>
	    <td><?php echo CHtml::activeTextField($user, 'realname', array('class'=>'txt')); ?></td>
	  </tr>
	  <tr>
	    <td class="ar">选择地址：</td>
	    <td>
	    	<?php echo CHtml::activeDropDownList($user, 'city_id', $cityarray, array('id'=>'city_id'));?>
	    	<?php echo CHtml::activeDropDownList($user, 'district_id', $districtarray, array('id'=>'district_id'));?>
	    </td>
	  </tr>
	  <tr>
	    <td class="ar">选择办公楼宇：</td>
	    <td>
	    	<?php echo CHtml::textField('office_building_name', $user->officeBuilding->name, array('id'=>'office_building_name', 'class'=>'txt building', 'readonly'=>true, 'onclick'=>"selectBuilding(setOfficeBuilding,this.lang)",'lang'=>''));?>
		    <a href="javascript:void(0);" onclick="selectBuilding(setOfficeBuilding,this.lang)" lang="" id="selectOfficeLink">选择楼宇</a>
		    <?php echo CHtml::activeHiddenField($user, 'office_building_id', array('id'=>'office_building_id'));?>
		    <?php echo CHtml::activeHiddenField($user, 'office_map_x', array('id'=>'office_map_x'));?>
		    <?php echo CHtml::activeHiddenField($user, 'office_map_y', array('id'=>'office_map_y'));?>
	    </td>
	  </tr>
	  <tr>
	    <td class="ar">选择住宅小区：</td>
	    <td>
	    	<?php echo CHtml::textField('home_building_name', $user->homeBuilding->name, array('id'=>'home_building_name', 'class'=>'txt building', 'readonly'=>true, 'onclick'=>"selectBuilding(setHomeBuilding,this.lang)", 'lang'=>''));?>
		    <a href="javascript:void(0);" onclick="selectBuilding(setHomeBuilding,this.lang)" lang="" id="selectHomeLink">选择楼宇</a>
		    <?php echo CHtml::activeHiddenField($user, 'home_building_id', array('id'=>'home_building_id'));?>
		    <?php echo CHtml::activeHiddenField($user, 'home_map_x', array('id'=>'home_map_x'));?>
		    <?php echo CHtml::activeHiddenField($user, 'home_map_y', array('id'=>'home_map_y'));?>
	    </td>
	  </tr>
	  <tr>
	    <td class="ar">性别：</td>
	    <td>
		<?php echo CHtml::activeRadioButtonList($user, 'gender', User::$genders, array('separator'=>'&nbsp;&nbsp;')); ?></td>
	  </tr>
	  <tr>
	    <td class="ar">生日：</td>
	    <td><?php
	    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $user,
		    'attribute' => 'birthday',
		    'language' => 'zh',
		    'htmlOptions'=>array( 'class'=>'txt', 'readOnly'=>true),
	    	'options' => array(
	    		'dateFormat' => 'yy-mm-dd',
	    		'changeYear' => true,
	    		'changeMonth' => true,
	    		'showAnim' => 'fold',
	    		'yearRange' => '1960:2010',
	    		'defaultDate' => '1980-01-01',
	    	)
		));
		?></td>
	  </tr>
	  <tr>
	    <td class="ar">电话：</td>
	    <td><?php echo CHtml::activeTextField($user, 'telphone', array('class'=>'txt')); ?></td>
	  </tr>
	  <tr>
	    <td class="ar">手机：</td>
	    <td><?php echo CHtml::activeTextField($user, 'mobile', array('class'=>'txt')); ?></td>
	  </tr>
	  <tr>
	    <td class="ar">QQ：</td>
	    <td><?php echo CHtml::activeTextField($user, 'qq', array('class'=>'txt')); ?></td>
	  </tr>
	  <tr>
	    <td class="ar">MSN：</td>
	    <td><?php echo CHtml::activeTextField($user, 'msn', array('class'=>'txt')); ?></td>
	  </tr>
	  
	  <tr><td colspan="2" class="ac">
	  	<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'save_profile',
				'caption' => '保存资料',
			)
		);
		?>
	  </td></tr>
	</table>
    <?php echo CHtml::endForm(); ?>
    <?php echo user()->getFlash('errorSummary'); ?>
</div>
<div class="clear"></div>
</div>
<script language="JavaScript">
function setOfficeBuilding(name, building_id, map_x, map_y) {
	$("#office_building_id").val(building_id);
	$("#office_map_x").val(map_x);
	$("#office_map_y").val(map_y);
	$("#office_building_name").val(name);
}
function setHomeBuilding(name, building_id, map_x, map_y) {
	$("#home_building_id").val(building_id);
	$("#home_map_x").val(map_x);
	$("#home_map_y").val(map_y);
	$("#home_building_name").val(name);
}
$(function(){
	$("#city_id").change(function(){
		var getDistrictUrl = '<?php echo url('at/district'); ?>';
		var city_id = $("#city_id").val();
		$("#selectOfficeLink").attr('lang',city_id);
		$("#selectHomeLink").attr('lang',city_id);
		$("#office_building_name").attr('lang',city_id);
		$("#home_building_name").attr('lang',city_id);
		$.get(getDistrictUrl,{city_id:city_id},function(data){
			var html = '';
			for(var i=0; i<data.length; i++) {
				html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
			}
			$("#district_id").html(html);
		},'json');
	});
	
	$("#selectOfficeLink").attr('lang',$("#city_id").val());
	$("#selectHomeLink").attr('lang',$("#city_id").val());
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