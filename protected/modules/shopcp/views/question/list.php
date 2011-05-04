<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/question/noreply");?>">未回复留言</a></li>
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/question/list");?>">全部留言</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/question/commonreply");?>">常用回复</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
	<?php if (count($shopComment) > 0):?>
        <?php foreach ($shopComment as $key=>$val):?>
        <div>
        	<span class="ma-r20px">用户：<?php echo $val->user->username;?></span>
            <span class="ma-r20px">订单号：<?php echo $val->order->orderSn;?></span>
        	<?php if (!$val->reply_time):?><span class="fr ma-r10px"><a href="<?php echo url('shopcp/question/reply', array('qid'=>$val->id))?>"><span class="f14px color">回复留言</span></a></span><?php endif;?>
        </div>  
    	<p>留言内容：<?php echo h($val->content)?> &nbsp;&nbsp;<span class="fi cgray f10px"><?php echo $val->shortCreateDateTimeText;?></span></p>
    	<?php if ($val->reply_time):?><p><span style="color:red">回复内容：<?php echo h($val->reply)?></span> &nbsp;&nbsp;<span class="fi cgray f10px"><?php echo $val->shortReplyDateTimeText;?></span></p><?php endif;?>
    	<div class="line1px ma-b5px ma-t5px"></div>
    	<?php endforeach;?>
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
    <?php else:?>
    	<div>您目前没有用户留言</div>
    <?php endif;?>
    </div>
</div>