<?php

   include("./includes/connection.php");
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
   $filled_status="Nurse";
   $return_date=  mysqli_real_escape_string($conn,$_POST['return_date']);
   $trainee=  mysqli_real_escape_string($conn,$_POST['trainee']);
   $VA_RE= mysqli_real_escape_string($conn,$_POST['VA_RE']);
   $VA_LE= mysqli_real_escape_string($conn,$_POST['VA_LE']);
   $VA_WPIN_RE= mysqli_real_escape_string($conn,$_POST['VA_WPIN_RE']);
   $VA_WPIN_LE= mysqli_real_escape_string($conn,$_POST['VA_WPIN_LE']);
   $IOP_LE= mysqli_real_escape_string($conn,$_POST['IOP_LE']);
   $VA_WGLASSES_LE= mysqli_real_escape_string($conn,$_POST['VA_WGLASSES_LE']);
   $VA_WGLASSES_RE= mysqli_real_escape_string($conn,$_POST['VA_WGLASSES_RE']);
   $IOP_RE= mysqli_real_escape_string($conn,$_POST['IOP_RE']);
   $a_scan= mysqli_real_escape_string($conn,$_POST['a_scan']);
   $k_scan= mysqli_real_escape_string($conn,$_POST['k_scan']);
     $Registration_ID=$_POST['Registration_ID'];
     $optical=mysqli_query($conn,"SELECT optical_clinic_ID, VA_RE, VA_LE, Employee_ID, VA_WPIN_RE, VA_WPIN_LE, IOP_RE, IOP_LE, trainee, VA_GLASSES_RE, VA_GLASSES_LE, date_exam, return_date, picture_note, diabetes, Registration_ID, optical_image, filled_status, a_scan, k_scan FROM optical_clinic WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE");
     if(mysqli_num_rows($optical)>0){
        $insert_in_table_optical_clinic=mysqli_query($conn,"UPDATE optical_clinic SET VA_RE='$VA_RE',VA_LE='$VA_LE',Employee_ID='$Employee_ID',VA_WPIN_RE='$VA_WPIN_RE',VA_WPIN_LE='$VA_WPIN_LE',IOP_RE='$IOP_RE',IOP_LE='$IOP_LE',trainee='$trainee',VA_GLASSES_RE='$VA_WGLASSES_RE',VA_GLASSES_LE='$VA_WGLASSES_LE',filled_status='$filled_status',a_scan='$a_scan',k_scan='$k_scan' WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE")  or die(mysqli_error($conn));
        if($insert_in_table_optical_clinic){
            echo "updated";
        }
        else{
            echo "not updated";
        }

     }else{
        $insert_in_table_optical_clinic=mysqli_query($conn,"INSERT INTO optical_clinic(VA_RE,VA_LE,VA_WPIN_RE,VA_WPIN_LE,IOP_RE, IOP_LE, VA_GLASSES_RE,VA_GLASSES_LE, Registration_ID,Employee_ID,filled_status,a_scan,k_scan)
        VALUES ('$VA_RE','$VA_LE','$VA_WPIN_RE','$VA_WPIN_LE','$IOP_RE','$IOP_LE','$VA_WGLASSES_RE','$VA_WGLASSES_LE','$Registration_ID','$Employee_ID','$filled_status','$a_scan','$k_scan')")  or die(mysqli_error($conn));
        if($insert_in_table_optical_clinic){
            echo "Saved";
        }
        else{
            echo "Not Saved";
        }
     }
            
         
  
?>