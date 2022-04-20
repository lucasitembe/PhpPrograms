<?php

if(function_exists($_GET['method']) && !empty($_GET['method'])){

	$_GET['method']();
 


}

function getData(){

	$mydata=array('hrdata');

	$data = json_encode($mydata);

	echo $_GET['jsoncallback'].'('.$data.')';
}