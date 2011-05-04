<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/question/list");?>">已回复留言</a></li>
	  <li class="select corner-top cgray"><a href="<?php echo url("my/question/alllist");?>">全部留言</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<?php foreach($data as $row):?>
	<div>
		<span class="fl">店铺名称：<?php echo $row->shop->getNameLinkHtml(0,'_blank');?></span>
		<?php if($row->order->orderSn):?>
		<span class="fl ma-r10px">&nbsp;&nbsp;订单号：<?php echo $row->order->orderSn;?></span>
		<?php endif;?>
		<div class="clear"></div>
	</div>
	<div>我的问题: <?php echo $row->content;?><span class="f10px fi">(<?php echo $row->createDateTimeText;?>)</span></div>
	<?php if($row->reply):?>
	<div><span class="cred">商家回复:</span> <?php echo nl2br(h($row->reply));?><span class="f10px fi">(<?php echo $row->replyDateTimeText;?>)</span></div>
	<?php endif;?>
	<div class="line1px ma-t10px ma-b10px"></div>
	<?php endforeach;?>
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
	<div class="ma-t10px ma-b10px"></div>
</div>