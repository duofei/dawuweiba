<data52wm>
<data>
	<errorCode>0</errorCode>
	<errorMessage></errorMessage>
	<?php if ($loginState):?>
	<loginState><?php echo $loginState?></loginState>
	<?php endif;?>
	<user><?php if($user):?>
		<id><?php echo $user->id;?></id>
		<name><?php echo $user->username;?></name>
		<realname><?php echo $user->realname;?></realname>
		<gender><?php echo $user->gender;?></gender>
		<birthday><?php echo $user->birthday;?></birthday>
		<telphone><?php echo $user->telphone;?></telphone>
		<mobile><?php echo $user->mobile;?></mobile>
		<integral><?php echo $user->integral;?></integral>
		<credit><?php echo $user->credit;?></credit>
		<creditNums><?php echo $user->credit_nums;?></creditNums>
		<bcnums><?php echo $user->bcnums;?></bcnums>
		<qq><?php echo $user->qq;?></qq>
		<msn><?php echo $user->msn;?></msn>
		<city_id><?php echo $user->city_id;?></city_id>
		<district_id><?php echo $user->district_id;?></district_id>
	<?php endif;?></user>
</data>
</data52wm>