<?php if ($goods_list) :?>
<table  class="tabcolor list-tbl" width="100%">
    <tr class="title">
        <th class="al">商品名称</th>
        <th class="al" width="50"><span class="<?php echo $sortclass['state'];?>"><?php echo $sort->link('state', '状态', array('class'=>'block'));?></span></th>
        <th class="al" width="100">操作</th>
    </tr>
  <?php foreach ((array)CakeGoods::$categorys as $k=>$c):?>
  <tr><td colspan="6"></td></tr>
  <tr class="divbg1"><th class="al" colspan="6"><h4><?php echo $c;?></h4></th></tr>
  <?php foreach ($goods_list as $key=>$val) :?>
	<?php if($val->cakeGoods->category_id == $k):?>
  <tr><td colspan="6"></td></tr>
  <tr>
    <td><div class="pictext"><?php echo $val->nameLinkHtml;?><?php if ($val->pic){ ?>&nbsp;&nbsp;[<span style="color: orange">图</span>]</div><div class="pic none showbox"><?php echo $val->picHtml?></div><?php }?></td>
    <td class="al state<?php echo $val->state;?>"><?php echo $val->stateText?></td>
    <td class="al"><a href="<?php echo url('shopcp/goods/editCake', array(id=>$val->id))?>"><span class="color">编辑</span></a>
    <?php if ($val->state != Goods::STATE_SELL) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>1))?>"><span class="color">上架</span></a>
    <?php endif;?>
    <?php if ($val->state != Goods::STATE_NOSELL) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>2))?>"><span class="color">下架</span></a>
    <?php endif;?>
    <?php if ($val->state != Goods::STATE_SELLOUT) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>3))?>"><span class="color">售完</span></a>
    <?php endif;?>
    </td>
  </tr>
  <?php endif;endforeach;endforeach;?>
</table>

  <?php else:?>
  <div>您目前没有添加商品</div>
  <?php endif;?>
  
  <script type="text/javascript">
$(function() {
    $("div.pictext").mouseover(function(){
	 	$(this).next("div.pic").attr("class","pic showbox");
	})
	$("div.pictext").mouseout(function(){
		$(this).next("div.pic").attr("class","pic none showbox");
	})
});
</script> 