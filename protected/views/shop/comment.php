<div class="pa-10px">
<?php if($list):?>
	<?php foreach ((array)$list as $row):?>
	<div class="bline lh24px m10px pa-b5px bgcolor">
	    <p>
	    	<span class="cred f14px"><?php echo $row->user->screenName;?></span>
	    	<span class="f12px cgray"><?php echo $row->createDateTimeText;?></span>
	    </p>    
	    <p class="f12px">
	    	<?php echo nl2br(h($row->content));?>
	    	<?php if($row->order_id):?>
	    	&nbsp;&nbsp;订单号：<?php echo $row->order->orderSn;?>
	    	<?php endif;?>
	    </p>
	    <?php if($row->reply):?>
	   	<div class="m5px ma-l10px border-dashed pa-5px pa-l10px">
	    	<p>
	    		<span class="cred f14px">商铺回复</span>
	    		<span class="f12px cgray"><?php echo $row->replyDateTimeText;?></span>
	    	</p>
	    	<p class="f12px"><?php echo nl2br(h($row->reply));?></p>
		</div>
		<?php endif;?>
	</div>
	<?php endforeach;?>
	<div class="pages">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
<?php else:?>
<div class="ac m10px f14px">目前还没有用户留言！</div>
<?php endif;?>
	<?php if(!user()->isGuest):?>
	<?php echo CHtml::form(url('shop/newComment',array('shopid'=>$model->shop_id)),'post',array('name'=>'postNewComment'));?>
	<div class="m10px f14px">
		<div><?php echo CHtml::activeTextArea($model, 'content', array('class'=>'feedback-content', 'tabIndex'=>1));?></div>
		<div class="ma-t10px">
		验证码：
		<?php echo CHtml::activetextField($model ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
		<?php $this->widget('CCaptcha',array(
			'captchaAction' => 'captcha',
			'showRefreshButton' => true,
			'buttonLabel' => '换一个',
			'clickableImage' => true,
			'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
		));?>
		</div>
		<div class="ma-t10px"><?php echo CHtml::activeHiddenField($model, 'shop_id') ?><?php echo CHtml::submitButton('发 表', array('class'=>'btn-two', 'tabIndex'=>3));?></div>
	</div>
	<?php echo CHtml::endForm();?>
	<?php endif;?>
</div>