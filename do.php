<?php
include_once('connect.php');//连接数据库

$action = $_GET['action'];
if($action=='add'){
	$events = stripslashes(trim($_POST['event']));//事件内容
	//$events=mysql_real_escape_string(strip_tags($events)); //过滤HTML标签，并转义特殊字符
        $events = $link->real_escape_string(strip_tags($events));

	$isallday = $_POST['isallday'];//是否是全天事件
	$isend = $_POST['isend'];//是否有结束时间

	$startdate = trim($_POST['startdate']);//开始日期
	$enddate = trim($_POST['enddate']);//结束日期

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

	if($isallday==1 && $isend==1){
		$starttime = strtotime($startdate);
		$endtime = strtotime($enddate);
	}elseif($isallday==1 && $isend==""){
		$starttime = strtotime($startdate);
	}elseif($isallday=="" && $isend==1){
		$starttime = strtotime($startdate.' '.$s_time);
		$endtime = strtotime($enddate.' '.$e_time);
	}else{
		$starttime = strtotime($startdate.' '.$s_time);
	}

	$colors = array("#360","#f30","#06c");
	$key = array_rand($colors);
	$color = $colors[$key];

	$isallday = $isallday?1:0;
        
        $sql = '';
        if($isend){
            $sql = "insert into `calendar` (`title`,`starttime`,`endtime`,`allday`,`color`) values ('$events','$starttime','$endtime','$isallday','$color')";
        }else{
            $sql = "insert into `calendar` (`title`,`starttime`,`allday`,`color`) values ('$events','$starttime','$isallday','$color')";
        }
        
        mysqli_query($link, $sql);//执行新增
        //获取最后新增id
	if(mysqli_insert_id($link)>0){
		echo '1';
	}else{
		echo '写入失败！ ';
	}
}elseif($action=="edit"){
	$id = intval($_POST['id']);
	if($id==0){
		echo '事件不存在！';
		exit;	
	}
	$events = stripslashes(trim($_POST['event']));//事件内容
	//$events=mysql_real_escape_string(strip_tags($events),$link); //过滤HTML标签，并转义特殊字符
        $events = $link->real_escape_string(strip_tags($events));

	$isallday = $_POST['isallday'];//是否是全天事件
	$isend = $_POST['isend'];//是否有结束时间

	$startdate = trim($_POST['startdate']);//开始日期
	$enddate = trim($_POST['enddate']);//结束日期

	$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
	$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

	if($isallday==1 && $isend==1){
		$starttime = strtotime($startdate);
		$endtime = strtotime($enddate);
	}elseif($isallday==1 && $isend==""){
		$starttime = strtotime($startdate);
		$endtime = 0;
	}elseif($isallday=="" && $isend==1){
		$starttime = strtotime($startdate.' '.$s_time);
		$endtime = strtotime($enddate.' '.$e_time);
	}else{
		$starttime = strtotime($startdate.' '.$s_time);
		$endtime = 0;
	}

	$isallday = $isallday?1:0;
        $sql = "update `calendar` set `title`='$events',`id`='$id',`starttime`='$starttime',`endtime`='$endtime',`allday`='$isallday' where `id`='$id'";
	mysqli_query($link, $sql);//执行新增
	if(mysqli_affected_rows($link)==1){
		echo '1';
	}else{
		echo '出错了！';	
	}
}elseif($action=="del"){
	$id = intval($_POST['id']);
	if($id>0){
                $sql = "delete from `calendar` where `id`='$id'";
		mysqli_query($link, $sql);
		if(mysqli_affected_rows($link)==1){
			echo '1';
		}else{
			echo '出错了！';	
		}
	}else{
		echo '事件不存在！';
	}
}elseif($action=="drag"){
	$id = $_POST['id'];
	$daydiff = (int)$_POST['daydiff']*24*60*60;
	$minudiff = (int)$_POST['minudiff']*60;
	$allday = $_POST['allday'];
        $sql = "select * from `calendar` where id='$id'";
	$query  = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($query);
	//echo $allday;exit;
	if($allday=="true"){
		if($row['endtime']==0){
			$sql = "update `calendar` set starttime=starttime+'$daydiff' where id='$id'";
		}else{
			$sql = "update `calendar` set starttime=starttime+'$daydiff',endtime=endtime+'$daydiff' where id='$id'";
		}
		
	}else{
		$difftime = $daydiff + $minudiff;
		if($row['endtime']==0){
			$sql = "update `calendar` set starttime=starttime+'$difftime' where id='$id'";
		}else{
			$sql = "update `calendar` set starttime=starttime+'$difftime',endtime=endtime+'$difftime' where id='$id'";
		}
	}
	$result = mysqli_query($link,$sql);
	if(mysqli_affected_rows($link)==1){
		echo '1';
	}else{
		echo '出错了！';	
	}
}elseif($action=="resize"){
	$id = $_POST['id'];
	$daydiff = (int)$_POST['daydiff']*24*60*60;
	$minudiff = (int)$_POST['minudiff']*60;
	
        $sql = "select * from `calendar` where id='$id'";
	$query  = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($query);
	//echo $allday;exit;
	$difftime = $daydiff + $minudiff;
	if($row['endtime']==0){
		$sql = "update `calendar` set endtime=starttime+'$difftime' where id='$id'";
	}else{
		$sql = "update `calendar` set endtime=endtime+'$difftime' where id='$id'";
	}
	
	$result = mysqli_query($link, $sql);
	if(mysqli_affected_rows($link)==1){
		echo '1';
	}else{
		echo '出错了！';	
	}
}else{
	
}
?>