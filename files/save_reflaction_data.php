<?php
     include("./includes/connection.php");
     session_start();
     $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
     $saved_date=date("y-m-d");
     $objective_RE=  mysqli_real_escape_string($conn,$_POST['objective_RE']);
     $objective_LE=  mysqli_real_escape_string($conn,$_POST['objective_LE']);
     $subjective_RE=  mysqli_real_escape_string($conn,$_POST['subjective_RE']);
     $subjective_LE=  mysqli_real_escape_string($conn,$_POST['subjective_LE']);
     $phoria=  mysqli_real_escape_string($conn,$_POST['phoria']);
     $pd=  mysqli_real_escape_string($conn,$_POST['pd']);
     $diagnosis_management=  mysqli_real_escape_string($conn,$_POST['diagnosis_management']);
     $vision_assesment_note=  mysqli_real_escape_string($conn,$_POST['vision_assesment_note']);
     $orthoptics_notes=  mysqli_real_escape_string($conn,$_POST['orthoptics_notes']);
     $diagnosis2=  mysqli_real_escape_string($conn,$_POST['diagnosis2']);
     $Payment_Item_Cache_List_ID=  mysqli_real_escape_string($conn,$_POST['Payment_Item_Cache_List_ID']);
     $Registration_ID=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
     $consultation_ID=  mysqli_real_escape_string($conn,$_POST['consultation_ID']);
     $refraction_remark=  mysqli_real_escape_string($conn,$_POST['refraction_remark']);
     $add_remark=  mysqli_real_escape_string($conn,$_POST['add_remark']);
     
     
     $sql="INSERT INTO tbl_refraction(objective_RE, objective_LE, subjective_RE, subjective_LE, phoria, pd, eom, npc, diagnosis_management, vision_assesment_note, orthoptics_notes, diagnosis, Payment_Item_Cache_List_ID, Registration_ID, Employee_ID, saved__data,consultation_ID,refraction_remark,add_remark) VALUES ('$objective_RE','$objective_LE','$subjective_RE','$subjective_LE','$phoria','$pd','$eom','$npc','$diagnosis_management','$vision_assesment_note','$orthoptics_notes','$diagnosis2','$Payment_Item_Cache_List_ID','$Registration_ID','$Employee_ID','$saved_date','$consultation_ID','$refraction_remark','$add_remark')";
     
     $check=mysqli_query($conn,$sql) or die(mysqli_error($conn));
     if($check){
         echo "inserted";
     }
     else{
         echo "Not Inserted";
     }


?>