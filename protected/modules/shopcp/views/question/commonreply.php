<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/question/noreply");?>">未回复留言</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/question/list");?>">全部留言</a></li>
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/question/commonreply");?>">常用回复</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
 <?php echo CHtml::beginForm(url('shopcp/question/create'),'post',array('name'=>'edit'));?>
	<ul>
	<li>请添加常用回复内容，添加后您在给买家回复时出现在回复提示框内，您选择一下就可以回复，不用每回都输入了。</li>  
	<li><h3>添加内容：</h3></li>
	<li><?php echo CHtml::textArea('ShopCommonReply[content]', $ShopCommon->content, array('cols'=>'90', 'rows'=>'5'))?></li>
	<li>
	<?php
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'submit',
			'caption' => '提 交',
		)
	);
	?></li>
	</ul>
	<br />
 <?php echo user()->getFlash('errorSummary'); ?>
 <?php echo CHtml::endForm();?>
	<h3>常用回复显示：</h3>
	<?php if ($commonreply) :?>
	 	<?php foreach ($commonreply as $key=>$val):?>
		<div class="fl width"><?php echo $key . '、' . $val->content?></div> 
		<div class="fl ma-l20px"><a href="<?php echo url('shopcp/question/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a></div>
		<div class="clear"></div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>