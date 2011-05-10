 <?php echo CHtml::beginForm(url('admin/user/search'),'get',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户名：</td>
        <td><?php echo CHtml::textField('User[username]', $user['username'], array('class'=>'txt')); ?></td>
        <td width="120" class="ar">注册时间：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'User[create_time_start]',
    'value' => $user['create_time_start'],
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate'), strtotime('last Week')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?>-<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'User[create_time_end]',
    'value' => $user['create_time_end']?$user['create_time_end']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt w100'),
));
?></td>
    </tr>
    <tr>
        <td width="120" class="ar">电话：</td>
        <td><?php echo CHtml::textField('User[telphone]', $user['telphone'], array('class'=>'txt')); ?></td>
        <td width="120" class="ar">手机号：</td>
        <td><?php echo CHtml::textField('User[mobile]', $user['mobile'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
		<td width="120" class="ar">邮箱：</td>
        <td><?php echo CHtml::textField('User[email]', $user['email'], array('class'=>'txt')); ?></td>
        <td width="120" class="ar">状态：</td>
        <td><?php echo CHtml::radioButtonList('User[state]', $user['state'], User::$states, array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">办公楼：</td>
        <td><?php echo CHtml::textField('User[office_building_name]', $user['office_building_name'], array('id'=>'office_building_name', 'class'=>'txt building', 'readonly'=>true, 'onclick'=>"selectBuilding(setOfficeBuilding,this.lang)",'lang'=>''));?>
		    <a href="javascript:void(0);" onclick="selectBuilding(setOfficeBuilding,this.lang)" lang="" id="selectOfficeLink">选择楼宇</a>
		    <?php echo CHtml::hiddenField('User[office_building_id]', $user['office_building_id'], array('id'=>'office_building_id'));?></td>
        <td width="120" class="ar">小区：</td>
        <td><?php echo CHtml::textField('User[home_building_name]', $user['home_building_name'], array('id'=>'home_building_name', 'class'=>'txt building', 'readonly'=>true, 'onclick'=>"selectBuilding(setHomeBuilding,this.lang)", 'lang'=>''));?>
		    <a href="javascript:void(0);" onclick="selectBuilding(setHomeBuilding,this.lang)" lang="" id="selectHomeLink">选择楼宇</a>
		    <?php echo CHtml::hiddenField('User[home_building_id]', $user['home_building_id'], array('id'=>'home_building_id'));?></td>
    </tr>
</table>
<?php $this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'search',
		'caption' => '搜 索',
	)
);?>
 <?php echo CHtml::endForm();?>
<?php if ($users) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="110">用户名</th>
        <th class="al" width="150">注册时间</th>
        <th class="al" width="150">最后登录时间</th>
        <th class="al">积分</th>
        <th class="al">信用</th>
        <th class="al">评价次数</th>
        <th class="al">白吃点</th>
        <th class="al">状态</th>
        <th class="al">认证状态</th>
        <th class="al">类型</th>
        <th class="al" width="160">操作</th>
    </tr>
<?php foreach ($users as $key=>$val) :?>
	<tr>
		<td><?php echo $val->username?></td>
		<td><?php echo $val->createTimeText?></td>
		<td><?php echo $val->lastLoginTimeText?></td>
		<td><?php echo $val->integral?></td>
		<td><?php echo $val->credit?></td>
		<td><?php echo $val->credit_nums?></td>
		<td><?php echo $val->bcnums?> <?php echo l('增加', url('admin/user/addbcnums', array('id'=>$val->id)))?></td>
		<td><?php echo $val->stateText?></td>
		<td><?php echo $val->approveStateText?></td>
		<td><?php echo empty($val->shops) ? '个人' : '商家';?></td>
		<td>
		<a href="<?php echo url('admin/user/info', array('id'=>$val->id))?>"><span class="color">查看</span></a>
		<?php if ($val->state==1) :?>
		<!-- <a href="<?php //echo url('admin/user/state', array('id'=>$val->id, 'state'=>STATE_DISABLED))?>" onclick="return confirm('确定要禁用吗？');"><span class="color">禁用</span></a> -->
		<?php else:?>
		<!-- <a href="<?php //echo url('admin/user/state', array('id'=>$val->id, 'state'=>STATE_ENABLED))?>" onclick="return confirm('确定要启用吗？');"><span class="color">启用</span></a> -->
		<?php endif;?>
		<a href="<?php echo url('admin/user/setmanager', array('id'=>$val->id));?>">设为管理人员</a>
		<a href="<?php echo url('admin/user/bindshop', array('id'=>$val->id));?>">绑定商铺</a>
		</td>
	</tr>
<?php endforeach;?>
</table>
 	<div class="pages ar">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '翻页',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
 <?php else:?>
  <div>目前没有符合搜索条件的用户</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>
<script type="text/javascript">

//选择Building操作
function selectBuilding(callback, city_id) {
	var key,district_id,letter,page;
	var loading = $("#ShowBuildingDialog .loading");
	$("select").css('visibility','hidden');
	// 载入行政区域
	var loadDistrict = function() {
		var district = $("#ShowBuildingDialog .district");
		var urlDistrict = '/at/district';
		$.get(urlDistrict,{city_id:city_id},function(data){
			var html = '';
			for(var i=0; i<data.length; i++) {
				html += '<a alt="' + data[i].id + '">' + data[i].name + '</a>';
			}
			html += '<div class="clear"></div>';
			district.html(html);
			district.find('a').click(function(){
				if(district_id==$(this).attr('alt')) {
					$(this).removeClass('selected');
					district_id = 0;
				} else {
					district.find('a').removeClass('selected');
					$(this).addClass('selected');
					district_id = $(this).attr('alt');
				}
				loadBuilding();
			});
		},'json');
	}
	
	// 载入建筑物
	var loadBuilding = function(urlBuilding) {
		var building = $("#ShowBuildingDialog .result");
		if(!urlBuilding) {
			var urlBuilding= '/at/building';
		}
		loading.show();
		$.get(urlBuilding,{key:key, city_id:city_id, district_id:district_id, letter:letter, page:page},function(data){
			if(data) {
				building.html(data);
				// 处理分页链接
				$("#ShowBuildingDialog .pages a").click(function(){
					loadBuilding($(this).attr('href'));
					return false;
				});
				// 处理建筑物点击事件
				$("#ShowBuildingDialog .buildlist a").click(function(){
					callback($(this).attr('building_name'),$(this).attr('alt'),$(this).attr('map_x'),$(this).attr('map_y'));
					$("#ShowBuildingDialog").dialog("close");
				});
				loading.hide();
			} else {
				alert('No Building');
			}
		});
	}
	
	// 打开图层
	$("#ShowBuildingDialog").dialog("open");
	// 载入行政区域
	loadDistrict();
	// 载入建筑物
	loadBuilding();
	
	// 字母绑定click事件
	$("#ShowBuildingDialog .letter a").click(function(){
		if(letter==$(this).text()) {
			$(this).removeClass('selected');
			letter = '';
		} else {
			$("#ShowBuildingDialog .letter a").removeClass('selected');
			$(this).addClass('selected');
			letter = $(this).text();
		}
		page = 1;
		loadBuilding();
	});
	
	// 搜索按钮绑定click事件
	$("#building_button").click(function(){
		key = $("#building_key").val();
		loadBuilding();
	});
	// 搜索框绑定回车事件
	$("#building_key").keyup(function(e){
		if(e.keyCode==13){
			key = $("#building_key").val();
			loadBuilding();
		}
	});
}
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