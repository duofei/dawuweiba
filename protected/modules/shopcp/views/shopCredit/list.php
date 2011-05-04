<span class="fr ma-r10px"><h3>好评率：<?php echo $num['probability']?>%</h3></span>
<table  class="tabcolor list-tbl border-black tab-bo" width="100%" >
  <tr>
    <td></td>
    <td>最近1周</td>
    <td>最近1个月</td>
    <td>最近6个月</td>
    <td>6个月前</td>
    <td>总计</td>
  </tr>
  <tr>
    <td>好评</td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'week', evaluate=>'1'))?>"><?php echo $num['week_1']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'month', evaluate=>'1'))?>"><?php echo $num['month_1']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'six', evaluate=>'1'))?>"><?php echo $num['six_1']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'before', evaluate=>'1'))?>"><?php echo $num['before_1']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'amount', evaluate=>'1'))?>"><?php echo $num['amount_1']?></a></td>
  </tr>
  <tr>
    <td>差评</td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'week', evaluate=>'0'))?>"><?php echo $num['week_0']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'month', evaluate=>'0'))?>"><?php echo $num['month_0']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'six', evaluate=>'0'))?>"><?php echo $num['six_0']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'before', evaluate=>'0'))?>"><?php echo $num['before_0']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'amount', evaluate=>'0'))?>"><?php echo $num['amount_0']?></a></td>
  </tr>
  <tr>
    <td>总计</td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'week'))?>"><?php echo $num['week']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'month'))?>"><?php echo $num['month']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'six'))?>"><?php echo $num['six']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'before'))?>"><?php echo $num['before']?></a></td>
    <td><a href="<?php echo url('shopcp/shopcredit/creditsort', array(sort=>'amount'))?>"><?php echo $num['amount']?></a></td>
  </tr>
</table>
<br />
<h3>收到的评价</h3>
<table  class="tabcolor list-tbl" width="100%">
    <tr class="title">
        <th class="al" width="30">评价</th>
        <th class="al" width="*">评论</th>
        <th class="al" width="100">评价人</th>
        <th class="al" width="100">订单号</th>
        <th class="al" width="160">评价时间</th>
    </tr>
  <?php if ($credit_list) : foreach ($credit_list as $key=>$val):?>
    <tr <?php if ($key%2 != 0) { echo 'class="divbg1"'; }?>>
        <td><?php echo $val->evaluatesText?></td>
        <td><?php echo $val->comment?></td>
        <td><?php echo $val->order->user->username?></td>
        <td><?php echo $val->order->orderSn?></td>
        <td><?php echo date("Y-m-d H:i",$val->create_time)?></td>
	    </td>
    </tr>
  <?php endforeach; endif;?>
</table>
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