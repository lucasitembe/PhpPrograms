<?php

   include("./includes/connection.php");
   session_start();
   $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
//    die("SELECT Employee_Type FROM tbl_employee WHERE Employee_ID=$Employee_ID=");
   $select_Employee_Type=mysqli_query($conn,"SELECT Employee_Type FROM tbl_employee WHERE Employee_ID=$Employee_ID=");
   while($type=mysqli_fetch_array($select_Employee_Type)){
    $filled_status=$type['Employee_Type'];
   }
   //$date_exam=date("y-m-d h:m:s");
   
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
   $file_input= mysqli_real_escape_string($conn,$_POST['file_input']);
   $Patient_Payment_Item_List_ID= mysqli_real_escape_string($conn,$_POST['Patient_Payment_Item_List_ID']);
   $Patient_Payment_ID= mysqli_real_escape_string($conn,$_POST['Patient_Payment_ID']);
     $Registration_ID=$_POST['Registration_ID'];

            $optical_image=mysqli_fetch_assoc(mysqli_query($conn, "SELECT optical_img from optical_image where Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' order by optical_ID desc limit 1 "))['optical_img'];

            //  $insert_in_table_optical_clinic=mysqli_query($conn,"INSERT INTO optical_clinic(VA_RE,VA_LE,VA_WPIN_RE,VA_WPIN_LE,IOP_RE, IOP_LE, trainee, VA_GLASSES_RE,VA_GLASSES_LE,return_date, Registration_ID,Employee_ID,picture_note,optical_image,filled_status)
            //  VALUES ('$VA_RE','$VA_LE','$VA_WPIN_RE','$VA_WPIN_LE','$IOP_RE','$IOP_LE','$trainee','$VA_WGLASSES_RE','$VA_WGLASSES_LE','$return_date','$Registration_ID','$Employee_ID','$file_input','$optical_image','$filled_status')")  or die(mysqli_error($conn));
            $optical=mysqli_query($conn,"SELECT optical_clinic_ID, VA_RE, VA_LE, Employee_ID, VA_WPIN_RE, VA_WPIN_LE, IOP_RE, IOP_LE, trainee, VA_GLASSES_RE, VA_GLASSES_LE, date_exam, return_date, picture_note, diabetes, Registration_ID, optical_image, filled_status, a_scan, k_scan FROM optical_clinic WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE");
            if(mysqli_num_rows($optical)>0){
                $update_in_table_optical_clinic=mysqli_query($conn,"UPDATE optical_clinic SET VA_RE='$VA_RE',VA_LE='$VA_LE',Employee_ID='$Employee_ID',VA_WPIN_RE='$VA_WPIN_RE',VA_WPIN_LE='$VA_WPIN_LE',IOP_RE='$IOP_RE',IOP_LE='$IOP_LE',trainee='$trainee',VA_GLASSES_RE='$VA_WGLASSES_RE',VA_GLASSES_LE='$VA_WGLASSES_LE',return_date='$return_date',picture_note='$file_input',optical_image='$optical_image',filled_status='$filled_status' WHERE Registration_ID='$Registration_ID' and date(date_exam)=CURRENT_DATE")  or die(mysqli_error($conn));
             if($update_in_table_optical_clinic){
                 echo "Updated";
                 mysqli_query($conn,"DELETE from optical_image where Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
             }
             else{
                 echo "Not Updated";
             }
            }else{
                $insert_in_table_optical_clinic=mysqli_query($conn,"INSERT INTO optical_clinic(VA_RE,VA_LE,VA_WPIN_RE,VA_WPIN_LE,IOP_RE, IOP_LE, trainee, VA_GLASSES_RE,VA_GLASSES_LE,return_date, Registration_ID,Employee_ID,picture_note,optical_image,filled_status)
                 VALUES ('$VA_RE','$VA_LE','$VA_WPIN_RE','$VA_WPIN_LE','$IOP_RE','$IOP_LE','$trainee','$VA_WGLASSES_RE','$VA_WGLASSES_LE','$return_date','$Registration_ID','$Employee_ID','$file_input','$optical_image','$filled_status')")  or die(mysqli_error($conn));
                    if($insert_in_table_optical_clinic){
                        echo "Saved";
                    }
                    else{
                        echo "Not Saved";
                    }
            }

            
         
  
?>