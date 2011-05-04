<?php
$iss = array(
	'1' => '是',
	'0' => '否',
);
?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">用户姓名：</td>
        <td><?php echo $user_info->username?></td>
    </tr>
    <tr>
        <td width="120" class="ar">认证状态：</td>
        <td>
        	<?php echo $user_info->approveStateText;?>
        	　　　操作：
        	<?php echo l('已认证',url('admin/user/approveStateOprate', array('userid'=>$user_info->id, 'state'=>User::APPROVE_STATE_VERIFY)), array('onclick'=>"return confirm('确定要设置为已认证用户吗？');"));?>
        	　<?php echo l('待认证',url('admin/user/approveStateOprate', array('userid'=>$user_info->id, 'state'=>User::APPROVE_STATE_UNSETTLED)), array('onclick'=>"return confirm('确定要设置为待认证用户吗？');"));?>
        	　<?php echo l('黑名单',url('admin/user/approveStateOprate', array('userid'=>$user_info->id, 'state'=>User::APPROVE_STATE_BLACKLIST)), array('onclick'=>"return confirm('确定要设置为黑名单用户吗？');"));?>
        </td>
    </tr>
    <tr>
        <td class="ar">邮箱：</td>
        <td><?php echo $user_info->email ?></td>
    </tr>
    <tr>
        <td class="ar">真实姓名：</td>
        <td><?php echo $user_info->realname ?></td>
    </tr>
    <tr>
        <td class="ar">性别：</td>
        <td><?php echo $user_info->genderText?></td>
    </tr>
    <tr>
        <td class="ar">生日：</td>
        <td><?php echo $user_info->birthday?></td>
    </tr>
    <tr>
        <td class="ar">电话：</td>
        <td><?php echo $user_info->telphone?></td>
    </tr>
    <tr>
        <td class="ar">手机号：</td>
        <td><?php echo $user_info->mobile?></td>
    </tr>
    <tr>
        <td class="ar">注册时间：</td>
        <td><?php echo $user_info->createTimeText?></td>
    </tr>
    <tr>
        <td class="ar">注册ip：</td>
        <td><?php echo $user_info->create_ip?> (<?php echo $user_info->createIpCityText;?>)</td>
    </tr>
    <tr>
        <td class="ar">最后登录时间：</td>
        <td><?php echo $user_info->lastLoginTimeText?></td>
    </tr>
    <tr>
        <td class="ar">最后登录时间：</td>
        <td><?php echo $user_info->last_login_ip?> (<?php echo $user_info->lastLoginIpCityText;?>)</td>
    </tr>
    <tr>
        <td class="ar">登录次数：</td>
        <td><?php echo $user_info->login_nums?></td>
    </tr>
    <tr>
    	<td class="ar">最近几次登陆信息：</td>
    	<td class="lh20px">
    	<?php foreach ($user_info->userLoginLogs as $key=>$loginlog):?>
    	<?php echo '<strong>时间:</strong>' . $loginlog->shortCreateDateTimeText . ' <strong>IP:</strong>' . $loginlog->create_ip . ' <strong>来源:</strong>' . $loginlog->referer; ?> <br />
    	<?php if($key > 3) break;?>
    	<?php endforeach;?>
    	</td>
    </tr>
    <tr>
    	<td class="ar">最近几次搜索记录：</td>
    	<td>
    	<?php foreach ($user_info->searchLogs as $key=>$log):?>
    	<span title="搜索时间：<?php echo $log->shortCreateDateTimeText;?>"><?php echo $log->keywords;?></span>　&nbsp;
    	<?php if($key > 13) break;?>
    	<?php endforeach;?>
    	</td>
    </tr>
    <tr>
    	<td class="ar">最近几次商铺评论：</td>
    	<td class="lh20px">
    	<?php foreach ($user_info->shopComments as $key=>$comment):?>
    	<a href="<?php echo url('shop/show', array('shopid'=>$comment->shop_id, 'tab'=>'comment'));?>" target="_blank"><?php echo h($comment->content);?></a> <span class="f10px"><?php echo $comment->shortCreateDateTimeText;?></span><br />
    	<?php if($key > 3) break;?>
    	<?php endforeach;?>
    	</td>
    </tr>
    <tr>
    	<td class="ar">最近几次商品评论：</td>
    	<td class="lh20px">
    	<?php foreach ($user_info->goodsRateLogs as $key=>$log):?>
    	<div class="star-small-gray ma-t5px ma-r5px fl"><div class="star-small-color" style="width:<?php echo $log->rateStarWidth;?>px;"></div></div><a href="<?php echo url('goods/show', array('goodsid'=>$log->goods_id));?>" target="_blank"><?php echo h($log->content);?></a> <span class="f10px"><?php echo $log->shortCreateDateTimeText;?></span><br />
    	<?php if($key > 3) break;?>
    	<?php endforeach;?>
    	</td>
    </tr>
    <tr>
        <td class="ar">积分：</td>
        <td><?php echo $user_info->integral?></td>
    </tr>
    <tr>
        <td class="ar">信用：</td>
        <td><?php echo $user_info->credit?></td>
    </tr>
    <tr>
        <td class="ar">评价次数：</td>
        <td><?php echo $user_info->credit_nums?></td>
    </tr>
    <tr>
        <td class="ar">白吃点：</td>
        <td><?php echo $user_info->bcnums?></td>
    </tr>
    <tr>
        <td class="ar">qq：</td>
        <td><?php echo $user_info->qq?></td>
    </tr>
    <tr>
        <td class="ar">msn：</td>
        <td><?php echo $user_info->msn?></td>
    </tr>
    <tr>
        <td class="ar">行政区域：</td>
        <td><?php echo $user_info->city->name.' '.$user_info->district->name?></td>
    </tr>
    <tr>
        <td class="ar">来源：</td>
        <td><?php echo $user_info->sourceText?></td>
    </tr>
    <tr>
        <td class="ar">办公楼：</td>
        <td><?php echo $user_info->officeBuilding->name?></td>
    </tr>
    <tr>
        <td class="ar">小区：</td>
        <td><?php echo $user_info->homeBuilding->name?></td>
    </tr>
    <tr>
        <td class="ar">状态：</td>
        <td><?php echo $user_info->stateText?></td>
    </tr>
    <tr>
        <td class="ar">管理的店铺：</td>
        <td><?php foreach ($user_info->shops as $key=>$val)  echo $val->shop_name . ' ';?></td>
    </tr>
    <tr>
        <td class="ar">业务数量：</td>
        <td>未审核：<?php echo $user_info->yewuCloseNums;?>&nbsp;&nbsp;通过审核：<?php echo $user_info->yewuOpenNums;?>&nbsp;&nbsp;打回：<?php echo $user_info->yewuSuspendNums;?></td>
    </tr>
</table>