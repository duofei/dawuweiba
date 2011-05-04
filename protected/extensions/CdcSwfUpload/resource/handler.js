
/*
 * swf载入完成事件
 */
function cdcSwfuploadLoaded()
{
	this.setButtonDisabled(false);
	
}
/*
 * 打开选择文件对话框事件
 */
function cdcFileDialogStart()
{
	this.setButtonDisabled(true);
}

/*
 * 文件被 添加到序列后事件
 */
function cdcFileQueued(file)
{
	var movieName = this.getSetting('movieName');
	if (file.filestatus == SWFUpload.FILE_STATUS.QUEUED) {
		var html = '<tr class="' + file.id + '">'
		+ '<td class="findex">' + file.index + '</td>'
		+ '<td><div class="fname" title="' + file.name + '">' + file.name + '</div></td>'
		+ '<td class="fsize">' + (file.size / 1024).toFixed(2)  + 'K</td>'
		+ '<td class="fstatus">等待</td></tr>';
		$('.selected-files-' + movieName).append(html).show();
	}
}
/*
 * 将文件添加到序列发生错误时事件
 */
function cdcFileQueueError(file, errorCode, message)
{
	alert('序列错误' + file.id + file.name + ' ' + errorCode + message);
	this.setButtonDisabled(false);
}

/*
 * 选择完文件，点击确定按钮后事件
 */
function cdcFileDialogComplete(numFilesSelected, numFilesQueued)
{
	this.setButtonDisabled(false);
	var tthis = this;
	var movieName = this.getSetting('movieName');
	$('#btn-start-' + movieName).removeAttr('disabled').click(function(){tthis.startUpload()});
}

/*
 * 开始上传事件
 */
function cdcUploadStart(file)
{
	try {
		this.setButtonDisabled(true);
		var movieName = this.getSetting('movieName');
		$('#btn-start-' + movieName).attr('disabled', 'disabled');
	} catch (ex) {
		this.debug(ex);
	}
}

/*
 * 文件上传过程事件
 */
function cdcUploadProgress(file, bytesLoaded, bytesTotal)
{
	try {
		var note = Math.ceil((bytesLoaded / file.size) * 100) + '%';
		var movieName = this.getSetting('movieName');
		$('#container-' + movieName + ' tr.' + file.id + ' td.fstatus').html(note);
	} catch (ex) {
		this.debug(ex);
	}
}
/*
 * 文件上传错误事件
 */
function cdcUploadError(file, errorCode, message)
{
	try {
		if (this.getStats().files_queued > 0) {
			this.cancelUpload();
		}
		var movieName = this.getSetting('movieName');
		$('#container-' + movieName + ' tr.' + file.id + ' td.fstatus').html('错误' + errorCode + message);
	} catch (ex) {
		this.debug(ex);
	}
}
/*
 * 文件上传成功事件
 */
function cdcUploadSuccess(file, serverData, responseReceived)
{
	try {
		var movieName = this.getSetting('movieName');
		$('#container-' + movieName + ' tr.' + file.id + ' td.fstatus').html('完成');
		//alert(serverData);
		var previewTypes = ['.jpg', '.jpeg', '.gif', '.png', '.bmp'];
		if ($.inArray(file.type, previewTypes) != -1) {
			var fname = '<a class="thumbnail" href="javascript:void(0);" title="' + serverData + '">' + file.name + '</a>';
		} else 
			var fname = '<a class="attachment" href="javascript:void(0);" title="' + serverData + '">' + file.name + '</a>';
		$('#container-' + movieName + ' tr.' + file.id + ' div.fname').html(fname);
	} catch (ex) {
		this.debug(ex);
	}
}
/*
 * 文件上传完成事件
 */
function cdcUploadComplete(file)
{
	try {
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			this.setButtonDisabled(false);
			var movieName = this.getSetting('movieName');
			$('#btn-start-' + movieName).val('上传完成');
		}
	} catch (ex) {
		this.debug(ex);
	}
}




function showThumbnail()
{
	var tthis = $(this);
	var layer = $('#thumbnail-layer');
	var imgSrc = tthis.attr('title');
	var pos = tthis.position();
	layer.html('<img src="' + imgSrc + '" />').css('top', pos.top - layer.height()).css('left', pos.left + 80).show();
	return false;
}
function hideThumbnail()
{
	$('#thumbnail-layer').hide();
	return false;
}

function insertToContent()
{
	var tthis = $(this);
	var type = tthis.attr('class');
	var linkUlr = $(this).attr('title');
	if (type == 'thumbnail') {
		var html = '<img src="' + linkUlr + '" border="0" alt="' + linkUlr + '" />';
	} else {
		var html = '<a href="' + linkUlr + '" target="_blank"/>链接文字</a>';
	}
	
	CKEDITOR.instances.content.insertHtml(html);
}


