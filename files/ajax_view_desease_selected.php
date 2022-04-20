
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
     

$sql_search_disease_code_result=mysqli_query($conn,"SELECT anasthesia_diagnosis_id,disease_code,disease_name FROM tbl_disease td,tbl_anasthesia_diagnosis ad WHERE td.disease_ID=ad.disease_ID AND Registration_ID='$Registration_ID' AND anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_disease_code_result)>0){
    $count_sn=1;
    while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
        $anasthesia_diagnosis_id=$disease_rows['anasthesia_diagnosis_id'];
        $disease_code=$disease_rows['disease_code'];
        $disease_name=$disease_rows['disease_name'];
        $added_disease .= "$disease_name($disease_code) ; ";
    }
}
echo $added_disease;