<?php
cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');
?>
<div class="fl ma-r10px w720px relative">
	<div class="pa-b10px border-red h-100"  >
     	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 团购详情</h3>
     	</div>
                  
	 	<h3 class="m10px lh24px f16px ">
	 	<?php if ($tuannav->create_time >= strtotime(date('Y-m-d'))) echo '今日团购：'?>
	 	<a class="fb" href="<?php echo $tuannav->url?>" target="_blank"> 【<?php echo $tuannav->tuandata->name?>】<?php echo $tuannav->title ?></a>
	 	</h3>
                
        <div class="m10px pa-t10px border-top-dot">
        	<div class="fl w370px ma-r10px">
            	<div class="ma-l10px ma-b10px">
            	<a class="fb" href="<?php echo $tuannav->url?>" target="_blank"><?php echo CHtml::image($tuannav->image, '', array('class'=>'image-show'))?></a>
                </div>
                <div class="cblack pa-l10px">
                    <a class="today-keep fl ma-l40px lh24px" href="<?php echo url('tuannav/favorite', array('id'=>$tuannav->id, 'type'=>'favorite'))?>">收藏<span class="cred">(<?php echo $tuannav->favorite_num?>)</span>次</a>
					<span class="today-buyone ma-l40px fl lh24px">买了(<?php echo $tuannav->buy_num?>)次</span>
              </div>
              <div class="clear"></div>
                
                <div class="ma-t10px lh24px f14px indent24px">
                	<p>该团购活动由<a href="<?php echo $tuannav->tuandata->url?>" target="_blank"><?php echo $tuannav->tuandata->name?></a>组织，参与此团购将会与<a href="<?php echo $tuannav->tuandata->url?>" target="_blank"><?php echo $tuannav->tuandata->name?>团购网</a>产生直接买卖关系。</p>
                </div>
  		<?php echo user()->getFlash('errorSummary'); ?>
            </div>
                       
            <div class="fl w315px" >
            	<div class="fl ma-t15px w130px">
                	<span class="cgray"><?php echo $tuannav->city->name?></span>
                	<ul class="ma-t15px">
                      <li class="lh24px f14px">现价：<span class="cred fb"><?php echo $tuannav->group_price ?>元</span></li>
                      <li class="lh24px f14px">原价：<?php echo $tuannav->original_price ?>元</li>
                      <li class="lh24px f14px">折扣：<?php echo $tuannav->discount==0 ? '无折扣' : $tuannav->discount.'折'; ?></li>
                    </ul>
                </div>
                
                <div class="fl ma-t15px ">
                	<span class="cgray">剩余时间:<?php echo $tuannav->effectiveTime?></span><br />
					<a class="today-buy ma-t20px" href="<?php echo url('tuannav/favorite', array('id'=>$tuannav->id, 'type'=>'buy'))?>" target="_blank" rel="nofollow"></a>
                    <p class="f14px ma-l20px ma-t10px fb">节省：<?php echo $tuannav->original_price-$tuannav->group_price ?>元</p>
                </div><div class="clear"></div>
                
                <p class=" ma-t20px f14px lh24px " ></p>
                <div class=" f14px lh24px fl w235px">
                    <p class="f14px lh24px">有效期：<?php echo $tuannav->effective_time ?> </p>
                    <p class="f14px lh24px">更多<a href="<?php echo url('tuannav/list')?>">济南团购网站</a>大全信息</p>
                </div>
                
                <div class="fl f14px lh24px" >
                    <p><a   href="<?php echo url('tuannav/report', array('id'=>$tuannav->id))?>">我要举报</a></p>
                   <p> <a class="ico-question block ">友情提示</a></p>
                </div><div class="clear"></div>
		        <div class=" absolute border-gray bg-ff question lh20px cgray none">
		           <li>目前国内的团购网站良莠不齐，为了避免上当受骗，我爱外卖提醒您注意：</li>
		           <li> 1. 确保您去的团购网站是您了解和信任的，请查看它过往的团购记录和商家合作情况。</li>
		           <li> 2. 可以通过网络搜索等方式查看网友们对此团购网站以及合作商家活动的口碑评价。</li>
		           <li> 3. 确认交易流程中不要随意泄漏您的账号、密码，保留好您的支付及消费凭证。</li>
		           <li> 4. 如果您在消费后能回到我爱外卖这里留下您对本次团购的经历评价，会帮助其他许多和您一样的网友！</li>
		           <li class="ma-t20px">注： 作为中立客观的团购导航网站，我爱外卖无法监控团购网站中的不实宣传，也没有行政处罚的权利，但我们希望可以网聚消费者的力量让大家 团的放心，用的开心！</li>
		        </div>
        
                <div class="border-dot ma-t10px pa-5px h-100">
                	<div class="w100px fl"><img class="ma-t5px" src="<?php echo $tuannav->tuandata->logo;?>" width="100" /></div>
                    <div class="fl ma-l10px lh20px f14px w180px ">
                    	<p class="cred">此团购信息来源于<?php echo $tuannav->tuandata->name?></p>
                        <p> 电话：<?php echo $tuannav->tuandata->mobile?></p>
                        <p>QQ：<?php echo $tuannav->tuandata->QQ?></p>
                        <p class="overflow">邮箱：<?php echo $tuannav->tuandata->email?></p>
                        <p>查看<a href="<?php echo aurl('tuannav/info', array('source_id' => $tuannav->tuandata->id))?>" target="_blank"><?php echo $tuannav->tuandata->name?></a>的所有团购信息</p>
                    </div><div class="clear"></div>
              </div>
            </div><div class="clear"></div>
        </div>
    <div class="border-gray ma-l10px ma-r10px pa-10px bg-ff">
    	  <p class="ac bg-red f14px w100px pa-5px ma-b10px">团购用户须知</p>
          <div class="lh24px cgray"><?php echo nl2br($tuannav->content);?></div>
    </div>
    </div><!--end 1-->
    
     <div class="border-red ma-t10px pa-b10px h-100">
     	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px">团购评论</h3>
     	</div>
        
        <div class="pa-20px">
        <?php if ($tuanComment) :?>
     	<?php $num = count($tuanComment); foreach ($tuanComment as $key=>$val) :?>
     		<div class="border-bottom-black pa-10px">
                <div class="lh24px  border-bottom-dot">
                	<div class="today-arrow fl pa-l20px "><span class="cred f14px"><?php echo $val->user->username?></span><span class="ma-l20px cgray"> 发表于<?php echo $val->createTimeText?></span></div>
                	<div class="fr pa-r10px f14px color-c60 "><?php echo $num-$key;?></div><div class="clear"></div>
                </div>
                <p class="lh24px indent24px ma-t5px "><?php echo $val->content?></p>
        	</div>
        <?php endforeach;?>
	 <?php else:?>
	  <div>目前没有评论</div>
	  <?php endif;?>
	  <div class="ma-l20px">
        <?php echo CHtml::beginForm(url('tuannav/comment'),'post',array('name'=>'add'));?>
        	<input type="hidden" name="id" value="<?php echo $tuannav->id?>">
        	<?php echo CHtml::textArea('Comment[content]', '', array('class'=>'ma-t10px','rows'=>'2','cols'=>'60'))?><br>
			验证码：
			<?php echo CHtml::textField('validateCode', '', array('class'=>' validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
			<?php $this->widget('CCaptcha',array(
				'captchaAction' => 'captcha',
				'showRefreshButton' => true,
				'buttonLabel' => '换一个',
				'clickableImage' => true,
				'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
			));?><br>
        	<input class="today-btn ma-t10px cwhite fb ma-b10px" name="" type="submit" value="发表评论" />
        <?php echo CHtml::endForm();?>
        </div>
  		<?php echo user()->getFlash('errorSummaryC'); ?>
        </div>
	</div>
</div><!--end left-->
	
	<?php $this->renderPartial('/tuannav/right', array('tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'success'=>$success));?>

<script type="text/javascript">
$(function(){
	$(".li-title").each(function(i){               //菜单切换
		var tab_content = $(".cut-all>div");
		$(this).click(function(){
			$(".li-title").removeClass("li-title-select");
			$(this).addClass("li-title-select").siblings(".li-title").removeClass("cred").addClass("cwhite");
			tab_content.addClass("none");
			tab_content.eq(i).removeClass("none");
		});
	});

	$(".ico-question").mouseover(function(){
		$(".question").show();
	});
	 
	$(".question").mouseleave(function(){
		$(".question").hide();
	});
})
</script>
