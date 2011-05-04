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
				ptr.fadeOut(200, function(){$(this).remove()});
			$('#ajax-note').html(data.message);
		}
	});
	return false;
}