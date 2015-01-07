<?php
include_once('connect.php');

$sql = "select * from calendar";
$query = mysqli_query($link, $sql);
//$query = mysql_query($sql);
while($row=mysqli_fetch_array($query)){
	$allday = $row['allday'];
	$is_allday = $allday==1?true:false;
	
	$data[] = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'start' => date('Y-m-d H:i',$row['starttime']),
		'end' => date('Y-m-d H:i',$row['endtime']),
		'url' => $row['url'],
		'allDay' => $is_allday,
		'color' => $row['color']
	);
}
echo json_encode($data);
?>