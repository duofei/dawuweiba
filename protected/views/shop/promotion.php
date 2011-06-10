<div class="f14px pa-20px">
<?php if ($list):?>
<?php foreach((array)$list as $row): ?>
<div class="border-dashed pa-10px lh24px">
	<?php echo nl2br(h($row->content));?> 截止日期：<?php echo $row->endDateText;?>
</div>
<div class="space10pxline"></div>
<?php endforeach;?>
<?php else:?>
<div class="ac f14px">目前还没有优惠信息！</div>
<?php endif;?>
</div>