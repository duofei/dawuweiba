<div class="right-nav">
    <ul class="nav">
	  <li class="corner-top cgray"><a href="<?php echo url("my/integral/bcintegral");?>">白吃点使用记录</a></li>
	  <li class="corner-top cgray select"><a href="<?php echo url("my/integral/change");?>">积分换白吃点</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
<?php echo CHtml::beginForm('', 'post');?>
	<table class="list lh30px">
	  <tr>
	    <td class="ar" width="300">您目前的积分：　</td>
	    <td class="al"><?php echo $user->integral;?></td>
	  </tr>
	  <tr>
	    <td class="ar">您目前的白吃点：　</td>
	    <td class="al"><?php echo $user->bcnums;?></td>
	  </tr>
	  <tr>
	    <td class="ar">白吃点与积分的比例：　</td>
	    <td class="al"> 1白吃点 ：1000积分</td>
	  </tr>
	  <tr>
	    <td class="ar">兑换白吃点：　</td>
	    <td class="al">
	    <?php echo CHtml::textField('integral', $integral, array('class'=>'txt', 'style'=>'width:60px', 'id'=>'integral'));?>
	           需要<span id="inum"><?php echo $integral * 1000;?></span>积分
	    </td>
	  </tr>
	  <?php if($error):?>
	  <tr>
	  	<td class="ac cred" colspan="2"><?php echo $error;?></td>
	  </tr>
	  <?php endif;?>
	  <tr>
	    <td class="ac" colspan="2">
		<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'change',
				'caption' => '积分兑换',
			)
		);
		?>
		</td>
	  </tr>
	</table>
<?php echo CHtml::endForm();?>
</div>
<script>
$(function(){
	$('#integral').keyup(function(){
		re = /^[0-9]+$/;
		var num = $(this).val();
		if(!num.match(re)) {
			num = parseInt(num);
			if(isNaN(num)) {
				num = 0;
			}
			$(this).val(num);
		}
		$('#inum').html(num * 1000);
	});
});
</script>