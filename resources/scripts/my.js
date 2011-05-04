// 获取当前鼠标的top,left值
var x,y;
$(document).mousemove(function(e){ 
	x = e.pageX;
	y = e.pageY;
});

function isEmpty(value) {
	if(value) {
		return false;
	} else {
		return true;
	}
}

// 显示评分对应标题名称
function rateShowTitle() {
	var id = '#mark_' + $(this).attr('name');
	$(id).text($(this).attr('title'));
}

function rateShowNone() {
	var id = '#mark_' + $(this).attr('name');
	$(id).text('还未打分');
}

// 显示增加积分效果
function showIntegral(v) {
	var integral = $(".integral");
	integral.show();
	integral.html('<div>积分+' + v + '</div>');
	integral.css('top', (y-70)+'px');
	integral.css('left', x+'px');
	
	$(".integral div").animate({top: "0",opacity: "0"}, 1500, function(){
		integral.hide();
	});
}

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