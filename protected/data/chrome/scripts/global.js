var request_interval;
var music_interval;
var notification;

var rTime = 20000;		// 请求时间
var playTime = 60000; 	// 播放声音
var audioSrc = 'system.wav';

/* 初始化 */
function init() {
	setValue('waitNum', '0');
	setValue('o_approve', '0');
	setValue('p_unline', '0');
	
	chrome.browserAction.setBadgeBackgroundColor({
		'color':[039,139,001,255]
	});
}

/* 服务器请求 */
function getRequest() {
	var url = 'http://www.52wm.com/chromeapp/remind';
	$.getJSON(url, function(data){
		if(data) {
			var o_approve = data.o_approve;
			setValue('o_approve', o_approve);
			var p_unline = data.p_unline;
			setValue('p_unline', p_unline);
			var o_undisposed = data.o_undisposed;
			setValue('o_undisposed', o_undisposed);
			
			var waitNum = parseInt(o_approve) + parseInt(p_unline) + parseInt(o_undisposed);
			waitNum = isNaN(waitNum) ? 0 : waitNum;
			setValue('waitNum', waitNum);
			
			showBadge();
			if(waitNum > 0) {
				showNotification();
			}
		}
	});	
}

/* setBadge */
function showBadge() {
	var text = getValue('waitNum');
	chrome.browserAction.setBadgeText({'text':text});
}

/* 显示提示 */
function showNotification(){
	notification = webkitNotifications.createHTMLNotification(
		'notification.html'
	);
	notification.onclose = function(){
		request_interval = setInterval(getRequest, rTime);
		clearInterval(music_interval);
	}
	notification.ondisplay = function() {
		clearInterval(request_interval);
		playMusic();
		music_interval = setInterval(playMusic, playTime);
	}
	notification.show();
}

/* 播放音乐 */
function playMusic() {
	var audio = new Audio();
	audio.src = audioSrc;
	audio.play();
}

/* localStorage */
function setValue(k, v) {
	localStorage[k] = v;
}
function getValue(k) {
	return localStorage[k];
}
function clearValue(k) {
	localStorage[k] = '';
}
function clearAllValue() {
	localStorage.clear();
}