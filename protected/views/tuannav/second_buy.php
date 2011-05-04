<?php
cs()->registerCssFile(resBu("styles/tuannav.css"), 'screen');
?>
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
         <li id="more" class="pa-l10px cblack">
            <a class="fl cursor">更多>></a>
          </li>
       </ul><div class="clear"></div>
	</div><!--end 列表-->
    
 <div class="group-sort f14px">
    <div class="h44px"></div>
    <ul class="lh26px pa-l30px">
	<?php foreach ((array)$category as $key=>$val) :?>
		<li><a href="<?php echo aurl('tuannav/secondSearch', array('category_id' => $val->id, 'sort'=>'buy'))?>" title="<?php echo $val->name?>"><?php echo $val->name?></a></li>
	<?php endforeach;?>
    </ul>
 </div><div class="clear"></div>
 
 <div class="ma-t10px border-red pa-b10px h-100" >
      <div class=" groupbuy-title1">
       <div class="fl w-50" >
       		<ul class=" subfl ul-title ">
            	<li class="li-title ac lh30px f14px fb cwhite ma-r10px"><a href="<?php echo url('tuannav/list')?>">今日团购</a></li>
                <li class="li-title ac lh30px f14px fb cred li-title-select">转让求购</li>
            </ul>
       </div>
      </div><!--end title--> <div class="clear"></div>

    <div class="pa-10px">
		<div class="border-bottom-dot pa-5px ma-b5px">
            <ul class="subfl">
                <li class="sort pa-5px ma-r10px" ><a href="<?php echo url('tuannav/second', array('sort'=>'sell'))?>">转让</a></li>
                <li class="sort pa-5px bg-red">求购</li>
            </ul><div class="clear"></div>
       </div>
    
    <div class="border-bottom-dot pa-5px">
            <ul class="subfl ma-t5px">
                <li class="pa-5px">分类：</li>
         <?php if (!$_GET['category_id']):?>
         	<li class="sort pa-5px ma-r10px <?php if (!$_GET['category_id']) echo 'bg-red';?>">全部</li>
         <?php else :?>
                <li class="sort pa-5px ma-r10px <?php if (!$_GET['category_id']) echo 'bg-red';?>"><a href="<?php echo url('tuannav/second', array('sort'=>'buy'))?>">全部</a></li>
         <?php endif;?>
         <?php foreach ((array)$category as $key=>$val) :?>
         		<?php if ($val->id == $_GET['category_id']): ?>
            		<li class="ma-r10px pa-5px bg-red"><?php echo $val->name?></li>
            	<?php else:?>
            		<li class="ma-r10px pa-5px"><a href="<?php echo aurl('tuannav/secondSearch', array('category_id' => $val->id, 'sort'=>'buy'))?>"><?php echo $val->name?></a></li>
	            <?php endif;?>
         <?php endforeach;?>
            </ul>
        
        <div class="fr ma-r40px  f14px cwhite">
        <a class=" block ico-post-four lh30px fb ac" href="<?php echo url('tuannav/secondCreate')?>">发布求购</a>
        </div><div class="clear"></div>
    </div>
<?php echo CHtml::beginForm(url('tuannav/secondSearch'),'get',array('name'=>'add'));?>
<input type="hidden" name="sort" value="buy">
    <div class=" pa-10px">
        快速搜索：<?php echo CHtml::textField('keywords', $kw, array('class'=>'txt'))?>
        <input class="ico-post cwhite fb" name="" type="submit" value="搜&nbsp;索" />
    </div>
<?php echo CHtml::endForm()?>

    <?php if ($secondhard):?>
    	<table width="100%" class="border-gray f14px">
          <tr class="tr-h30px bg-f9 fb cgray">
            <td width="80%" class="pa-l10px">转让求购团购券信息</td>
            <td width="20%">作者/时间</td>
          </tr>
        </table>
        <?php foreach ($secondhard as $key=>$val) :?>
        <table class=" seller-tab"  width="100%">
          <tr class="cblack">
            <td width="80%">
            <div class="fl <?php if ($val->state == STATE_ENABLED) echo 'a-bargain'; else echo 'a-buy';?> ico-w35px "></div>
            	<div class="fl pa-t5px w510px cursor"><?php echo $val->title?></div>
            <div class="clear"></div>
            </td>
            <td width="20%"><?php echo $val->user->username?><br /><span class="cgray"><?php echo $val->shortCreateDateTimeText?></span></td>
          </tr>
        </table>
        <div class=" none pa-10-40  bg-f9 lh20px border-gray">
            <p>【详细内容】<span class="cgray"><?php echo $val->content?></span></p>
            <p>【链接地址】<span class="cgray">交易的团购网站链接地址：</span><a class="f16px" href="<?php echo $val->url?>" target="_blank"><?php echo $val->url?></a></p>
            <p>【数量】<span class="cgray"><?php echo $val->nums?></span></p>
            <p>【价格】<span class="cred f14px"><?php echo $val->price?>元</span></p>
            <p>【联系电话】<span class="cred f14px"> <?php echo $val->mobile?></span></p>
        </div>
        
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
	  <div>目前没有求购</div>
	  <?php endif;?>
   </div><!--end 二手-->
 </div><!--end 详情-->
</div><!--end w720px-->
	
	<?php $this->renderPartial('/tuannav/right', array('tuanbuy'=>$tuanbuy, 'tuansecond'=>$tuansecond, 'success'=>$success));?>
    
<script type="text/javascript">
$(function(){
	$(".seller-tab").click(function(){
		 $(this).next(".none").toggle().siblings(".none:visible").toggle(false)
	})
	
	$(".sort").click(function(){
		$(this).addClass("bg-red").siblings(".sort").removeClass("bg-red");
	})
});
$(function(){
	$("#more").click(function(){
		$("#tuandata").removeClass("none");
		$("#more").addClass("none");
	})
})
</script>

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