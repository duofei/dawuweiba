<?php echo CHtml::beginForm(url('admin/tuannav/create'),'post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">标题：</td>
        <td><?php echo CHtml::textArea('Tuannav[title]', $tuannav_post['title'], array('cols'=>'80', 'rows'=>'3')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">内容：</td>
        <td><?php echo CHtml::textArea('Tuannav[content]', $tuannav_post['content'], array('cols'=>'80', 'rows'=>'8')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">分类：</td>
        <td><?php echo CHtml::radioButtonList('Tuannav[category_id]', $tuannav_post['category_id']?$tuannav_post['category_id']:'1', CHtml::listData($category, 'id', 'name'), array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">连接url：</td>
        <td><?php echo CHtml::textField('Tuannav[url]', $tuannav_post['url'], array('class'=>'txt', 'style'=>'width:600px;')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">图片url：</td>
        <td><?php echo CHtml::textField('Tuannav[image]', $tuannav_post['image'], array('class'=>'txt', 'style'=>'width:600px;')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">来源：</td>
        <td><?php echo CHtml::radioButtonList('Tuannav[source_id]', $tuannav_post['source_id']?$tuannav_post['source_id']:'1', CHtml::listData($tuandata, 'id', 'name'), array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">团购价：</td>
        <td><?php echo CHtml::textField('Tuannav[group_price]', $tuannav_post['group_price'], array('class'=>'txt')); ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">折扣：</td>
        <td><?php echo CHtml::textField('Tuannav[discount]', $tuannav_post['discount'], array('class'=>'txt')); ?></td>
    </tr>
    --><tr>
        <td width="120" class="ar">原价：</td>
        <td><?php echo CHtml::textField('Tuannav[original_price]', $tuannav_post['original_price'], array('class'=>'txt')); ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">售出件数：</td>
        <td><?php echo CHtml::textField('Tuannav[sell_num]', $tuannav_post['sell_num'], array('class'=>'txt')); ?></td>
    </tr>
    -->
    <tr>
    	<td class="ar">剩余时间：</td>
    	<td>
			<?php echo CHtml::textField('d', intval($tuannav->endTime['d']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 天
			<?php echo CHtml::textField('h', intval($tuannav->endTime['h']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 小时
			<?php echo CHtml::textField('i', intval($tuannav->endTime['i']), array('class'=>'txt ar', 'style'=>'width:24px;'))?> 分钟
		</td>
    </tr>
    <tr>
    	<td class="ar">状态：</td>
    	<td><span><?php echo CHtml::radioButtonList('Tuannav[state]', $tuannav_post['state'], Tuannav::$states, array('separator'=>'</span> &nbsp; <span>')); ?></span></td>
    </tr>
</table>
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