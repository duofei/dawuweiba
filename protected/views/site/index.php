<div class="indexnav ma-t10px ma-b10px pleft10px">
    <ul class="f14px lh30px ac subfl cgray fb">
    	<?php $this->renderDynamic('getClassicUserNav');?>
    </ul>
</div>
<div class="index-main fl">
	<?php echo CHtml::beginForm(url('at/search'), 'get');?>
    <div id="search" class="cwhite">
        <h1 class="f18px fb lh40px">请输入您需要服务的地址</h1>
        <div class="search-form">
            <div class="fl input-search">
            	<input class="search-input cgray" name="kw" type="text" value="您所在的地址：例如数码港公寓" id="location_search" />
            </div>
            <div class="fl search-btn">
            	<input class="btn-search fb f16px cred" type="submit" value="叫外卖" id="searchSubmit" />
            </div>
 		</div><div class="clear"></div>
        <p class="lh20px">历史地点： <?php $this->renderDynamic('getUserSearchLocationHistory');?></p>
		<p class="lh20px">热门搜索： <?php echo Location::getSearchHotNameLinkHtml();?></p>
	</div>
	<?php echo CHtml::endForm();?>
<div class="ma-t10px f14px">
	<div class="main-tools fl ma-r10px">
		<div class="corner shop-summary">
	  		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
	   		<div class="content lh24px">
				<h3 class="f16px fb cred ma-l10px ma-t5px ma-b5px"> 济南商铺</h3>
				<ul class="ma-l20px bg-icon shop-nums2">
					<li>简餐<?php echo $categoryShopCount['jc'];?>家</li>
					<li>家宴<?php echo $categoryShopCount['jy'];?>家</li>
					<!-- <li>蛋糕<?php //echo $categoryShopCount['cake'];?>家</li> -->
				</ul>
	  		</div>
      		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
     	</div>
		<div class="ma-t10px corner wm-social">
    		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
			<div class="content lh24px">
				<h3 class="f16px fb cred ma-l10px ma-b5px ma-t5px">关注我们</h3>
				<ul class="social ma-l20px bg-icon cblack">
					<li><?php echo l('我们的人人主页', param('renrenUrl'), array('target'=>'_blank'));?></li>
					<li><?php echo l('我们的新浪微博', param('sinatUrl'), array('target'=>'_blank'));?></li>
					<li><?php echo l('我们的腾讯微博', param('qqtUrl'), array('target'=>'_blank'));?></li>
					<li><?php echo l('我们的开心主页', param('kaixin001Url'), array('target'=>'_blank'));?></li>
				</ul>
			</div>
        	<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
    	</div>
	</div>
	<div class="main-ad fr corner">
		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
		<div id="slide" class="content" style="height:280px;">
		    <ol class="ks-switchable-content">
		    	<li><a href="javascript:void(0);"><img src="<?php echo resBu('images/index-1.jpg');?>" /></a></li>
		        <li class="none"><a href="javascript:void(0);"><img src="<?php echo resBu('images/index-2.jpg');?>" /></a></li>
		    </ol>
		</div>
		<!--end c-->
		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b></b>
	</div>
	<div class="clear"></div>
</div>


<div class="ma-t10px lh24px">
	<div class="joinin corner corner-gray ma-r10px fl ">
		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
		<div class="content">
			<h3 class="f16px cred ma-l10px ma-b5px ma-t5px">商铺加盟</h3>
            <ul class="disc ma-l30px">
                <li>扩展店铺生意，而不增加面积、设备。</li>
                <li>免费的宣传推广。</li>
                <li>免费的、完美的网站管理平台。</li>
            </ul>
		</div>
		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
	</div>
	<div class="opensite ma-r10px fl corner">
		<b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
		<div class="content">
    		<h3 class="f16px cred ma-l10px ma-b5px ma-t5px">城市开通</h3>
<script type="text/javascript">
//定义 城市 数据数组
cityArray = new Array();
cityArray[0] = new Array("北京市","北京市");
cityArray[1] = new Array("上海市","上海市");
cityArray[2] = new Array("天津市","天津市");
cityArray[3] = new Array("重庆市","重庆市");
cityArray[4] = new Array("河北省","石家庄|邯郸|邢台|保定|张家口|承德|廊坊|唐山|秦皇岛|沧州|衡水");
cityArray[5] = new Array("山西省","太原|大同|阳泉|长治|晋城|朔州|吕梁|忻州|晋中|临汾|运城");
cityArray[6] = new Array("内蒙古自治区","呼和浩特|包头|乌海|赤峰|呼伦贝尔盟|阿拉善盟|哲里木盟|兴安盟|乌兰察布盟|锡林郭勒盟|巴彦淖尔盟|伊克昭盟");
cityArray[7] = new Array("辽宁省","沈阳|大连|鞍山|抚顺|本溪|丹东|锦州|营口|阜新|辽阳|盘锦|铁岭|朝阳|葫芦岛");
cityArray[8] = new Array("吉林省","长春|吉林|四平|辽源|通化|白山|松原|白城|延边");
cityArray[9] = new Array("黑龙江省","哈尔滨|齐齐哈尔|牡丹江|佳木斯|大庆|绥化|鹤岗|鸡西|黑河|双鸭山|伊春|七台河|大兴安岭");
cityArray[10] = new Array("江苏省","南京|镇江|苏州|南通|扬州|盐城|徐州|连云港|常州|无锡|宿迁|泰州|淮安");
cityArray[11] = new Array("浙江省","杭州|宁波|温州|嘉兴|湖州|绍兴|金华|衢州|舟山|台州|丽水");
cityArray[12] = new Array("安徽省","合肥|芜湖|蚌埠|马鞍山|淮北|铜陵|安庆|黄山|滁州|宿州|池州|淮南|巢湖|阜阳|六安|宣城|亳州");
cityArray[13] = new Array("福建省","福州|厦门|莆田|三明|泉州|漳州|南平|龙岩|宁德");
cityArray[14] = new Array("江西省","南昌市|景德镇|九江|鹰潭|萍乡|新馀|赣州|吉安|宜春|抚州|上饶");
cityArray[15] = new Array("山东省","青岛|淄博|枣庄|东营|烟台|潍坊|济宁|泰安|威海|日照|莱芜|临沂|德州|聊城|滨州|菏泽");
cityArray[16] = new Array("河南省","郑州|开封|洛阳|平顶山|安阳|鹤壁|新乡|焦作|濮阳|许昌|漯河|三门峡|南阳|商丘|信阳|周口|驻马店|济源");
cityArray[17] = new Array("湖北省","武汉|宜昌|荆州|襄樊|黄石|荆门|黄冈|十堰|恩施|潜江|天门|仙桃|随州|咸宁|孝感|鄂州");
cityArray[18] = new Array("湖南省","长沙|常德|株洲|湘潭|衡阳|岳阳|邵阳|益阳|娄底|怀化|郴州|永州|湘西|张家界");
cityArray[19] = new Array("广东省","广州|深圳|珠海|汕头|东莞|中山|佛山|韶关|江门|湛江|茂名|肇庆|惠州|梅州|汕尾|河源|阳江|清远|潮州|揭阳|云浮");
cityArray[20] = new Array("广西壮族自治区","南宁|柳州|桂林|梧州|北海|防城港|钦州|贵港|玉林|南宁地区|柳州地区|贺州|百色|河池");
cityArray[21] = new Array("海南省","海口|三亚");
cityArray[22] = new Array("四川省","成都|绵阳|德阳|自贡|攀枝花|广元|内江|乐山|南充|宜宾|广安|达川|雅安|眉山|甘孜|凉山|泸州");
cityArray[23] = new Array("贵州省","贵阳|六盘水|遵义|安顺|铜仁|黔西南|毕节|黔东南|黔南");
cityArray[24] = new Array("云南省","昆明|大理|曲靖|玉溪|昭通|楚雄|红河|文山|思茅|西双版纳|保山|德宏|丽江|怒江|迪庆|临沧");
cityArray[25] = new Array("西藏自治区","拉萨|日喀则|山南|林芝|昌都|阿里|那曲");
cityArray[26] = new Array("陕西省","西安|宝鸡|咸阳|铜川|渭南|延安|榆林|汉中|安康|商洛");
cityArray[27] = new Array("甘肃省","兰州|嘉峪关|金昌|白银|天水|酒泉|张掖|武威|定西|陇南|平凉|庆阳|临夏|甘南");
cityArray[28] = new Array("宁夏回族自治区","银川|石嘴山|吴忠|固原");
cityArray[29] = new Array("青海省","西宁|海东|海南|海北|黄南|玉树|果洛|海西");
cityArray[30] = new Array("新疆维吾尔族自治区","乌鲁木齐|石河子|克拉玛依|伊犁|巴音郭勒|昌吉|克孜勒苏柯尔克孜|博尔塔拉|吐鲁番|哈密|喀什|和田|阿克苏");
cityArray[31] = new Array("香港特别行政区","香港特别行政区");
cityArray[32] = new Array("澳门特别行政区","澳门特别行政区");
cityArray[33] = new Array("台湾省","台北|高雄|台中|台南|屏东|南投|云林|新竹|彰化|苗栗|嘉义|花莲|桃园|宜兰|基隆|台东|金门|马祖|澎湖");
cityArray[34] = new Array("其它","北美洲|南美洲|亚洲|非洲|欧洲|大洋洲");

function getCity(currProvince) {
	var currProvincecurrProvince = currProvince;
	var i,j,k;
	var option = '';
	$("#selCity").html();
	for (i=0; i<cityArray.length; i++) {
		if(cityArray[i][0]==currProvince) {
			tmpcityArray = cityArray[i][1].split("|");
			for(j=0;j<tmpcityArray.length;j++) {
				option += "<option value='"+tmpcityArray[j]+"'>"+tmpcityArray[j]+"</option>";
			}
			$("#selCity").html(option);
		}
	}
}
</script>
<?php echo CHtml::beginForm('', 'post');?>
                <ul class="disc ma-l30px">
                    <li>希望在
                    <select id="selProvince" onchange="getCity(this.options[this.selectedIndex].value)" style="width:80px" name="OpenSuggest[province]">
						<option value="">-请选择-</option>
						<option value="北京市">北京市</option>
						<option value="上海市">上海市</option>
						<option value="天津市">天津市</option>
						<option value="重庆市">重庆市</option>
						<option value="河北省">河北省</option>
						<option value="山西省">山西省</option>
						<option value="内蒙古自治区">内蒙古自治区</option>
						<option value="辽宁省">辽宁省</option>
						<option value="吉林省">吉林省</option>
						<option value="黑龙江省">黑龙江省</option>
						<option value="江苏省">江苏省</option>
						<option value="浙江省">浙江省</option>
						<option value="安徽省">安徽省</option>
						<option value="福建省">福建省</option>
						<option value="江西省">江西省</option>
						<option value="山东省">山东省</option>
						<option value="河南省">河南省</option>
						<option value="湖北省">湖北省</option>
						<option value="湖南省">湖南省</option>
						<option value="广东省">广东省</option>
						<option value="广西壮族自治区">广西壮族自治区</option>
						<option value="海南省">海南省</option>
						<option value="四川省">四川省</option>
						<option value="贵州省">贵州省</option>
						<option value="云南省">云南省</option>
						<option value="西藏自治区">西藏自治区</option>
						<option value="陕西省">陕西省</option>
						<option value="甘肃省">甘肃省</option>
						<option value="宁夏回族自治区">宁夏回族自治区</option>
						<option value="青海省">青海省</option>
						<option value="新疆维吾尔族自治区">新疆维吾尔族自治区</option>
						<option value="香港特别行政区">香港特别行政区</option>
						<option value="澳门特别行政区">澳门特别行政区</option>
						<option value="台湾省">台湾省</option>
                    </select>
                    <select id="selCity" style="width:80px" name="OpenSuggest[city]">
                    	<option>-请选择-</option>
                    </select>
                    <br />开通我爱外卖服务。
                    </li>
    				<li>邮箱：<input name="OpenSuggest[email]" type="text" size="16" class="txt" style="width:120px; height:22px;" />&nbsp;&nbsp;<?php echo CHtml::submitButton('提交');?></li>
    				<?php if($success):?>
    				<li class="cred"><?php echo $success;?></li>
    				<?php endif;?>
    			</ul>
<?php echo CHtml::endForm();?>
    	</div>
		<b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
	</div>
    <div class="wm-feature fr corner corner-gray">
        <b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
        <div class="content">
            <h3 class="f16px cred ma-l10px ma-b5px ma-t5px">选择我爱外卖</h3>
            <ul class="disc ma-l30px">
                <li>覆盖全市的本地化服务方便你的生活。</li>
                <li>蛋糕，美食以及不断增加的本地服务。</li>
                <li>客观丰富的点评，帮你挑选喜爱的服务。</li>
        	</ul>
        </div>
        <b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
        </div><div class="clear"></div>
    </div>
</div>

<div class="index-sidebar fr">
    <div class="online-help corner corner-gray">
        <b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
        <div class="content lh24px">
            <h3 class="ma-l10px f16px ma-b10px ma-t5px">在线帮助</h3>
            <div class="ma-l10px f14px cgray">
                <p>需要帮助请和我们联系</p>
                <p>服务时间9:00-18:00</p>
                <div class="ma-t10px ma-l30px">
                	<a class="btn-four-gray block ac lh30px" id="comm100-chat" target="_blank" href="<?php echo param('comm100Url');?>"><span class="cred f12px">在线客服</span></a>
                </div><div class="clear"></div>
            </div>
        </div>
        <b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
    </div>
    <div class="order-summary corner ma-t10px">
        <b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
        <div class="content lh24px ac f12px">
            <h3 class="ma-t10px f14px cgray">店铺数量</h3>
            <h2 class="fnum"><?php echo $shopCount;?></h2>
            <h3 class="ma-t10px f14px cgray">最近24小时订单数量</h3>
            <h2 class="fnum ma-b10px"><?php echo $order24Count;?></h2>
        </div>
        <b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
    </div>
	
	<div class="corner ma-t5px">
        <b class="corner-top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
        <div class="content ac"><?php echo l(CHtml::image(resBu('images/index-integral.gif')), url('my/default/inviteurl'))?></div>
        <b class="corner-bottom"><b class="b4"></b><b class="b3"></b><b	class="b2"></b><b class="b1"></b></b>
    </div>
    <?php // 最新动态 $this->widget('LatestTendency');?>
</div>


<?php
cs()->registerScriptFile(resBu('scripts/kissy-min.js'), CClientScript::POS_HEAD);
cs()->registerScriptFile(resBu('scripts/suggest-pkg-min.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/switchable-pkg-min.js'), CClientScript::POS_END);
?>
<script type="text/javascript">
KISSY.ready(function(S) {
	var dataUrl = '<?php echo aurl('at/suggest');?>';
	var sug = new S.Suggest('#location_search', dataUrl, {
		containerCls: 'suggest-container',
		//resultFormat: '',
		charset: 'utf-8',
		queryName: 'kw',
		callbackFn: 'get52WmKeyWords'
	});
	sug.on('dataReturn', function() {
		this.returnedData = this.returnedData || [];
	});
	S.Slide('#slide', {
        effect: 'scrolly',
        easing: 'easeOutStrong',
        countdown: true,
        countdownFromStyle: 'width:18px'
    });
});

$(function(){
	// 搜索框文字处理
	var location_search_input = $("#location_search").val();
	$("#location_search").blur(function(){
		if($(this).val()=='') {
			$(this).val(location_search_input);
		}
		$(this).removeClass('cblack');
	});
	$("#location_search").focus(function(){
		if($(this).val()==location_search_input) {
			$(this).val('');
		}
		$(this).addClass('cblack');
	});
	$("#searchSubmit").click(function(){
		if($("#location_search").val()==location_search_input) {
			alert('请输入您所在的地址');
			return false;
		}
	});
	
	$('#comm100-chat').click(function(e){
		e.preventDefault();
		wopen('<?php echo param('comm100Url');?>', 'comm100', 552, 550);
	});
});
</script>


<?php if(time() < mktime(0,0,0,11,28,2010)): ?>
<!-- 开业之前显示的提示 -->
<span id="showOverlayBox" url="<?php echo url('site/overlaybox');?>"></span>
<script type="text/javascript">
$(function(){
	showOverlayBox($('#showOverlayBox').attr('url'));
});
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>
<?php endif;?>