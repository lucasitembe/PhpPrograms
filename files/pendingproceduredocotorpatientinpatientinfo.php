<?php
include("./includes/header.php");
include("./includes/connection.php");
$issPorcessed = FALSE;
$queryString = '';
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if (isset($_POST['process_procedure'])) {
    // echo 'Sent';exit;
    $paymentItermCache = $_POST['paymentItermCache'];
    $Registration_ID = $_POST['Registration_ID'];
    $queryString = $_POST['queryString'];

    $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
                                where Registration_ID = '$Registration_ID'
                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));

    $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];

    $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];
    
    foreach ($paymentItermCache as $value) {
        $status = $_POST['status_' . $value];
        $status_pro = $_POST['status_pro_' . $value];
        $billing_type = $_POST['billing_type_' . $value];
        $transaction_type = $_POST['transaction_type_' . $value];

        if (($billing_type == 'outpatient cash' && $transaction_type == "cash") || ($billing_type == 'outpatient credit' && $transaction_type == "cash")) {
            if ($status !== 'Select progress') {

                mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='" . $_POST['remarks_' . $value] . "',ServedDateTime=NOW(),ServedBy='" . $_SESSION['userinfo']['Employee_ID'] . "' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
            }
        } else {
            //echo $status.'<br/>';
            //Billy the patient
            if ($status !== 'Select progress') {
                if ($status == 'served') {
				
				$Item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_item_list_cache il WHERE Payment_Item_Cache_List_ID='$value' ") )['Item_ID'];
                
                $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
                    $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

                    if (mysqli_num_rows($check_if_covered2) > 0) {
                           mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='" . $_POST['remarks_' . $value] . "',ServedDateTime=NOW(),ServedBy='" . $_SESSION['userinfo']['Employee_ID'] . "' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
                           continue;
                    } 
				
                    $checkPAymentStatus = "SELECT Registration_ID,Billing_Type,Folio_Number,Sponsor_ID,Sponsor_Name,Item_ID,Quantity,Price,Discount,Consultant,Consultant_ID FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE Payment_Item_Cache_List_ID='" . $value . "'";
                    $paymentQuery = mysqli_query($conn,$checkPAymentStatus);
                    $rows = mysqli_fetch_array($paymentQuery);

                    $has_no_folio = false;
                    $Folio_Number = '';


                      $selectInfo = mysqli_query($conn,"select Folio_Number,pp.Sponsor_ID,Guarantor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments pp JOIN tbl_sponsor sp ON pp.Sponsor_ID=sp.Sponsor_ID  where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND pp.Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

                    if (mysqli_num_rows($selectInfo)) {
                        $rowsInfos = mysqli_fetch_array($selectInfo);
                        $Supervisor_ID = $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                        $Folio_Number = $rowsInfos['Folio_Number'];
                        $Sponsor_Name = $rowsInfos['Guarantor_Name'];
                        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                        $Claim_Form_Number = $rowsInfos['Claim_Form_Number'];
						$Billing_Type = $rows['Billing_Type'];

                        //get last check in id
                    } else {
                        include("./includes/Folio_Number_Generator_Emergency.php");
                        $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
                        $rows_info = mysqli_fetch_array($select);

                        $Sponsor_Name = $rows_info['Guarantor_Name'];
                        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                        $Claim_Form_Number = $rows_info['Claim_Form_Number'];

                        if (strtolower($Sponsor_Name) == 'cash') {
                            $Billing_Type = "Inpatient Cash";
                        } else {
                            $Billing_Type = "Inpatient Credit";
                        }

                        $has_no_folio = true;
                    }


                    //get supervisor id
                    if (isset($_SESSION['Procedure_Supervisor'])) {
                        $Supervisor_ID = $_SESSION['Procedure_Supervisor']['Employee_ID'];
                    } else {
                        $Supervisor_ID = $_SESSION['userinfo']['Employee_ID'];
                    }

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
                            $details_data = mysql_fetch_row($select_details);
                            $Patient_Payment_ID = $details_data[0];
                            $Receipt_Date = $details_data[1];
                        } else {
                            $Patient_Payment_ID = 0;
                            $Receipt_Date = '';
                        }

                        //get data from tbl_item_list_cache
                        $Item_ID = $rows['Item_ID'];
                        $Discount = $rows['Discount'];
                        $Price = $rows['Price'];
                        $Quantity = $rows['Quantity'];
                        $Consultant = $rows['Consultant'];
                        $Consultant_ID = $rows['Consultant_ID'];


                        //insert data to tbl_patient_payment_item_list
                        if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                            $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,remarks,ServedDateTime,ServedBy,ItemOrigin)
                                                        values('Procedure','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','$status','$Patient_Payment_ID',(select now()),'" . $_POST['remarks_' . $value] . "',NOW(),'" . $_SESSION['userinfo']['Employee_ID'] . "','Doctor')") or die(mysqli_error($conn));
                            if ($insert) {

                                mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='" . $_POST['remarks_' . $value] . "',ServedDateTime=NOW(),ServedBy='" . $_SESSION['userinfo']['Employee_ID'] . "',Patient_Payment_ID = '$Patient_Payment_ID', Payment_Date_And_Time = '$Receipt_Date' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
                            }

                            //check if this user has folio 

                            if ($has_no_folio) {
                                $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                                $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                                                    WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                                mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                            }
                        }
                    }
                }//End of status=Done
                else {
                    //echo "$status Processed";

                    mysqli_query($conn,"update tbl_item_list_cache set Status='$status',remarks='" . $_POST['remarks_' . $value] . "',ServedDateTime=NOW(),ServedBy='" . $_SESSION['userinfo']['Employee_ID'] . "' WHERE Payment_Item_Cache_List_ID='$value'") or die(mysqli_error($conn));
                }
            }
        }
    }


    echo "<script type='text/javascript'>
                                alert('INFORMATION SAVED SUCCESSFULLY');
                                window.location='pendingproceduredocotorpatientinpatientinfo.php?" . $queryString . "'
                            </script>";
}

$Registration_ID = $_GET['Registration_ID'];
$section = '';


$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];
$Sponsor = strtolower($_GET['Sponsor']);

$locationURL = '';
//	$locationURL='procedurepatientinfo.php?section=Procedure&typeconsultant='.$typeconsultant.'&Registration_ID='.$Registration_ID.'&Transaction_Type='.$transaction_type.'&Payment_Cache_ID='.$Payment_Cache_ID.'&NR=True&Billing_Type='.$billing_type.'&Sub_Department_ID='.$Sub_Department_ID.'&statusMsg='.$_GET['statusMsg'].'&ProcedureWorks=ProcedureWorksThisPage';

$_SESSION['REDIRECT_TO_PROCEDURE'] = $locationURL;
//        $billing_type='Outpatient Cash';
//        $status="paid";
//echo $query_string;
//exit;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


$doctorproce = '';
$section = '';
if(isset($_GET['Section']) && $_GET['Section']=='Doctor'){
    $section="Section=Doctor&Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&";
}else{
    $section="sectionpatnt=doctor_with_patnt&Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&item_ID=". $_GET['item_ID']."&";
} 

?>
<a href='searchpatientpendingproceduredoctorlistinpatient.php?<?php echo $section ?>' class='art-button-green'>
    BACK
</a>
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>

<script type='text/javascript'>
    function di() {
        alert("All");
        $("#d").attr("hidden", "false").dialog();
    }
    function b(val) {
        alert(val);
    }
</script>





<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>




<?php
//    select patient information
if (isset($Registration_ID)) {
    // $Payment_Cache_ID = $_GET['Payment_Cache_ID']; 

    $selectPatQry = "select * from tbl_patient_payments pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
					    pc.Registration_ID = pr.Registration_ID and
						    pc.Employee_ID = emp.Employee_ID and
							    pc.Sponsor_ID = sp.Sponsor_ID and
								    pc.Registration_ID = '$Registration_ID'";

//        if(isset($_GET['typeconsultant']) && $_GET['typeconsultant']=='OTHERS_CONSULT'){
//             $selectPatQry="select * from tbl_patient_payments pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
//					    pc.Registration_ID = pr.Registration_ID and
//						    pc.Employee_ID = emp.Employee_ID and
//							    pc.Sponsor_ID = sp.Sponsor_ID and
//								    pc.Registration_ID = '$Registration_ID'";
//       
//        }else if(isset($_GET['typeconsultant'])&& $_GET['typeconsultant']=='DOCTOR_CONSULT'){
//             $selectPatQry="select * from tbl_payment_cache pc, tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp where
//					    pc.Registration_ID = pr.Registration_ID and
//						    pc.Employee_ID = emp.Employee_ID and
//							    pc.Sponsor_ID = sp.Sponsor_ID and
//								      pc.Registration_ID = '$Registration_ID'";
//             }

    $select_Patient = mysqli_query($conn,$selectPatQry) or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    // echo $no;exit;

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Billing_Type = $row['Billing_Type'];
            $Consultant = $row['Employee_Name'];
            $Folio_Number = '';
            if (isset($_GET['typeconsultant']) && $_GET['typeconsultant'] == 'OTHERS_CONSULT') {
                $Folio_Number = '';
            } else {
                $Folio_Number = $row['Folio_Number'];
            }
        }

        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Consultant = '';
        $Folio_Number = '';
        $Billing_Type = '';
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $Consultant = '';
    $Folio_Number = '';
    $Billing_Type = '';
}


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['Payment_Date_And_Time'])) {
    $Payment_Date_And_Time = $_GET['Payment_Date_And_Time'];
} else {
    $Payment_Date_And_Time = '';
}
?>
<!-- end of getting receipt number and receipt date-->
<br/><br/>
<fieldset style="background-color:#EEEEEE">  
    <legend  align="right"><b>PENDING / NOT APPLICABLE PROCEDURE PROCESSING</b></legend>
    <center>
        <table width='100%' style="background-color:white"> 
            <tr> 
                <td>
                    <table width=100%>
                        <tr>
                            <td width='10%' style="text-align:right;">Patient Name</td>
                            <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                            <td width='12%' style="text-align:right;">Card Expire Date</td>
                            <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                            <td width='11%' style="text-align:right;">Gender</td>
                            <td width='12%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
                            <td style="text-align:right;">Folio Number</td>
                            <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>

                        </tr> 
                        <tr>
                            <td style="text-align:right;">Billing Type</td> 
                            <td>
                                <select name='Billing_Type' id='Billing_Type'>
                                    <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                </select>
                            </td>
                            <td style="text-align:right;" >Claim Form Number</td>
                            <!--<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>-->
                            <?php
                            $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
                            $no = mysqli_num_rows($select_claim_status);
                            if ($no > 0) {
                                while ($row = mysqli_fetch_array($select_claim_status)) {
                                    $Claim_Number_Status = $row['Claim_Number_Status'];
                                }
                            } else {
                                $Claim_Number_Status = '';
                            }
                            ?>
                            <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'  placeholder='Claim Form Number'></td>
                            <?php } else { ?>
                                <td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number'></td>
                            <?php } ?>
                            <td style="text-align:right;">Occupation</td>
                            <td>
                                <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                            </td>
                            <td style="text-align:right;">&nbsp;</td>
                            <td>&nbsp;</td>

                        </tr>
                        <tr>
                            <td style="text-align:right;">Patient Age</td>
                            <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                            <td style="text-align:right;">Registered Date</td>
                            <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                            <td style="text-align:right;">Supervised By</td>

                            <?php
                            if (isset($_SESSION['Procedure_Supervisor']['Employee_Name'])) {
                                if (isset($_SESSION['Procedure_Supervisor']['Employee_Name'])) {
                                    if ($_SESSION['Procedure_Supervisor']['Employee_Name'] != '') {
                                        $Supervisor = $_SESSION['Procedure_Supervisor']['Employee_Name'];
                                    } else {
                                        $Supervisor = "Unknown Supervisor";
                                    }
                                } else {
                                    $Supervisor = "Unknown Supervisor";
                                }
                            } else {
                                $Supervisor = "Unknown Supervisor";
                            }
                            ?> 
                            <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>

                        </tr>
                        <tr> 
                            <td style="text-align:right;">Patient Direction</td>
                            <td>
                                <select id='direction' name='direction' > 
                                    <option selected='selected'>Others</option>
                                </select>
                            </td>
                            <td style="text-align:right;">Sponsor Name</td>
                            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            <td style="text-align:right;">Phone Number</td>
                            <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>

                        </tr>
                        <tr>
                            <td style="text-align:right;">Consultant</td>
                            <td>
                                <select name='Consultant' id='Consultant'>
                                    <option selected='selected'><?php echo $Consultant; ?></option>
                                </select>
                            </td>
                            <td style="text-align:right;">Registration Number</td>
                            <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>    
                            <td style="text-align:right;">Member Number</td>
                            <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 

                        </tr> 
                    </table>
                </td> 
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style="background-color:#EEEEEE">   
    <center>
        <table width=100%>
            <tr>

                <td style='text-align: right;'>
                    <?php
                    $typeconsultant = '';
                    $Transaction_Status_Title = '';
                    if (isset($_GET['section']) && ($_GET['section'] == 'doctor' || $_GET['section'] == 'Doctorlist')) {
                        
                    } else if (isset($_GET['sectionpatnt']) && $_GET['sectionpatnt'] == 'doctor_with_patnt') {
                        
                    } else {
                        ?> 
                        <input name="patient_file" id="patient_file" value="PATIENT FILE" onclick="Show_Patient_File(<?php echo $Registration_ID; ?>)" class="art-button-green" type="button">

                        <?php
                    }
                    ?>
                    <button type="button" class="submitData art-button" id="process_patient" onclick="checkStatus()" >PROCESS PATIENT</button>



                </td>
            </tr> 
        </table>
    </center>
</fieldset>


<fieldset >   
    <center>
        <center><b>LIST OF ITEMS </b></center>
        <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' name='myForm' id='myForm' enctype="multipart/form-data">
            <table width="100%" style="background-color:white">
                <tr>
                    <td>

                        <div id="patientItemsList" style='height:200px;overflow-x:hidden;overflow-y:scroll;  '>
                            <?php include "procedurependingdoctor_iframe.php"; ?>

                        </div>
                    </td>
                </tr>

            </table>
            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>">
            <input type="hidden" name="process_procedure" value="true"/>
            <input type="hidden" name="queryString" value="<?php echo $_SERVER['QUERY_STRING'] ?>"/>
        </form>
    </center>
</fieldset>



<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
    .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
    .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    #displaySelectedTests,#items_to_choose{
        overflow-y:scroll;
        overflow-x:hidden; 
    }
</style>

<script type='text/javascript'>

    $(document).ready(function () {
        if ($('.notpaid').length) {
            //alert('aaa');
            $("#process_patient").remove();
        }
        else if ($('.Procedureprogress').length == 0) {
            $("#process_patient").remove();
        }


        $.fn.dataTableExt.sErrMode = 'throw';
        $('#procedwureinfoData').DataTable({
            "bJQueryUI": true,
            "bFilter": false,
            "sPaginationType": "fully_numbers",
            "sDom": 't'

        });



    });
</script>
<script>
    function checkStatus() {

        if ($('.notpaid').length) {
            alert("What are you trying to do? The patient hasn't paid and you are processing him! Don't do that.");
            exit;
        }

        //check if atleaset one item is selected
        var chk = 0;
        $(".Procedureprogress").each(function () {
            var status = $(this).val();

            if (status == 'Select progress') {
                chk++;
            }
        });

        if (chk == $('.Procedureprogress').length) {
            alert("Please select progress status");
            $(".Procedureprogress").css('border', '1px solid red');
            exit;
        }

        if ($('.Procedureprogress').length) {
            if (confirm('Are you sure you want to save informations?')) {
                document.getElementById('myForm').submit();
            }
        } else {
            alert("It seems the patient have already been processed!");
            exit;
        }
    }
</script>
<script>
    function Show_Patient_File(Registration_ID) {
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=' + Registration_ID + '&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<!--End datetimepicker-->
<?php
include("./includes/footer.php");
?>