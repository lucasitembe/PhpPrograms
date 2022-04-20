<?php
    include("./includes/connection.php");
    session_start();
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
     
    $maincomplain=$_POST['maincomplain'];
    $firstsymptom_date=$_POST['firstsymptom_date'];
    $history_present_illness=$_POST['history_present_illness'];
    $review_of_other_systems =$_POST['review_of_other_systems'];
    $general_observation=$_POST['general_observation'];
    $systemic_observation = $_POST['systemic_observation'];
    $Comment_For_Laboratory = $_POST['Comment_For_Laboratory'];
    $Comment_For_Radiology = $_POST['Comment_For_Radiology'];
    $investigation_comments=$_POST['investigation_comments'];
    $remarks=$_POST['remark'];
    $Consultation_Date_And_Time = Date('Y-m-d h:i:s');
    
    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
        //Update here
        $update_query = "UPDATE tbl_consultation SET maincomplain='$maincomplain',firstsymptom_date='$firstsymptom_date',
                        history_present_illness='$history_present_illness',review_of_other_systems='$review_of_other_systems',
                        general_observation='$general_observation',systemic_observation='$systemic_observation',
                        Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',
                        investigation_comments='$investigation_comments',remarks='$remarks',
                        Consultation_Date_And_Time='$Consultation_Date_And_Time'
                        WHERE consultation_ID = '$consultation_ID'";
        if(mysqli_query($conn,$update_query)){
        $url = "./doctordiagnosisselect.php?Consultation_Type=$Consultation_Type&consultation_id=$consultation_ID&Registration_ID=$Registration_ID";
        $url.="&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        ?>
        <script type='text/javascript' >
            document.location = '<?php echo $url;?>';
        </script>
        <?php
        }
    }else{
        $insert_query = "INSERT INTO tbl_consultation(employee_ID, Registration_ID, maincomplain,
                                       firstsymptom_date, history_present_illness, review_of_other_systems,
                                       general_observation, systemic_observation,
                                       investigation_comments, remarks,
                                       Comment_For_Laboratory,Comment_For_Radiology,Patient_Payment_Item_List_ID,Consultation_Date_And_Time)
        VALUES ('$employee_ID', '$Registration_ID', '$maincomplain',
                                       '$firstsymptom_date', '$history_present_illness', '$review_of_other_systems',
                                       '$general_observation', '$systemic_observation',
                                       '$investigation_comments', '$remarks',
                                       '$Comment_For_Laboratory','$Comment_For_Radiology','$Patient_Payment_Item_List_ID','$Consultation_Date_And_Time')";
        if(mysqli_query($conn,$insert_query)){
            $result = mysqli_query($conn,"SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation
                                           WHERE Registration_ID='$Registration_ID' AND employee_ID='$employee_ID'");
            $row = mysqli_fetch_assoc($result);
            $consultation_ID = $row['consultation_ID'];
            $url = "./doctordiagnosisselect.php?Consultation_Type=$Consultation_Type&consultation_id=$consultation_ID&Registration_ID=$Registration_ID";
            $url.="&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
            ?>
            <script type='text/javascript' >
               document.location = '<?php echo $url;?>';
            </script>
            <?php
        }
    }
	
	
?>