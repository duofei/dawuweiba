// 浮动小提示, 需要top,left
function showPopTag(v) {
	var popTag = $(".pop-tag");
	popTag.show();
	popTag.html('<div>' + v + '</div>');
	popTag.css('top', (y-70)+'px');
	popTag.css('left', x-20+'px');
	popTag.find('div').animate({top: "0",opacity: "0"}, 1500, function(){
		popTag.hide();
	});
}

// 收藏商品
function favoriteGoods(e)
{
	e.preventDefault();
	var tthis = $(this);
	var thisParent = tthis.parent();
	thisParent.click(function(){ 
		return false;
	});
	var tempHtml = thisParent.html();
	$.ajax({
		type: 'post',
		url: tthis.parents('a').attr('href'),
		dataType: 'html',
		beforeSend: function(){
			var html = '<img src="' + RESBU + 'images/loading.gif' + '" class="note-favorite" />';
			thisParent.html(html);
		},
		success: function(data){
			thisParent.html(tempHtml);
			thisParent.find('.goods-favorite').click(favoriteGoods);
			showPopTag(data);
		}
	});
}

//收藏店铺
function favoriteShop(e)
{
	e.preventDefault();
	var tthis = $(this);
	$.ajax({
		type: 'post',
		url: tthis.attr('href'),
		dataType: 'html',
		beforeSend: function(){
			tthis.next('.note-favorite').remove();
			var html = '<img src="' + RESBU + 'images/loading.gif' + '" class="note-favorite" />';
			tthis.after(html);
		},
		success: function(data){
			tthis.next('.note-favorite').remove();
			tthis.after(data);
		}
	});
}

//打开填写蛋糕祝福语的层
function openBlessingDialog(e)
{
	e.preventDefault();
	$('#blessingDialog').dialog('open');
	$('#cakeBlessingDiv').hide();
	$('#cardBlessingDiv').hide();
	$('#cakeBlessingDiv textarea').val('');
	$('#cardBlessingDiv textarea').val('');
	if($(this).attr('cakeblessing')==1) {
		$('#cakeBlessingDiv').show();
		$('#cakeBlessingDiv textarea').val($(this).attr('cakeblessingcontent'));
		$('#cakeBlessingDiv select').change(function(){
			$('#cakeBlessingDiv textarea').val($(this).val());
		});
	}
	if($(this).attr('cardblessing')==1) {
		$('#cardBlessingDiv').show();
		$('#cardBlessingDiv textarea').val($(this).attr('cardblessingcontent'));
		$('#cardBlessingDiv select').change(function(){
			$('#cardBlessingDiv textarea').val($(this).val());
		});
	}
	$('#saveBlessing').attr('cartid', $(this).attr('cartid'));
}

//蛋糕订单添加祝福语
function addCartBlessing(e)
{
	e.preventDefault();
	var cakeblessing = $('#cakeBlessingDiv textarea').val();
	var cardblessing = $('#cardBlessingDiv textarea').val();
	var cartid = $(this).attr('cartid');
	var url = $(this).attr('href');
	$.ajax({
		type: 'post',
		url: url,
		data: "cartid=" + cartid + "&cakeblessing=" + cakeblessing + "&cardblessing=" + cardblessing,
		dataType: 'html',
		cache: false,
		success: function(data){
			if(data=='-1') {
				alert('出错，请重试');
			} else {
				$('#blessingDialog').dialog('close');
				var html = '';
				if(cakeblessing) {
					html += '蛋糕祝福语：' + cakeblessing + '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				if(cardblessing) {
					html += '贺卡祝福语：' + cardblessing; 
				}
				$('#blessing_msg_' + cartid).html(html);
				$('#addblessingId_' + cartid).attr('cakeblessingcontent', cakeblessing);
				$('#addblessingId_' + cartid).attr('cardblessingcontent', cardblessing);
			}
		}
	});
}

//购物车里加入一个商品
function buyOneGoods(e)
{
	e.preventDefault();
	var tthis = $(this);
	$.ajax({
		type: 'post',
		url: tthis.attr('href'),
		data:"cakepriceid=" + tthis.attr('cakepriceid'),
		dataType: 'html',
		cache: false,
		success: function(data){
			if(data == -1) {
				showOverlayBox($("#overlayBox").attr('cart'));
			} else if (data == -2) {
				showOverlayBox($("#overlayBox").attr('location'));
			} else if (data == -3) {
				showOverlayBox($("#overlayBox").attr('selectBuilding'));
			} else if (data == -4) {
				showOverlayBox($("#overlayBox").attr('noGroupInCart'));
			} else if (data == -5) {
				showOverlayBox($("#overlayBox").attr('groupInCart'));
			} else {
				$('#cart').html(data);
				var html = '<img src="' + RESBU + 'images/pixel.gif' + '" class="bg-icon cart-ok" />';
				tthis.parents('li').find('.buy-confirm').html(html);
			}
		}
	});
}

//购物车里删除一个商品
function delCartOneGoods(e)
{
	e.preventDefault();
	var tthis = $(this);
	var view = $('#cart').attr('view');
	$.ajax({
		type: 'post',
		url: tthis.attr('href'),
		data: 'view=' + view,
		dataType: 'html',
		cache: false,
		success: function(data){
			// 如果是蛋糕商品就重新载入购物车
			if(view == 'checkout_cake_cart') {
				location.reload();
				return ;
			}
			if (data == 0) {
				location.reload();
				return ;
			}
			$('#cart').html(data);
			var gid = tthis.attr('gid');
			var confirm = $('.goods-list li[gid=' + gid + ']').find('.buy-confirm');
			confirm.html('');
		}
	});
}

//更新购物车商品数量
function updateCartGoodsNums(e)
{
	var tthis = $(this);
	var nums = tthis.val();
	var url = tthis.parents('tr').attr('url');
	var gid = tthis.parents('tr').attr('gid');
	var view = $('#cart').attr('view');
	$.ajax({
		type: 'post',
		url: url,
		data: 'num=' + nums + '&view=' + view,
		dataType: 'html',
		cache: false,
		success: function(data){
			$('#cart').html(data);
			$('.goods-item-nums' + gid).focus();
		}
	});
}

//过滤美食商品列表
function filterGoods(e)
{
	e.preventDefault();
	
	$(this).parents('li').siblings('li').children('a').removeClass('link-selected');
	$(this).addClass('link-selected');
	
	var k = $(this).text();
	if ($(this).attr('id') == 'goodsall') {
		$('.goods-list').show();
		return true;
	}
	$('.goods-list').hide();
	$('.goods-list[category=' + k + ']').show();
}

//美食商品列表快速搜索
function goodsQuickSearch(e)
{
	e.preventDefault();
	var kw = $.trim($(this).val());
	if (kw.length == 0) {
		$('.goods-list:hidden').show();
		$('.goods-item:hidden').show();
		return true;
	}
	$('.goods-item:visible').hide();
	$('.goods-list:visible').hide();
	var gn = $('.goods-name:contains(' + kw + ')');
	gn.parents('.goods-list').show();
	gn.parents('.goods-item').show();
}

//过滤蛋糕商品列表
function filterCakeGoods(e)
{
	e.preventDefault();
	
	$(this).parents('li').siblings('li').children('a').removeClass('link-selected');
	$(this).addClass('link-selected');
	
	var category_sid = $(".cake-filter a[type='category'][class='link-selected']").attr('sid');
	var purpose_sid = $(".cake-filter a[type='purpose'][class='link-selected']").attr('sid');
	var variety_sid = $(".cake-filter a[type='variety'][class='link-selected']").attr('sid');
	var shape_sid = $(".cake-filter a[type='shape'][class='link-selected']").attr('sid');
	var qw = $("#quick_search").val();
	
	$(".cake-list").show();
	$(".cake-category-list").show();
	
	if(category_sid==1) {
		$("#cake-type").show();
	} else {
		$("#cake-type").hide();
	}
	
	if(category_sid > 0) {
		$(".cake-category-list").each(function(){
			if($(this).attr('category') != category_sid) {
				$(this).hide();
			}
		});
	}
	
	$(".cake-list").each(function(){
		if(category_sid==1) {
			if(shape_sid > 0  && $(this).attr('shape') != shape_sid) {
				$(this).hide();
			}
			var purpose_re = new RegExp(',' + purpose_sid + ',', 'i');
			if(purpose_sid > 0 && $(this).attr('purpose').search(purpose_re)==-1) {
				$(this).hide();
			}
			var variety_re = new RegExp(',' + variety_sid + ',', 'i');
			if(variety_sid > 0 && $(this).attr('variety').search(variety_re)==-1) {
				$(this).hide();
			}
		}
		var qw_re = new RegExp(qw, 'i');
		if(qw && $(this).attr('name').search(qw_re)==-1) {
			$(this).hide();
		}
	});
}

//通过下拉选择改变蛋糕价格
function changeCakePrice()
{
	var id = $(this).val();
	var option = $(this).find("option:selected");
	var wmprice = option.attr('wmprice');
	var marketprice = option.attr('marketprice');
	$(this).parent().parent().find(".market-price").html(marketprice);
	$(this).parent().parent().find(".wm-price").html(wmprice);
	$(this).parent().parent().find("a.btn-buy").attr('cakepriceid', id);
}

//选择用户地址
function selectUserAddress()
{
	var tthis = $(this);
	if(tthis.attr('class')=='new-address') {
		$('#consignee').val('');
		$('#address').val('');
		$('#telphone').val('');
		$('#mobile').val('');
		$('#addressid').val('');
		$('#editAddress').val(0);
	} else {
		var consignee = tthis.siblings(':hidden[name=consignee]').val();
		var address = tthis.siblings(':hidden[name=address]').val();
		var telphone = tthis.siblings(':hidden[name=telphone]').val();
		var mobile = tthis.siblings(':hidden[name=mobile]').val();
		var addressid = tthis.siblings(':hidden[name=aid]').val();
		var cityid = tthis.siblings(':hidden[name=city_id]').val();
		$('#consignee').val(consignee);
		$('#address').val(address);
		$('#telphone').val(telphone);
		$('#mobile').val(mobile);
		$('#addressid').val(addressid);
		if($('#cityid')) {
			$('#cityid').val(cityid);
		}
		if(tthis.attr('class')=='edit-address') {
			$('#editAddress').val(1);
			tthis.siblings(':radio[name=address-item]').attr('checked', true);
		} else {
			$('#editAddress').val(0);
		}
	}
}

function phoneCheckOut(e)
{
	e.preventDefault();
	var shopTelphone = $('#shopTelphone');
	var phoneOrderCornerBox = $('#phoneOrderCornerBox');
	var phoneOrderCornerContent = $('#phoneOrderCornerContent');
	var tthis = $(this);
	var shop_id = tthis.attr('alt');
	var url = tthis.attr('href');
	$.ajax({
		type: 'post',
		url: url,
		data: {shop_id:shop_id},
		dataType: 'html',
		cache: false,
		success: function(data){
			if(data) {
				phoneOrderCornerBox.show();
				phoneOrderCornerContent.html(data);
			}
			shopTelphone.show();
			tthis.hide();
		}
	});
}

function changeCakePriceId() 
{
	var id = $(this).val();
	$(this).parent().parent().siblings().find("a.btn-buy").attr('cakepriceid', id);
}