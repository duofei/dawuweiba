<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>
数据接口</title>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.1&services=true"></script>
</head>
<body>
<div style="width:520px;height:340px;border:1px solid gray" id="container">
</div>
<div id="log" style="font-size:13px;margin-top:10px;">
</div>
<div>
	<input type="text" id="text" />
	<input type="button" value="查询" onclick="search()" />
</div>
</body>
</html>
<script type="text/javascript">

var map = new BMap.Map("container");
map.centerAndZoom(new BMap.Point(116.987313, 36.672182), 11); //116.396713,39.908419

var options = {
		pageCapacity: 10,
  onSearchComplete: function(results){
    if (local.getStatus() == BMAP_STATUS_SUCCESS){
      // 判断状态是否正确
      var s = [];
      for (var i = 0; i < results.getCurrentNumPois(); i ++){
        s.push(results.getPoi(i).title + ", " + results.getPoi(i).address + ", " + results.getPoi(i).point.lat + "," + results.getPoi(i).point.lng);
      }
      document.getElementById("log").innerHTML = s.join("<br/>");
    }
  }
};
var local = new BMap.LocalSearch(map, options);
local.setPageCapacity(50);
function search(){
	var kw = document.getElementById('text').value;
	local.search(kw);
}
</script>