<?php 
cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');
?>
<div class="border-red pa-b20px f14px">
	<div class="groupbuy-title">
     		<h3 class="h3-report lh30px"> 举报不良信息</h3>
    </div>
    
    <div class="pa-l20px pa-t20px pa-b10px lh20px">
    	您要举报 <span class=" color-c60"><?php echo $tuannav->title?></span> <br />
    	（<a href="<?php echo $tuannav->absoluteUrl?>"><?php echo $tuannav->absoluteUrl?></a>）请选择原因：
    </div>
    
<?php echo CHtml::beginForm('','post',array('name'=>'add'));?>
<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
<div class="pa-l30px lh24px">
    	<?php echo CHtml::activeRadioButtonList($model, 'type', TuanReport::$types, array('separator'=>'<br />'))?>
       <p class="ma-t10px lh24px"> 举报说明：(可不填写)</p>
      <?php echo CHtml::activeTextArea($model, 'content', array('cols'=>'60', 'rows'=>'2')); ?>
      <p class="ma-t10px">请留下您的邮箱，以便联系：<?php echo CHtml::activeTextField($model, 'email', array('class'=>'txt')); ?></p>
      <p class="ma-t10px">
	      验证码：
		<?php echo CHtml::textField('validateCode', '', array('class'=>' validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
		<?php $this->widget('CCaptcha',array(
			'captchaAction' => 'captcha',
			'showRefreshButton' => true,
			'buttonLabel' => '换一个',
			'clickableImage' => true,
			'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
		));?>
      </p>
      <input class="ma-t20px ico-post f14px cwhite fb ma-l20px ma-b5px" name="" type="submit" value="提交" />
    <?php echo CHtml::errorSummary($model); ?>
</div>
<?php CHtml::endForm();?>
    <div class="border-top-dot lh20px cgray ma-t20px pa-t10px ma-l20px ma-r20px f12px cgray">    
        <p> 如果您所举报的内容情节严重,请收集详细的材料，发送email至：<span class="cred"> contact@52wm.com</span> 以便我们尽快处理。</p>
    </div>
</div><!--end -->
