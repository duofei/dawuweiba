<h2 class="pa-l10px cred f16px bline lh30px">优惠信息</h2>
<?php if (count($data) > 0):?>
<?php foreach ((array)$data as $v):?>
<a name="$v->id"></a>
<div class="bline lh24px m10px pa-b10px f14px bgcolor">
	<p class="bg-icon arrows pa-l20px" ><?php echo $v->shop->nameLinkHtml;?>&nbsp;&nbsp;&nbsp;&nbsp;截止日期：<?php echo $v->endDateText;?> </p>   
	<p class="indent28px"><?php echo h($v->content);?></p> 
</div>
<?php endforeach;?>
<?php else:?>
<div class="lh24px m10px f14px">当前位置无优惠信息</div>
<?php endif;?>

<script type="text/javascript">
$(function(){
    $('div.bgcolor').hover(function(){
    	$(this).addClass('bg-gray')
    },function(){
    	$(this).removeClass('bg-gray')
    });
});
</script> 