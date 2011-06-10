<?php if ($shops) :?>
<table class="tabcolor list-tbl ma-b5px" cellspacing="1">
    <tr class="title">
    	<th class="ac" width="30"></th>
        <th class="al" width="150">店铺名称</th>
        <th class="al" width="120">所属用户</th>
        <th class="al" width="60">营业状态</th>
        <th class="al" width="60">商铺状态</th>
        <th class="al">商铺地址</th>
    </tr>
<?php foreach ($shops as $key=>$val) :?>
	<tr>
		<td class="ac"><input type="radio" name="shopid" value="<?php echo $val->id;?>" /></td>
		<td><?php echo $val->getNameLinkHtml(0, '_blank');?></td>
		<td><?php echo $val->user->username;?></td>
		<td><?php echo $val->businessStateText;?></td>
		<td><?php echo $val->stateText;?></td>
		<td><?php echo $val->address;?></td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
<div>目前搜索结果</div>
<?php endif;?>