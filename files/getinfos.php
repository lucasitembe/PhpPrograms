<?php
if(isset($_GET['getage'])){
$date1=strtotime(date('Y-m-d'));
$time1=$_GET['getage']*31556926;
$dob1 = $date1-$time1;
$dob=date('Y-m-d',$dob1);
echo $dob;
}
