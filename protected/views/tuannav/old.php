<?php 
cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');
?>
<div class="fl w720px ma-r10px">
	<div class="border-red ma-b10px h-100">
		<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px">网站信息</h3>
     	</div>
        
        <div class="pa-20px">
            <div class="fl w200px">
                <img src="<?php echo $tuaninfo->logo?>" width="200" height="45" /><br />
    		</div>
    
    
            <div class="fl ma-l10px">
                <h3 class="ma-t5px f16px ma-b5px"><?php echo $tuaninfo->name?>网</h3>
                <p class="ma-t10px f16px"><a href="<?php echo $tuaninfo->url?>" target="_blank"><?php echo $tuaninfo->url?></a></p>
            </div>
    
            <div class="fr">
                <p class="ar f14px ma-t5px">已有评论（<?php echo $pages->itemCount?>）</p>
            </div><div class="clear"></div>
            
             <div class="fl w230px ma-t20px ">
                <p class="f14px border-bottom-red lh24px cred">团购网站信息</p>
                <p class="lh24px pa-l10px"> 上线时间：<?php echo $tuaninfo->online_time?></p>
                <p class="lh24px pa-l10px"> 购买类型：<?php echo $tuaninfo->buyTypeText?></p>
                <p class="lh24px pa-l10px"> 团购组织频率：<?php echo $tuaninfo->postFrequencyText?></p>
                <p class="lh24px pa-l10px"> 团购平均购买人数：<?php echo $tuaninfo->buyNumText?></p>
            </div>
            
            <div class="fl ma-l20px ma-t20px ma-l40px w400px">    	
                <div class="border-bottom-red  lh24px" style="height:24px">
                    <div class="fl f14px cred">流量排名</div>        	
<!--                    <div class="fr ico-china ma-r10px pa-l20px ">中国472</div>-->
<!--                    <div class="fr ico-global ma-r10px pa-l20px">全球5972</div>        -->
                </div>
               
                <img src="http://traffic.alexa.com/graph?&w=400&h=220&o=f&c=1&y=t&b=ffffff&n=666666&r=3m&u=<?php echo $tuaninfo->url?>" width="400" height="220" />
            </div><div class="clear"></div>
            
             <div class="ma-t20px">
                <div class="border-bottom-red lh24px f14px cred">团购网简要介绍</div>
                <div class="indent24px lh20px ma-t5px">
                <?php echo $tuaninfo->intro?>
                </div> 
            </div>
            
            <div class="ma-t20px">
                <div class="border-bottom-red lh24px f14px cred ma-b5px">团购网联系方式</div>
                
                <div class="fl w-50">
                     <p class="lh20px">Email：<?php echo $tuaninfo->email?></p>
                     <p class="lh20px">电话：<?php echo $tuaninfo->mobile?> </p>
                     <p class="lh20px">地址：<?php echo $tuaninfo->adress?>  </p>
                </div>
            
                <div class="fl w-50">
                    <p class="lh20px">QQ：<?php echo $tuaninfo->QQ?></p>
                    <p class="lh20px">网站创办者：<?php echo $tuaninfo->create?></p>
                </div><div class="clear"></div>
            </div>
     
       </div>  
    </div>
    
	<div class="border-red ma-t10px pa-b10px h-100">
     	<div class="border-bottom-red">
        	<ul class=" subfl ul-title">
            	<li class="li-title ac lh30px f14px fb cwhite ma-r10px "><a href="<?php echo url('tuannav/info', array('source_id' => $tuaninfo->id))?>">团购评论</a></li>
                <li class="li-title li-title-select ac lh30px f14px fb cred">历史团购</li>
            </ul>    	
     	</div>
        
      <div class="cut-all">
      <div class="pa-20px">
       <?php if ($tuannav) :?>
     	<?php  foreach ($tuannav as $key=>$val) :?>
			<div class="fl w100px ma-r20px ma-b10px">
       	    <img src="<?php echo $val->image?> " width="100" height="60" />
            </div>
            <div class="fl w556px ma-b10px">
            	<p class="lh20px">【<?php echo $val->tuandata->name?>】&nbsp;【0天6小时30分50秒】</p>
                <p class="lh20px"><a href="<?php echo $val->absoluteUrl?>"><?php echo $val->title?></a></p>
            </div>
            <div class="clear"></div>
		<?php endforeach;?>
		<div class="pages ar">
		<?php $this->widget('CLinkPager', array(
			'pages' => $pages,
		    'header' => '翻页',
		    'firstPageLabel' => '首页',
		    'lastPageLabel' => '末页',
		    'nextPageLabel' => '下一页',
		    'prevPageLabel' => '上一页',
		));?>
		</div>
	 <?php else:?>
	  <div>目前历史团购</div>
	  <?php endif;?>
      </div>
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
			$(this).addClass("li-title-select").removeClass("cwhite").siblings(".li-title").removeClass("cred").addClass("cwhite");
			tab_content.addClass("none");
			tab_content.eq(i).removeClass("none");
		});
	});
});
</script>
