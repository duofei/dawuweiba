<?php if ($goods_list) :?>
<?php echo CHtml::beginForm(url('shopcp/goods/deletemore'), 'post');?>
<table  class="tabcolor list-tbl" width="100%" id="goods-list">
    <tr class="title">
    	<th width="15"></th>
        <th class="al">商品名称</th>
        <th class="al" width="120">商品分类</th>
        <th class="al" width="50"><?php echo $sort->link('wm_price', '外卖价');?></th>
        <?php if ($_SESSION['shop']->is_group):?><th class="al" width="50">团购价</th><?php endif;?>
        <th class="al" width="50"><?php echo $sort->link('state', '状态');?></th>
        <th class="al" width="120">操作</th>
    </tr>
  <?php foreach ((array)$goods_list as $k=>$v) :?>
  <tr><td colspan="7"></td></tr>
  <tr class="divbg1"><th class="al" colspan="7"><h4><?php echo $k;?></h4></th></tr>
  <?php foreach ((array)$v as $key=>$val):?>
  <tr>
  	<td>
  	<?php if($_SESSION['shop']->state != Shop::STATE_VERIFY):?>
  		<input type="checkbox" name="goodsid[]" value="<?php echo $val->id;?>" />
  	<?php endif;?>
  	</td>
    <td><div class="pictext"><?php echo $val->nameLinkHtml;?><?php if ($val->pic){ ?>&nbsp;&nbsp;[<span style="color: orange">图</span>]</div><div class="pic none showbox"><?php echo $val->picHtml?></div><?php }?></td>
    <td><?php echo $val->foodGoods->goodsCategory->name?></td>
    <td class="al"><?php echo $val->foodGoods->wmPrice?>元</td>
    <?php if ($_SESSION['shop']->is_group):?><td class="al"><?php echo $val->foodGoods->groupPrice;?>元</td><?php endif;?>
    <td class="al state<?php echo $val->state;?>"><?php echo $val->stateText?></td>
    <td class="al"><a href="<?php echo url('shopcp/goods/edit', array(id=>$val->id))?>"><span class="color">编辑</span></a>
    <?php if ($val->state != Goods::STATE_SELL) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>'1'))?>"><span class="color">上架</span></a>
    <?php endif;?>
    <?php if ($val->state != Goods::STATE_NOSELL) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>'2'))?>"><span class="color">下架</span></a>
    <?php endif;?>
    <?php if ($val->state != Goods::STATE_SELLOUT) :?>
    	<a href="<?php echo url('shopcp/goods/state', array(id=>$val->id, state=>'3'))?>"><span class="color">售完</span></a>
    <?php endif;?>
    <?php if($_SESSION['shop']->state != Shop::STATE_VERIFY):?>
    	<?php echo l('删除', url('shopcp/goods/delete', array('id'=>$val->id)), array('onclick'=>'return confirm("确定要删除吗？");'));?>
    <?php endif;?>
    </td>
  </tr>
  <?php endforeach;?>
  <?php endforeach;?>
  <?php if($_SESSION['shop']->state != Shop::STATE_VERIFY):?>
  <tr><td colspan="7">
  	<a href="javascript:void(0);" onclick="selectAll()">全选</a>/<a href="javascript:void(0);" onclick="counterSelect()">反选</a>
  	<?php echo CHtml::submitButton('删除', array('onclick'=>'return confirm("确定要删除吗？");'));?>
  </td></tr>
  <?php endif;?>
</table>
<?php echo CHtml::endForm();?>
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

function selectAll() {
	$("#goods-list input[type=checkbox]").attr('checked', 'checked');
}

function counterSelect() {
	$("#goods-list input[type=checkbox]").each(function(){
		if($(this).attr('checked')) {
			$(this).attr('checked', '');
		} else {
			$(this).attr('checked', 'checked');
		}
	});
}
</script>