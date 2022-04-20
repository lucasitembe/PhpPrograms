<?php
require_once('./includes/connection.php');
session_start();
$medication_type =mysqli_real_escape_string($conn,$_POST['medication_type']);
$Round_ID =mysqli_real_escape_string($conn,$_POST['Round_ID']);
$Registration_ID =mysqli_real_escape_string($conn,$_POST['Registration_ID']);
$medication_data =$_POST['medication_data'];
$consultation_ID =mysqli_real_escape_string($conn,$_POST['consultation_ID']);
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$anasthesia_record_id = mysqli_real_escape_string($conn, $_POST['anasthesia_record_id']);
$anaesthesiamedication =mysqli_real_escape_string($conn, $_POST['anaesthesiamedication']);
$count_succ=0;
$count_fail=0;
foreach($medication_data as $data){
    
    $data_array=explode("unganisha",$data);
    $Payment_Item_Cache_List_ID=mysqli_real_escape_string($conn,$data_array[0]);
    $drip_rate=mysqli_real_escape_string($conn,$data_array[1]);
    $remarks=mysqli_real_escape_string($conn,$data_array[2]);
    $discontinue=mysqli_real_escape_string($conn,$data_array[3]);
    $discontinue_reason=mysqli_real_escape_string($conn,$data_array[4]);
    $route_type=mysqli_real_escape_string($conn,$data_array[5]);
    $amount_given=mysqli_real_escape_string($conn,$data_array[6]);
    $medication_time=mysqli_real_escape_string($conn,$data_array[7]);
    $Item_ID=mysqli_real_escape_string($conn,$data_array[8]);
    $dosage_new=mysqli_real_escape_string($conn,$data_array[9]);

    if($discontinue_reason==""){
        $discontinue_reason=".";
    }
        $insert_services_given = "INSERT INTO  tbl_inpatient_medicines_given(
				Payment_Item_Cache_List_ID,
                                Item_ID,
                                Time_Given, 
				Amount_Given, 
				Nurse_Remarks, 
				Employee_ID, 
				Registration_ID,
                                consultation_ID,
				Discontinue_Status, 
				Discontinue_Reason,
                                Round_ID,
                                Medication_type,route_type,drip_rate,medication_time,given_time
				) 
				VALUES(
				$Payment_Item_Cache_List_ID,
                                    '$Item_ID ',
				NOW(), 
				'$amount_given', 
				'$remarks', 
				'$Employee_ID', 
				'$Registration_ID', 
                                '$consultation_ID',
				'$discontinue', 
				'$discontinue_reason',
                                '$Round_ID',
                                '$medication_type','$route_type','$drip_rate','$medication_time','$dosage_new'
				)";
// die($insert_services_given);

    $save_services_given = mysqli_query($conn,$insert_services_given) or die(mysqli_error($conn));
    if($save_services_given){
       $count_succ++; 
    }else{
       $count_fail++;
    }

    $insertmaintanance = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_maintanance (Item_ID,anasthesia_record_id, Registration_ID, Employee_ID) VALUES('$Item_ID','$anasthesia_record_id', '$Registration_ID', '$Employee_ID' ) ") or die(mysqli_error($conn));

}
echo "<div class='alert alert-success'><b>Process Successfully!</b> $count_succ saved , $count_fail fail</div>";