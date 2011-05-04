
<script type="text/javascript">
<!--
$(function(){
	var <?php echo $this->id;?>;
	var settings_object = {
		file_post_name: '<?php echo $this->id;?>',
		movieName: '<?php echo $this->id;?>',
		upload_url :'<?php echo $this->options['upload_url'];?>',
		flash_url : '<?php echo $this->assets;?>SWFUpload/Flash/swfupload.swf',
		post_params: {PHPSESSID:'<?php echo app()->session->sessionID;?>'},
		
		file_size_limit : '<?php echo ($this->options['file_size_limit']) ? $this->options['file_size_limit'] : '20MB'?>',
		file_types : '<?php echo ($this->options['file_types']) ? $this->options['file_types'] : '*.jpg;*.gif;*.png;*.bmp;*.zip;*.rar;*.tar.gz;*.tar;*.bz2'?>',
		file_types_description : '<?php echo ($this->options['file_types_description']) ? $this->options['file_types_description'] : '选择文件'?>',
		file_upload_limit : <?php echo ($this->options['file_upload_limit']) ? $this->options['file_upload_limit'] : 20?>,
		file_queue_limit : <?php echo ($this->options['file_queue_limit']) ? $this->options['file_queue_limit'] : 20?>,
		debug: <?php echo ($this->options['debug']) ? $this->options['debug'] : 'false';?>,
		
		button_placeholder_id : '<?php echo ($this->options['button_placeholder_id']) ? $this->options['button_placeholder_id'] : $this->id;?>',
		button_placeholder : '<?php echo ($this->options['button_placeholder']) ? $this->options['button_placeholder'] : null;?>',
		button_image_url : '<?php echo ($this->options['button_image_url']) ? $this->options['button_image_url'] : $this->assets . 'images/TestImageNoText_65x29.png'?>',
		button_width: <?php echo ($this->options['button_width']) ? $this->options['button_width'] : 65?>,
		button_height: <?php echo ($this->options['button_height']) ? $this->options['button_height'] : 29?>,
		button_text : '<?php echo ($this->options['button_text']) ? $this->options['button_text'] : '<span class="btn">选择文件</span>'?>',
		button_text_style : '<?php echo ($this->options['button_text_style']) ? $this->options['button_text_style'] : '.btn{font-size: 12px; font-weight:bold;}'?>',
		button_text_top_padding: <?php echo ($this->options['button_text_top_padding']) ? $this->options['button_text_top_padding'] : 4?>,
		button_text_left_padding: <?php echo ($this->options['button_text_left_padding']) ? $this->options['button_text_left_padding'] : 3?>,
		button_action: <?php echo ($this->options['button_action']) ? $this->options['button_action'] : 'SWFUpload.BUTTON_ACTION.SELECT_FILES'?>,
		button_window_mode: <?php echo ($this->options['button_window_mode']) ? $this->options['button_window_mode'] : 'SWFUpload.WINDOW_MODE.TRANSPARENT'?>,
		button_cursor: <?php echo ($this->options['button_cursor']) ? $this->options['button_cursor'] : 'SWFUpload.CURSOR.HAND'?>,
		//button_disabled: <?php echo ($this->options['button_disabled']) ? $this->options['button_disabled'] : 'true';?>,
				
		swfupload_loaded_handler: <?php echo ($this->options['swfupload_loaded_handler']) ? $this->options['swfupload_loaded_handler'] : 'cdcSwfuploadLoaded'?>,
		file_dialog_start_handler: <?php echo ($this->options['file_dialog_start_handler']) ? $this->options['file_dialog_start_handler'] : 'cdcFileDialogStart'?>,
		file_queued_handler: <?php echo ($this->options['file_queued_handler']) ? $this->options['file_queued_handler'] : 'cdcFileQueued'?>,
		file_queue_error_handler: <?php echo ($this->options['file_queue_error_handler']) ? $this->options['file_queue_error_handler'] : 'cdcFileQueueError'?>,
		file_dialog_complete_handler: <?php echo ($this->options['file_dialog_complete_handler']) ? $this->options['file_dialog_complete_handler'] : 'cdcFileDialogComplete'?>,
		upload_start_handler: <?php echo ($this->options['upload_start_handler']) ? $this->options['upload_start_handler'] : 'cdcUploadStart'?>,
		upload_progress_handler: <?php echo ($this->options['upload_progress_handler']) ? $this->options['upload_progress_handler'] : 'cdcUploadProgress'?>,
		upload_error_handler: <?php echo ($this->options['upload_error_handler']) ? $this->options['upload_error_handler'] : 'cdcUploadError'?>,
		upload_success_handler: <?php echo ($this->options['upload_success_handler']) ? $this->options['upload_success_handler'] : 'cdcUploadSuccess'?>,
		upload_complete_handler: <?php echo ($this->options['upload_complete_handler']) ? $this->options['upload_complete_handler'] : 'cdcUploadComplete'?>,
		custom_setting: {
			
		}
	};
	
	<?php echo $this->id;?> = new SWFUpload(settings_object);
	$('#container-<?php echo $this->id;?> a.thumbnail').live('mouseover', showThumbnail);
	$('#container-<?php echo $this->id;?> a.thumbnail').live('mouseout', hideThumbnail);
	$('#container-<?php echo $this->id;?> a').live('click', insertToContent);
});
//-->
</script>
<div class="swfupload-container" id="container-<?php echo $this->id;?>">
    <div class="swfupload-float-left"><span id="<?php echo $this->id;?>"></span></div>
    <div class="swfupload-float-left">&nbsp;<input type="button" value="开始上传" class="btn-start-uploads btn" id="btn-start-<?php echo $this->id;?>" disabled="disabled" /></div>
    <div class="swfupload-clear"></div>
    <table width="100%" class="selected-files-<?php echo $this->id;?> swfupload-hide" cellspacing="1" cellpadding="0" border="0">
    	<tr><th>ID</th><th>文件名</th><th>大小</th><th>状态</th></tr>
    </table>
</div>

<div id="thumbnail-layer" class="swfupload-hide"></div>
