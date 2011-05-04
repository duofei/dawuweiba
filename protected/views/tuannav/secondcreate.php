<?php 
cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');
?>
<?php echo CHtml::beginForm(url('tuannav/secondCreate'),'post',array('name'=>'add'));?>
<div class="border-red pa-b10px">
	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 发布信息</h3>
     	</div>
    
    <div class="pa-20px ">
        <table class=" seller-tab" width="70%">
            <tr>
                <td width="15%" class="ar pa-r10px">选择交易类别：</td>
                <td width="85%"><p><?php echo CHtml::activeRadioButtonList($model, 'trade_sort', TuanSecondHand::$trade_sorts, array('separator'=>' '))?><span class="cred">*</span></p>
                 </td>                
            </tr>
            <tr>
                <td class="ar pa-r10px" >选择分类：</td>
                <td ><?php echo CHtml::activeRadioButtonList($model, 'category_id', CHtml::listData($category, 'id', 'name'), array('separator'=>' '))?><span class="cred">*</span>
                </td>                
            </tr>         
            <tr >
                <td class="ar pa-r10px">标题：</td>
                <td ><?php echo CHtml::activeTextField($model, 'title', array('class'=>'txt')); ?><span class="cred">*</span>
                </td>                
            </tr>
            <tr >
                <td class="ar pa-r10px">链接地址：</td>
                <td ><?php echo CHtml::activeTextField($model, 'url', array('class'=>'txt')); ?>(输入您要交易的团购网站的链接地址)<span class="cred">*</span>
                </td>                
            </tr>       
            <tr >
                <td class="ar pa-r10px">详细内容：</td>
                <td ><?php echo CHtml::activeTextArea($model, 'content', array('cols'=>'60', 'rows'=>'2')); ?>
                </td>                
            </tr>
             <tr >
                <td class="ar pa-r10px">交易数量：</td>
                <td ><?php echo CHtml::activeTextField($model, 'nums', array('class'=>'txt')); ?><span class="cred">*</span>
                </td>                
            </tr>
             <tr >
                <td class="ar pa-r10px">交易价格：</td>
                <td ><?php echo CHtml::activeTextField($model, 'price', array('class'=>'txt')); ?><span class="cred">*</span>					
                </td>                
            </tr>
             <tr>
                <td class="ar pa-r10px">联系电话：</td>
                <td ><?php echo CHtml::activeTextField($model, 'mobile', array('class'=>'txt')); ?><span class="cred">*</span>
                </td>                
            </tr>               
            <tr>
            <td class="ar pa-r10px">验证码：</td>
            <td>
		<?php echo CHtml::textField('validateCode', '', array('class'=>' validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
		<?php $this->widget('CCaptcha',array(
			'captchaAction' => 'captcha',
			'showRefreshButton' => true,
			'buttonLabel' => '换一个',
			'clickableImage' => true,
			'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
		));?></td>
            </tr>
        </table>
        <input class=" ico-post f14px cwhite fb ma-t10px ma-l100px ma-b10px" name="" type="submit" value="发&nbsp;帖" />
      <?php echo CHtml::errorSummary($model); ?>
    </div>
</div>
<?php echo CHtml::endForm();?>