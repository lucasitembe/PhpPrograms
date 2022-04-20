<?php
session_start();
include("../includes/connection.php");

if(isset($_POST['action'])){
  if($_POST['action']=='history'){
     $itemID=$_POST['itemID'];
     $patientID=$_POST['patientID'];
     $parameterID=$_POST['parameterID'];
	 $ppil=$_POST['ppil'];
     $sn=1;
	 
	// die($ppil);
     
    $historyQ="SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='".$parameterID."' AND tilc.Item_ID='".$itemID."' AND tr.payment_item_ID<>'".$ppil."'";
	
	//die($historyQ);
    $Queryhistory=mysqli_query($conn,$historyQ);
    $myrows=mysqli_num_rows($Queryhistory);
    echo "<center><table class='' style='width:100%'>";       
    echo "<tr style='background-color:rgb(200,200,200)'>
    <th width='1%'>S/n</th>
    <th width=''>Date</th>
    <th width=''>Results</th>
    </tr>";
     while ($result3=  mysqli_fetch_assoc($Queryhistory)){
         echo '<tr>';
         echo '<td>'.$sn++.'</td>';
         echo '<td>'.$result3['TimeSubmitted'].'</td>';
         echo '<td>'.$result3['result'].'</td>';   
         echo '</tr>';
}
echo '</table>';  
     
     
  }  
  
}else{
$parameter=$_POST['parameter'];
$paymentID=$_POST['paymentID'];
//die($paymentID.' '.$parameter);
$sn=1;
$viewHistory="SELECT * FROM tbl_test_result_modification JOIN tbl_employee ON tbl_employee.Employee_ID=tbl_test_result_modification.employee_ID WHERE `test_result_ID`='".$paymentID."' and `Parameter`='".$parameter."'";
$QueryHistory=  mysqli_query($conn,$viewHistory);
        echo "<center><table class='' style='width:100%'>";       
        echo "<tr style='background-color:rgb(200,200,200)'>
        <th width='1%'>S/n</th>
        <th width=''>Modifier</th>
        <th width=''>Time</th>
        <th width=''>Results</th>
        </tr>";
                
        while ($result3=  mysqli_fetch_assoc($QueryHistory)){
         echo '<tr>';
         echo '<td>'.$sn++.'</td>';
         echo '<td>'.$result3['Employee_Name'].'</td>';
         echo '<td>'.$result3['timeModified'].'</td>';
         echo '<td>'.$result3['result'].'</td>';   
         echo '</tr>';
       }
echo '</table>';  
    
    
}


?>