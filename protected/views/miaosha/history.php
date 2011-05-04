<div class="main">
<div><img src="<?php echo resBu('miaosha/images/titjr4.jpg');?>" /></div>
<div style="width:874px; font-size:14px; margin: 0px auto; background:#ffffff; border-left:1px solid #CBCBCB; border-right:1px solid #CBCBCB; line-height:30px; padding:0px 20px">
	<div class="M_cc M_top" name="top"><img src="<?php echo resBu('miaosha/images/jilu_r1_c6.jpg');?>" width="389" height="94" /></div>
	<div id="M_nav" class="M_cc">
	<?php for ($i=param('miaoshaStartTime'); $i<param('miaoshaEndTime'); $i=$i+24*3600):?>
		<?php if(date('m/d', $i) == date('m/d', time())):?>
		<span class="M_red cred"><a href="<?php echo url('miaosha/history', array('d'=>$i))?>"><?php echo date('m/d', $i);?>进行中</a></span>
		<?php elseif ($i < time()):?>
		<span class="M_hei"><a href="<?php echo url('miaosha/history', array('d'=>$i))?>"><?php echo date('m/d', $i);?>记录</a></span>
		<?php else:?>
		<span class="M_hui"><?php echo date('m/d', $i);?>未开始</span>
		<?php endif;?>
	<?php endfor;?>
	</div>
	<div id="M_bc"><a href="<?php echo url('miaosha/index')?>"></a></div>
	<div class="M_cc M_top2"><img src="<?php echo resBu('miaosha/images/jilu_r10_c5.jpg')?>" width="82" height="90" /><h1><?php echo date('m月d日', $d);?>秒杀记录</h1></div>
	<div class="M_tc"><img src="<?php echo resBu('miaosha/images/jilu_r13_c2.jpg')?>" width="747" height="27" /></div>
	<?php if($history):?>
	<?php foreach ((array)$history as $h):?>
		<?php if($h->order_id > 0):?>
		<div class="M_su M_cc"><a><span><?php echo str_pad(mb_substr($h->user->username, 0, 2), strlen($h->user->username), '*');?></span><span><?php echo $h->goods->name;?></span><span><?php echo date('H点i分s秒', $h->create_time);?></span><b class="M_m1">成功</b></a></div>
		<?php else:?>
		<div class="M_fil M_cc"><a><span><?php echo str_pad(mb_substr($h->user->username, 0, 2), strlen($h->user->username), '*');?></span><span><?php echo $h->goods->name;?></span><span><?php echo date('H点i分s秒', $h->create_time);?></span><b class="M_m1">失败</b></a></div>
		<?php endif;?>
	<?php endforeach;?>
	<?php else:?>
		<div class="ac">暂无记录</div>
	<?php endif;?>
	<div style="height:50px;"></div>
	<div class="pages ac">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</div>
	<div class="M_hei M_lr"><a href="#top">返回顶部</a></div>
	<div style="height:40px;"></div>
</div>
<div><img src="<?php echo resBu('miaosha/images/bg04.jpg');?>" /></div>
</div>
<script type="text/javascript">

</script>