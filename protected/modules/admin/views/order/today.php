 <?php if ($order) :?>
<table  class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="0">
    <tr class="title">
        <th class="al" width="200">订单号</th>
        <th class="al" width="60"></th>
        <th class="al" width="60">收货人</th>
        <th class="al" width="90">电话号码</th>
        <th class="al">送餐地址</th>
        <th class="al" width="50">配送费</th>
        <th class="al" width="60">订单总价</th>
        <th class="al" width="50">已付款</th>
        <th class="al" width="50">应收款</th>
        <th class="al" width="70">餐厅类型</th>
        <th class="al" width="70">订单状态</th>
    </tr>
</table>
  <?php foreach ($order as $key=>$val) :?>
  <div class="order-look <?php if ($key==0){ echo 'border-black ma-b10px';}?>">
  <table class="list-tbl bline <?php if ($key==0){ echo 'line divbg1 order-title'; }?>" width="100%" cellspacing="0">
	  <tr>
	    <td width="200">
	    	<?php echo $val->orderSn?>-
	    	<span class="cgray"><?php echo $val->shop->shop_name;?></span>
	    	<span><a href="<?php echo url('admin/shop/setSession', array('id'=>$val->shop_id));?>" target="_blank">管理</a></span>
	    	<?php if($val->buy_type==Shop::BUYTYPE_PRINTER):?>
	    	<br />打印机状态：<?php echo $val->shop->printer->getOrderStateHtml();?>
	    	<?php endif;?>
	    </td>
		<td width="60"><a href=""><span class="look color f14px">查看订单</span></a></td>
		<td width="60"><?php echo l(h($val->consignee ? $val->consignee : $val->user->username), url('admin/user/info', array('id'=>$val->user->id)));?></td>
	    <td width="90"><?php echo h($val->telphone)?><br /><?php echo h($val->mobile);?></td>
	    <td class="pa-r10px">
	    	<?php if(preg_match("/^([0-9\.]+),([0-9\.]+)$/", $val->address, $match)):?>
	    	<?php echo l(h($val->address), url('shop/list', array('lat'=>$match[1], 'lon'=>$match[2])), array('target'=>'_blank')); ?>
	    	<?php else:?>
	    	<?php echo h($val->address)?>
	    	<?php endif;?>
	    </td>
	    <td width="50">&yen;<?php echo $val->dispatchingAmountPrice;?></td>
	    <td width="60">&yen;<?php echo $val->amountPrice;?></td>
	    <td width="50">&yen;<?php echo $val->paidAmountPrice;?></td>
	    <td width="50">&yen;<?php echo $val->dueAmountPrice;?></td>
	    <td width="70"><?php echo $val->shop->categoryText?>(<?php echo $val->shop->buyTypeText;?>)</td>
	    <td width="70"><?php echo $val->statusText?></td>
	  </tr>
 </table>
  <div class="look <?php if ($key!=0){ echo 'none'; }?>">
  <table class="ma-t10px ma-b10px ma-l10px" width="90%" >
        <tr class="fb">
            <td width="160">商品名称</td>
            <td width="50">数量</td>
            <td width="70">单价</td>
            <td width="300">小计</td>
        </tr>
        <?php if ($val->orderGoods) : foreach ($val->orderGoods as $k=>$v) :?>
        <tr>
            <td><?php echo $v->goods_name?></td>
            <td><?php echo $v->goods_nums?></td>
            <td><?php echo $v->goodsPrice?></td>
            <td><?php echo $v->goodsAmount?></td>
        </tr>
        <?php endforeach;endif;?>
    </table>
    <div class="pa-l10px tline divbg1 order-title lh30px">
    <span class="ma-r20px">下单时间:<?php echo $val->shortCreateDateTimeText;?></span>
    <span class="ma-r10px">备注:<?php echo $val->message;?></span>
    </div>
    </div><!--end look-->
  </div>
  <?php endforeach;?>
 <?php else:?>
  <div>您目前没有订单</div>
  <?php endif;?>
 	<div class="pages ar">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '翻页',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
<script type="text/javascript">
$(function() {
    $("span.look").toggle(function(){
	 	$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").toggleClass("line divbg1 order-title");
	 	$(this).parents("div.order-look").toggleClass("border-black ma-b10px");
	},function(){
		$(this).parents("table").next(".look").toggle();
	  	$(this).parents("table").toggleClass("line divbg1 order-title");
	 	$(this).parents("div.order-look").toggleClass("border-black ma-b10px");
	})
});
</script>