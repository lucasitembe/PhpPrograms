<?php	
include("./includes/connection.php");
if(isset($_POST['BillId'])){
   $BillId=$_POST['BillId']; 
}
if(isset($_POST['RI'])){
    $Registration_ID=$_POST['RI']; 
 }
$BillControlNumber=0;
$sql_select_control_number_result=mysqli_query($conn,"SELECT BillControlNumber FROM bill WHERE BillId='$BillId'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_control_number_result)>0){
   $BillControlNumber=mysqli_fetch_assoc($sql_select_control_number_result)['BillControlNumber']; 
}
if ($BillControlNumber > 0){
   echo "<h3>Control Number:".$BillControlNumber."</h3><br /><a style='' href='print_gepg_controlnumber.php?previewnly=true&RI=" . $Registration_ID . "&cnumber=" . $BillControlNumber . "'' class='art-button-green' target='_blank'>P R I N T</a>"; 
}else{
    echo "<h3>Control Number:".$BillControlNumber."</h3><br />";
}
