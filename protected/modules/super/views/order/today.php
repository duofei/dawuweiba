 <?php if ($ordercount) :?>
<table  class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="1">
    <tr class="title">
        <th class="al" width="100">城市</th>
        <th class="al" width="100">今日订单数</th>
        <th class="al" width="*">今日订单额</th>
    </tr>
  <?php foreach ($ordercount as $key=>$val) :?>
	  <tr>
	    <td><?php echo $key?></td>
	    <td><?php echo $val['count']?></td>
	    <td><?php echo $val['amount']?>元</td>
	  </tr>
  <?php endforeach;?>
</table>
<?php endif;?>