<?php

   include("./includes/connection.php");
   // $optical=$_POST["optical"];
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   $date_exam=date("y-m-d");
   $corneal_oedema_LE=  mysqli_real_escape_string($conn,$_POST['corneal_oedema_LE']);
   $corneal_oedema_RE=  mysqli_real_escape_string($conn,$_POST['corneal_oedema_RE']);
   $knots_exposed_LE=  mysqli_real_escape_string($conn,$_POST['knots_exposed_LE']);
   $knots_exposed_RE= mysqli_real_escape_string($conn,$_POST['knots_exposed_RE']);
   $fibrin_LE= mysqli_real_escape_string($conn,$_POST['fibrin_LE']);
   $fibrin_RE= mysqli_real_escape_string($conn,$_POST['fibrin_RE']);
   $hyphaema_RE= mysqli_real_escape_string($conn,$_POST['hyphaema_RE']);
   $hyphaema_LE= mysqli_real_escape_string($conn,$_POST['hyphaema_LE']);

   $iris_prolapse_LE= mysqli_real_escape_string($conn,$_POST['iris_prolapse_LE']);
   $VA_LE= mysqli_real_escape_string($conn,$_POST['VA_LE']);
   $iris_prolapse_RE= mysqli_real_escape_string($conn,$_POST['iris_prolapsOe_RE']);
   $irregular_pupil_RE= mysqli_real_escape_string($conn,$_POST['irregular_pupil_RE']);
   $irregular_pupil_LE= mysqli_real_escape_string($conn,$_POST['irregular_pupil_LE']);
   $iopmmg_RE= mysqli_real_escape_string($conn,$_POST['iopmmg_RE']);
   $iopmmg_LE= mysqli_real_escape_string($conn,$_POST['iopmmg_LE']);
   $VA_WPIN_RE= mysqli_real_escape_string($conn,$_POST['VA_WPIN_RE']);
   $VA_WPIN_LE= mysqli_real_escape_string($conn,$_POST['VA_WPIN_LE']);
   $iop_RE= mysqli_real_escape_string($conn,$_POST['iop_RE']);
   $iop_LE= mysqli_real_escape_string($conn,$_POST['iop_LE']);
   $VA_WGLASSES_LE= mysqli_real_escape_string($conn,$_POST['VA_WGLASSES_LE']);
   $VA_WGLASSES_RE= mysqli_real_escape_string($conn,$_POST['VA_WGLASSES_RE']);
   $consultation_ID= mysqli_real_escape_string($conn,$_POST['consultation_ID']);
    $Registration_ID=$_POST['Registration_ID'];
         $optical_examination=mysqli_query($conn,"SELECT Registration_ID FROM examination_operated_eye WHERE Registration_ID='$Registration_ID' and date(created_at)='$date_exam'");
     if(mysqli_num_rows($optical_examination)>0){
        $insert_in_table_optical_clinic=mysqli_query($conn,"UPDATE examination_operated_eye SET corneal_oedema_RE='$corneal_oedema_RE',knots_exposed_LE='$knots_exposed_LE',fibrin_LE='$fibrin_LE',fibrin_RE='$fibrin_RE',hyphaema_RE='$hyphaema_RE',hyphaema_LE='$hyphaema_LE',iris_prolapse_RE='$iris_prolapse_RE',iris_prolapse_LE='$iris_prolapse_LE',irregular_pupil_RE='$irregular_pupil_RE',irregular_pupil_LE='$irregular_pupil_LE',iopmmg_RE='$iopmmg_RE',iopmmg_LE='$iopmmg_LE', VA_WPIN_RE='$VA_WPIN_RE',VA_WPIN_LE='$VA_WPIN_LE',iop_RE='$iop_RE',iop_LE='$iop_LE',VA_WGLASSES_RE='$VA_WGLASSES_RE',VA_WGLASSES_LE='$VA_WGLASSES_LE',Employee_ID='$Employee_ID' WHERE Registration_ID='$Registration_ID' and consultation_ID='$consultation_ID' and date(created_at)='$date_exam'")  or die(mysqli_error($conn));
        if($insert_in_table_optical_clinic){
            echo "Data updated Sucessfully";
        }
        else{
            echo "Data Not updated";
        }
     }else{
        $insert_in_table_optical_clinic="INSERT INTO examination_operated_eye(corneal_oedema_RE, corneal_oedema_LE, knots_exposed_RE, knots_exposed_LE, fibrin_LE, fibrin_RE, hyphaema_RE, hyphaema_LE, iris_prolapse_RE, iris_prolapse_LE, irregular_pupil_RE, irregular_pupil_LE, iopmmg_RE, iopmmg_LE, VA_WPIN_RE, VA_WPIN_LE, iop_RE, iop_LE, VA_WGLASSES_RE, VA_WGLASSES_LE, Registration_ID, Employee_ID, consultation_ID) VALUES ('$corneal_oedema_RE','$corneal_oedema_LE','$knots_exposed_RE','$knots_exposed_LE','$fibrin_LE','$fibrin_RE','$hyphaema_RE','$hyphaema_LE','$iris_prolapse_RE','$iris_prolapse_LE','$irregular_pupil_RE','$irregular_pupil_LE','$iopmmg_RE','$iopmmg_LE','$VA_WPIN_RE','$VA_WPIN_LE','$iop_RE','$iop_LE','$VA_WGLASSES_RE','$VA_WGLASSES_LE','$Registration_ID','$Employee_ID','$consultation_ID')";
             if(mysqli_query($conn,$insert_in_table_optical_clinic) or die(mysqli_error($conn))){
                 echo "Saved Successfully";
             }
             else{
                 echo "Data Not Saved";
             }
     }
             
         
  
?>