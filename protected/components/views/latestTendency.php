<h3 class="f16px cred ma-t10px pa-l10px">最新动态</h3>
<?php foreach($list as $useraction):?>
<div class="cgray ma-t5px pa-l10px pa-r10px">
	<p>[<?php echo $useraction->shortCreateTimeText;?>]</p>
	<p><?php echo $useraction->content;?></p>
</div>
<?php endforeach;?>