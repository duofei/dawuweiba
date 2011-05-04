function getDateFromTimestamp(timestamp)
{
	d = new Date(timestamp * 1000);
 	return d.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " "); 
}

function bookmark()
{
	if ($.browser.msie)
		window.external.addFavorite(getHost(), document.title);
	else if ($.browser.mozilla)
		window.sidebar.addPanel(document.title, getHost(), "");
	else 
		alert('您使用的浏览器不地支持此操作');
	return false;
}

function getHost(url) {
    var host = "null";
    if (typeof url == "undefined" || null == url)
    	url = window.location.href;
    var regex = /(.*\:\/\/[^\/]*).*/;
    var match = url.match(regex);
    if(typeof match != 'undefined' && null != match)
    	host = match[1];
    return host;
}


function setHomepage(obj)
{
	if ($.browser.msie) {
		obj.style.behavior='url(#default#homepage)';
		obj.setHomePage(getHost());
	} else 
		alert('您使用的浏览器不地支持此操作');
	return false;
}

function updateVisitNums(postId)
{
	if (isNaN(postId)) return false;
	var url = $('#jsvar').attr('updateVisitNumsUrl');
	var data = 'postid=' + postId;
	$.post(url, data);
}

function share2Renren(e)
{
	e.preventDefault();
	var tthis = $(this);
	var shareInfo = {
		'medium': tthis.attr('share_type'),
		'title': tthis.attr('share_title'),
		'link': tthis.attr('share_link'),
		'image_src': tthis.attr('share_image'),
		'message': tthis.attr('share_message') ? tthis.attr('share_message') : '味道不错！',
		'description': tthis.attr('share_description')
	};
	XN.Connect.showShareDialog(shareInfo);
}

function share2Kaixin001(e)
{
	e.preventDefault();
	var tthis = $(this);
	var title = tthis.attr("share_title");
	var desc = tthis.attr("share_description");
	var url = 'http://www.kaixin001.com/repaste/bshare.php?rtitle=' + title + '&rcontent=' + desc;
	wopen(url, 'shareKaixin001', 700, 400);
}

function share2Sinat(e)
{
	e.preventDefault();
	var tthis = $(this);
	var title = tthis.attr('share_title');
	var appkey = tthis.attr('appkey');
	var shareUrl = tthis.attr('share_link');
	var picUrl = tthis.attr('share_pic');
	var url = 'http://v.t.sina.com.cn/share/share.php?appkey=' + appkey + '&title=' + title + '&url=' + shareUrl + '&pic=' + picUrl;
	wopen(url, 'shareSinat', 700, 400);
}

function wopen(url, name, width, height)
{
	var t = (window.screen.availHeight-30-height)/2;
	var l = (window.screen.availWidth-10-width)/2;
	window.open(url, name, "height="+height+",innerHeight="+height+",width="+width+",innerWidth="+width+",top="+t+",left="+l+",toolbar=no,menubar=no,scrollbars=auto,resizeable=no,location=no,status=no");
}

function share2Qqt(e)
{
	e.preventDefault();
	var tthis = $(this);
	var title = tthis.attr('share_title');
	var shareUrl = tthis.attr('share_link');
	var picUrl = tthis.attr('share_pic');
	var url = 'http://v.t.qq.com/share/share.php?title=' + title + '&url=' + shareUrl + '&pic=' + picUrl;
	wopen(url, 'shareQqt', 700, 400);
}


function loadRenrenLoader()
{
	$.getScript('http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp', function(){
    	XN_RequireFeatures(["EXNML"], function(){
    		XN.Main.init('49e422d84b694b69ba5e3c5809db4102', '/renren/xd_receiver.html');
    	});
    });
}

function showOverlayBox(url)
{
	$.getScript(RESBU + 'scripts/jquery.colorbox-min.js', function(){
		$.colorbox({href:url, overlayClose:false});
	});
}