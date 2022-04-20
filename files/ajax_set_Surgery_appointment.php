<?php
include("includes/connection.php");

    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    $Employee_ID = $_GET['Employee_ID'];
    $rejection_reason = $_GET['rejection_reason'];
    $assign = $_GET['assign'];
    $Surgeon_filled = $_GET['Surgeon_filled'];
    $Anaesthesia_Type = $_GET['Anaesthesia_Type'];
    $Action = $_GET['Action'];

    

    if($Action == 'Anasthesia Type' && $assign == 'Accept'){
        $UPDATE = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Anaesthesia_Type = '$Anaesthesia_Type' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
    }elseif(isset($Payment_Item_Cache_List_ID) && $assign == 'Accept'){
        $Surgery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];
            if($Surgery_Status == 'pending'){
                $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = 'pending', Final_Decision = 'Accepted', Surgeon_filled = '$Surgeon_filled' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
            }else{
                
                $Insert = mysqli_query($conn, "INSERT INTO tbl_surgery_appointment(Payment_Item_Cache_List_ID, Final_Decision, Surgery_Status, Date_Time, Employee_ID, Surgeon_filled) VALUES('$Payment_Item_Cache_List_ID', 'Accepted', 'pending', NOW(), '$Employee_ID', '$Surgeon_filled')");
            }
            if($Insert){
                echo 200;
            }else{
                echo 201;
            }
    }elseif(isset($Payment_Item_Cache_List_ID) && $assign == 'Reject'){
        $Surgery_Status = mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
        if(mysqli_num_rows($Surgery_Status)){
            
            $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = 'removed', Final_Decision = 'Rejected', Remarks = '$rejection_reason' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND (Final_Decision = 'Accepted' OR Final_Decision IS NULL)");
                if($Delete_ID){
                    echo 200;
                }else{
                    echo 201;
                }
        }else{
            $Insert = mysqli_query($conn, "INSERT INTO tbl_surgery_appointment(Payment_Item_Cache_List_ID, Final_Decision, Surgery_Status, Date_Time, Employee_ID, Remarks) VALUES('$Payment_Item_Cache_List_ID', 'Rejected', 'removed', NOW(), '$Employee_ID', '$rejection_reason')");
                if($Insert){
                    echo 200;
                }else{
                    echo 201;
                }
        }
    }elseif(isset($Payment_Item_Cache_List_ID) && ($assign == 'On Progress')){
        $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = '$assign', Final_Decision = 'Accepted' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Surgery_Status = 'Active'");
            if($Delete_ID){
                echo "The Surgery was Successfully Updated to ".$assign;
            }else{
                echo "You can't Change this Surgery for Now, Please contact Person In charge";
            }
    }elseif(isset($Payment_Item_Cache_List_ID) && ($assign == 'Completed')){
        $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = '$assign', Final_Decision = 'Accepted' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Surgery_Status = 'On Progress'");
            if($Delete_ID){
                echo "The Surgery was Successfully Updated to ".$assign;
            }else{
                echo "You can't Change this Surgery for Now, Please contact Person In charge";
            }
    }elseif(isset($Payment_Item_Cache_List_ID) && ($assign == 'Death')){
        $Delete_ID = mysqli_query($conn, "UPDATE tbl_surgery_appointment SET Surgery_Status = '$assign', Final_Decision = 'Death', Remarks = '$rejection_reason' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
            if($Delete_ID){
                echo "The Patient is DEAD, This Surgery will no longer take place";
            }else{
                echo "You can't Change this Surgery for Now, Please contact Person In charge";
            }
    }elseif(isset($Payment_Item_Cache_List_ID) && ($assign == 'check Status')){
        // die("SELECT Appointment_ID FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Surgery_Status NOT IN('Death', 'removed')");
        $Select_Status = mysqli_query($conn, "SELECT Appointment_ID FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND Surgery_Status NOT IN('Death', 'removed')");
            if($Select_Status > 0){
                echo 200;
            }else{
                echo 201;
            }
    }elseif($assign == 'Add Anaesthesia' && $Anaesthesia_Type != ''){
        $Select_exist = mysqli_query($conn, "SELECT Anaesthesia_Type FROM tbl_anaesthesia_type WHERE Anaesthesia_Type = '$Anaesthesia_Type'") or die(mysqli_error($conn));
            if(mysqli_num_rows($Select_exist)> 0){
                echo "This Type Anaesthesia is already Specified in the database";
            }else{
                $Insert_Anaesthesia = mysqli_query($conn, "INSERT INTO tbl_anaesthesia_type(Anaesthesia_Type, Date_Time) VALUES('$Anaesthesia_Type', NOW())") or die(mysqli_error($conn));
                    if($Insert_Anaesthesia){
                        echo "Anaesthesia Type Inserted Successfully";
                    }else{
                        echo "Failed to Insert Data, Please Contact System Administrator for further Assistance";
                    }
            }
    }

    mysqli_close($conn);
?>