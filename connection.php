<?php
	$location="localhost";
	$account="root";
	$password="123456";
	if(isset($location)&&isset($account)&&isset($password)){
		$link=mysql_pconnect($location,$account,$password);
		if(!link){
			echo'無法連接資料庫';
			exit();
		}
		else {};
	}
?>