<?php
    session_start();
    include("./includes/connection.php");
    
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_POST['update_consultationdate'])){
       
        $select_consultation_clinic = mysqli_query($conn, "SELECT  Payment_Date_And_Time, c.Registration_ID,  c.consultation_id FROM  tbl_consultation c,  tbl_payment_cache pc WHERE pc.consultation_id=c.consultation_id AND c.Registration_ID=pc.Registration_ID   AND DATE(Consultation_Date_And_Time) =DATE('0000-00-00 00:00') ") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_consultation_clinic)>0){
                while($rows = mysqli_fetch_assoc($select_consultation_clinic)){
                    $consultation_id = $rows['consultation_id'];
                    $Registration_ID = $rows['Registration_ID'];
                    $Payment_Date_And_Time = $rows['Payment_Date_And_Time'];
                    $update_query = mysqli_query($conn, "UPDATE tbl_consultation SET Consultation_Date_And_Time =DATE('$Payment_Date_And_Time') WHERE consultation_id='$consultation_id'  AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    if($update_query){
                        echo $Registration_ID." Updated </br>";
                    }else{
                        echo "NO Didn't match";
                    }
                    
                }
            }else{
                echo "No result found";
            }
    }

?>