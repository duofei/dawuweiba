
<div class="fl w240px" >
    <div class="border-red">
    	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px">历史团购</h3>
     	</div>
        
        <div class="Calendar">
            <div class="Calendar-title">
                <div id="idCalendarPre"><<</div>
                <div id="idCalendarNext">>></div>
                <span id="idCalendarYear">2008</span>年 <span id="idCalendarMonth">8</span>月
            </div>
            <table cellspacing="3" >
                <thead >
                    <tr class="cblack fw">
                        <td>日</td>
                        <td>一</td>
                        <td>二</td>
                        <td>三</td>
                        <td>四</td>
                        <td>五</td>
                        <td>六</td>
                    </tr>
                </thead>
                <tbody id="idCalendar">
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="border-red ma-t10px pa-b5px h-100" >
    	<div class="groupbuy-title ma-b5px">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 人气排行</h3>
     	</div>
        <?php foreach ((array)$tuanbuy as $key=>$val) :?>
   		<div class=" border-bottom-dot m5px pa-b5px">
        	<div class="fl click-num lh24px ac"><?php echo $val->buy_num?></div>            
            <div class="fl ma-l10px w176px" >
      			【<?php echo $val->tuandata->name?>】 <a href="<?php echo $val->absoluteUrl?>" target="_blank" title="<?php echo $val->title?>"><?php echo $val->titlesub?></a>
            </div><div class="clear"></div>
        </div>
        <?php endforeach;?>
     </div>   
     <div class="border-red ma-t10px pa-b5px h-100" >
    	<div class="groupbuy-title">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 转让求购</h3>
     	</div>
        <div class="pa-5px lh30px cblack ">
        <?php foreach ((array)$tuansecond as $key=>$val):?>
        <?php if ($val->trade_sort == TuanSecondHand::TRADE_SORT_SELL):?>
        	<p class="<?php if ($val->state == STATE_ENABLED) echo 'a-bargain'?> a-seller border-bottom-dot"><a href="<?php echo url('tuannav/second', array('sort'=>'sell' ))?>" target="_blank"> <?php echo $val->titleSub?></a></p>
        <?php else :?>
        	<p class="<?php if ($val->state == STATE_ENABLED) echo 'a-bargain'?> a-buy border-bottom-dot"><a href="<?php echo url('tuannav/second', array('sort'=>'buy' ))?>" target="_blank"> <?php echo $val->titleSub?></a></p>
        <?php endif;?>
        <?php endforeach;?>
             <p class="ar ma-r10px"><a href="<?php echo url('tuannav/second', array('sort'=>'sell' ))?>" target="_blank">更多>></a></p>         
        </div> 
     </div>
     <div class="border-red ma-t10px pa-b5px h-100" >
    	<div class="groupbuy-title ma-b5px">
     		<h3 class="groupbuy-title-h3 lh30px f14px"> 推荐网站</h3>
     	</div>
        <?php echo CHtml::beginForm('','post',array('name'=>'add'));?>
       <div class="pa-5px lh20px cgray">
        	<p>欢迎推荐您喜欢的团购网站，我们会尽快核实网站的信息，收录到团购导航。</p>
            <p class="ma-t10px">网址 ：<?php echo CHtml::textField('url', '', array('class'=>'txt', 'style'=>'width:160px;'))?></p>
            <p><input class="ico-post cwhite fb ma-t10px " name="" type="submit" value="提&nbsp;交" /></p>
            <p><?php echo $success?></p>
        </div>
        <?php CHtml::endForm();?>
     </div>
</div>
<div class="clear"></div>



<script type="text/javascript"> 
var $$ = function (id) { 
return "string" == typeof id ? document.getElementById(id) : id; 
}; 
var Class = { 
create: function() { 
return function() { 
this.initialize.apply(this, arguments); 
} 
} 
} 
Object.extend = function(destination, source) { 
for (var property in source) { 
destination[property] = source[property]; 
} 
return destination; 
} 
var Calendar = Class.create(); 
Calendar.prototype = { 
initialize: function(container, options) { 
this.Container = $$(container);//容器(table结构) 
this.Days = [];//日期对象列表 
this.SetOptions(options); 
this.Year = this.options.Year; 
this.Month = this.options.Month;
this.SelectDay = this.options.SelectDay ? new Date(this.options.SelectDay) : null; 
this.onSelectDay = this.options.onSelectDay; 
this.onToday = this.options.onToday; 
this.onFinish = this.options.onFinish; 
this.Draw(); 
}, 
//设置默认属性 
SetOptions: function(options) { 
this.options = {//默认值 
Year: new Date().getFullYear(),//显示年 
Month: new Date().getMonth() + 1,//显示月 
SelectDay: null,//选择日期 
onSelectDay: function(){},//在选择日期触发 
onToday: function(){},//在当天日期触发 
onFinish: function(){}//日历画完后触发 
}; 
Object.extend(this.options, options || {}); 
}, 
//上一个月 
PreMonth: function() { 
//先取得上一个月的日期对象 
var d = new Date(this.Year, this.Month - 2, 1); 
//再设置属性 
this.Year = d.getFullYear(); 
this.Month = d.getMonth() + 1; 
//重新画日历 
this.Draw(); 
}, 
//下一个月 
NextMonth: function() { 
var d = new Date(this.Year, this.Month, 1); 
this.Year = d.getFullYear(); 
this.Month = d.getMonth() + 1; 
this.Draw(); 
}, 
//画日历 
Draw: function() { 
//用来保存日期列表 
var arr = []; 
//用当月第一天在一周中的日期值作为当月离第一天的天数 
for(var i = 1, firstDay = new Date(this.Year, this.Month - 1, 1).getDay(); i <= firstDay; i++){ arr.push(" "); } 
//用当月最后一天在一个月中的日期值作为当月的天数 
for(var i = 1, monthDay = new Date(this.Year, this.Month, 0).getDate(); i <= monthDay; i++){ arr.push(i); } 
var frag = document.createDocumentFragment(); 
this.Days = []; 
while(arr.length > 0){ 
//每个星期插入一个tr 
var row = document.createElement("tr"); 
//每个星期有7天 
for(var i = 1; i <= 7; i++) { 
	var cell = document.createElement("td");
	cell.innerHTML = ' '; 
	if(arr.length > 0) { 
		var d = arr.shift(); 
		cell.innerHTML = d; 
		if(d > 0) { 
    		this.Days[d] = cell; 
    		//判断是否今日 
			var dd = <?php echo strtotime(strip_tags(trim($_GET['day'])))*1000;?>;
			var ddd = dd ? new Date(dd) : new Date();
    		if(this.IsSame(new Date(this.Year, this.Month - 1, d), ddd)){ this.onToday(cell);}
    		//判断是否选择日期 
    		if(this.SelectDay && this.IsSame(new Date(this.Year, this.Month - 1, d), this.SelectDay)){ this.onSelectDay(cell); } 
		}
	}
	row.appendChild(cell);
}
frag.appendChild(row); 
}
//先清空内容再插入(ie的table不能用innerHTML)
while(this.Container.hasChildNodes()){ this.Container.removeChild(this.Container.firstChild);}
this.Container.appendChild(frag); 
this.onFinish(); 
}, 
//判断是否同一日 
IsSame: function(d1, d2) { 
return (d1.getFullYear() == d2.getFullYear() && d1.getMonth() == d2.getMonth() && d1.getDate() == d2.getDate()); 
} 
}; 
</script>
<script language="JavaScript"> 
var cale = new Calendar("idCalendar", { 
	SelectDay: new Date().setDate(0), 
	onSelectDay: function(o){ o.className = "onSelect"; }, 
	onToday: function(o){ o.className = "onToday"; }, 
	onFinish: function(){ 
		$$("idCalendarYear").innerHTML = this.Year; 
		$$("idCalendarMonth").innerHTML = this.Month; 

		var len = this.Days.length;
		
		for(var i = 1; i < len; i++){
			var url = '' + this.Year +'-'+ this.Month +'-'+ i;
			this.Days[i].innerHTML = '<a href="<?php echo url('tuannav/search').'?day='?>'+ url +'">' + i + '</a>'; 
		} 
	} 
}); 
$$("idCalendarPre").onclick = function(){ cale.PreMonth(); } 
$$("idCalendarNext").onclick = function(){ cale.NextMonth(); } 
</script>
