<h2 class="pa-l10px cred f16px bline lh30px">反馈留言</h2>
<?php foreach ((array)$feedback as $v):?>
<div class="bline lh24px m10px pa-b5px bgcolor">
    <p class="bg-icon arrows pa-l20px">
    	<span class="cred f14px"><?php echo $v->user->screenName;?></span>
    	<span class="ma-l10px f12px cgray"><?php echo $v->createDateTimeText;?></span>
    	<?php if(!user()->isGuest):?>
    	<span class="cred f12px"><a href="<?php echo url('feedback/reply', array('id'=>$v->id));?>" class="f12px">[回复本文]</a></span>
    	<?php endif;?>
    </p>
    <p class="f14px"><?php echo nl2br(h($v->content));?></p>
    <?php foreach ((array)$v->reply as $r):?>
    <div class="m10px ma-l20px border-dashed pad10px">
    	<p>
    		<span class="cred f14px"><?php echo $r->user->screenName;?></span>
    		<span class="ma-l10px f12px cgray"><?php echo $r->createDateTimeText;?></span>
    	</p>
    	<p class="f14px"><?php echo nl2br(h($r->content));?></p>
    </div>
    <?php endforeach;?>
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
<?php if(user()->isGuest):?>
	<div class="m10px ma-t20px f14px">想要留言？请先<?php echo l('登录', user()->loginUrl);?></div>
<?php else:?>
<?php echo CHtml::form(url('feedback/create'), 'post');?>
<div class="m10px f14px">
	<div><?php echo CHtml::activeTextArea($model, 'content', array('class' => 'feedback-content', 'tabIndex'=>1));?></div>
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
	<div class="ma-t10px"><?php echo CHtml::submitButton('发 表', array('class'=>'btn-two','tabIndex'=>3));?></div>
</div>
<?php echo CHtml::endForm();?>
<?php endif;?>