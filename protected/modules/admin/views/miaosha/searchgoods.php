<?php if ($goods) :?>
<table  class="tabcolor list-tbl ma-b5px" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="30"></th>
        <th class="al">商品名称</th>
        <th class="al">商品价格</th>
        <th class="al">商品介绍</th>
    </tr>
<?php foreach ($goods as $g) :?>
	<tr>
		<td class="ac"><input type="checkbox" name="goods_id[]" value="<?php echo $g->id;?>" <?php echo in_array($g->id, $checkArray) ? 'checked' : '';?> /></td>
		<td><?php echo $g->name;?></td>
		<td><?php echo $g->goodsModel->wm_price;?></td>
		<td><?php echo $g->goodsModel->desc;?></td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
<div>此商铺下没有商品</div>
<?php endif;?>
