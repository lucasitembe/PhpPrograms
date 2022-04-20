<?php
    session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo']['Quality_Assurance'])){
        if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //get start date, end date & sponsor id
    
    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = '';
    }
    
    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = '';
    }
    
    if(isset($_GET['Bill_Description'])){
        $Bill_Description = $_GET['Bill_Description'];
    }else{
        $Bill_Description = '';
    }
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }

    if(isset($_GET['Patient_Bill_ID'])){
        $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
    }

    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }
    if(isset($_GET['full_disease_data'])){
        $full_disease_data = $_GET['full_disease_data'];
    }

    //get employee ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Check_In_ID'])){
        $Check_In_ID = $_GET['Check_In_ID'];
    }else{
        $Check_In_ID=0;
    }
    
   

    
    //generate bill    
    if($Sponsor_ID != null && $Employee_ID != 0 && $Employee_ID != null){

        $sql_insert = mysqli_query($conn,"INSERT into tbl_bills(Sponsor_ID,Bill_Date_And_Time,Bill_Date,Start_Date,End_Date,Employee_ID,Bill_Description)  values('$Sponsor_ID',(select now()),(select now()),'$Start_Date','$End_Date','$Employee_ID','$Bill_Description')") or die(mysqli_error($conn));
        if($sql_insert){
            //get the last bill id based on employee id
            $select_bill_id = mysqli_query($conn,"SELECT Bill_ID from tbl_bills where Employee_ID = '$Employee_ID' order by Bill_ID desc limit 1") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select_bill_id);
            if($num > 0){
                while($row = mysqli_fetch_array($select_bill_id)){
                    $Bill_ID = $row['Bill_ID'];
                }

                //assing bill id to selected transactions only 
                if(isset($_GET['Patient_Payment_ID'])){    
                    $sql_update = mysqli_query($conn,"UPDATE tbl_patient_payments set Billing_Process_Status = 'billed', Bill_ID = '$Bill_ID'     where Billing_Process_Status = 'Approved' and   Sponsor_ID = '$Sponsor_ID' and Registration_ID = '$Registration_ID' and     Patient_Bill_ID = '$Patient_Bill_ID'    AND   Check_In_ID='$Check_In_ID'") or die(mysqli_error($conn));
                    // Folio_Number = '$Folio_Number' and  AND   Check_In_ID='$Check_In_ID' 
                }   

              //UPDATE tbl_patient_payments SET Billing_Process_Status = NULL WHERE Billing_Process_Status = 'Approved'
                if($sql_update){
                $array=explode("||",$full_disease_data);
                $disease_ids=explode(";",$array[0]);// array of disease ids
                $codes=explode(";",$array[1]);// array of disease codes
                $doctors=explode(";",str_replace("+"," ",$array[2]));// array of consaltant names
                $ids=explode(";",$array[3]);// array of consaltant ids
                $time=explode(";",chop($array[4],";"));// array of diseases consulatation time
                $diagnosis_type=explode(";",chop($array[5],";"));// array of diagnosis type
                    
              
                for($i=0; $i<sizeof($codes); $i++){
                    
                    if(!empty($codes[$i]) || !empty($doctors[$i]) || !empty($ids[$i]) ){
                       
                        // $selectdiagosisexit = mysqli_query($conn, "SELECT * FROM tbl_edited_folio_diseases WHERE diagnosis_type='$diagnosis_type[$i]' AND Disease_Code='$codes[$i]' AND Consultant_ID='$ids[$i]'") or die(mysqli_error($conn));
                        // if(mysqli_num_rows($selectdiagosisexit)>0){
                            
                        // }else{
                            // if($Registration_ID =='86870'){
                            //     $result = explode('||',$full_disease_data);
                            //     die("INSERT into tbl_edited_folio_diseases(Disease_ID,Disease_Code,diagnosis_type,Consultant_ID,Consultation_Time,Consultant_Name,Bill_ID) values('$disease_ids[$i]','$codes[$i]','$diagnosis_type[$i]','$ids[$i]','$time[$i]','$doctors[$i]','$Bill_ID')");
                            // }
                            mysqli_query($conn,"INSERT into tbl_edited_folio_diseases(Disease_ID,Disease_Code,diagnosis_type,Consultant_ID,Consultation_Time,Consultant_Name,Bill_ID) values('$disease_ids[$i]','$codes[$i]','$diagnosis_type[$i]','$ids[$i]','$time[$i]','$doctors[$i]','$Bill_ID')") or die(mysqli_error($conn));
                        // }

                       
                    }
                }

                    echo json_encode(array(
                       'status'=>200,
                       'data'  =>$Bill_ID
                    ));
                }else{
                      echo json_encode(array(
                       'status'=>300,
                       'data'  =>$Bill_ID
                    ));
                }
            }
        }
    }

?>
