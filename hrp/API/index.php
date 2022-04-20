<?php
//$connection =mysql_connect('localhost','root','gpitg2014');
//$conn=mysql_select_db($connection,'hrp') or die(mysql_error('database connection fail'));

$connection = mysql_connect("127.0.0.1","root","ehms2gpitg2014");
	if (!$connection) {
		die("Database connection failed: " . mysql_error());
	}
	
	// 2. Select a database to use 
	
        $db_select = mysql_select_db("hrp",$connection);

         
	if (!$db_select) {
	   die("Database selection failed: " . mysql_error());
	} 


//our api file

if(function_exists($_GET['method'])){
	$_GET['method']();
}

function getLoan(){

$mydata = array();
$query = "select * from loan";
$result =mysql_query($query);
while($data=mysql_fetch_array($result)){
$mydata[] = $data;

}


	$data1  = json_encode($mydata);
	echo $_GET['jsoncallback'].'('.$data1.')';
}
