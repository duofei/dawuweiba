<?php $split = false;?>
<?php foreach ((array)$shops as $k => $v):?>
<?php if($v->buy_type == Shop::BUYTYPE_PRINTER) $split = true;?>
<?php if($split && $v->buy_type != Shop::BUYTYPE_PRINTER):?>
<div class="clear"></div>
<div class="bline tline ma-b10px" style="background:#cccccc; height:100px;"><img src="<?php echo resBu('images/flow.jpg');?>" /></div>
<?php $split = false;?>
<?php endif;?>
<div class="wm-shop fl m10px" tags="<?php echo $v->filterTags;?>">
    <div class="shop-thumbnail fl ma-r10px relative">
    	<div><?php echo $v->logoLinkHtml;?></div>
    	<?php echo $v->isMuslimIcon;?>
    	<?php echo $v->isOpeningHtml;?>
    </div>
    <div class="shop-summary fl">
    	<dl class="lh20px">
        	<dd class="ma-b5px"><h1 class="f14px">
        	    <?php echo $v->getNameLinkHtml(30);?>
        	    <?php echo $v->isNewIcon;?>
        	    <?php echo $v->isSanitaryIcon;?>
        	    <?php echo $v->isCommercialIcon;?>
        	    <?php echo $v->isSignerIcon;?>
        	    <?php echo $v->isBcshopIcon;?>
        	    <?php echo $v->isVoucherIcon;?>
    		</h1></dd>
    		<?php if ($groupon):?>
    		<dt>达成条件：同楼所有订单总额达到<span class="cyellow"><?php echo (int)$v->group_success_price;?></span>元</dt>
    		<dt>目前已经订餐的总额：<span class="cyellow"><?php echo $chae = $v->getBuildingGrouponAmount($building->id);?>元</span></dt>
    		<dt>目前还差<span class="cyellow"><?php echo (int)$v->group_success_price - $chae;?>元</span>才能达成同楼订餐</dt>
    		<?php else:?>
        	<!-- <dt>方式：<?php echo $v->buyTypeText;?></dt> -->
        	<dt>距离您：<span style="color:#40a8ca;"><?php echo $v->distanceText;?></span></dt>
        	<dt>主营：<?php echo $v->tagsText;?></dt>
        	<dt>地址：<?php echo $v->address;?></dt>
        	<?php endif;?>
    	</dl>
    	<div class="fl ma-t10px shop-rating">
            <div class="lh12px ma-b5px" title="<?php echo $v->serviceAverageMark;?>">
            	<div class="fl">服务：</div>
            	<div class="fl star-big-gray">
                	<div class="star-big-color" style="width:<?php echo $v->serviceStarWidth;?>px"></div>
           	 	</div><div class="clear"></div>
        	</div>
            <div class="lh12px" title="<?php echo $v->tasteAverageMark;?>">
                <div class="fl">口味：</div>
                <div class=" fl star-big-gray">
                    <div class="star-big-color" style="width:<?php echo $v->tasteStarWidth;?>px"></div>
                </div><div class="clear"></div>
            </div>
    	</div>
    	<div class="fr ma-t10px ma-r10px">
        <?php
            $label = $groupon ? '同楼订餐' : '在线菜单';
            echo $v->getMenuBtnHtml($label);
        ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php
$this->beginWidget('WmCornerBox', array(
	'htmlOptions' => array('class'=>'corner-gray wm-shop-tip absolute none'),
));?>
<ul class="lh20px">
	<li class="fb">
		<div class="fl ma-r5px f14px"><?php echo $v->nameLinkHtml;?></div>
		<div class="star-big-gray fl ma-t5px"><div class="star-big-color"></div></div>
		<div class="clear"></div>
	</li>
	<li><?php echo $v->businessStateText;?></li>
	<?php if ($v->transport_time):?><li>送餐时间：<?php echo $v->transport_time;?></li><?php endif;?>
	<?php if ($v->matchTransportCondition):?><li>起送条件：<?php echo $v->matchTransportCondition;?></li><?php endif;?>
	<?php if ($v->desc):?><li>餐厅介绍：<?php echo $v->desc;?></li><?php endif;?>
	<?php if ($v->announcement && 0):?><li class="cred"><?php echo $v->announcement;?></li><?php endif;?>
</ul>
<?php $this->endWidget();?>
<?php endforeach;?>
<div class="clear"></div>

<script type="text/javascript">
$(function(){
    $('.wm-shop .shop-logo').mouseover(function(e){
    	var position = $(this).parents('.wm-shop').position();
    	var top = position.top + 10;
    	var left = position.left + 120;
    	$(this).parents('.wm-shop').next('.wm-shop-tip').css({'top':top, 'left':left}).show();
    });
    $('.wm-shop .shop-logo').mouseout(function(){$(this).parents('.wm-shop').next('.wm-shop-tip').hide();});
});
</script>