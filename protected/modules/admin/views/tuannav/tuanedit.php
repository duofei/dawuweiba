<?php echo CHtml::beginForm(url('admin/tuannav/tuanEdit'),'post',array('name'=>'add'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">名称：</td>
        <td><?php echo CHtml::textField('Tuandata[name]', $tuandata['name'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">电话：</td>
        <td><?php echo CHtml::textField('Tuandata[mobile]', $tuandata['mobile'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">QQ：</td>
        <td><?php echo CHtml::textField('Tuandata[QQ]', $tuandata['QQ'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">邮箱：</td>
        <td><?php echo CHtml::textField('Tuandata[email]', $tuandata['email'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">网址url：</td>
        <td><?php echo CHtml::textField('Tuandata[url]', $tuandata['url'], array('class'=>'txt', 'style'=>'width: 670px;')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">网站logo-url：</td>
        <td><?php echo CHtml::textField('Tuandata[logo]', $tuandata['logo'], array('class'=>'txt', 'style'=>'width: 670px;')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">地址：</td>
        <td><?php echo CHtml::textField('Tuandata[adress]', $tuandata['adress'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">创办者：</td>
        <td><?php echo CHtml::textField('Tuandata[create]', $tuandata['create'], array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">上线时间：</td>
        <td><?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'Tuandata[online_time]',
    'value' => $tuandata['online_time']?$tuandata['online_time']:date(param('formatDate')),
    'language' => 'zh',
    'options'=>array(
		'dateFormat' => 'yy-mm-dd',
        'showAnim'=>'fold',
        'defaultDate' => date(param('formatDate')),
    ),
    'htmlOptions' => array('class'=>'txt'),
));
?></td>
    </tr>
    <tr>
        <td width="120" class="ar">购买类型：</td>
        <td><?php echo CHtml::radioButtonList('Tuandata[buy_type]', $tuandata['buy_type']?$tuandata['buy_type']:'0', TuanData::$buy_types, array('separator'=>' '))?></td>
    </tr>
    <!--<tr>
        <td width="120" class="ar">网站类型：</td>
        <td><?php echo CHtml::radioButtonList('Tuandata[web_type]', $tuandata['web_type']?$tuandata['web_type']:'0', TuanData::$buy_types, array('separator'=>' '))?></td>
    </tr>
    --><tr>
        <td width="120" class="ar">组织频率：</td>
        <td><?php echo CHtml::radioButtonList('Tuandata[post_frequency]', $tuandata['post_frequency']?$tuandata['post_frequency']:'0', TuanData::$post_frequencys, array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">平均购买人数：</td>
        <td><?php echo CHtml::radioButtonList('Tuandata[buy_num]', $tuandata['buy_num']?$tuandata['buy_num']:'0', TuanData::$buy_nums, array('separator'=>' '))?></td>
    </tr>
    <tr>
        <td width="120" class="ar">简介：</td>
        <td><?php echo CHtml::textArea('Tuandata[intro]', $tuandata['intro'], array('cols'=>'80', 'rows'=>'3')); ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">api类型：</td>
        <td><?php echo CHtml::dropDownList('Tuandata[apitype]', $tuandata['apitype'], TuanData::$apitypes, array('empty'=>array(0=>'选择api类型'))); ?> 没有不要选择</td>
    </tr>
    <tr>
        <td width="120" class="ar">api地址：</td>
        <td><?php echo CHtml::textField('Tuandata[apiurl]', $tuandata['apiurl'], array('class'=>'txt', 'style'=>'width: 600px;')); ?> 没有不要填写</td>
    </tr>
    <tr>
        <td width="120" class="ar">排序值：</td>
        <td><?php echo CHtml::textField('Tuandata[orderid]', $tuandata['orderid'], array('class'=>'txt')); ?> 值越大，越排到前头</td>
    </tr>
</table>
<input type="hidden" value="<?php echo $tuandata->id;?>" name="id">
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