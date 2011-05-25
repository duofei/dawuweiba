<div class="f14px pa-20px">
<?php if ($list):?>
<?php foreach((array)$list as $row): ?>
<div class="border-dashed pa-10px lh24px ac">
	<?php echo CHtml::image(sbu($row->img));?>
	<br />
	<span class="cblack"><?php echo l('马上打印', url('voucher/print', array('id'=>$row->id)), array('target'=>'_blank'));?></span>
</div>
<div class="space10pxline"></div>
<?php endforeach;?>
<?php else:?>
<div class="ac f14px">目前还没有优惠券！</div>
<?php endif;?>
</div>