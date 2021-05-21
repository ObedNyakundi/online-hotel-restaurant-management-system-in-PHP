<?php

//unset all existing cookies and head to index

//set cookies to remember the user for 30 days
	setcookie("name",$uname,time()-86400*31,"/","",0);
	setcookie("em",$em,time()-86400*31,"/","",0);
	setcookie("role",$type,time()-86400*31,"/","",0);
	
	//direct user to index.
	header("location:index.php");


?>