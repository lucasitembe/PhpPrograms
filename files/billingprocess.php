<?php
@session_start();
include("./includes/connection.php");
$controlforminput = 'initial';
$control_quantity = 'yes';

$GrandTotal = 0;
$Billing_Process_Status = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$can_edit_claim_bill = $_SESSION['userinfo']['can_edit_claim_bill'];
?>
<?php
$temp = 1;
$Sub_Total = 0;
$GrandTotal = 0;
?>

<script type='text/javascript'>
    function Approval() {
        var Folio_Number = "<?php
if (isset($_GET['Folio_Number'])) {
    echo $_GET['Folio_Number'];
} else {
    echo 0;
}
?>";
        var Insurance = "<?php
if (isset($_GET['Insurance'])) {
    echo $_GET['Insurance'];
} else {
    echo '';
}
?>";
        var Registration_ID = "<?php
if (isset($_GET['Registration_ID'])) {
    echo $_GET['Registration_ID'];
} else {
    echo 0;
}
?>";


        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Approval').disabled = 'disabled';
                document.getElementById('Approval_Comment').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Registration_ID=' + Registration_ID + '&Insurance=' + Insurance + '&Folio_Number=' + Folio_Number, true);
        myObject.send();
    }
</script>
<?php
//get today date
$select_date = mysqli_query($conn,"select now() as Today");
while ($Today_Date = mysqli_fetch_array($select_date)) {
    $Today = $Today_Date['Today'];
}
?>

<?php
if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = 0;
}
if (isset($_GET['Insurance'])) {
    $Insurance = $_GET['Insurance'];
} else {
    $Insurance = '';
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}
if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = '';
}
//check if check in id miss match

// $check_id_missmatch = mysqli_query($conn,"SELECT tbl_patient_payments.Patient_Payment_ID as old_id FROM `tbl_check_in`,tbl_patient_payments WHERE tbl_patient_payments.Payment_Date_And_Time > tbl_check_in.Check_In_Date_And_Time AND tbl_patient_payments.Registration_ID = tbl_check_in.Registration_ID and tbl_patient_payments.Check_In_ID < tbl_check_in.Check_In_ID and tbl_patient_payments.Check_In_ID <> tbl_check_in.Check_In_ID and tbl_check_in.Check_In_ID='$Check_In_ID' AND tbl_check_in.Registration_ID='$Registration_ID' and tbl_patient_payments.Billing_Type IN('Outpatient Credit', 'Inpatient Credit')") or die(mysqli_error($conn));
// while($row = mysqli_fetch_assoc($check_id_missmatch)){
//     $pay_id = $row['old_id'];
//     mysqli_query($conn,"UPDATE tbl_patient_payments SET Check_In_ID = '$Check_In_ID' WHERE Patient_Payment_ID = '$pay_id'");
// }

//Resolve the multiple bill for the same attendance
$Patient_Bill_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Bill_ID FROM tbl_patient_payments WHERE Check_In_ID = '$Check_In_ID' LIMIT 1"))['Patient_Bill_ID'];
mysqli_query($conn,"UPDATE tbl_patient_payments SET Patient_Bill_ID = '$Patient_Bill_ID' WHERE Check_In_ID = '$Check_In_ID' AND Bill_ID IS NULL");
?>
<br/>
<?php

$select_Transaction_Items = mysqli_query($conn,"
    SELECT pp.Check_In_ID,ppl.Patient_Payment_Item_List_ID, pp.Registration_ID,pr.Gender,pr.Phone_Number,
    pr.Employee_Vote_Number,ppl.Patient_Payment_ID,pr.Member_Number,pr.Occupation,pr.Date_Of_Birth ,
    pr.Patient_Name, sp.Guarantor_Name AS Sponsor_Name,pp.Billing_Type, pp.Folio_Number, pp.Sponsor_ID from

    tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
    tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic,tbl_sponsor sp

    where  pp.Patient_Payment_ID = ppl.Patient_Payment_ID and  sp.Sponsor_ID = pp.Sponsor_ID and
    pr.registration_id = pp.registration_id and
    e.employee_id = pp.employee_id and
    pp.Billing_Type IN('Outpatient Credit', 'Inpatient Credit') and
    ic.item_category_id = ts.item_category_id  and
    ts.item_subcategory_id = t.item_subcategory_id and
    t.item_id = ppl.item_id and
    pp.Bill_ID IS NULL and
    
    pp.registration_id = '$Registration_ID' and
    pp.Patient_Bill_ID = '$Patient_Bill_ID' and pp.Check_In_ID='$Check_In_ID' ") or die(mysqli_error($conn));
// AND pp.Folio_Number = '$Folio_Number'
$no = mysqli_num_rows($select_Transaction_Items);
//echo $no; exit;
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Transaction_Items)) {
        //$Check_In_ID = $row['Check_In_ID'];
        $Patient_Name = $row['Patient_Name'];
        $Folio_Number = $row['Folio_Number'];
        $Sponsor_Name = $row['Sponsor_Name'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $Gender = $row['Gender'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Member_Number = $row['Member_Number'];
        $Billing_Type = $row['Billing_Type'];
        $Occupation = $row['Occupation'];
        $Patient_Payment_ID = $row['Patient_Payment_ID'];
        $Employee_Vote_Number = $row['Employee_Vote_Number'];
        $Phone_Number = $row['Phone_Number'];

    }
} else {
    $Check_In_ID = '';
    $Patient_Name = '';
    $Folio_Number = '';
    $Sponsor_Name = '';
    $Sponsor_ID = '';
    $Gender = '';
    $Date_Of_Birth = '';
    $Member_Number = '';
    $Billing_Type = '';
    $Occupation = '';
    $Patient_Payment_ID = '';
    $Employee_Vote_Number = '';
    $Phone_Number = '';
}


$date1 = new DateTime(Date("Y-m-d"));
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";

//get visit date
$updatefolio = mysqli_query($conn, "UPDATE tbl_patient_payments SET Folio_Number='$Folio_Number' WHERE Folio_Number = '0' and Registration_ID = '$Registration_ID' and Patient_Bill_ID = '$Patient_Bill_ID' and Check_In_ID='$Check_In_ID' AND Billing_Type IN('Outpatient Credit', 'Inpatient Credit')" ) or die(mysqli_error($conn));
$select_visit = mysqli_query($conn,"SELECT Visit_Date from tbl_check_in where
                    Check_In_ID = (select Check_In_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                    where pp.patient_payment_id = ppl.patient_payment_id and
                    
                    pp.Registration_ID = '$Registration_ID' AND pp.Folio_Number = '$Folio_Number' and
                    pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                    pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1) and pp.Check_In_ID='$Check_In_ID'
                    order by pp.Patient_Payment_ID limit 1)") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_visit);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_visit)) {
        $Visit_Date = $row['Visit_Date'];
    }
} else {
    $Visit_Date = '';
}

//get last billing type
$select = mysqli_query($conn,"SELECT Billing_Type , pp.payment_type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where     pp.patient_payment_id = ppl.patient_payment_id and     pp.Registration_ID = '$Registration_ID' and      pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance')  and pp.Check_In_ID='$Check_In_ID'    order by pp.patient_payment_id desc limit 1") or die(mysqli_error($conn));

$numrow = mysqli_num_rows($select);
if ($numrow > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Billing_Type = $row['Billing_Type'];
        $payment_type =$row['payment_type'];
    }
} else {
    $Billing_Type = '';
    $payment_type='';
}

  if($Billing_Type == 'Inpatient'){
    mysqli_query($conn,"UPDATE tbl_patient_payments SET Billing_Type = 'Inpatient Credit' WHERE Patient_Payment_ID = '$Patient_Payment_ID' ");
  }

 $select111 = mysqli_query($conn,"SELECT ad.Admision_ID, DATE(ad.Admission_Date_Time) AS Admission_Date_Time,consultation_ID, DATE(Discharge_Date_Time) AS Discharge_Date_Time FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = '$Registration_ID' AND cd.Check_In_ID = '$Check_In_ID'");

if(mysqli_num_rows($select111) > 0){
    while($rwselect = mysqli_fetch_assoc($select111)){
        $patient_type = "Inpatient";
        $patient_status = "IN";
        $Discharge_Date_Time  = $rwselect['Discharge_Date_Time'];
        $Admision_ID = $rwselect['Admision_ID'];
        $patientstatusbtn = "<input type='button' class='art-button-green' value='Change Discharge date' onclick='change_discharge_dialogue($Admision_ID);'>";
        $consultation_ID=$rwselect['consultation_ID'];

        $inputdischarge ="<td  style='color:black; border:2px solid #ccc;text-align:right;' colspan=''>Discharge Date Time </td><td><input type='text'  value='$Discharge_Date_Time' id='update_dis_time' >$patientstatusbtn</td>";
        
    }
	   

} else {
    $patient_type = "Outpatient";
    $patient_status = "OUT";
    $inputdischarge='';
}

        //select diagnosis details outpatient WHERE c.Consultation_ID=dc.Consultation_ID AND d.Disease_ID = dc.Disease_ID
        $diagnosis = "";
        $diagnosis_ids = "";
        $all_diagnosis_list = "";
        $all_consultants_list = "";
        $diagnosis_type_list = "";
        $Consultant_Name = "";
        $Consultants_IDs = "";
        $Consultation_Time = "";
        $Consultation_ID = '';

        $valid_items = array();

        // $Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT co.consultation_ID FROM tbl_consultation co, tbl_check_in ci WHERE co.Registration_ID=ci.Registration_ID and (date(co.Consultation_Date_And_Time) = date(ci.Check_In_Date_And_Time) OR date(co.Consultation_Date_And_Time) = DATE_ADD(date(ci.Check_In_Date_And_Time), INTERVAL 1 DAY))  AND ci.Check_In_ID='$Check_In_ID'"))['consultation_ID'];

        $Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE  Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'"))['consultation_ID'];
        // $select = mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE  Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'");
        // if(mysqli_num_rows($select)>0){
        //     while($rw = mysqli_fetch_assoc($select)){
        //         $Consultation_ID = $rw['consultation_ID'];
        //     }
        // }else{
        //     $Consultation_ID=0;
        // }
        // die($consultation_ID."===0000");
      
        $select_con = mysqli_query($conn,"SELECT  c.consultation_ID, d.disease_code, dc.Employee_ID, dc.diagnosis_type, dc.disease_ID, dc.Disease_Consultation_Date_And_Time, e.Employee_Name as Consultant_Name FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c, tbl_disease_consultation dc, tbl_employee e, tbl_disease d WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID AND c.consultation_ID = dc.consultation_ID AND dc.employee_ID = e.Employee_ID  AND d.disease_ID = dc.disease_ID AND pp.Check_In_ID = '$Check_In_ID' AND dc.diagnosis_type IN('diagnosis','provisional_diagnosis') GROUP BY disease_code , diagnosis_type") or die(mysqli_error($conn));

        //new diagnosis check
        if($patient_type =='Inpatient'){
           
            $select_con = mysqli_query($conn, "SELECT wd.Disease_ID, e.Employee_Name as Consultant_Name, wr.Employee_ID,  diagnosis_type,disease_name,wd.Round_Disease_Date_And_Time AS Disease_Consultation_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d ,  tbl_employee e   WHERE   wd.disease_ID = d.disease_ID AND    wr.Round_ID = wd.Round_ID AND diagnosis_type='diagnosis'  AND  wr.consultation_ID ='$Consultation_ID'  AND wr.Employee_ID = e.Employee_ID GROUP BY d.Disease_ID , diagnosis_type") or die(mysqli_error($conn));
            
            if(mysqli_num_rows($select_con)<1){
                
                $select_con = mysqli_query($conn,"SELECT  c.consultation_ID, d.disease_code, dc.Employee_ID, dc.diagnosis_type, dc.disease_ID, dc.Disease_Consultation_Date_And_Time, e.Employee_Name as Consultant_Name FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c, tbl_disease_consultation dc, tbl_employee e, tbl_disease d WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID AND c.consultation_ID = dc.consultation_ID AND dc.employee_ID = e.Employee_ID  AND d.disease_ID = dc.disease_ID AND pp.Check_In_ID = '$Check_In_ID' AND dc.diagnosis_type IN('diagnosis','provisional_diagnosis') GROUP BY disease_code , diagnosis_type") or die(mysqli_error($conn));
            }
            
        }else{
            $select_con = mysqli_query($conn,"SELECT  c.consultation_ID,pp.Check_In_ID, d.disease_code, dc.Employee_ID, dc.diagnosis_type, dc.disease_ID, dc.Disease_Consultation_Date_And_Time, e.Employee_Name as Consultant_Name FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_consultation c, tbl_disease_consultation dc, tbl_employee e, tbl_disease d WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID AND c.consultation_ID = dc.consultation_ID AND dc.employee_ID = e.Employee_ID  AND d.disease_ID = dc.disease_ID AND pp.Check_In_ID = '$Check_In_ID'  GROUP BY d.disease_code , diagnosis_type") or die(mysqli_error($conn));
        }
        //  End new diagnosis check
        $no_of_rows = mysqli_num_rows($select_con);
        if ($no_of_rows > 0) {
            while ($diagnosis_row = mysqli_fetch_array($select_con)) {
                if($diagnosis_row['diagnosis_type'] == 'diagnosis'){
                    $diagnosis .= $diagnosis_row['disease_code'] . "; ";
                    $Consultant_Name .= $diagnosis_row['Consultant_Name'] . "; ";

                }

                $diagnosis_ids .= $diagnosis_row['disease_ID'] . "; ";
                $Consultants_IDs .= $diagnosis_row['Employee_ID'] . "; ";
                $Consultation_Time .= $diagnosis_row['Disease_Consultation_Date_And_Time'] . "; ";
                $all_diagnosis_list .= $diagnosis_row['disease_code'] . "; ";
                $all_consultants_list .= $diagnosis_row['Consultant_Name']."; ";
                $diagnosis_type_list .= $diagnosis_row['diagnosis_type']. "; ";
                $Consultation_ID =$diagnosis_row['consultation_ID'];
                $Check_In_ID_c=$diagnosis_row['Check_In_ID'];

            }
        }

        
        $checkUnprocessedItems = mysqli_query($conn, "SELECT Item_ID, Check_In_Type FROM tbl_payment_cache pc, tbl_item_list_cache ilc WHERE pc.Payment_Cache_ID=ilc.Payment_Cache_ID AND consultation_ID='$Consultation_ID' AND ilc.status IN ('paid', 'approved', 'active') ") or die(mysqli_error($conn));
        if(mysqli_num_rows($checkUnprocessedItems)>0){
            $notDone='yes';
            $unprocessed = " style=' width:60%; display:inline;'";
        }else{
            $notDone='';
            $unprocessed = "style='display:none;'";
        }
    
        $select_con1 = mysqli_query($conn,"SELECT c.consultation_ID, d.disease_code, wr.Employee_ID, wrd.diagnosis_type, wrd.disease_ID, wrd.Round_Disease_Date_And_Time AS Disease_Consultation_Date_And_Time, e.Employee_Name as Consultant_Name FROM tbl_consultation c, tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_ward_round wr, tbl_ward_round_disease wrd , tbl_disease d, tbl_employee e WHERE c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND c.consultation_ID = wr.consultation_ID AND wr.Round_ID = wrd.Round_ID AND wrd.disease_ID = d.disease_ID AND wr.Employee_ID = e.Employee_ID AND pp.Check_In_ID = '$Check_In_ID' GROUP BY wrd.disease_ID, Consultant_Name, pp.Check_In_ID, wr.consultation_ID ") or die(mysqli_error($conn));
       
        $no_of_rows1 = mysqli_num_rows($select_con1);
        if ($no_of_rows1 > 0) {
            while ($diagnosis_row1 = mysqli_fetch_array($select_con1)) {
            
                if($diagnosis_row1['diagnosis_type'] == 'diagnosis'){
                    $diagnosis .= $diagnosis_row1['disease_code'] . "; ";
                    $Consultant_Name .= $diagnosis_row1['Consultant_Name'] . "; ";
                }

                $diagnosis_ids .= $diagnosis_row1['disease_ID'] . "; ";
                $Consultants_IDs .= $diagnosis_row1['Employee_ID'] . "; ";
                $Consultation_Time .= $diagnosis_row1['Disease_Consultation_Date_And_Time'] . "; ";
                $all_diagnosis_list .= $diagnosis_row1['disease_code'] . "; ";
                $all_consultants_list .= $diagnosis_row1['Consultant_Name'].";";
                $diagnosis_type_list .= $diagnosis_row1['diagnosis_type']. "; ";

            }
        }

      
        //get full disease data
        $full_disease_data = str_replace("; ", ";", $diagnosis_ids."||".$all_diagnosis_list."||".$all_consultants_list."||".$Consultants_IDs."||".$Consultation_Time."||".$diagnosis_type_list);

        // if($patient_type =='Inpatient'){
        //     echo $full_disease_data."<h6 style='color:red;'>=====WAIT TO APPROVE INPATIENT</h6>=".$diagnosis_ids;
           
        // }
        //GETTING AuthorizationNo
        $get_check_in = mysqli_query($conn,"select AuthorizationNo from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
        $n_autho = mysqli_num_rows($get_check_in);
        if ($n_autho > 0) {
            while ($auth = mysqli_fetch_array($get_check_in)) {
                $AuthorizationNo = $auth['AuthorizationNo'];
            }
        } else {
            $AuthorizationNo = '';
        }
?>

<fieldset>
    <!--<legend align='right'><b>Billing Processing</b></legend>-->
    <table width="100%">
        <tr>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Partient Name</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Patient_Name; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Registration N<u>o</u></td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" width=8%>
                <input type='text' readonly='readonly' value='<?php echo $Registration_ID; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" width=7%>Age</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" width=14%>
                <input type='text' readonly='readonly' value='<?php echo $age; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Gender</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Gender; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Patient Status</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $patient_status; ?>'>
            </td>
        </tr>

        <tr>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Folio number</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Folio_Number; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Date of Attendance</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Visit_Date; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Billing Type</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Billing_Type; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Sponsor Name</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Sponsor_Name; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Membership No :</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Member_Number; ?>'>
            </td>
        </tr>
        <tr>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Date of Attendance</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' readonly='readonly' value='<?php echo $Visit_Date; ?>'>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Type of illness</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" colspan=2>
                <input type='text' readonly='readonly' id='illness_type' value='<?php echo $diagnosis; ?>'>
            </td>
            <td>
                <!--select id="diagnosis_disease_id" style="width: 100%">
                    <option>Select Disease</option>
                    <?php
                        $sql_select_diagnosis_result=mysqli_query($conn,"SELECT disease_ID,disease_name FROM tbl_disease") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_diagnosis_result)>0){
                          while($disease_rows=mysqli_fetch_assoc($sql_select_diagnosis_result)){
                              $disease_ID=$disease_rows['disease_ID'];
                              $disease_name=$disease_rows['disease_name'];
                              echo "<option value='$disease_ID'>$disease_name</option>";
                          }
                        }
                     ?>
                </select--><nobr>
                &emsp;<input type="submit" name="" value="Add Diseases" class="art-button-green" onclick="display_add_disease_dialog();">

                <input type="submit" name="" value="Remove" class="art-button-green" onclick="display_remove_disease_dialogx(<?=$Folio_Number?>,<?=$Registration_ID?>,<?=$Patient_Bill_ID?>,<?=$Check_In_ID?>,<?=$Consultation_ID?>);"></nobr>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Consultant Name </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" colspan=3>
                <input type='text' readonly='readonly' id='Consultant_Name' value='<?php echo $Consultant_Name; ?>'>
            </td>
        </tr>
        <tr>
            <td style="color:black; border:2px solid #ccc;text-align:right;">Authorization No</td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <input type='text' name='AuthorizationNo' id='AuthorizationNo' value="<?= $AuthorizationNo ?>" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" >
                <input id="CardStatus" type='hidden' name='CardStatus'  >
                <input id="Remarks" type='hidden' name='Remarks'  >
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;">
                <?php
                if (isset($_GET['Registration_ID']) && ($AuthorizationNo == "" || is_null($AuthorizationNo) || $AuthorizationNo == "undefined"  || strlen($AuthorizationNo) < 12 )) {
                  $Current_Sponsor=trim(strtolower($Sponsor_Name));
                  if (substr($Current_Sponsor,0,4) == 'nhif') {
                        ?>
                        <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF2('<?php echo $Member_Number; ?>');" class="art-button-green"  />
                        <?php
                  }
                }
                ?>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" >
                <?php if (isset($_GET['Registration_ID']) && ($AuthorizationNo == "" || is_null($AuthorizationNo) || $AuthorizationNo == "undefined"  || strlen($AuthorizationNo) < 12 )) { ?>
                    <input type="button" value="Save" onclick="updateAuthorizationNo('<?php echo $Check_In_ID; ?>');" class="art-button-green" />
                <?php }
                ?>
            </td>
            <?php 
                if($can_edit_claim_bill=='yes'){                
                    echo $inputdischarge;
                }
            ?>
            <td style="color:black; border:2px solid #ccc;text-align:right;" colspan=2>
  <div align="center" style="display:none" id="verifyprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            </td>
            <td style="color:black; border:2px solid #ccc;text-align:right;"></td>
            <td style="color:black; border:2px solid #ccc;text-align:right;" colspan=3>
<div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            </td>
            
        </tr>
    </table>
</fieldset>


<fieldset style='overflow-y: scroll; height: 400px;'>
    <?php
    $has_pending_bill = 'false';
    $control_title = 'true';
   

    
    $select_Categories = mysqli_query($conn,"SELECT ic.Item_Category_ID, ic.Item_Category_Name,pp.Patient_Payment_ID from
            tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
            tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
            pr.registration_id = pp.registration_id and
            e.employee_id = pp.employee_id and
            ic.item_category_id = ts.item_category_id and ppl.Status<>'removed' and
            ts.item_subcategory_id = t.item_subcategory_id and
            t.item_id = ppl.item_id and
            pp.Billing_Type IN('Outpatient Credit', 'Inpatient Credit')  AND
            
            pp.registration_id = '$Registration_ID' and
            pp.Check_In_ID = '$Check_In_ID' and
            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
            pp.Bill_ID IS NULL
            group by pr.Registration_ID, pp.Patient_Bill_ID, ic.Item_category_ID  order by ic.Item_Category_Name DESC") or die(mysqli_error($conn));
    $numrow = mysqli_num_rows($select_Categories);
    if ($numrow > 0) {
        while ($row = mysqli_fetch_array($select_Categories)) {
            //select items based on category selected
            $Item_Category_ID = $row['Item_Category_ID'];
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Item_Category_Name = $row['Item_Category_Name'];
            echo $Item_Category_Name;
            //echo "</td></tr></table";
            $select_items = mysqli_query($conn,"SELECT pp.Billing_Type,e.Employee_Name,t.Item_ID,pp.Check_In_ID,ic.Item_Category_Name, pp.Patient_Payment_ID,pp.Patient_Signature,t.Product_Name, pp.Claim_Form_Number, pp.Billing_Process_Status,
                pp.Receipt_Date, ppl.Price, pp.Billing_Process_Employee_ID, ppl.Quantity, ppl.Discount, t.Product_Code ,pp.Payment_Date_And_Time from
                tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pr.registration_id = pp.registration_id and
                e.employee_id = pp.employee_id and ppl.Status<>'removed' AND 
                ic.item_category_id = ts.item_category_id and
                ts.item_subcategory_id = t.item_subcategory_id and
                t.item_id = ppl.item_id and 
                pp.Billing_Type IN('Outpatient Credit', 'Inpatient Credit')  AND
                pp.Transaction_status <> 'cancelled' and
                
                pp.Check_In_ID = '$Check_In_ID' and
                pp.registration_id = '$Registration_ID' and
                pp.Bill_ID IS NULL and
                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                ic.Item_category_ID = '$Item_Category_ID' GROUP BY Product_Code, Payment_Date_And_Time") or die(mysqli_error($conn));
                //AND pp.Folio_Number = '$Folio_Number'
            $num_rows = mysqli_num_rows($select_items);

            while ($data = mysqli_fetch_array($select_items)) {
                $Check_In_ID = $data['Check_In_ID'];
                if (strtolower($data['Billing_Type']) == "inpatient credit") {
                    $chk = mysqli_query($conn,"SELECT Discharge_Clearance_Status FROM  tbl_admission ad JOIN tbl_check_in_details ch ON ch.Admission_ID=ad.Admision_ID WHERE ch.Check_In_ID='$Check_In_ID' AND Discharge_Clearance_Status != 'cleared'") or die(mysqli_error($conn));

                    if (mysqli_num_rows($chk) > 0) {
                        $has_pending_bill = 'true';
                        //comment continue button if you want to see the total cost of currently unclosed inpatient bill...gkc
                        //continue;
                    }
                }

                if ($control_title == 'true') {
                    echo '<table width=100% border=1 style="border-collapse: collapse;">';
                    $control_title = 'false';

                    echo '<tr>
                            <td width=4% style="color:black; border:2px solid #ccc; text-align: right;"><b>SN</b></td>
                            <td width=4% style="color:black; border:2px solid #ccc; text-align: right;">&nbsp;&nbsp;<b>Codes</b></td>
                            <td width=35% style="color:black; border:2px solid #ccc; text-align: left;"><b>&nbsp;&nbsp;Item Description</b></td>
                                    <td width=11% style="color:black; border:2px solid #ccc; text-align: left;"><b>Billing type</b></td>
                            <td width=10% style="color:black; border:2px solid #ccc; text-align: right;"><b>D Note N<u>o</u>&nbsp;&nbsp;</b></td>
                            <td width=15% style="color:black; border:2px solid #ccc; text-align: left;"><b>Debit Date & Time</b></td>
                            <td width=6% style="color:black; border:2px solid #ccc; text-align: right;"><b>Price</b></td>
                            <td width=4% style="color:black; border:2px solid #ccc; text-align: right;"><b>Qty</b></td>
                            <td width=6% style="color:black; border:2px solid #ccc; text-align: right;"><b>Discount</b></td>
                            <td width=6% style="color:black; border:2px solid #ccc; text-align: right;"><b>Amount</b></td>
                            <td width=20% style="color:black; border:2px solid #ccc; text-align: center;"><b>Employee</b></td>
                        </tr>';
                }

                //get billing process status and (employee approved the bill if and only if is approved)
                $Billing_Process_Status = $data['Billing_Process_Status'];
                $Billing_Process_Employee_ID = $data['Billing_Process_Employee_ID'];
                $Patient_Signature = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'  AND signature IS NOT NULL"));
                if ($data['Billing_Type'] == "Outpatient Credit") {
                    $Billing_Type = "OUT";
                } else {
                    $Billing_Type = "IN";
                }
                array_push($valid_items,
                  array(
                      'item_id' => $data['Item_ID'],
                      'product_code' => $data['Product_Code'],
                      'unit_price' => $data['Price'],
                      'quantity' => $data['Quantity'],
                      'discount' => $data['Discount']
                    )
                  );
				if($data['Billing_Type'] == 'Patient From Outside'){
                    if($patient_status == 'IN'){
                      mysqli_query($conn,"UPDATE tbl_patient_payments SET Billing_Type = 'Inpatient Credit' WHERE Patient_Payment_ID = ".$data['Patient_Payment_ID']);
                    }else{
                      mysqli_query($conn,"UPDATE tbl_patient_payments SET Billing_Type = 'Outpatient Credit' WHERE Patient_Payment_ID = ".$data['Patient_Payment_ID']);
                    }
                  }
				if(strtolower(trim($data['Billing_Type'])) == 'inpatient'){
                      mysqli_query($conn,"UPDATE tbl_patient_payments SET Billing_Type = 'Inpatient Credit' WHERE Patient_Payment_ID = ".$data['Patient_Payment_ID']);
				}

                echo '<tr>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">' . $temp . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">&nbsp;&nbsp;' . $data['Product_Code'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: left;">&nbsp;&nbsp;' . $data['Product_Name'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: left;">&nbsp;&nbsp;' . $data['Billing_Type'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">';
                            if($can_edit_claim_bill=='yes'){
                                echo ' <a target="_blank" href="patientbillingtransactionedit.php?Patient_Payment_ID=' . $data['Patient_Payment_ID'] . '&Insurance='.$Sponsor_Name.'&Registration_ID=' . $Registration_ID . '&Selected=Selected&EditPayment=EditPaymentThisForm&from=ebill">' . $data['Patient_Payment_ID'] . '</a>&nbsp;&nbsp;</td>';
                            }else{
                                echo $data['Patient_Payment_ID'];
                            }
                            echo '<td style="color:black; border:2px solid #ccc;text-align: left;">&nbsp;&nbsp;' . $data['Payment_Date_And_Time'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">' . number_format($data['Price']) . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">' . $data['Quantity'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">' . $data['Discount'] . '</td>
                            <td style="color:black; border:2px solid #ccc;text-align: right;">' . number_format(($data['Price'] - $data['Discount']) * $data['Quantity']) . '</td>
                                    <td style="color:black; border:2px solid #ccc;text-align: left;">' . $data['Employee_Name'] . '</td>
                        </tr>';
                $temp++;
                $GrandTotal = $GrandTotal + (($data['Price'] - $data['Discount']) * $data['Quantity']);
                if ($data['Quantity'] == 0) {
                    $control_quantity = 'no';
                }
            }
            echo '</table><br/><tr><td><hr></td>  </tr><br/>';
            $control_title = 'true';
            $temp = 1;
        }
    }
    $valid_claims_items = array(
      'valid_items' => $valid_items,
      'Registration_ID' => $Registration_ID,
      'AuthorizationNo' => $AuthorizationNo
    );
    // print_r($valid_claims_items);
    // $valid_claims_items = json_encode($valid_claims_items);
    // echo $valid_claims_items);
    ?>
</fieldset>
<table width=90% border=1 style="border-collapse: collapse;">
    <tr>
        <td style="color:black; border:2px solid #ccc;text-align:right;" id="Approval_Area">
            <img id="progressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none;float: right">&nbsp;&nbsp;

            <?php
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            
            $Patient_Signature = mysqli_fetch_assoc(mysqli_query($conn, "SELECT signature FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'  AND signature IS NOT NULL"))['signature'];
            
            if ($has_pending_bill == 'true') {
                ?>

                <input type="button" name="Approval_Fail_Message" id="Approval_Fail_Message" class="art-button-green" value="APPROVE TRANSACTION" onclick="alert('This patient has inpatient bill not approved! Please approve the inpatient bill before continueing.')">
                <?php
            } else if ($Billing_Process_Status == 'Approved') {
                //get employee Name
                $select_employee = mysqli_query($conn,"SELECT Employee_Name from tbl_employee where Employee_ID = '$Billing_Process_Employee_ID'") or die(mysqli_error($conn));

                while ($select_emp = mysqli_fetch_array($select_employee)) {
                    $Employee_Name = $select_emp['Employee_Name'];
                }
                echo "<span style='color: #037CB0;'><b>Approved By " . $Employee_Name . "</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                if ($Patient_Signature == '' || $Patient_Signature == 'NULL') {
                    echo '<input type="button" name="Approval_Button" id="Approval_Button" value="PATIENT APPROVE TRANSACTION" onclick="openSignatureDialog(' . $Registration_ID . ',' . $Folio_Number . ',' . $Sponsor_ID . ',' . $Patient_Bill_ID . ',' . $Patient_Payment_ID . ')" class="art-button-green">
                    <a target="_blank" href="../esign/signature.php?Registration_ID='.$Registration_ID.'&Check_In_ID='.$Check_In_ID.'" class="art-button-green">TAKE PATIENT SIGNATURE</a>';
                } else {
                    echo "<a href='eclaimprintreport.php?Folio_Number=" . $Folio_Number . "&Insurance=" . $Insurance . "&Registration_ID=" . $Registration_ID ."&Check_In_ID=".$Check_In_ID ."&Patient_Bill_ID=" . $Patient_Bill_ID . "' style='text-decoration: none;' class='btn btn-success btn-sm' target='_blank'>PRINT PATIENT BILL </a></td>";
                }
                ?>
                <input type='button' name='Approval_Button' id='Approval_Button' value="APPROVE eCLAIM BILL" onclick="Confirm_Approval_Trnsaction('<?php echo $Registration_ID; ?>', '<?php echo $Folio_Number; ?>', '<?php echo $Sponsor_ID; ?>', '<?php echo $Patient_Bill_ID; ?>', '<?php echo $control_quantity; ?>', '<?php echo $Patient_Payment_ID; ?>', '<?php echo $Sponsor_Name; ?>', '<?php echo $Check_In_ID; ?>','<?php echo $AuthorizationNo; ?>','<?php echo $full_disease_data; ?>', '<?php echo $notDone; ?>')" class="art-button-green">
                <?php
            } else {
                if ($diagnosis != null && $diagnosis != '' && $Consultant_Name != '' && $Consultant_Name != null && $Patient_Signature != '' && $Patient_Signature != NULL  ) {
                    // if($Employee_ID=='2712'){
                    ?>
                    
                     
                    <input type='button' name='View_Button' id='View_Button' value='Reshresh' onclick="openItemDialog(<?php echo $Folio_Number; ?>, '<?php echo$Insurance; ?>',<?php echo $Registration_ID; ?>, '<?php echo $payment_type; ?>', '<?php echo $Patient_Bill_ID; ?>','<?php echo $Check_In_ID; ?>');" class='art-button-green'>
                    <a target="_blank" href="../esign/signature.php?Registration_ID=<?php echo $Registration_ID; ?>&Check_In_ID=<?php echo $Check_In_ID; ?>" class="art-button-green">TAKE PATIENT SIGNATURE</a> 
                    <a target="_blank" href="./patient_file_bill.php?bill&Registration_ID=<?php echo $Registration_ID; ?>&Consultation_ID=<?php echo $Consultation_ID; ?>&Check_In_ID=<?php echo $Check_In_ID; ?>"  class="art-button-green">PRINT CASE NOTES </a>
                   
                    <input type='button' name='Approval_Button' id='Approval_Button' value="APPROVE eCLAIM BILL" onclick="Confirm_Approval_Trnsaction('<?php echo $Registration_ID; ?>', '<?php echo $Folio_Number; ?>', '<?php echo $Sponsor_ID; ?>', '<?php echo $Patient_Bill_ID; ?>', '<?php echo $control_quantity; ?>', '<?php echo $Patient_Payment_ID; ?>', '<?php echo $Sponsor_Name; ?>', '<?php echo $Check_In_ID; ?>','<?php echo $AuthorizationNo; ?>','<?php echo $full_disease_data; ?>', '<?php echo $notDone; ?>')" class="art-button-green">
                    <?php 
                        echo "<a href='eclaimprintreport.php?Folio_Number=" . $Folio_Number . "&Insurance=" . $Insurance . "&Registration_ID=" . $Registration_ID ."&Check_In_ID=".$Check_In_ID ."&Patient_Bill_ID=" . $Patient_Bill_ID . "' style='text-decoration: none;' class='btn btn-danger btn-sm' target='_blank'>PRINT PATIENT BILL </a></td>";
                    
                    ?>
                    <div class="row"  <?= $unprocessed ?> ><br>
                    <a href="Patientfile_Record_Detail.php?Registration_ID=<?=$Registration_ID?>&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record" target="_blank" rel="noopener noreferrer" class="btn btn-warning btn-sm" >REVIEW PATIENT FILE</a>
                        <label for="">Resoan For approving Bill with Un Processed Service</label>
                        <input type="text" id='unprocesseditems' <?= $unprocessed ?> class="form-control" placeholder="Enter Reason For Approval bill with unprocessd Service">
                    </div>

                <?php
                    // }else{
                    //     echo "<h5 style='color:red;'>THESE BUTTONS ARE UNDER MANTAINANCE PLEASE WAIT / CONTINUE WITH TAKING SIGNATURE  </h5>";
                    // }
                } else { ?>
                    <input type='button' name='View_Button' id='View_Button' value='Reshresh' onclick="openItemDialog(<?php echo $Folio_Number; ?>, '<?php echo$Insurance; ?>',<?php echo $Registration_ID; ?>, '<?php echo $payment_type; ?>', '<?php echo $Patient_Bill_ID; ?>','<?php echo $Check_In_ID; ?>');" class='art-button-green'>
                    <a href="Patientfile_Record_Detail.php?Registration_ID=<?=$Registration_ID?>&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record" target="_blank" rel="noopener noreferrer" class="art-button-green" >REVIEW PATIENT FILE</a>

                    <a target="_blank" href="../esign/signature.php?Registration_ID=<?php echo $Registration_ID; ?>&Check_In_ID=<?php echo $Check_In_ID; ?>" class="art-button-green">TAKE PATIENT SIGNATURE</a>

                    <input type="button" name="Approval_Fail_Message" id="Approval_Fail_Message" class="art-button-green" value="APPROVE TRANSACTION ERROR" onclick="Approval_Fail_Message()">
                    <?php
                }
            }
            ?>
        </td>
        <td style="color:black; border:2px solid #ccc;text-align:right;">
            <?php
            echo '<h3><b>Grand Total </b>' . number_format($GrandTotal) . '</h3>';
            ?>
        </td>
    </tr>
</table>
<input type="hidden" name="" value="<?=$Consultation_ID?>" id="Consultation_ID">
<input type="hidden" name="" value="<?=$patient_status?>" id="patient_status">
<input type="hidden" name="" value="<?=$Folio_Number?>" id="Folio_Number">
<input type="hidden" name="" value="<?=$Insurance?>" id="Insurance">
<input type="hidden" name="" value="<?=$Registration_ID?>" id="Registration_ID">
<input type="hidden" name="" value="<?=$payment_type?>" id="payment_type">
<input type="hidden" name="" value="<?=$Patient_Bill_ID?>" id="Patient_Bill_ID">
<input type="hidden" name="" value="<?=$Check_In_ID?>" id="Check_In_ID">

<script>
    function openItemDialog(Folio_Number, Insurance, Registration_ID, payment_type, Patient_Bill_ID) {
        if (window.XMLHttpRequest) {
            myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function () {
            data29 = myObjectGetDetails.responseText;
            if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Folio_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectGetDetails.open('GET', 'billingprocess.php?Folio_Number=' + Folio_Number + '&Insurance=' + Insurance + '&Registration_ID=' + Registration_ID + '&payment_type=' + payment_type + '&Patient_Bill_ID=' + Patient_Bill_ID, true);
        myObjectGetDetails.send();
    }

    function authorize(ni) {
        alert(ni);
    }
</script>