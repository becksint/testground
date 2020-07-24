<?php
session_start();
include('../api/common/collect.php');
include('../api/common/jason.php');
include('../api/common/sendemail.php');

if(isset($_REQUEST["want"])){
	$q = $_REQUEST["want"];
	$call = explode("__",$q);
	if(isset($call[7])){  $request = array(2); }
	else{ $request = $call[1]; } 
	echo cards($call[0], $request, $call[1], $call[2],$call[3] , $call[4] , $call[5], $call[6]); 
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //var_dump($_POST);
    sendmail($_POST['input']);
    unset($_SERVER['REQUEST_METHOD']);
}else{
   //echo 'Apologies request was not'
}

?>