<h2 class="pa-l10px cred f16px bline lh30px">回复反馈留言</h2>
 
<div class="bline lh24px m10px pa-b5px bgcolor">
    <p class="bg-icon arrows pa-l20px">
    	<span class="cred f14px"><?php echo $feedback->user->screenName;?></span>
    	<span class="ma-l10px f12px cgray"><?php echo $feedback->createDateTimeText;?></span>
    	<span class="cred f12px"><a href="<?php echo url('feedback/reply', array('id'=>$feedback->id));?>" class="f12px">[回复本文]</a></span>
    </p>    
    <p class="f14px"><?php echo nl2br(h($feedback->content));?></p>
    <?php foreach ((array)$feedback->reply as $r):?>
    <div class="m10px ma-l20px border-dashed pad10px">
    	<p>
    		<span class="cred f14px"><?php echo $r->user->screenName;?></span>
    		<span class="ma-l10px f12px cgray"><?php echo $r->createDateTimeText;?></span>
    	</p>
    	<p class="f14px"><?php echo nl2br(h($r->content));?></p>
    </div>
    <?php endforeach;?>
</div>

<?php echo CHtml::form(url('feedback/create'), 'post');?>
<div class="m10px f14px">
	<div>
		<?php echo CHtml::activeTextArea($model, 'content', array('class' => 'feedback-content', 'tabIndex'=>1));?>
		<?php echo CHtml::activeHiddenField($model, 'post_id'); ?>
	</div>
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
	<div class="ma-t10px"><?php echo CHtml::submitButton('发 表', array('class'=>'btn-two', 'tabIndex'=>3));?></div>
</div>
<?php echo CHtml::endForm();?>