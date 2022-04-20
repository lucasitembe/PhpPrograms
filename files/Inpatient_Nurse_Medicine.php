
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    } 
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$nav = '';
$divStyle = 'style="height: 220px;overflow-y: auto;overflow-x: hidden;background-color:white"';
$consult_ID=0;
$regist_ID=0;
if (isset($_GET['discharged'])) {
    $nav = '&discharged=discharged';
    $divStyle = 'style="height: 280px;overflow-y: auto;overflow-x: hidden;background-color:white"';
}


//if (isset($_SESSION['userinfo'])) {
//    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
    if(isset($_GET['from_pharmacy'])){
        $divStyle = 'style="height: 450px;overflow-y: auto;overflow-x: hidden;background-color:white"';
        ?>
        <a href="ipd_medication_history.php" alt="" class='art-button-green'>
            BACK
        </a>
        <?php
    }elseif(isset($_GET['from_patient_file'])){
        if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];

} else {
    $Registration_ID = 0;
}
        $divStyle = 'style="height: 450px;overflow-y: auto;overflow-x: hidden;background-color:white"';
        ?>
        <a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>&section=Patient&PatientFile=PatientFileThisForm&this_page_from=patient_record" alt="" class='art-button-green'>
            BACK
        </a>
        <?php
    }else{
        $backlink = $_SERVER['HTTP_REFERER'];
        ?>
        <a href="nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>" alt="" class='art-button-green'>
            BACK
        </a>
        <?php
}
//    }
//}
?>


<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];

} else {
    $Registration_ID = 0;
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];

} else {
    $consultation_ID = 0;
}
//get last consultation id hii sio sawa inabdadilisha consultation 
// $sql_select_consultation_result=mysqli_query($conn,"SELECT consultation_ID,Consultation_Date_And_Time FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1") or die(mysqli_error($conn));
// if(mysqli_num_rows($sql_select_consultation_result)>0){
//     $rows_cons= mysqli_fetch_assoc($sql_select_consultation_result);
//     $consultation_ID=$rows_cons['consultation_ID'];
//     $Consultation_Date_And_Time=$rows_cons['Consultation_Date_And_Time'];
//     //clear missing drugcd
//     mysqli_query($conn,"UPDATE tbl_inpatient_medicines_given SET consultation_ID='$consultation_ID' WHERE Registration_ID='$Registration_ID' AND DATE(Time_Given) >=DATE('$Consultation_Date_And_Time')") or die(mysqli_error($conn));
// }

if ($Registration_ID != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name,sp.Sponsor_ID, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Sponsor_ID = 0;
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Sponsor_ID = 0;
    $Registration_ID = 0;
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;
?>
<?php
if (isset($_POST['submitservice'])) {
    $medication_type = mysqli_real_escape_string($conn,$_POST['medication_type']);
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    $amount_given = mysqli_real_escape_string($conn,$_POST['amount_given']);
    $given_time = mysqli_real_escape_string($conn,$_POST['given_time']);
    $route_type = mysqli_real_escape_string($conn,$_POST['route_type']);
    $drip_rate = mysqli_real_escape_string($conn,$_POST['drip_rate']);
    $medication_time = mysqli_real_escape_string($conn,$_POST['medication_time']);

    if (empty($medication_type) || empty($Payment_Item_Cache_List_ID)) {
        echo "<script>
                alert('Medication Type and Medication Name is reqiured');
                window.location='Inpatient_Nurse_Medicine.php?" . $_SERVER['QUERY_STRING'] . "';
              </script>";
    }

    // == 'outside' || medication_type == 'others'
    if ($medication_type == 'outside' || $medication_type == 'others') {
        $Round_ID = '';
        $Item_ID = $Payment_Item_Cache_List_ID;
        $Payment_Item_Cache_List_ID = 'NULL';
    } else {
        $Round_ID = $_POST['r_' . $Payment_Item_Cache_List_ID];
        $Item_ID = $_POST['t_' . $Payment_Item_Cache_List_ID];
        $Payment_Item_Cache_List_ID=$_POST['pcl_' . $Payment_Item_Cache_List_ID];
    }
//    die($Item_ID."--".$Round_ID.">>>>>>>>>>>>>>>>$Payment_Item_Cache_List_ID");
    $Registration_ID = mysqli_real_escape_string($conn,$_POST['registration_ID']);
//    $consultation_ID = mysqli_real_escape_string($conn,$_POST['consultation_ID']);
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    if ($amount_given < 1 || empty($amount_given)) {
        $amount_given = 0;
    }

    if (empty($Round_ID) || is_null($Round_ID)) {
        $Round_ID = 'NULL';
    }

    if (isset($_POST['discontinue'])) {
        $discontinue = 'yes';
    } else {
        $discontinue = 'no';
    }
    $discontinue_reason = $_POST['discontinue_reason'];
    $remarks = $_POST['remarks'];
    $From_outside_amount =$_POST['From_outside_amount'];

    $medicine_status="SELECT Discontinue_Status FROM tbl_inpatient_medicines_given WHERE Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID' AND Item_ID='$Item_ID' ORDER BY Time_Given DESC LIMIT 1";
    $results=mysqli_query($conn,$medicine_status) or die(mysqli_error($conn));
    $row=mysqli_fetch_assoc($results);
    $medicine_status_results=$row['Discontinue_Status'];
    if($medicine_status_results=='yes'){
        echo "<script> 

        alert('THIS MEDICINE HAS BEEN DISCOUNTINUED TO THIS PATIENT');</script>";
    }else{
        
       
//       echo "----------------------------------------";
//       die("...........$$Payment_Item_Cache_List_ID");

    $insert_services_given = "
		INSERT INTO 
			tbl_inpatient_medicines_given(
				Payment_Item_Cache_List_ID,
                                Item_ID,
                                Time_Given, 
				Amount_Given, 
				Nurse_Remarks, From_outside_amount,
				Employee_ID, 
				Registration_ID,
                                consultation_ID,
				Discontinue_Status, 
				Discontinue_Reason,
                                Round_ID,
                                Medication_type,given_time,route_type,drip_rate,medication_time
				) 
				VALUES(
				$Payment_Item_Cache_List_ID,
                                    '$Item_ID ',
				NOW(), 
				'$amount_given', 
				'$remarks', '$From_outside_amount',
				'$Employee_ID', 
				'$Registration_ID', 
                '$consultation_ID',
				'$discontinue', 
				'$discontinue_reason',
                                $Round_ID,
                                '$medication_type','$given_time','$route_type','$drip_rate','$medication_time'
				)";
//die($insert_services_given);

    $save_services_given = mysqli_query($conn,$insert_services_given) or die(mysqli_error($conn));

    if ($save_services_given) {

        if ($medication_type == 'others') {
            //die("Deeee");
            bill($Item_ID, $amount_given);
        }
        if($discontinue=='yes'){
            echo "<script>

                    alert('THE SERVICE HAS BEEN DISCOUNTINUED');
                    window.location='Inpatient_Nurse_Medicine.php?" . $_SERVER['QUERY_STRING'] . "';
                
                     </script>";
        }else{
            echo "<script>

                   // alert('SERVICE SERVED SUCCESSIFULLY');
                    window.location='Inpatient_Nurse_Medicine.php?" . $_SERVER['QUERY_STRING'] . "';
                
                     </script>";
        }
    } else {
        echo 'error!';
    }
}
}

function bill($Given_Service_ID, $amount_given) {
    global $conn;
    $has_no_folio = false;
    $Folio_Number = '';
    $Registration_ID = $_GET['Registration_ID'];
    $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));


    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

    $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];

    $selectInfo = mysqli_query($conn,"select Folio_Number,pp.Sponsor_ID,Guarantor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

    if (mysqli_num_rows($selectInfo)) {
        $rowsInfos = mysqli_fetch_array($selectInfo);
        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Folio_Number = $rowsInfos['Folio_Number'];
        $Sponsor_ID = $rowsInfos['Sponsor_ID'];
        $Sponsor_Name = $rowsInfos['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rowsInfos['Claim_Form_Number'];

        $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Given_Service_ID . "";
        $check_if_covered = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
        if (mysqli_num_rows($check_if_covered) > 0) {
            $Billing_Type = "Inpatient Cash";
        } else {
            if (strtolower($Sponsor_Name) == 'cash') {
                $Billing_Type = "Inpatient Cash";
            } else {
                $Billing_Type = "Inpatient Credit";
            }
        }

        //get last check in id
    } else {
        include("./includes/Folio_Number_Generator_Emergency.php");
        $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
        $rows = mysqli_fetch_array($select);

        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Supervisor_ID = $_SESSION['Admission_Supervisor']['Employee_ID'];
        $Sponsor_ID = $rows['Sponsor_ID'];
        $Sponsor_Name = $rows['Guarantor_Name'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Claim_Form_Number = $rows['Claim_Form_Number'];

        $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Given_Service_ID . "";
        $check_if_covered = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
        if (mysqli_num_rows($check_if_covered) > 0) {
            $Billing_Type = "Inpatient Cash";
        } else {
            if (strtolower($Sponsor_Name) == 'cash') {
                $Billing_Type = "Inpatient Cash";
            } else {
                $Billing_Type = "Inpatient Credit";
            }
        }

        $has_no_folio = true;
    }

    $pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

    if ($pre_paid == '0') {
        include("./includes/Get_Patient_Transaction_Number.php");

        $sql = " insert into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                              (select now()),'$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID',
                                              '$Sponsor_Name','$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID')";

        //die($sql);

        $insert = mysqli_query($conn,$sql) or die(mysqli_error($conn));

        if ($insert) {

            //get the last patient_payment_id & date
            $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
            $num_row = mysqli_num_rows($select_details);
            if ($num_row > 0) {
                $details_data = mysqli_fetch_row($select_details);
                $Patient_Payment_ID = $details_data[0];
                $Receipt_Date = $details_data[1];
            } else {
                $Patient_Payment_ID = 0;
                $Receipt_Date = '';
            }

            $queryName = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
            $Guarantor_Name = mysqli_fetch_assoc($queryName)['Guarantor_Name'];
            //get data from tbl_item_list_cache
            $Item_ID = $Given_Service_ID;
            $Discount = 0;
            $Price = getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name);
            $Quantity = $amount_given;
            $Consultant = '';
            $Consultant_ID = $Employee_ID;


            //insert data to tbl_patient_payment_item_list
            if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                $insert = mysqli_query($conn,"INSERT into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time)  values('IPD Services','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW())") or die(mysqli_error($conn));
            }

            //check if this user has folio 

            if ($has_no_folio) {
                $update_checkin_details = "
			UPDATE tbl_check_in_details 
				SET Folio_Number='$Folio_Number'
					WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID='" . $_GET['consultation_ID'] . "'";
                mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
            }
        }
    }
}

function getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name) {
    global $conn;
    $Item_ID = $Item_ID;
    $Billing_Type = $Billing_Type;
    $Guarantor_Name = $Guarantor_Name;

    $Price = 0;

    $Sponsor_ID = 0;

    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    }

    $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

    if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
        $Price = $row['price'];
        if ($Price == 0) {
            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {
                $row = mysqli_fetch_assoc($itemGenResult);
                $Price = $row['price'];
            } else {
                $Price = 0;
            }
        }

        //echo $Select_Price;
    } else {
        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemGenResult) > 0) {
            $row = mysqli_fetch_assoc($itemGenResult);
            $Price = $row['price'];
        }
    }

    return $Price;
}
?>
<style>
    select,input{
        padding:5px;
        font-size:18px;
        width:100%; 
    }

    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }	

</style>
<?php
    $hide_field="";
    if(isset($_GET['from_pharmacy'])||isset($_GET['from_patient_file'])){
       $hide_field="class='hide'"; 
    }
    
?>
<fieldset>
    <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
            <b>PATIENT MEDICATION ADMINISTRATION</b><br/>
            <?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>";
            ?></b>
    </legend>

    <?php if (empty($nav)) { ?> 
    <div class="row">
        <div class="col-md-2">
            <label>MEDICATION TYPE: </label>   
        </div>
        <div class="col-md-3">
            <select name="medication_type"  onchange="Get_Medicines_Type(this.value)" id="medication_type_new" required style="width:100%">
                <option></option>
                <option value = 'dispensed'>Received</option>
                <option value = 'Pending'>Dispensed & Not Received</option>
                <option value = 'active'>Not Dispensed</option>                
                <option value = 'outside'>From Outside (Dawa alizokujanazo mgonjwa)</option>
                <option value = 'others'>Others (Emergency drugs)</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="new_drag_sheet">
            
        </div>
    </div>
        <center>
            <table width="100%" <?= $hide_field ?>  style='display: none' id='old_drug_table'> 

                <tr>
                    <td colspan="4" width="100%"><hr></td>
                </tr>
                <tr>
                    <td colspan="4">


                        <form action='#' method='POST'  name='myForm' id='myFormMedication' >
                            <center>
                                <table style="width:100%" class="hiv_table"  >

                                    <tr>
                                        <td style="text-align:right;font-size:14px" width="13%"><b>Medication Type</b></td>
                                        <td>
                                            <select name="medication_type" onchange="GetMedicines(this.value)" id="medication_type" required style="width:100%">
                                                <option></option>
                                                <option value = 'dispensed'>Received</option>
                                                <option value = 'pending'>Collected And Not Received</option>
                                                <option value = 'active'>Not Collected</option>
                                                <option value = 'outside'>From Outside (Dawa alizokujanazo mgonjwa)</option>
                                                <option value = 'others'>Others (Emergency Tray)</option>
                                            </select>
                                        </td>
                                         <td style="text-align:right;font-size:14px;" width="30%" required="required"><b>Dosage</b></td>
                                        <td>
                                            <input type='text' id='dosage' name='given_time' value='' required style="width:70%"/>
                                            <input type='hidden'  id='nowTime' name='nowTime' />
                                        </td>
                                       
                                    </tr>
                                    <tr>
                                        <td style="text-align:right;font-size:14px" width="18%"><b>Medication</b></td>
                                        <td id="medicine_here">
                                            <select name='Payment_Item_Cache_List_ID' required onchange='Get_Last_Given_Time(this.value);testFucntion(this.value)' id='medication_name'  style="width:100%">
                                                <option> Select Medication-------------------- </option>
                                            </select>

                                        </td>
                                        
                                    <!--SELECT DATE_FORMAT("2017-06-15", "%W %M %e %Y");-->
                                        <td style="text-align:right;font-size:14px " width="13%" ><b>Quantity</b></td>
                                        <td ><input type='text' required id="amount_given_val" name='amount_given' required style="width:70%" /></td> 
                                    </tr>
                                    <tr>
                                       
                                    </tr>
                                        <tr>
                                          <td style="text-align:right;font-size:14px" width="13%"><b>Time lapsed</b></td>
                                        <td id="Lapsed_Time" style="width:15%">
                                            <input size="50" type='text' disabled='disable' value='' />
                                        </td> 
                                           <td style="text-align:right;font-size:14px" width="13%"><b>Route</b></td>
                                        <td>
                                            <select name="route_type" id="route_type" required style="width:70%">
                                                <option></option>
                                                <option value = 'Injection'>Injection</option>
                                                <option value = 'Oral'>Oral</option>
                                                <option value = 'Sublingual'>Sublingual</option>
                                                <option value = 'Rectal'>Rectal</option>
                                                <option value = 'Avaginal'>Avaginal</option>
                                                <option value = 'Obular'>Obular</option>
                                                <option value = 'Otic'>Otic</option>
                                                <option value = 'Nasal'>Nasal</option>
                                                <option value = 'Inhalation'>Inhalation</option>
                                                <option value = 'Nebulazation'>Nebulazation</option>
                                                <option value = 'Very_rarely_transdermal'>Very rarely transdermal</option>
                                                <option value = 'Cutaneous'>Cutaneous</option>
                                                <option value = 'Intramuscular'>Intramuscular</option>
                                                <option value = 'Intravenous'>Intravenous</option>
                                                
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                      <td style="text-align:right;font-size:14px" width="13%"><b>Time Saved</b></td>
                                        <td id="Last_Given_Time" style="width:20%; margin-right:20%">
                                            <input size='50' type='text' id='Last_Given_Time_val' disabled='disable' value="" />
                                        </td>
                                         
                                         <td style="text-align:right;font-size:14px " width="13%" ><b>Drip rate</b></td>
                                        <td ><input type='text' required id="drip_rate" name='drip_rate' required style="width:70%" /></td>
                                       
                                    <tr>
                                    
                                    <tr>
                                        <td style="text-align:right;font-size:14px" width="13%"><b>Time Given </b>
                                            
                                        <td>
                                            <input type="text" placeholder="Enter Medication ----Time" id="medication_time" class="" name="medication_time" readonly="readonly"/>
<!--                                            <select style="width:70%" id="medication_time" name="medication_time">
                                                <option value="">Medication Time</option>
                                                <?php 
                                                    //select all saved medication time
                                                    $sql_select_all_saved_medication_time_result=mysqli_query($conn,"SELECT medication_time FROM tbl_medication_time") or die(mysqli_error($conn));

                                                    if(mysqli_num_rows($sql_select_all_saved_medication_time_result)>0){
//                                                        echo "<option value=''>Select Medication TIme</option>";
                                                        while($time_rows=mysqli_fetch_assoc($sql_select_all_saved_medication_time_result)){
                                                            $medication_time=$time_rows['medication_time'];
                                                            echo "<option value='$medication_time'>$medication_time</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <input type="button" class="art-button-green pull-right" onclick="open_add_medication_time_dialog()" value="ADD" style="width:10%"/>-->
                                        </td>
                                        <td style="text-align:right;font-size:14px" width="13%" ><b>Remarks/Significant Events and Interventions </b></td>
                                        <td>
                                            <textarea name='remarks' required style="width:70%" ></textarea>
                                        </td>
                                        
                                    </tr>
                                
                                    <tr>
                                        <td style="text-align:right;font-size:14px" width="13%" ><b>From outside amount</b></td>                                         
                                        <td id="From_outside_amount">
                                            <input type="text" name="From_outside_amount" id="From_outside_amount" value="<?php echo $From_outside_amount; ?>">
                                        </td>
                                               
                                        
                                        <td style="text-align:right;font-size:14px" width="13%" required="required"><b>Discontinue Service?</b></td>
                                        <td style="float:left">
                                            <table width="100%">
                                                <tr>
                                                    <td>
                                                        <input type='checkbox'  name='discontinue'  id='discontinue' value="yes" onclick="RequireReason(this)" />
                                                    </td>
                                                    <td>
                                                        <input type='hidden' name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                                                        <input type='hidden' name="consultation_ID" value='<?php echo $consultation_ID; ?>'/>

                                                        <input type="hidden" name="discontinue_reason" value="" id="discontinue_reason" value="" />
                                                        <input type="hidden" name="discontinue_status" value="" id="discontinue_status" value="" />
                                                        <input type="hidden" name="savechart" value="" id="savechart" value="" />
                                                        <input type='hidden' name="mysp_ID" id="mysp_ID" value='<?php echo $Sponsor_ID; ?>'/>
                                                        <input type='submit' name='submit' id='submit' value='SAVE' onclick="return showStatus()"  class='art-button-green'>
                                                        <input type='hidden' name='submitservice' value='true'>

                                                    </td>
                                                </tr>
                                            </table>


                                        </td>


                                    </tr>

                                    <tr><td colspan="4"><b id='drug_discontinued_alaert' style="color:red;font-size:25px"></b></td></td></tr>
                                </table> 
                            </center>
                        </form>


                    </td>
                </tr>


            </table>               
        </center>
    <?php } ?>
</fieldset> 
<br/>
    <div id="completed_medicine_list" >
                                           
    </div>
 <div id="discontinued_services_list" >

 </div>
<?php 
    
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Filter_Value.' 23:59';;

?>
<fieldset>
    <center>
        <table width='100%'>
            <tr>
                <td>    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="start_date" value="<?= $Start_Date ?>" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="end_date" value="<?= $End_Date ?>" placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" style='text-align: center;width:10%;' class="art-button-green" onclick="filterPatient()"> 
                    <input type="button" name=""  style='text-align: center;width:15%;' class="btn btn-danger btn-sm" onclick="viewCanceledMedicine()" value="View Discontinued Medicines">
                    <input type="button" name=""  style='text-align: center;width:15%;' class="btn btn-success btn-sm" onclick="completed_medication()" value="View Completed Medicines">
                    <a href="Inpatient_Nurse_Medicine_print.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $consultation_ID; ?>" id="printPreview" class="art-button-green" target="_blank" style="float:right">Preview</a>
                    <input type="text" id="demo" value="" class="hide">
                </td>
            </tr>
        </table>
    </center>    
</fieldset>
<div id="Display_Discontinue_Details" style="width:50%;" >
    <span id='Details_Area'>
        <table width="100%" border="0" style='border-style: none;'>
            <tr>
                <td>Discontinue Reason</td>
                <td>
                    <textarea  id='discontinue_reason_dialog' cols='70' rows='2'></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center"> <button type="button" onclick="closeDialog()" class="art-button-green">Save and Close</button></td>
            </tr>
        </table>
    </span>
</div>
<div id="medication_item_dialog">
    <div class="col-md-8">
        <input type="text" id="medication_time_txt"  placeholder="Enter Medication Time"/>
    </div>
    <div class="col-md-3">
        <input type="button" value="ADD" class="art-button-green" onclick="add_medication_time()"/>
    </div>
</div>
<br/>
<div id="Search_Iframe" <?php echo $divStyle ?>>
    <?php include 'Inpatient_Nurse_Medicine_Iframe.php'; ?>
</div>
<script>
    function Get_Medicines_Type(Status){ 
        if (Status == 'active') {
            document.getElementById("savechart").value = 'cant';
        } else {
            document.getElementById("savechart").value = 'save';
        }
        if(Status=="outside"||Status=="others"){
           $("#old_drug_table").show(); 
           $("#new_drag_sheet").hide(); 
           if(Status=="outside"){ $("#medication_type").html("<option>outside</option>");}
           if(Status=="others")$("#medication_type").html("<option>others</option>");
        }else{
           $("#old_drug_table").hide(); 
           $("#new_drag_sheet").show(); 
        }

        var Registration_ID = <?php echo $_GET['Registration_ID']; ?>;
        var consultation_ID = <?php echo $_GET['consultation_ID']; ?>;
        if (window.XMLHttpRequest) {
            medics = new XMLHttpRequest();
            
        }
        else if (window.ActiveXObject) {
            medics = new ActiveXObject('Micrsoft.XMLHTTP');
            medics.overrideMimeType('text/xml');
        }
        document.getElementById('new_drag_sheet').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        medics.onreadystatechange = function () {
            var medicines_select = medics.responseText;
        if(Status=="outside"||Status=="others"){
            document.getElementById('medicine_here').innerHTML = medicines_select;
        }else{
            document.getElementById('new_drag_sheet').innerHTML = medicines_select;
        }
            
            $('select').select2();


             $('.date_n_time').datetimepicker({
            dayOfWeekStart: 1,
                lang: 'en',
                startDate: 'now'
            });
            $('.date_n_time').datetimepicker({value: '', step: 1});
            if (Status == 'outside' || Status == 'others') {
                initializeRemoteSelect2();
            }
            

        };

        medics.open('GET', 'Get_Inpatient_Medication.php?Status=' + Status + '&Reg_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID, true);
        medics.send();
    }
</script>

<script>
    function open_add_medication_time_dialog(){
        $("#medication_item_dialog").dialog("open")
    }
    function add_medication_time(){
       var medication_time_txt=$("#medication_time_txt").val();
       if(medication_time_txt==""){
           $("#medication_time_txt").css("border","2px solid red");
           $("#medication_time_txt").focus();
       }else{
         $.ajax({
             type:'POST',
             url:'ajax_add_medication_time.php',
             data:{medication_time_txt:medication_time_txt},
             success:function(data){
                $("#medication_time").html(data); 
                console.log(data);
             }
         });  
       }
    }
    function filterPatient() {
        var start = document.getElementById('start_date').value;
        var end = document.getElementById('end_date').value;
        var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
        var consultation_ID = '<?php echo $consultation_ID; ?>';

        if (start == '' || end == '') {
            alert("Please enter both dates");
            exit;
        }

        $('#printPreview').attr('href', 'Inpatient_Nurse_Medicine_print.php?start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID);


        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        $.ajax({
            type: "GET",
            url: "Inpatient_Nurse_Medicine_Iframe.php",
            data: 'start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID,
            success: function (data) {
                if (data != '') {
                    $('#Search_Iframe').html(data);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#nurse_medicine').DataTable({
                        "bJQueryUI": true,
                        "bFilter": false,
                        "sPaginationType": "fully_numbers",
                        "sDom": 't'

                    });
                }
            }
        });
    }
</script>
<script>
    function RequireReason(instance) {
        if (instance.checked) {
            document.getElementById('discontinue_status').value = 'yes';
            $("#Display_Discontinue_Details").dialog('open');

        } else {
            $('#discontinue').attr("checked", false);
            document.getElementById('discontinue_status').value = 'no';
        }
        return true;
    }
    function completed_medication(){
        var Registration_ID ='<?php echo $Registration_ID; ?>';
        var consultation_ID ='<?php echo $consultation_ID; ?>';
        $.ajax({
            type:'POST',
            url:'Inpatient_nurse_medicineChart.php',
            data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID, CompletedMedication:'' },
            success:function(responce){
                $("#completed_medicine_list").dialog({
                    title: 'COMPLETED MEDICINE LIST',
                    width: '80%', 
                    height: 550, 
                    modal: true
                });
                $("#completed_medicine_list").html(responce);  
            }
        }); 
    }
    function viewCanceledMedicine(){
        var Registration_ID ='<?php echo $Registration_ID; ?>';
        var consultation_ID ='<?php echo $consultation_ID; ?>';
        $.ajax({
            type:'POST',
            url:'Inpatient_nurse_medicineChart.php',
            data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID, medicationCanceled:'' },
            success:function(responce){
                $("#discontinued_services_list").dialog({
                    title: 'DISCOUNTINUED MEDICINE LIST',
                    width: '80%', 
                    height: 550, 
                    modal: true
                });
                $("#discontinued_services_list").html(responce);  
            }
        });
    }
    function rejectmedication(id, Payment_Item_Cache_List_ID){
        var remarks_new  = $("#remarks_new"+Payment_Item_Cache_List_ID).val();
        var Consultation_ID ='<?php echo $consultation_ID; ?>';
        if(remarks_new==''){
            $("#remarks_new"+Payment_Item_Cache_List_ID).focus();
            $("#uncheckcheck"+id).prop('checked', false);
            exit;
        }
        var medication_type_new = $("#medication_type_new").val();
        if(confirm("Are you sure you want to Reject this Mediction?")){
            $.ajax({
                type:'POST',
                url:'Inpatient_nurse_medicineChart.php',
                data:{id:id,remarks_new:remarks_new, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Consultation_ID:Consultation_ID, rejectmedicationid:''},
                success:function(data){
                    Get_Medicines_Type(medication_type_new)
                    // alert(data);
                    // location.reload();
                }
            });
        }
    }

    function receive_medication(){
        
        var Consultation_ID ='<?php echo $consultation_ID; ?>';
        var selected_mediccation = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            // selected_mediccation.push($(this).val());
            // $("#uncheckcheck"+id).prop('checked', false);
            var Payment_Item_Cache_List_ID =$(this).val();
            var remarks_new  = $("#remarks_new"+Payment_Item_Cache_List_ID).val();
            selected_mediccation.push(Payment_Item_Cache_List_ID+"unganisha"+remarks_new)
            
        });
        if(selected_mediccation ==''){
            alert('Please select medication you want to receive'); exit;
        }
        var medication_type_new = $("#medication_type_new").val();
        if(confirm("Are you sure you want to receive this Mediction?")){
            $.ajax({
                type:'POST',
                url:'Inpatient_nurse_medicineChart.php',
                data:{selected_mediccation:selected_mediccation, Consultation_ID:Consultation_ID, receivemedicationid:''},
                success:function(data){
                    // alert(data);
                    // location.reload();
                    Get_Medicines_Type(medication_type_new)
                }
            });
        }
    }

    function returnMedication(){
        
        var Consultation_ID ='<?php echo $consultation_ID; ?>';
        var selected_mediccation = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            // selected_mediccation.push($(this).val());
            var Payment_Item_Cache_List_ID =$(this).val();
            var Quantity_remained  = $("#Quantity_remained"+Payment_Item_Cache_List_ID).val();
            var remainedID = $("#Quantity_remainedID"+Payment_Item_Cache_List_ID).val();
            selected_mediccation.push(Payment_Item_Cache_List_ID+"unganisha"+Quantity_remained+'unganisha'+remainedID)
            
        });
        if(selected_mediccation ==''){
            alert('Please select medication you want to receive'); exit;
        }
        var medication_type_new = $("#medication_type_new").val();
        if(confirm("Are you sure you want to return this Mediction?")){
            $.ajax({
                type:'POST',
                url:'Inpatient_nurse_medicineChart.php',
                data:{selected_mediccation:selected_mediccation, Consultation_ID:Consultation_ID, returndicationid:''},
                success:function(data){
                    // alert(data);
                    // location.reload();
                    Get_Medicines_Type(medication_type_new)
                }
            });
        }
    }
</script>

<script>
   
    function Get_Last_Given_Time(Payment_Item_Cache_List_ID) {
        var medication_type = document.getElementById('medication_type').value;
        supported(Payment_Item_Cache_List_ID, medication_type);
        
        check_if_medicine_has_been_discontinued(Payment_Item_Cache_List_ID,medication_type);
    }
    function check_if_medicine_has_been_discontinued(Item_ID,medication_type){
        var Registration_ID='<?= $Registration_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_check_if_medicine_has_been_discontinued.php',
            data:{Item_ID:Item_ID,Registration_ID:Registration_ID},
            success:function(data){
                if(data=="yes"){
                    $("#drug_discontinued_alaert").html("The Selected Medicine Has been discontinued to this patient&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='CANCEL DISCONTINUATION' onclick='cancel_discontinuetion("+Item_ID+","+Registration_ID+")' class='art-button-green' style='width:200px'>");
                }else{
                    $("#drug_discontinued_alaert").html("");
                }
                console.log(data);
            }
        });
    }
    function cancel_discontinuetion(Item_ID,Registration_ID, Payment_Item_Cache_List_ID){
        if(confirm("Are you sure you want to undo Medicine Discontinuation")){
            $.ajax({
                type:'POST',
                url:'ajax_cancel_discontinuetion.php',
                data:{Item_ID:Item_ID,Registration_ID:Registration_ID, Payment_Item_Cache_List_ID},
                success:function(data){
                    if(data==="success"){
                        alert("Process Successfully");
                        location.reload();
                    }else{
                       alert("Process Fail...Try again"); 
                    }
                }
            });
        }
    }
    function testFucntion(Item_ID){
        var Registration_ID =<?php echo $Registration_ID; ?>;
        var consultation_ID =<?php echo $consultation_ID; ?>;
        $.ajax({
            type:'POST',
            url:'medicine_from_outside.php',
            data:{Item_ID:Item_ID,consultation_ID:consultation_ID,Registration_ID:Registration_ID},
            success:function(responce){
                $("#From_outside_amount").html(responce);

            }
        });
    }
    function supported(Item_ID,medication_type) {
        var registration_ID =<?php echo $Registration_ID; ?>;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var resp = xhttp.responseText;
                
                if (resp == 'yes' && medication_type == 'others') {
                    // document.getElementById('demo').value='';
                    if (window.confirm('This item is not supported by this sponsor,the patient must pay cash.Are you sure you want to continue?')) {
                        takeItem(Item_ID);
                    } else {
                        window.location = "Inpatient_Nurse_Medicine.php?<?php echo $_SERVER['QUERY_STRING'] ?>";
                    }
                } else {
                   takeItem(Item_ID);
                }
            }
        }
        xhttp.open('GET', 'checkIfSupported.php?action&id=' + Item_ID + '&registration_ID=' + registration_ID, true);
        xhttp.send();
    }
</script>
<script>
    function takeItem(ItemID) {
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var medication_type = document.getElementById('medication_type').value;
        var doseid = 'i' + Item_ID;
        var Item_ID = '';
        var dosage = '';
        if (medication_type == 'outside' || medication_type == 'others') {
            Item_ID = ItemID;
        } else {
            Item_ID = document.getElementById('t_' + ItemID).value;
            doseid = 'i' + ItemID;
            dosage = document.getElementById(doseid).value;
        }

        var consultation_ID = <?php echo $consultation_ID; ?>;
       
        document.getElementById('dosage').value = dosage;
        document.getElementById('dosage').setAttribute('title', dosage);

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = function () {
            var LastDate = mm.responseText;
            
            document.getElementById('Last_Given_Time').innerHTML = "<input size='50' type='text' disabled='disabled' title='" + LastDate + "' value='" + LastDate + "' />";

            Get_Lapsed_Time(LastDate);
        };

        mm.open('GET', 'Medicine_Last_Given_Time.php?Item_ID=' + Item_ID + '&Reg_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID, true);
        mm.send();
    }
</script>
<script>
    function Get_Lapsed_Time(LastTimeGiven) {
        if (window.XMLHttpRequest) {
            lt = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            lt = new ActiveXObject('Micrsoft.XMLHTTP');
            lt.overrideMimeType('text/xml');
        }

        lt.onreadystatechange = function () {
            var Lapsed_Time = lt.responseText;
            //alert(Lapsed_Time);
            document.getElementById('Lapsed_Time').innerHTML = "<input size='50' type='text' disabled='disabled' title='" + Lapsed_Time + "' value='" + Lapsed_Time + "' />";
        };

        lt.open('GET', 'Service_Lapsed_Time.php?LastTimeGiven=' + LastTimeGiven, true);
        lt.send();
    }


</script>

<script>
    function showStatus(){
        var medicationname = document.getElementById('medication_name').value;
        var amount_givenval = document.getElementById('amount_given_val').value;
        var status = document.getElementById("savechart").value;
        var disc_reason = document.getElementById("discontinue_reason_dialog").value;
        var medication_time = document.getElementById("medication_time").value;

        if (status == 'save') {
            if($('#discontinue').prop('checked')){
                //document.getElementById('amount_given_val').value=0;
                return true;
            }
            if(medication_time==""){
                alert("Please select time given");
                return false;
            }
            if (medicationname == '') {
                alert("Please select medication to continue");
                return false;
            } else if (isNaN(amount_givenval)) {
                alert("Please enter a valid quantity number");
                return false;
            } else if (amount_givenval <= 0 || amount_givenval == '') {
                if (confirm("Quantity should be grater than 0.Are sure you want to continue")) {
                    return true;
                }
                //alert("Quantity should be grater than 0");
                return false;
            } else {
                if (confirm('Are you sure you want to save patient medication administration?')) {
                    //document.getElementById('myFormMedication').submit();
                    return true;
                }
            }
        } else if (status == 'cant') {
            alert("You can't save the medication. You must first collect it from pharmacy.");
            return false;
        }
    }

//    $('#submit').on('click',function(e){
//      e.preventDefault();
////     showStatus();
//       var medicationna=$('#medicationname').val();
//       var medication_type=$("#medication_type").val();
//       var mysp_ID=$("#mysp_ID").val();      
//       alert(medicationna);     
//    });

//    onclick="return showStatus()" 
</script>
<script>
    $(document).ready(function () {
        $.fn.dataTableExt.sErrMode = 'throw';
        $('#nurse_medicine').DataTable({
            "bJQueryUI": true,
            "bFilter": false,
            "sPaginationType": "fully_numbers",
            "sDom": 't'

        });

        $("#Display_Discontinue_Details").dialog({autoOpen: false, width: '60%', title: 'DISCOUNTINUE DETAILS', modal: true});
        // $("#discontinued_services_list").dialog({autoOpen: false, width: '80%',height:'500', title: 'DISCOUNTINUED MEDICINE LIST', modal: true});

        // $("#completed_medicine_list").dialog({autoOpen: false, width: '80%',height:'500', title: 'COMPLETED MEDICINE LIST', modal: true});

        $("#medication_item_dialog").dialog({autoOpen: false, width: '40%',height:'100', title: 'ADD MEDICATION TIME', modal: true});

        $('.ui-dialog-titlebar-close').click(function () {
            $('#discontinue').attr("checked", false);
        });


        $('#start_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#start_date').datetimepicker({value: '', step: 1});
        
        $('.date_n_time').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('.date_n_time').datetimepicker({value: '', step: 1});
        $('#medication_time').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#medication_time').datetimepicker({value: '', step: 1});

        $('#end_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#end_date').datetimepicker({value: '', step: 1});

        $('select').select2();

    });

</script>
<script>
    function closeDialog() {
        var discontinue_reason_dialog = document.getElementById("discontinue_reason_dialog").value;

        document.getElementById("discontinue_reason").value = discontinue_reason_dialog;

        //alert(document.getElementById("discontinue_reason").value);
        $("#Display_Discontinue_Details").dialog("close");

    }
</script>
<script>
    function initializeRemoteSelect2() {
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var consultation_ID = <?php echo $consultation_ID; ?>;
        //remote search test name select2

        $("#medication_name").select2({
            ajax: {
                url: "outside_others_medics.php",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        consultationType: 'Pharmacy', //consultation type
                        Reg_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: "~~~~~~~Select Medication Name~~~~~~~",
            allowClear: true
        });
    }


</script>
<script type="text/javascript">
	$("#btn_discontinued_services").on("click",function(){
		$("#discontinued_services_list").dialog("open");
	});
    $("#btn_completed_services").on("click",function(){
		$("#completed_medicine_list").dialog("open");
	});
	function Preview_Patient_Discontinued_List(Registration_ID,Consultation_ID){
		window.open('patient_discontinued_medicines_list_print.php?Registration_ID='+Registration_ID+'&consultation_ID='+Consultation_ID,'_blank');
		//OpenNewTab(URL);

	}
        
//        function select_all_unselect_all(){
//            console.log("clicked");
//            $(".Payment_Item_Cache_List_ID").not(this).prop('checked', this.checked);
//            console.log("clicked2");
//        }
        
        function save_medication(){
          var medication_data=[];
          var medication_type=$("#medication_type_new").val();
          var Round_ID='<?= $Round_ID ?>';
          var Registration_ID='<?= $Registration_ID ?>';
          var consultation_ID='<?= $consultation_ID ?>';
          var validate_input=0;
            $(".Payment_Item_Cache_List_ID:checked").each(function() {
                  var Payment_Item_Cache_List_ID=$(this).val();
//                  var Payment_Item_Cache_List_ID=medication_data.push($(this).val());
               var drip_rate_new =$("#drip_rate_new"+Payment_Item_Cache_List_ID).val()
               var remarks_new =$("#remarks_new"+Payment_Item_Cache_List_ID).val()
               var discontinue ="";
               var discontinue_reason_new =$("#discontinue_reason_new"+Payment_Item_Cache_List_ID).val()
               var route_type_new =$("#route_type_new"+Payment_Item_Cache_List_ID).val()
               var amount_given_new =parseFloat($("#amount_given_new"+Payment_Item_Cache_List_ID).val());
               var medication_time_new =$("#medication_time_new"+Payment_Item_Cache_List_ID).val()
               var Item_ID =$("#Item_ID"+Payment_Item_Cache_List_ID).val()
               var dosage_new =$("#dosage_new"+Payment_Item_Cache_List_ID).val()
               var Quantity_remained = parseFloat($("#Quantity_remained"+Payment_Item_Cache_List_ID).val());
               if(amount_given_new > Quantity_remained){
                $("#Quantity_remained"+Payment_Item_Cache_List_ID).css("border","2px solid green");
                $("#amount_given_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 

                alert("You can't prescribe this medication Remained quantity is "+Quantity_remained);

                exit();
               }
               if(dosage_new==""){
                  $("#dosage_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                   $("#dosage_new"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if(route_type_new==""){
                  $("#route_td_"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                   $("#route_td_"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if(drip_rate_new==""){
                  $("#drip_rate_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                  $("#drip_rate_new"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if(remarks_new==""){
                  $("#remarks_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                  $("#remarks_new"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if(amount_given_new==""){
                  $("#amount_given_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                  $("#amount_given_new"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if(medication_time_new==""){
                  $("#medication_time_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
                  validate_input++;
               }else{
                  $("#medication_time_new"+Payment_Item_Cache_List_ID).css("border",""); 
               }
               if($("#discontinue_"+Payment_Item_Cache_List_ID).is(":checked")){
                   discontinue="yes";
                   if(discontinue_reason_new==""){
                       $("#discontinue_reason_new"+Payment_Item_Cache_List_ID).css("border","2px solid red");
                       validate_input++;
                   }
               }else{
                   $("#discontinue_reason_new"+Payment_Item_Cache_List_ID).css("border","");
                   discontinue="no";
               }
                medication_data.push(Payment_Item_Cache_List_ID+"unganisha"+drip_rate_new+"unganisha"+remarks_new+"unganisha"+discontinue+"unganisha"+discontinue_reason_new+"unganisha"+route_type_new+"unganisha"+amount_given_new+"unganisha"+medication_time_new+"unganisha"+Item_ID+"unganisha"+dosage_new)
          }); 
          if(medication_data.length>0){
                if(validate_input<=0){
                    if(confirm("Are you sure you want to save these medications?")){
                        $.ajax({
                            type:'POST',
                            url:'ajax_save_inpatient_medication.php',
                            data:{medication_type:medication_type,Round_ID:Round_ID,Registration_ID:Registration_ID,medication_data:medication_data,consultation_ID:consultation_ID},
                            success:function(data){
                                $("#feedback_message").html(data);
                                
                                //Inpatient_Nurse_Medicine
                                setTimeout(function(){ location.reload() }, 3000);
                            }
                        });
                    }
                }
            }else{
               $(".checkbox_select").css("border","2px solid red"); 
            }
        }
        
    //     function checkUncheck(){ //alert("yes");
    //     if(this.checked) {
    //         // Iterate each checkbox
    //         $(':checkbox').each(function() {
    //             this.checked = true;            
    //         });
    //     }else {
    //         $(':checkbox').each(function() {
    //             this.checked = false;
    //         });
    //     }
    // }

    $("#mark_all").change(function(e){
        e.preventDefault();
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;            
            });
        }else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<?php
include("./includes/footer.php");
?>
