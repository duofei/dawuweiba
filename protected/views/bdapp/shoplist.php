<!-- 搜索 begin -->
<?php echo CHtml::form(aurl('bdapp/goodsSearch'), 'get');?>
<div class="sousuo2">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input02"  />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索美食" class="chaxun2" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<!-- 搜索 end -->

<div style="width:493px; margin:0 auto;">
<?php foreach ((array)$data as $k => $v):?>
    <div class="shopbox">
        <div style="float:left;"><a href="<?php echo aurl('bdapp/shop', array('shopid'=>$v['id']));?>"><img src="<?php echo $v['logoUrl'];?>" class="shop-thumbnail" /></a></div>
        <div class="shopboxxq">
            <div>
                <h1 class="f14px cdf3b1c" style="line-height:24px; float:left; margin-right:5px;"><?php echo l($v['shop_name'], aurl('bdapp/shop', array('shopid'=>$v['id'])));?></h1>
                <!--<div style="float:left; line-height:24px;">距离您少于<?php echo $v['distanceText'];?></div>-->
        		<div class="clear"></div>
            </div>
            <div>
                <div class="slping">
                    <ul class="fr lh20px ma-t5px ma-r20px shop-rating" sizcache="1" sizset="20">
                        <li>
                        	<div class=fl>服务：</div>
                        	<div class="fl star-big-gray ma-t5px">
								<div style="width:<?php echo $v['serviceStarWidth'];?>px" class=star-big-color></div>
                       		</div>
                        	<div class=clear></div>
                        </li>
                        <li>
                        	<div class=fl>口味：</div>
                        	<div class="fl star-big-gray ma-t5px">
                          		<div style="width:<?php echo $v['tasteStarWidth'];?>px" class=star-big-color></div>
                        	</div>
                        	<div class=clear></div>
                        </li>
                    </ul>
                </div>
				<img src="<?php echo $v['isOpening'] ? resBu('baidu/img/yingye.png') : resBu('baidu/img/xiuxi.png');?>" /></div>
                <p>主营：<?php echo $v['tagsText'];?> </p>
                <p>送餐电话：<?php echo $v['telphone'];?></p>
                <p>起送条件：<?php echo $v['transport_condition'];?></p>
          </div>
    </div>
<?php endforeach;?>
    <div class="megas512"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
    <div class="spaceline"></div>
 </div>
