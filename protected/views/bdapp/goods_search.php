<!-- 搜索 begin -->
<?php echo CHtml::form(aurl('bdapp/goodsSearch'), 'get');?>
<div class="sousuo2">
    <div style="float:left; padding-top:2px; padding-left:20px;">
        <input name="kw" type="text" class="input02" value="<?php echo $_GET['kw'];?>" />
    </div>
    <div style="float:right; margin-right:10px;"><input type="submit" value="搜索美食" class="chaxun2" /></div>
    <div class="clear"></div>
</div>
<?php echo CHtml::endForm();?>
<!-- 搜索 end -->

<div style="width:490px; margin:0 auto; background:url(<?php echo resBu('baidu/img/pic22.png');?>) no-repeat; height:48px;"></div>
<div style=" background:url(<?php echo resBu('baidu/img/pic23.png');?>) repeat-y; width:490px; margin:0 auto;">
<?php foreach ((array)$data as $k=>$v):?>
  	<div class="list03">
    	<h1 class="f14px lh30px cc61819" style="text-indent:10px;"><?php echo l($k, aurl('bdapp/shop', array('shopid'=>$v[0]->shop->id)));?></h1>
        <ul>
        <?php foreach ((array)$v as $kk=>$vv):?>
        	<li><img src="<?php echo resBu('baidu/img/pic26.png');?>" /><span><strong class="f14px cc61819"></strong><?php echo $vv->foodGoods->wmPrice;?>元</span>&nbsp;<?php echo $vv->name;?></li>
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
