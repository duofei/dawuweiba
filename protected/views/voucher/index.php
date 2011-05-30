<h2 class="pa-l10px cred f16px lh30px">优惠券</h2>
<?php if (count($list) > 0):?>
<?php $shopid = 0;?>
<?php foreach ((array)$list as $v):?>
	<?php if($shopid!=$v->shop_id):?>
	<p class="bg-icon arrows pa-l20px lh24px f14px tline"><span class="fr toggle cursor cgray">隐藏</span><?php echo $v->shop->nameLinkHtml;?></p>
	<?php endif;?>
	<div class="pa-l20px ma-t5px ma-b5px" lang="div"><?php echo CHtml::image(sbu($v->img));?> <input type="checkbox" value="<?php echo $v->id;?>" name="id" /></div>
	<?php $shopid = $v->shop_id;?>
<?php endforeach;?>
	<div class="ar f14px lh30px tline">选择后进行打印 <input type="button" value=" 马上打印 " id="button" /></div>
<?php else:?>
<div class="lh24px m10px f14px">还没有优惠券</div>
<?php endif;?>

<script type="text/javascript">
$(function(){
    $('.toggle').click(function(){
    	var s = true;
    	if($(this).html() == '隐藏') {
			$(this).parent().nextAll().each(function(){
				if($(this).attr('lang') == 'div' && s) {
					$(this).hide();
				} else {
					s = false;
				}
			});
			$(this).html('显示')
    	} else {
    		$(this).parent().nextAll().each(function(){
				if($(this).attr('lang') == 'div' && s) {
					$(this).show();
				} else {
					s = false;
				}
			});
    		$(this).html('隐藏')
    	}
    });
    $('#button').click(function(){
		var url = "<?php echo url('voucher/print');?>";
		var id = 0;
		$("input[type='checkbox']:checked").each(function(){
			id += ',' + $(this).val();
		});
		if(id != 0) {
			location.href = url + '?id=' + id;
		} else {
			alert('请选择要打印的优惠券');
		}
    });
});
</script>