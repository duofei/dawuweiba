$(function(){
	$('#ajax-note').ajaxStart(function(){
		$(this).html('开始载入...').fadeIn('fast');
	});
	
	$('#ajax-note').ajaxError(function(){
		$(this).html('载入错误！');
	});
	
	$('#ajax-note').ajaxSend(function(){
		$(this).addClass('ajax-loading').html('正在发送请求...');
	});
	
	$('#ajax-note').ajaxComplete(function(){
		$(this).removeClass('ajax-loading');
	});
	
});

function deleteOneRecord()
{
	var a = confirm('您确定要隐藏此记录吗？');
	if (!a) return false;
	var tthis = $(this);
	var ptr = tthis.parents('tr');
	var subject = ptr.children('td.txt-name');
	$.ajax({
		url: tthis.attr('href'),
		type: 'post',
		dataType: 'json',
		cache: false,
		success: function(data) {
			if (data.result == 1) 
				ptr.fadeOut(1000, function(){$(this).remove()});
			$('#ajax-note').html(data.message);
		}
	});
	return false;
}

function deletePosts()
{
	var a = confirm('您确定要删除这些文章吗？');
	if (!a) return false;
	var chk = $(':checkbox:checked');
	var chkdata = chk.serialize();
	
	$.ajax({
		url: BU + $(this).attr('url'),
		type: 'post',
		dataType: 'json',
		cache: false,
		data: chkdata,
		success: function(data) {
			if (data.result == 1) {
				chk.parents('tr').fadeOut(2000);
			}
			$('#ajax-note').html(data.message);
		}
	});
	return false;
}


function trMouseOver()
{
	$(this).addClass('bg-dark');
}

function trMouseOut()
{
	$(this).removeClass('bg-dark');
}

function selectAll()
{
	$(':checkbox').attr('checked', 'checked');
}

function selectInverse()
{
	var chk = $(':checkbox');
	chk.each(function(){
		var tthis = $(this);
		var ischecked = tthis.attr('checked');
		if (ischecked)
			tthis.removeAttr('checked');
		else
			tthis.attr('checked', 'checked');
	});
}


function changeState()
{
	var tthis = $(this);
	$.ajax({
		url: tthis.attr('href'),
		type: 'post',
		cache: false,
		dataType: 'json',
		success: function(data) {
			if (data.result == 1 || data.result == 0) {
				var img = RESBU + 'admin/images/state' + data.result + '.gif';
				tthis.children('img').attr('src', img);
			}
			$('#ajax-note').html(data.message)
		}
	});
	return false;
}
