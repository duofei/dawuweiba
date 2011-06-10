<?php 
$iss = array(
	'1' => '是',
	'0' => '否',
);
?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">店铺名称：</td>
        <td><?php echo $shop_info->shop_name?></td>     
    </tr>
    <tr>
        <td width="120" class="ar">创建者用户名：</td>
        <td><?php echo $shop_info->user->username?></td>     
    </tr>
    <tr>
        <td class="ar">店主姓名：</td>
        <td><?php echo $shop_info->owner_name ?></td>     
    </tr>
    <tr>
        <td class="ar">身份证号：</td>
        <td><?php echo $shop_info->owner_card ?></td>     
    </tr>
    <tr>
        <td class="ar">店铺分类：</td>
        <td><?php echo $shop_info->categoryText?></td>     
    </tr>
    <tr>
        <td class="ar">餐厅分类：</td>
        <td><?php foreach (CHtml::listData($shop_info->tags, 'id', 'name') as $k=>$v) { echo $v.' '; }?></td>     
    </tr>
    <tr>
        <td class="ar">详细地址：</td>
        <td><?php echo $shop_info->district->city->name . ' ' . $shop_info->district->name . ' ' . $shop_info->address?></td>     
    </tr>
    <tr>
        <td class="ar">营业状态：</td>
        <td><?php echo $shop_info->businessStateText?></td>     
    </tr>
    <tr>
        <td class="ar">联系电话：</td>
        <td><?php echo $shop_info->telphone?></td>     
    </tr>
    <tr>
        <td class="ar">营业时间：</td>
        <td><?php echo $shop_info->business_time?></td>     
    </tr>
    <tr>
        <td class="ar">起送条件：</td>
        <td><?php echo $shop_info->transport_condition?></td>     
    </tr>
    <tr>
        <td class="ar">送餐时间：</td>
        <td><?php echo $shop_info->transport_time?></td>     
    </tr>
    <tr>
        <td class="ar">订餐方式：</td>
        <td><?php echo $shop_info->buyTypeText?></td>     
    </tr>
    <tr>
        <td class="ar">支付方式：</td>
        <td><?php echo $shop_info->payTypeText?></td>     
    </tr>
    <tr>
        <td class="ar">是否清真：</td>
        <td><?php echo $shop_info->MuslimText?></td>     
    </tr>
    <tr>
        <td class="ar">预订：</td>
        <td>可以提前<?php echo $shop_info->reserve_hour?>小时预订</td>     
    </tr>
    <tr>
        <td class="ar">团购：</td>
        <td><?php echo $shop_info->GroupText?></td>     
    </tr>
    <tr>
        <td class="ar">是否支持每日菜单：</td>
        <td><?php echo $iss[$shop_info->is_dailymenu]?></td>     
    </tr>
    <tr>
        <td class="ar">商铺公告：</td>
        <td><?php echo $shop_info->announcement?></td>     
    </tr>
    <tr>
        <td class="ar">创建时间：</td>
        <td><?php echo $shop_info->createDateTimeText?></td>     
    </tr>
    <tr>
        <td class="ar">创建ip：</td>
        <td><?php echo $shop_info->create_ip?></td>     
    </tr>
    <tr>
        <td class="ar">营业执照：</td>
        <td><?php echo $shop_info->commercialApproveText?></td>     
    </tr>
    <tr>
        <td class="ar">卫生许可证：</td>
        <td><?php echo $shop_info->sanitaryApproveText?></td>     
    </tr>
    <tr>
        <td class="ar">订单总数：</td>
        <td><?php echo $shop_info->order_nums?></td>     
    </tr>
    <tr>
        <td class="ar">未加工订单数：</td>
        <td><?php echo $shop_info->undressed_order_nums?></td>     
    </tr>
    <tr>
        <td class="ar">商品数量：</td>
        <td><?php echo $shop_info->goods_nums?></td>     
    </tr>
    <tr>
        <td class="ar">优惠信息数量：</td>
        <td><?php echo $shop_info->coupon_nums?></td>     
    </tr>
    <tr>
        <td class="ar">浏览次数：</td>
        <td><?php echo $shop_info->visit_nums?></td>     
    </tr>
    <tr>
        <td class="ar">收藏次数：</td>
        <td><?php echo $shop_info->favorite_nums?></td>     
    </tr>
    <tr>
        <td class="ar">评论次数：</td>
        <td><?php echo $shop_info->comment_nums?></td>     
    </tr>
    <tr>
        <td class="ar">口味总评分次数：</td>
        <td><?php echo $shop_info->taste_mark_nums?></td>     
    </tr>
    <tr>
        <td class="ar">口味平均分：</td>
        <td><?php echo $shop_info->taste_avg?></td>     
    </tr>
    <tr>
        <td class="ar">服务评分次数：</td>
        <td><?php echo $shop_info->service_mark_nums?></td>     
    </tr>
    <tr>
        <td class="ar">服务平均分：</td>
        <td><?php echo $shop_info->service_avg?></td>     
    </tr>
</table>