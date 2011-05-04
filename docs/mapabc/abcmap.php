<?php
$lnk = mysql_connect('localhost', 'root', '') or die ('Not connected : ' . mysql_error());
mysql_select_db('test', $lnk) or die ('Can\'t use test : ' . mysql_error());
mysql_query("set names utf8;");

/*
// 显示 重名的记录id
$array = array();
$ids = array();
$sql = "select * from abcmap order by id desc";
$query = mysql_query($sql);
while ($row = mysql_fetch_array($query)) {
	if($array[$row['name']]) {
		mysql_query("delete from abcmap where id=".$row['id']);
		//$ids[$array[$row['name']]] = $array[$row['name']];
		$ids[$row['id']] = "'".$row['name']."'";
	} else {
		$array[$row['name']] = $row['id'];
	}
}
echo implode(',', $ids);
*/

/*
// 过滤重复的删除
$array = array();
$ids = array();
$sql = "select * from abcmap order by id desc";
$query = mysql_query($sql);
while ($row = mysql_fetch_array($query)) {
	if($array[$row['name']] && $array[$row['name']]==$row['x'].','.$row['y']) {
		mysql_query("delete from abcmap where id=".$row['id']);
	} else {
		$array[$row['name']] = $row['x'].','.$row['y'];
	}
}
*/



// ajax 请求响应自己理
$data = $_POST['data'];
if($data) {
	$list = explode('||',$data);
	$keywords = $_POST['k'];
	$sql = "insert into abcmap values ";
	foreach($list as $value) {
		if($value) {
			$c = explode(':',$value);
			$a = explode(',',$c[1]);
			$name = $c[0];
			$x = $a[0];
			$y = $a[1];
			$address = $c[2];
			$type = $c[3];
			$sql .= " ('','$keywords','$name','$x','$y', '$address', '$type'),";
		}
	}
	$sql = substr($sql, 0, -1);
	if(mysql_query($sql)) {
		echo '1';
	}
}

?>