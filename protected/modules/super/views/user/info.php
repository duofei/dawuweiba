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
        <td><?php echo $user_info->create_ip?></td>     
    </tr>
    <tr>
        <td class="ar">最后登录时间：</td>
        <td><?php echo $user_info->lastLoginTimeText?></td>     
    </tr>
    <tr>
        <td class="ar">最后登录时间：</td>
        <td><?php echo $user_info->last_login_ip?></td>     
    </tr>
    <tr>
        <td class="ar">登录次数：</td>
        <td><?php echo $user_info->login_nums?></td>     
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
</table>