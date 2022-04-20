
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";  
        }
     $added_disease="";  
     
     $anasthesia_record = "SELECt anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND DATE(anasthesia_created_at)=CURDATE()";
     $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
     if(mysqli_num_rows($anasthesia_record_result)>0){
         $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
     }
     

$sql_search_surgeon_name_result=mysqli_query($conn,"SELECT anesthesia_surgeon_id, Employee_Name FROM tbl_employee td,tbl_anasthesia_surgeon ad WHERE td.Employee_ID=ad.Surgeon_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_surgeon_name_result)>0){
    $count_sn=1;
    while($disease_rows=mysqli_fetch_assoc($sql_search_surgeon_name_result)){
        $anesthesia_surgeon_id=$disease_rows['anesthesia_surgeon_id'];
        $Employee_Name=$disease_rows['Employee_Name'];
        $added_surgeon .= "$Employee_Name, ";
    }
}
echo $added_surgeon;