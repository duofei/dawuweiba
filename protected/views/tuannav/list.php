<?php cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');?>
<div class="fl ma-r10px w720px" >
	<div class="pa-b10px border-red h-100"  >
     	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 济南团购</h3>
     	</div>
          
	 	<ul class="sites_ul pa-l20px ma-t10px subfl">
	 	<div>
         <?php foreach ((array)$tuandata as $key=>$val) :?>
         <?php if ($key<17):?>
          <li class=" pa-l10px cblack">
            <a  href="<?php echo aurl('tuannav/info', array('source_id' => $val->id))?>" target="_blank" class="fl" title="<?php echo $val->name?>"><?php echo $val->name?></a>
            <a href="<?php echo $val->url?>" target="_blank" class="site-link"></a>
          </li>
          <?php endif;?>
         <?php endforeach;?>
         </div>
         <div id="tuandata" class="none">
         <?php foreach ((array)$tuandata as $key=>$val) :?>
         <?php if ($key>=17):?>
          <li class=" pa-l10px cblack">
            <a  href="<?php echo aurl('tuannav/info', array('source_id' => $val->id))?>" target="_blank" class="fl" title="<?php echo $val->name?>"><?php echo $val->name?></a>
            <a href="<?php echo $val->url?>" target="_blank" class="site-link"></a>
          </li>
          <?php endif;?>
         <?php endforeach;?>
         </div>
         <?php if ($key>=17):?>
         <li id="more" class="pa-l10px cblack">
            <a class="fl cursor">更多>></a>
          </li>
          <?php endif;?>
       </ul><div class="clear"></div>
	</div><!--end 列表-->
    
 <div class="group-sort f14px none">
    <div class="h44px"></div>
    <ul class="lh26px pa-l30px">
	<?php foreach ((array)$category as $key=>$val) :?>
		<li><a href="<?php echo aurl('tuannav/search', array('category_id' => $val->id, 'day'=>$date))?>" title="<?php echo $val->name?>"><?php echo $val->name?></a></li>
	<?php endforeach;?>
    </ul>
 </div>
    
   <div class="ma-t10px border-red pa-b10px h-100" >
      <div class=" groupbuy-title1">
       <div class="fl w-50" >
       		<ul class=" subfl ul-title ">
            	<?php if ($date):?>
            	<li class="li-title li-title-select ma-r10px ac lh30px f14px fb cred">历史团购</li>
            	<li class="li-title ma-r10px ac lh30px f14px fb cwhite"><a  href="<?php echo url('tuannav/list')?>">今日团购</a></li>
            	<?php else:?>
            	<li class="li-title li-title-select ma-r10px ac lh30px f14px fb cred">今日团购</li>
            	<?php endif;?>
                <li class="li-title ac lh30px f14px fb cwhite"><a href="<?php echo url('tuannav/second', array('sort'=>'sell'))?>">转让求购</a></li>
            </ul>
       </div>
    
       <div class="fl w-50 ma-t10px">
                    <span class="bg-pic sort-btn block fr ma-r20px indent10px lh24px <?php echo $sortclass['discount'];?>" ><?php echo $sort->link('discount', '折扣');?></span>
                    <span class="bg-pic sort-btn block fr ma-r5px indent10px lh24px <?php echo $sortclass['group_price'];?>"><?php echo $sort->link('group_price', '价格');?></span>
             		<span class="bg-pic sort-btn block fr ma-r5px indent10px lh24px <?php echo $sortclass['buy_num'];?>"><?php echo $sort->link('buy_num', '流行');?></span>
       </div>
    </div><!--end title--> <div class="clear"></div>
    
    <div class="pa-l10px pa-t10px ma-b10px border-bottom-dot" >
    	<ul class="subfl " >
        	<li class="pa-5px">类别：</li>
         <?php if (!$_GET['category_id']):?>
         	<li class="sort pa-5px ma-r10px <?php if (!$_GET['category_id']) echo 'bg-red';?>">全部</li>
         <?php else :?>
                <li class="sort pa-5px ma-r10px <?php if (!$_GET['category_id']) echo 'bg-red';?>"><a href="<?php echo aurl('tuannav/search', array('day'=>$date))?>">全部</a></li>
         <?php endif;?>
         <?php foreach ((array)$category as $key=>$val) :?>
         		<?php if ($val->id == $_GET['category_id']): ?>
            		<li class="ma-r10px pa-5px bg-red"><?php echo $val->name?></li>
            	<?php else:?>
            		<li class="ma-r10px pa-5px"><a href="<?php echo aurl('tuannav/search', array('category_id' => $val->id, 'day'=>$date))?>"><?php echo $val->name?></a></li>
	            <?php endif;?>
         <?php endforeach;?>
        </ul>
        <div class="clear"></div>
    </div>

<div class="pa-l10px">
<?php if ($tuannav) :?>
    <?php foreach ($tuannav as $key=>$val) :?>
      <div class="fl group-box pa-t5px ma-b10px">
        	<?php if ($val->create_time>strtotime(date('Y-m-d'))) :?><div class="group-box-new"></div><?php endif;?>
            <div  class="group-img ">
            	<?php echo $val->imageLinkHtml?>
           	<p class="ac lh20px group-time none"><?php if ($val->create_time>strtotime(date('Y-m-d'))) echo '今日开始，';?>剩余：<?php echo $val->effectiveTime?></p>
            </div>
			<p class="lh20px ma-t5px ma-l5px"><a href="<?php echo $val->absoluteUrl?>" target="_blank" title="<?php echo $val->title?>"><span class="fb ma-r5px">【<?php echo $val->tuandata->name?>】</span><span class="cblack"><?php echo $val->titleSub?></span></a> </p>
        	
            <div class="fl w130px ">
            	<ul class="lh20px pa-l5px ma-t5px ">
                	<li>团购价：<span class="cred f14px fb"><?php echo $val->group_price?>元</span></li>
                    <li>折扣：<span class="cred"><?php echo $val->discount==0 ? '无折扣' : $val->discount.'折';?></span></li>
                    <li>原价：<?php echo $val->original_price?>元</li>
                </ul>
            </div>
            
            <div class="fl w90px">
            	<div class=" pa-t20px"><a class="group-buy " href="<?php echo url('tuannav/favorite', array('id'=>$val->id, 'type'=>'buy'))?>" target="_blank" rel="nofollow"></a></div>
    		</div><div class="clear"></div>
            
            <div class=" group-comment fr">
                <a href="<?php echo $val->absoluteUrl?>" title="发表评论"><div class=" group-dp fl pa-l20px pa-r10px">(<?php echo $val->comment_nums?>)</div></a>
                <a href="<?php echo $val->absoluteUrl?>" title="收藏 买过"><div class=" group-sc fl pa-l20px">(<?php echo $val->favorite_num+$val->buy_num?>)</div></a>
            </div>
      </div><!--end groupbox-->
    <?php endforeach;?>
      <div class="clear"></div>
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
  <div>目前没有团购</div>
  <?php endif;?>
  <?php echo user()->getFlash('errorSummary'); ?>
  <div class="clear"></div>
</div>
</div>
</div>
	
<?php $this->renderPartial('/tuannav/right', array('tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'success'=>$success));?>

<script type="text/javascript">
/* <![CDATA[ */
onload = function() {
	$(".group-sort ").show();
	startPos = $(".group-sort ").position().top;
	divHeight = $(".group-sort ").outerHeight();
	$(".group-sort").css("height", divHeight + "px");
	if ($.browser.msie && $.browser.version <= 6 ) {
		scrTop = $(window).scrollTop();
		topPos = scrTop + ($(window).height()-divHeight)/2;
		$(".group-sort ").css("top", topPos +"px");
	} else {
		$(".group-sort ").css("position", "fixed").css("top",($(window).height()-divHeight)/2 + "px");
	}
	$(window).scroll(function (e) {
		scrTop = $(window).scrollTop();
			if ($.browser.msie && $.browser.version <= 6 ) {
				topPos = scrTop + ($(window).height()-divHeight)/2;
				$(".group-sort ").css("top", topPos +"px");
			} else {
				$(".group-sort ").css("position", "fixed").css("top",($(window).height()-divHeight)/2 + "px");
			}
	});
}

/* ]]> */
</script>

<script type="text/javascript">
$(function(){
	$(".group-box").hover(function(){
		$(this).find(".group-time").show()
	},function(){
		$(this).find(".group-time").hide()
	})
});
$(function(){
	$("#more").click(function(){
		$("#tuandata").removeClass("none");
		$("#more").addClass("none");
	})
})
</script>