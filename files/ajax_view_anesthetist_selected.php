
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";  
        }
     $added_anestheologist="";  
     
     $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND DATE(anasthesia_created_at)=CURDATE()";
     $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
     if(mysqli_num_rows($anasthesia_record_result)>0){
         $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
     }
     

$sql_search_anestheologist_name_result=mysqli_query($conn,"SELECT anesthesia_anesthetist_id, Employee_Name FROM tbl_employee td,tbl_anasthesia_anesthetist ad WHERE td.Employee_ID=ad.Anasthetist_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_anestheologist_name_result)>0){
    $count_sn=1;
    while($anestheologist_row=mysqli_fetch_assoc($sql_search_anestheologist_name_result)){
        $anesthesia_anesthetist_id=$anestheologist_row['anesthesia_anesthetist_id'];
        $Employee_Name=$anestheologist_row['Employee_Name'];
        $added_anestheologist .= "$Employee_Name, ";
    }
}
echo $added_anestheologist; 