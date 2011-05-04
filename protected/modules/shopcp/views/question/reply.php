<?php echo CHtml::beginForm('', 'post', array('name'=>'add'));?>
<div id="reply">
	<div class="fi ma-t10px ma-b10px">问题：<?php echo $comment->content;?></div>
	<div class="line1px ma-b10px"></div>
    <div id="commonReply">
    	<h3>常用回复：</h3>
        <?php foreach ((array)$commonreply as $key=>$val) :?>
        <p><input type="checkbox" id="<?php echo $val->id?>" value="<?php echo $val->content?>"><label for="<?php echo $val->id?>">&nbsp;<?php echo h($val->content);?></label></p>
        <?php endforeach;?>
    </div>
    <?php echo CHtml::TextArea('ShopComment[reply]', $comment->reply, array('cols'=>'90', 'rows'=>'5'));?>
</div>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '回复留言',
	)
);
?>
<?php echo CHtml::endForm();?>
 <?php echo user()->getFlash('errorSummary'); ?>
<script type="text/javascript">
$(function(){
	$(":checkbox").click(function(){
		if ($(this).attr('checked')) {
			$("#ShopComment_reply").val($("#ShopComment_reply").val() + $(this).val() + "\n");
		}
		$("#ShopComment_reply").focus();
	});
});
</script>