<?php
$iss = array(
	'1' => '是',
	'0' => '否',
);
?>
<?php echo CHtml::beginForm(url('admin/shop/profile'),'post',array('name'=>'edit'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">店铺名称：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'shop_name', array('class'=>'txt')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">店主姓名：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'owner_name', array('class'=>'txt')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">身份证号：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'owner_card', array('class'=>'txt')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">店铺分类：</td>
        <td><?php echo CHtml::activeRadioButtonList($shop_info, 'category_id', ShopCategory::$categorys, array('separator'=>'&nbsp;'));?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">餐厅分类：</td>
        <td><?php echo CHtml::checkBoxList('tags', CHtml::listData($shop_info->tags, 'id', 'id'), $shopTag, array('separator'=>'&nbsp;'));?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">所属地区：</td>
        <td><?php echo $shop_info->district->city->name . '|' . $shop_info->district->name?>
			<?php echo CHtml::activeDropDownList($shop_info, 'district_id', $district);?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">详细地址：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'address', array('class'=>'txt', 'style'=>'width:300px;')); ?></td>
    </tr>
    <tr>
        <td class="ar">联系电话：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'telphone', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td class="ar">店主手机：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'mobile', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td class="ar">联系QQ：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'qq', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td class="ar">营业时间：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'business_time', array('class'=>'txt')); ?> 格式：9:00-21:00</td>
    </tr>
    <tr>
        <td class="ar">送餐时间：</td>
        <td><?php echo CHtml::activeTextField($shop_info, 'transport_time', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td class="ar">预订时间限制:	</td>
        <td>最短可提前<?php echo CHtml::activeTextField($shop_info, 'reserve_hour', array('class'=>'txt' , 'style'=>'width:30px;')); ?>小时预订。</td>
    </tr>
    <tr>
        <td class="ar">是否支持网络订餐：</td>
        <td><?php echo CHtml::activeRadioButtonList($shop_info, 'buy_type', Shop::$buytype, array('separator'=>'&nbsp;')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">是否支持在线支付：</td>
        <td><?php echo CHtml::activeRadioButtonList($shop_info, 'pay_type', $iss, array('separator'=>'&nbsp;')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
        <td class="ar">审核：</td>
        <td><?php echo CHtml::activeRadioButtonList($shop_info, 'state', Shop::$states, array('separator'=>'&nbsp;')); ?><span class="cred">*</span></td>
    </tr>
    <tr>
    	<td class="ar">二级域名：</td>
    	<td><?php echo CHtml::activeTextField($shop_info, 'nick', array('class'=>'txt')); ?></td>
    </tr>
    <tr>
        <td class="ar">备注信息：</td>
        <td><?php echo CHtml::activeTextArea($shop_info,'remark', array('style'=>'width:500px')); ?></td>
    </tr>
</table>
<input type="hidden" value="<?php echo $shop_info->id;?>" name="id">
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
 <?php echo CHtml::endForm();?>
 <div class="clear"></div>
 <?php echo user()->getFlash('errorSummary'); ?>
