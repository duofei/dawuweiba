<?php echo CHtml::beginForm(url('shopcp/promotion/edit'),'post',array('name'=>'add'));?>
<input type="hidden" name="id" value="<?php echo $promotion->id?>">
<table class="height30px" width="600px">
    <tr>
        <td width="80">优惠信息：</td>
        <td width="200"><?php echo CHtml::activeTextArea($promotion, 'content', array('cols'=>'75', 'rows'=>'3')); ?></td>
        </tr>
        <tr>
        <td width="80">结束时间：</td>
        <td width="200">
        <?php
	    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $promotion,
		    'attribute' => 'end_time',
		    'language' => 'zh',
	    	'options' => array(
	    		'dateFormat' => 'yy-mm-dd',
	    		'showAnim'=>'fold',
	    	),
		    'htmlOptions'=>array(
		        'class'=>'txt'
		    ),
		));
		?></td>
		</tr>
		<tr>
        <td><?php 
	        $this->widget('zii.widgets.jui.CJuiButton',
				array(
					'name' => 'submit',
					'caption' => '提 交',
				)
			);
		?></td>
    </tr>

</table>
<?php echo CHtml::endForm();?>
  <?php echo user()->getFlash('errorSummary'); ?>