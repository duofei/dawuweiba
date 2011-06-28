<?php echo CHtml::beginForm(url('admin/tuannav/Edit'),'post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">标题：</td>
        <td><?php echo CHtml::activeTextArea($tuannav, 'title', array('cols'=>'80', 'rows'=>'3')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">内容：</td>
        <td><?php echo CHtml::activeTextArea($tuannav, 'content', array('cols'=>'80', 'rows'=>'8')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">分类：</td>
        <td><?php echo CHtml::activeRadioButtonList($tuannav, 'category_id', CHtml::listData($category, 'id', 'name'), array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">连接url：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'url', array('class'=>'txt', 'style'=>'width: 670px;')); ?> &nbsp; <?php echo l('打开网址', $tuannav->url, array('target'=>'_blank'));?></td>
    </tr>
    <tr>
        <td width="120" class="ar">图片url：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'image', array('class'=>'txt', 'style'=>'width: 670px;')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">来源：</td>
        <td><?php echo CHtml::activeRadioButtonList($tuannav, 'source_id', CHtml::listData($tuandata, 'id', 'name'), array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">团购价：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'group_price', array('class'=>'txt')); ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">折扣：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'discount', array('class'=>'txt')); ?></td>
    </tr>
    --><tr>
        <td width="120" class="ar">原价：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'original_price', array('class'=>'txt')); ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">售出件数：</td>
        <td><?php echo CHtml::activeTextField($tuannav, 'sell_num', array('class'=>'txt')); ?></td>
    </tr>
    -->
    <tr>
    	<td class="ar">剩余时间：</td>
    	<td>
			<?php echo CHtml::textField('d', intval($tuannav->endTime['d']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 天
			<?php echo CHtml::textField('h', intval($tuannav->endTime['h']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 小时
			<?php echo CHtml::textField('i', intval($tuannav->endTime['i']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 分钟
			&nbsp;&nbsp;&nbsp;&nbsp;<?php echo CHtml::checkBox('editTime', false);?> 修改剩余时间
		</td>
    </tr>
    <tr>
    	<td class="ar">状态：</td>
    	<td><span><?php echo CHtml::activeRadioButtonList($tuannav, 'state', Tuannav::$states, array('separator'=>'</span> &nbsp; <span>')); ?></span></td>
    </tr>
</table>
<input type="hidden" name="referer" value="<?php echo CdcBetaTools::getReferrer();?>" />
<input type="hidden" value="<?php echo $tuannav->id;?>" name="id">
    <?php
        $this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'submit',
				'caption' => '提 交',
			)
		);
	?>
<?php echo CHtml::endForm();?>
  <?php echo user()->getFlash('errorSummary'); ?>