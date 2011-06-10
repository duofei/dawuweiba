  <?php if ($shopcount) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title">
        <th class="al" width="100">城市</th>
        <th class="al" width="160">营业执照待审核餐厅数</th>
        <th class="al" width="">卫生许可证待审核餐厅数</th>
    </tr>
  <?php foreach ($shopcount as $key=>$val) :?>
	  <tr>
	    <td><?php echo $key?></td>
	    <td><?php echo $val['commercialcount']?></td>
	    <td><?php echo $val['sanitarycount']?></td>
	  </tr>
  <?php endforeach;?>
</table>
  <?php endif;?>