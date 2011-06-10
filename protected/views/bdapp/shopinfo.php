<div>
    <div style="width:490px; margin:0 auto; font-size:0px;"><img src="<?php echo resBu('baidu/img/pic20.png');?>" /></div>
    <div class="dpxq">
        <div style="float:left; width:100px; margin-left:10px;">
        	<a href="<?php echo aurl('bdapp/shop', array('shopid'=>$shop['id']));?>"><img src="<?php echo $shop['logoUrl'];?>" class="shop-thumbnail" /></a>
        	<div align="center" style="padding-top:5px;"><img src="<?php echo $shop['isOpening'] ? resBu('baidu/img/yingye.png') : resBu('baidu/img/xiuxi.png');?>" /></div>
        </div>
        <div class="shopboxxq2">
        	<div>
                <h1 class="f14px cdf3b1c" style="line-height:24px; margin-right:5px;"><?php echo $shop['shop_name'];?></h1>
            </div>
            <p>地址：<?php echo $shop['address'];?></p>
            <p>送餐电话：<?php echo $shop['telphone'];?></p>
            <p>送餐时间：<?php echo $shop['transport_time'];?> </p>
            <p>起送条件：<?php echo $shop['transport_condition'];?></p>
            <p>店铺简介：<?php echo $shop['desc'];?> </p>
            <div class="spaceline"></div>
          </div>
          <div class="clear"></div>
    </div>
</div>
<div style="width:490px; margin:0 auto; background:url(<?php echo resBu('baidu/img/pic22.png');?>) no-repeat; height:48px;"></div>
<div style=" background:url(<?php echo resBu('baidu/img/pic23.png');?>) repeat-y; width:490px; margin:0 auto;">
<?php foreach ((array)$goods as $k=>$v):?>
  	<div class="list03">
    	<h1 class="f14px lh30px cc61819" style="text-indent:10px;"><?php echo $k;?></h1>
        <ul>
        <?php foreach ((array)$v as $kk=>$vv):?>
        	<li><span><strong class="f14px cc61819"><?php echo sprintf('%.1f', $vv['wm_price']);?></strong>元</span>&nbsp;<?php echo $vv['name']?><?php if ($vv['picUrl']):?><img class="goods-thumbnail" src="<?php echo resBu('baidu/img/pic26.png');?>" pic="<?php echo $vv['picUrl'];?>" /><?php endif;?></li>
        <?php endforeach;?>
            <div class="clear"></div>
        </ul>
    </div>
<?php endforeach;?>
     <!-- fanye begin -->
    <div class="megas512"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
    <div class="spaceline"></div>
    <!-- fanye end -->
</div>
<div style="width:490px; margin:0 auto; background:url(<?php echo resBu('baidu/img/pic24.png');?>) no-repeat; height:43px;"></div>
<img src="" id="goods-big-thumbnail" class="hide" />

<?php cs()->registerCoreScript('jquery');?>
<script type="text/javascript">
$(function(){
	$('.goods-thumbnail').mouseover(show_thumbnail);
	$('.goods-thumbnail').mouseout(hide_thumbnail);
});
var show_thumbnail = function(event){
	var pic = $(this).attr('pic');
	if (!pic) return false;
	var p = $(this).position();
	var left = p.left - 70;
	var top = p.top + 30;
	$('#goods-big-thumbnail').attr('src', pic).css('left', left).css('top', top).show();
}
var hide_thumbnail = function(event){
	$('#goods-big-thumbnail').hide();
}
</script>