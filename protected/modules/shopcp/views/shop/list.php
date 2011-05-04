<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
		<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/shop/list");?>">商铺列表</a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/shop/create");?>">创建商铺</a></li>
	</ul>
	<div id="index" class="ui-tabs-panel">
	<?php if ($shops) :?>
		<table  class="tabcolor list-tbl ma-b5px" width="100%">
	    <tr class="title">
	        <th class="al">用户名</th>
	        <th class="al">商铺名称</th>
	        <th class="al">商铺地址</th>
	        <th width="60">订餐方式</th>
	        <th width="130">添加时间</th>
	        <th width="60">状态</th>
	        <th width="60">操作</th>
	    </tr>
	  	<?php foreach ($shops as $key=>$shop) :?>
		<tr>
		    <td><?php echo $shop->user->username;?></td>
		    <td <?php if($shop->map_x=='' || $shop->map_region=='') echo "class='cred'";?>><?php echo $shop->shop_name;?></td>
		    <td><?php echo $shop->address;?></td>
		    <td class="ac"><?php echo $shop->buyTypeText;?></td>
		    <td class="ac"><?php echo $shop->shortCreateDateTimeText;?></td>
		    <td class="ac"><?php echo $shop->stateText;?></td>
		    <td class="cred">
		    	<?php //if($shop->state == Shop::STATE_UNSETTLED || $shop->state == Shop::STATE_PSEUDO || $shop->user_id == $shop->yewu_id): ?>
		    		<?php echo l('商铺管理', url('shopcp/shop/setSession', array('id'=>$shop->id)));?>
		    	<?php //endif;?>
		    </td>
		</tr>
		<?php endforeach;?>
		</table>
	<?php else:?>
	  	<div>没有商铺列表</div>
	<?php endif;?>
	   	<div class="pages ar">
		<?php $this->widget('CLinkPager', array(
			'pages' => $pages,
		    'header' => '',
		    'firstPageLabel' => '首页',
		    'lastPageLabel' => '末页',
		    'nextPageLabel' => '下一页',
		    'prevPageLabel' => '上一页',
		));?>
		</div>
  	</div>
</div>