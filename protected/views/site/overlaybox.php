<div style="width:950px; height:390px;">
<?php echo CHtml::image(resBu('images/opening-img.png'), null, array('usemap'=>"#Map"));?>
</div>
<map name="Map" id="Map">
  <area shape="rect" coords="604,331,700,364" href="<?php echo url('shop/checkin');?>" />
  <area shape="rect" coords="822,334,895,364" href="<?php echo url('site/signup');?>" />
</map>