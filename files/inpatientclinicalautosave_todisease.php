<?php
    include("./includes/connection.php");
    @session_start();
    if(isset($_GET['Consultation_Type'])){
        $Consultation_Type = $_GET['Consultation_Type'];
    }else{
        header('location : ../index.php');
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        header('location : ./index.php');
    }
    if(isset($_GET['Patient_Payment_ID'])){ 
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        header('location : ./index.php');
    }
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
        header('location : ./index.php');
    }
    
    $employee_ID= $_SESSION['userinfo']['Employee_ID'];
    
    $Findings=$_POST['Findings'];
    $Comment_For_Laboratory = $_POST['Comment_For_Laboratory'];
    $Comment_For_Radiology = $_POST['Comment_For_Radiology'];
    $investigation_comments=$_POST['investigation_comments'];
    $remarks=$_POST['remark'];
    $Ward_Round_Date_And_Time = Date('Y-m-d h:i:s');
    
    if(isset($_GET['Round_ID'])){
        $Round_ID = $_GET['Round_ID'];
        //Update here
        $update_query = "UPDATE tbl_ward_round SET Findings='$Findings',Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',
                        investigation_comments='$investigation_comments',remarks='$remarks',
                        Ward_Round_Date_And_Time='$Ward_Round_Date_And_Time'
                        WHERE Round_ID = '$Round_ID'";
        if(mysqli_query($conn,$update_query)){
        $url = "./inpatientdoctordiagnosisselect.php?Consultation_Type=$Consultation_Type&Round_ID=$Round_ID&Registration_ID=$Registration_ID";
        $url.="&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        ?>
        <script type='text/javascript' >
            document.location = '<?php echo $url;?>';
        </script>
        <?php
        } else { echo "error";}
    }else{
		//echo "error";
        $insert_query = "INSERT INTO tbl_ward_round(employee_ID, Registration_ID, Findings,investigation_comments, remarks,
                                       Comment_For_Laboratory,Comment_For_Radiology,Patient_Payment_Item_List_ID,Ward_Round_Date_And_Time)
        VALUES ('$employee_ID', '$Registration_ID', '$Findings','$investigation_comments', '$remarks',
                                       '$Comment_For_Laboratory','$Comment_For_Radiology','$Patient_Payment_Item_List_ID','$Ward_Round_Date_And_Time')";
        if(mysqli_query($conn,$insert_query)){
            $result = mysqli_query($conn,"SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round
                                           WHERE Registration_ID='$Registration_ID' AND employee_ID='$employee_ID'") or die(mysqli_error($conn));
            $row = mysqli_fetch_assoc($result);
            $Round_ID = $row['Round_ID'];
            $url = "./inpatientdoctordiagnosisselect.php?Consultation_Type=$Consultation_Type&Round_ID=$Round_ID&Registration_ID=$Registration_ID";
            $url.="&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
            ?>
            <script type='text/javascript' >
                document.location = '<?php echo $url;?>';
            </script>
            <?php
        } else { echo mysqli_error($conn);}
    }
?>