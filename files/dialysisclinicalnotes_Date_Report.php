<?php 
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
        if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Dialysis_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      FROM tbl_patient_registration pr, tbl_sponsor sp 
                                        WHERE pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
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
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
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
        $Claim_Number_Status = '';
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
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
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
    $age = 0;
}
;echo '';
//get cache details
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];

    $select_receipt_details = "SELECT * FROM  tbl_item_list_cache ic,tbl_consultation c,tbl_payment_cache pc,
				   tbl_patient_payment_item_list ppl,tbl_patient_payments pp
				   WHERE pc.Payment_Cache_ID = $Payment_Cache_ID
				   AND pc.Payment_Cache_ID = ic.Payment_Cache_ID
				   AND c.consultation_ID = pc.consultation_ID
				   AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID
				   AND ppl.Patient_Payment_Item_List_ID = c.Patient_Payment_Item_List_ID
				   AND 	ic.Check_In_Type='Procedure' LIMIT 1";
    $receipt_result = mysqli_query($conn,$select_receipt_details);
    while ($receipt_row = mysqli_fetch_assoc($receipt_result)) {
        $Consultant = $receipt_row['Consultant'];
        $Patient_Direction = $receipt_row['Patient_Direction'];
        $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'], 0, 10);
        $Folio_Number = $receipt_row['Folio_Number'];
        $Sponsor_ID = $receipt_row['Sponsor_ID'];
        $Sponsor_Name = $receipt_row['Sponsor_Name'];
        $Billing_Type = 'Outpatient Credit';
        $branch_id = $_SESSION['userinfo']['Branch_ID'];
        $Patient_Payment_ID = $receipt_row['Patient_Payment_ID'];
        $Claim_Form_Number = $receipt_row['Claim_Form_Number'];
    }
}

if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
} else {
    $consultation_id = '';
}

if (isset($_GET['Patient_Payment_ID'])) {
    $Payment_Cache_ID = $_GET['Patient_Payment_ID'];
} else {
    $Payment_Cache_ID = '';
}
;echo '';
//on form submit add bill information to payment table for credit patient
if (isset($_POST['frmsubmit'])) {
    if (isset($_GET['Payment_Cache_ID']) && isset($_GET['Payment_Item_Cache_List_ID'])) {
        //insert into payment table
        $Supervisor_ID = $_SESSION['Dialysis_Supervisor']['Employee_ID'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

        $inser_qr1 = "INSERT INTO tbl_patient_payments(Registration_ID, Supervisor_ID,Employee_ID,
							    Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
							    Sponsor_ID,Sponsor_Name,Billing_Type,Receipt_Date,Transaction_status,
							    Transaction_type, branch_id)
						    VALUES ($Registration_ID,$Supervisor_ID,
							    $Employee_ID,NOW(),$Folio_Number,'$Claim_Form_Number',$Sponsor_ID,
							    '$Sponsor_Name','$Billing_Type',
							    NOW(),'active','indirect cash',$branch_id)";
        if (mysqli_query($conn,$inser_qr1)) {
            $select_payment_id = "(SELECT Patient_Payment_ID,Payment_Date_And_Time FROM tbl_patient_payments
					WHERE Registration_ID =$Registration_ID AND Employee_ID=$Employee_ID ORDER BY Payment_Date_And_Time DESC LIMIT 1)";
            $payment_results = mysqli_query($conn,$select_payment_id);
            $Patient_Payment_ID = mysqli_fetch_assoc($payment_results)['Patient_Payment_ID'];
            $Payment_Date_And_Time = mysqli_fetch_assoc($payment_results)['Payment_Date_And_Time'];

            //LOOP TO INSERT DETAILS FROM CACHE TABLE
            $select_from_cache = "SELECT * FROM tbl_item_list_cache il, tbl_items i
					WHERE il.Payment_Item_Cache_List_ID = " . $_GET['Payment_Item_Cache_List_ID'] . "
					AND i.Item_ID = il.Item_ID
					AND i.Consultation_Type = 'Dialysis'
					AND il.Check_In_Type='Procedure'";


            $cache_result_list = mysqli_query($conn,$select_from_cache);
            while ($cache_row = mysqli_fetch_assoc($cache_result_list)) {
                //insert items
                $Check_In_Type = $cache_row['Check_In_Type'];
                $Category = $cache_row['Category'];
                $Item_ID = $cache_row['Item_ID'];
                $Discount = $cache_row['Discount'];
                $Price = $cache_row['Price'];
                $Quantity = $cache_row['Quantity'];
                $Patient_Direction = $cache_row['Patient_Direction'];
                $Consultant = $cache_row['Consultant'];
                $Consultant_ID = $cache_row['Consultant_ID'];
                $Status = 'active';
                $Process_Status = 'served';
                $Sub_Department_ID = $cache_row['Sub_Department_ID'];

                $inser_qr2 = "INSERT INTO tbl_patient_payment_item_list(Check_In_Type, Category, Item_ID,
			Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status, Patient_Payment_ID,
			Transaction_Date_And_Time, Process_Status, Sub_Department_ID)
			VALUES ('$Check_In_Type','$Category',$Item_ID,
			'$Discount', '$Price','$Quantity', '$Patient_Direction','$Consultant', $Consultant_ID,
			'$Status', $Patient_Payment_ID,(SELECT NOW()),
			'$Process_Status',$Sub_Department_ID)";

                if (mysqli_query($conn,$inser_qr2)) {
                    mysqli_query($conn,"UPDATE tbl_item_list_cache SET Patient_Payment_ID=$Patient_Payment_ID,Payment_Date_And_Time='$Payment_Date_And_Time',Employee_ID=" . $_SESSION['userinfo']['Employee_ID'] . ",Status='served' WHERE Payment_Cache_ID = " . $Payment_Cache_ID);
                }
            }
            ;echo '            <script type=\'text/javascript\'>
                alert(\'Bill Created Successfully !\');
                document.location = "dialysisclinicalnotes.php?Dialysisclinicalnotes=DialysisPageclinicalnotes&Patient_Payment_ID='; echo $Patient_Payment_ID; ;echo '&Registration_ID='; echo $Registration_ID; ;echo '";
            </script>
            ';
        }
    }
}
;echo '';
//select payment details
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $select_payment_id = "(SELECT * FROM tbl_patient_payment_item_list
					WHERE Patient_Payment_ID=$Patient_Payment_ID)";
        $payment_results = mysqli_query($conn,$select_payment_id);
        $Patient_Payment_Item_List_ID = mysqli_fetch_assoc($payment_results)['Patient_Payment_Item_List_ID'];
    }

    $select_receipt_details = "SELECT * FROM tbl_patient_payment_item_list
				   WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'
				   ";
    $receipt_result = mysqli_query($conn,$select_receipt_details);
    while ($receipt_row = mysqli_fetch_assoc($receipt_result)) {
        $Consultant = $receipt_row['Consultant'];
        $Patient_Direction = $receipt_row['Patient_Direction'];
        $Transaction_Date_And_Time = substr($receipt_row['Transaction_Date_And_Time'], 0, 10);
    }
}
;echo '';
//  echo '<a href="dialysisclinicalnotes_Dates.php?Registration_ID='.$Registration_ID.'&Patient_Payment_ID='.$Patient_Payment_ID.'&Patient_Payment_Item_List_ID='.$Payment_Item_Cache_List_ID.'&NR=true&PatientBilling=PatientBillingThisForm" class="art-button-green">BACK</a>';
  echo '<a href="dialysispatientList.php?DialysisClinicalnotes=DialysisClinicalnotesThispage" class="art-button-green">BACK</a>';
;echo '';
 if(isset($_GET['dialysis_details_ID'])){
     
  $dialysis_ID=  mysqli_real_escape_string($conn,$_GET['dialysis_details_ID']);
  $attend_query=mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE dialysis_details_ID=".$dialysis_ID."");
  $row=  mysqli_fetch_assoc($attend_query);
  $DialysiS_ASttendace_Date = $row['Attendance_Date'];
 }

;echo '             
<div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myConsult">
    </div>
</div>
<center>
<center>
    <form action="#" method=\'post\' name=\'myForm\' id=\'myForm\' onsubmit="return validateForm();" enctype="multipart/form-data"> 
        <fieldset><legend align=\'right\'><b>Dialysis Works</b></legend>
            <table width=100%>
                <tr>
                    <td><b>Patient Name</b></td><td><input type="text" name="" readonly=\'readonly\' value=\'';
                        if (isset($Patient_Name)) {
                            echo $Patient_Name;
                        }
                        ;echo '\' id=""></td>
                    <td><b>Visit Date</b></td><td><input type="text" name="" id="" readonly=\'readonly\' value=\'';
                        if (isset($Transaction_Date_And_Time)) {
                            echo $Transaction_Date_And_Time;
                        }
                        ;echo '\'></td>
                </tr>
                <tr>
                    <td><b>Patient Number</b></td><td><input type="text" name="" readonly=\'readonly\' value=\'';
                        if (isset($Registration_ID)) {
                            echo $Registration_ID;
                        }
                        ;echo '\' id="" ></td>
                    <td><b>Gender</b></td><td><input type="text" name="" id="" readonly=\'readonly\' value=\'';
                        if (isset($Gender)) {
                            echo $Gender;
                        }
                        ;echo '\' ></td></td>
                </tr>
                <tr>
                    <td><b>Sponsor</b></td><td><input type="text" name="" id="" readonly=\'readonly\' value=\'';
                        if (isset($Guarantor_Name)) {
                            echo $Guarantor_Name;
                        }
                        ;echo '\' ></td>
                    <td><b>Age</b></td><td><input type="text" name="" id="" readonly=\'readonly\' value=\'';
                        if (isset($age)) {
                            echo $age;
                        }
                        ;echo '\' ></td></td>
                </tr>
                <tr>
                    <td><b>Doctor</b></td><td><input type="text" name="" id="" readonly=\'readonly\' value=\'';
                        if (isset($Consultant)) {
                            if ($Patient_Direction == 'Direct To Clinic') {
                                echo $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                echo $Consultant;
                            }
                        }
                        ;echo '\' ></td>
                     <td>&nbsp;</td><td>
                        <input type=\'hidden\' id=\'recentConsultaionTyp\' value=\'\'>
                        <input type="button" name="showItems" value="Doctor`s Order" class="art-button-green" id="showItems" onclick="addItems(\''; echo $Registration_ID; ;echo '\')"/>
                        <img src="images/ajax-loader_1.gif" style="border-color:white;display:none;" id="verifyprogress">
                        <input type="button" name="" value="Preview" class="art-button-green" id="" onclick="preview(\''; echo $Registration_ID; ;echo '\',\''; echo $DialysiS_ASttendace_Date;;echo '\')"/>
                    </td>
                </tr>
            </table>

    </form>
</fieldset>
<br>
         
<center>
    <div id="container" style="display:none">
        <div id="default">
            <h1>#{title}</h1>
            <p>#{text}</p>
        </div>
    </div>
</center>
 <br />
 
<fieldset style="padding-bottom: 5px">
<legend> <b>Attendance Date: '; echo $DialysiS_ASttendace_Date;;echo '</b> </legend>
    <div id="example-tabs" style="height: 385px;">
        <h4>Vitals</h4>
        <section>
            <form name="saveData" action="Savedialysisclinicalnotes_Edit.php" id="SaveVitalsform" method="POST">
                <table align="left" style="width:100%;margin-top: -25px">

                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="24%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Vitals</td> <td width="25%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Previous Post</td><td style="font-weight:bold; background-color:#006400;color:white" width="24%">Pre</td> <td style="font-weight:bold; background-color:#006400;color:white" width="25%">Post</td></tr>
                                <td width="33%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                B/P
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                Weight
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                EDW
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Time
                                            </td>

                                        </tr>



                                    </table>


                                </td>
                             
                                <td >           
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="40%">
                                                <span class="pointer"><input type="text"  id="BP_previous_post_sit" name="BP_previous_post_sit" value="'; echo $row['bpPrevious_Post_sit'];;echo '"></span>
                                                <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <span class="pointer"><input type="text"  id="BP_previous_post_stand" name="BP_previous_post_stand" value="'; echo $row['bpPrevious_Post_stand'];;echo '"></span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeN"><input type="text"  id="BujeN" name="Weight_previous_post_sit" value="'; echo $row['Weight_Previous_Post_sit'];;echo '"></span>
                                            </td>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Weight_previous_post_stand" value="'; echo $row['Weight_Previous_Post_stand'];;echo '"></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Weight-Gain
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Weight_Gain" name="Weight_Gain" value="'; echo $row['Weight_Gain'];;echo '"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Time-On
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_On" name="Time_On" value="'; echo $row['Time_On'];;echo '"></span>
                                            </td>
                                        </tr>


                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_sit" name="BP_Pre_sit" value="'; echo $row['bpPre_sit'];;echo '"></span>

                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_stand" name="BP_Pre_stand" value="'; echo $row['bpPre_stand'];;echo '"></span>

                                            </td>
                                        </tr>


                                        <tr>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeN"><input type="text"  id="weight_pre_sit" name="weight_pre_sit" value="'; echo $row['Weight_Pre_sit'];;echo '"></span>
                                            </td>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeH"><input type="text"  id="weight_pre_stand" name="weight_pre_stand" value="'; echo $row['Weight_Pre_stand'];;echo '"></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Weight-removal
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Weight_removal" name="Weight_removal" value="'; echo $row['Weight_removal'];;echo '"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Time-Off
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_Off" name="Time_Off" value="'; echo $row['Time_Off'];;echo '"></span>
                                            </td>
                                        </tr>

                                    </table>   

                                </td>


                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Post_Pre_sit" name="Post_Pre_sit" value="'; echo $row['bpPost_sit'];;echo '"></span>

                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Post_Pre_stand" name="Post_Pre_stand" value="'; echo $row['bpPost_stand'];;echo '"></span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeN"><input type="text"  id="Weight_Post_sit" name="Weight_Post_sit"></span>
                                            </td>
                                            <td></td>
                                            <td>
                                                <span class="pointer" id="spanBujeH"><input type="text"  id="Weight_Post_stand" name="Weight_Post_stand" value="'; echo $row['Weight_Post_stand'];;echo '"></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Area#
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Area" name="Area" value="'; echo $row['Area'];;echo '"></span>
                                            </td>

                                            <td style="text-align:right;width:200px;">
                                                Station#
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Station" name="Station" value="'; echo $row['Station'];;echo '"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Machine#
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Machine" name="Machine" value="'; echo $row['Machine'];;echo '"></span>
                                            </td>
                                        </tr>

                                    </table>   

                                </td>

<!--Hosp-->
                    </tr>

                </table> 

                <table style="width: 100%">
                    <tr>
                        <td>Hosp/ER/OP Procedure since last treatment?</td>
                        '; 
                        
                        if($row['Hosp']=='yes'){
                            $checked='yes';
                        }else{
                            
                            $checked='no';
                            
                        }
                         
                        ;echo '                        
                        <td><span><input type="radio" name="hosp" '; if(strtolower($checked) =='yes'){ echo 'checked="checked"'; };echo ' value="Yes">&nbsp;&nbsp;Yes</span></td> 
                        <td><span><input type="radio" name="hosp" '; if(strtolower($checked) =='no'){ echo 'checked="checked"'; };echo ' value="No">&nbsp;&nbsp;No</span></td>
                            
                        
                    </tr>

                </table>
                </td>

                </tr>
                <tr>
                 <td>
                <center><input type="submit" value="Save Data" id="saveVitals" name="saveVitals" class="art-button-green"></center>
                <input type="hidden" name="SubmitVitals">
                <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                </td>
                </tr>
                </table> 
            </form>
        </section>
        
        <h4>Mach. Assesment</h4>
        <section>
            <form id="MachineAccess" name="MachineAccess" action="Savedialysisclinicalnotes_Edit.php" method="POST">
                <table  class="" border="0" style="margin-top:-20px;width:100% " align="left" >
                    <tr>
                        <td >           
                            <table width="100%">
                                <tr>
                                    <td style="text-align:right;width:100px;">Conductivity Machine</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Conductivity_Machine" name="Conductivity_Machine" value="'; echo $row['Conductivity_Machine'];;echo '"></span>

                                    </td>

                                    <td style="text-align:right;width:100px;">Manual</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Conductivity_Manual" name="Conductivity_Manual" value="'; echo $row['Conductivity_manual'];;echo '"></span>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">pH Machine</td>
                                    <td>
                                        <span class="pointer" id="spanBujeN"><input type="text"  id="pH_Machine" name="pH_Machine" value="'; echo $row['pH_Machine'];;echo '"></span>
                                    </td>
                                    <td style="text-align:right;width:100px;">Manual</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="pH_Manual" name="pH_Manual" value="'; echo $row['pH_Manual'];;echo '"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Temperature Machine
                                    </td>
                                    <td>
                                        <span class="pointer" id="spankidondaN"><input type="text"  id="Temperature_Machine" name="Temperature_Machine" value="'; echo $row['Temperature_Machine'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;">Initial</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="Temperature_Initial" name="Temperature_Initial" value="'; echo $row['Temperature_Initial'];;echo '"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Alarm Test
                                    </td>
                                    <td>
                                        ';
                                        if($row['Alarm_Test']=='Pass'){
                                           echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  checked="true" id="Alarm_Test" name="Alarm_Test"> &nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
                                   '; 
                                        }else{
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="Alarm_Test" name="Alarm_Test"> &nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
                                   '; 
                                            
                                        }
                                        
                                        
                                        ;echo '
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Air Detector on
                                    </td>
                                    <td>
                                        ';
                                            if($row['Air_Detector']=='Yes'){
                                                echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox" checked="true"  id="Air_Detector" name="Air_Detector">&nbsp;&nbsp;&nbsp;&nbsp;Yes</span>
';
                                                
                                            }  else {
                                                
                                                echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="Air_Detector" name="Air_Detector">&nbsp;&nbsp;&nbsp;&nbsp;Yes</span>
';
                                                
                                            }
                                        
                                        ;echo '
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        UF System
                                    </td>
                                    <td>
                                       ';
                                       if($row['UF_System']=='Pass'){
                                       echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox" checked="true"  id="UF_System" name="UF_System">&nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
';  
                                       }  else {
                                           echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="UF_System" name="UF_System">&nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
';  
                                       }
                                       
                                       
                                       ;echo '                                    </td>

                                    <td style="text-align:right;width:100px;">Initial</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="UF_Initial" name="UF_Initial" value="'; echo $row['UF_System_initial'];;echo '"></span>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Positive Presence Test
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Positive_Presence" name="Positive_Presence" value="'; echo $row['Positive_Presence'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;"></td>
<!--                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                    </td>-->
                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Negative Residual Test
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Negative_Residual" name="Negative_Residual" value="'; echo $row['Negative_Residual'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;"></td>
<!--                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                    </td>-->
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Dialyzer ID
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dialyzer_ID" name="Dialyzer_ID" value="'; echo $row['Dialyzer_ID'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;">
                                      <center> <input type="submit" value="Save Data" name="SaveAcess" class="art-button-green"> </center>

                                    </td>
<!--                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                    </td>-->
                                </tr>

                            </table>
                        </td>

                    </tr>
                    <tr>
                    <td>
                        <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                        <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                        <input type="hidden" name="SaveMachineAccess">
                    </td>
                    </tr>

                </table> 
            </form>
        </section>

        <h4>Heparain</h4>
        <section>
            <form action="Savedialysisclinicalnotes_Edit.php" name="Heparainform" id="Heparainform" method="POST">
                <table  class="" border="0" style="margin-top:-20px;width:100% " align="left" >
                    <tr>
                        <td >           
                            <table width="100%">
                                <tr>
                                    <td style="text-align:right;width:100px;">Type</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Type" name="Type" value="'; echo $row['Type'];;echo '"></span>

                                    </td>

                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">Initial Bolus Units</td>
                                    <td>
                                        <span class="pointer" id="spanBujeN"><input type="text"  id="Initial_Bolus" name="Initial_Bolus" value="'; echo $row['Initial_Bolus'];;echo '"></span>
                                    </td>

                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Unit/Hr
                                    </td>
                                    <td>
                                        <span class="pointer" id="spankidondaN"><input type="text"  id="Unit_Hr" name="Unit_Hr" value="'; echo $row['Unit_Hr'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;">Infusion/Bolus</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="Infusion_Bolus" name="Infusion_Bolus" value="'; echo $row['Infusion_Bolus'];;echo '"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Stop time
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Stop_time" name="Stop_time" value="'; echo $row['Stop_time'];;echo '"></span>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        CVC Post Instil
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="CVC_Post" name="CVC_Post" value="'; echo $row['CVC_Pos'];;echo '"></span>
                                    </td>

                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Arterial
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Arterial" name="Arterial" value="'; echo $row['Arterial'];;echo '"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;">Venous</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="Venous" name="Venous" value="'; echo $row['Venous'];;echo '"></span>
                                    </td>
                                </tr>

                            </table>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                            <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                    <center> <input type="submit" value="Save Data" class="art-button-green"> </center>
                    <input type="hidden" name="HeparainSave">
                    </td>
                    </tr>

                </table> 
            </form>
        </section>
        <h4>Dialysis Orders</h4>
        <section>
            <form action="Savedialysisclinicalnotes_Edit.php" method="POST" id="DialysisOrdersform">
                <table  class="" border="0" style="width:100% " align="left" >
                    <tr>
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td> <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td><td style="font-weight:bold; background-color:#006400;color:white" width="16%"></td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%"></td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%"></td></tr>
                    <td  colspan="" align="right" style="text-align:right;">
                        <table width="100%">
                            <tr>
                                <td>
                                    Dialyzer
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Dialysate
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Sodium Modelling
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    UD Profiling Max UFR
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Dialysate Temp
                                </td>

                            </tr>


                        </table>


                    </td>




                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Dialyzer_1" name="Dialyzer_1" value="'; echo $row['Dialyzer_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Dialysate_1" name="Dialysate_1" value="'; echo $row['Dialysate_1'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Sodium_1" name="Sodium_1" value="'; echo $row['Sodium_1'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="UD_1" name="UD_1" value="'; echo $row['UD_1'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Temp_1" name="Temp_1" value="'; echo $row['Temp_1'];;echo '"></span>
                                </td>
                            </tr>

                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialyzer_2" name="Dialyzer_2" value="'; echo $row['Dialyzer_2'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialysate_2" name="Dialysate_2" value="'; echo $row['Dialysate_2'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Sodium_2" name="Sodium_2" value="'; echo $row['Sodium_2'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="UD_2" name="UD_2" value="'; echo $row['UD_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Temp_2" name="Temp_2" value="'; echo $row['Temp_2'];;echo '"></span>
                                </td>
                            </tr>



                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialyzer_3" name="Dialyzer_3" value="'; echo $row['Dialyzer_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialysate_3" name="Dialysate_3" value="'; echo $row['Dialysate_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Sodium_3" name="Sodium_3" value="'; echo $row['Sodium_3'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="UD_3" name="UD_3" value="'; echo $row['UD_3'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Temp_3" name="Temp_3" value="'; echo $row['Temp_3'];;echo '"></span>
                                </td>
                            </tr>


                        </table>   

                    </td>



                    <td>
                        <table width="100%">
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialyzer_4" name="Dialyzer_4" value="'; echo $row['Dialyzer_4'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dialysate_4" name="Dialysate_4" value="'; echo $row['Dialysate_4'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Sodium_4" name="Sodium_4" value="'; echo $row['Sodium_4'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="UD_4" name="UD_4" value="'; echo $row['UD_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Temp_4" name="Temp_4" value="'; echo $row['Temp_4'];;echo '"></span>
                                </td>
                            </tr>

                        </table> 
                    </td>

                </table>
                <br />
                <table>
                    <tr>
                        <td>
                    <center> 
                        <input type="submit" value="Save Data" class="art-button-green">
                        <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                        <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                        <input type="hidden" name="SaveDialysisbtn">
                    </center>
                    </td>
                    </tr>
                </table>

            </form>
        </section>

        <h4>Access Orders</h4>
        <section>
            <form action="Savedialysisclinicalnotes_Edit.php" method="POST" id="AccessOrdersform">
                <textarea name="Orderstextarea" id="Orderstextarea" style="width: 100%;height: 100px;margin-top: -30px">
                        
                '; echo $row['AccessOrdersNotes'];;echo '                </textarea>
                <table  class="" border="0" style="margin-top:10px;width:100% " align="left" >
                    <tr>
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td> <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Needle Gauge</td><td style="font-weight:bold; background-color:#006400;color:white" width="16%">Needle Length</td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Static Pressure</td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Bleeding Stopped</td></tr>
                    <td  colspan="" align="right" style="text-align:right;">
                        <table width="100%">
                            <tr>
                                <td>
                                    Arterial
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Venous
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Canulated/Catheter accessed by
                                </td>

                            </tr>


                        </table>


                    </td>




                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Arterial_Needle_Gauge" name="Arterial_Needle_Gauge" value="'; echo $row['Arterial_Needle_Gauge'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Venos_Needle_Gauge" name="Venos_Needle_Gauge" value="'; echo $row['Venos_Needle_Gauge'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Arterial_Needle_Length" name="Arterial_Needle_Length" value="'; echo $row['Arterial_Needle_Length'];;echo '"></span>
                                </td>
                            </tr>

                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Vonos_Needle_Length" name="Vonos_Needle_Length" value="'; echo $row['Vonos_Needle_Length'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Arterial_Static_Pressuer" name="Arterial_Static_Pressuer" value="'; echo $row['Arterial_Static_Pressuer'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Vonos_Static_Pressuer" name="Vonos_Static_Pressuer" value="'; echo $row['Vonos_Static_Pressuer'];;echo '"></span>
                                </td>
                            </tr>



                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Arterial_Bleeding_Stopped" name="Arterial_Bleeding_Stopped" value="'; echo $row['Arterial_Bleeding_Stopped'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Vonos_Bleeding_Stopped" name="Vonos_Bleeding_Stopped" value="'; echo $row['Vonos_Bleeding_Stopped'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="kidonda"></span>
                                </td>
                            </tr>


                        </table>   

                    </td>



                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="uchunguzi_titi"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="kidonda"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>

                    </tr>
                    
                </table> 
                
                <table>
                   <tr>
                    <td>
                    <center> <input type="submit" value="Save Data" id="AccessOrdersbtn" class="art-button-green"> </center>
                    <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                    <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                    <input type="hidden" name="AccessOrdersbtn">

                    </td>
                    </tr> 
                    
                </table>
            </form>
        </section>


        <h4>Doctor  Notes</h4>
        <section>
            <form action="Savedialysisclinicalnotes_Edit.php" method="POST" id="SaveNotesfrm">
                <table style="width: 100%">
                    <tr>
                        <td>

                            <textarea name="txtNotes" style="width: 100%;height: 200px;margin-top: -30px">
                                '; echo $row['Notes'];;echo '                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                    <center><input type="submit" value="Save Data" class="art-button-green" style="margin-top: 50px"></center>
                    <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                    <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                    <input type="hidden" name="SaveNotesbtn">
                    </td>
                    </tr>
                </table>
            </form>
        </section>

        <h4>Data Collection</h4>
        <section>
            <form action="Savedialysisclinicalnotes_Edit.php" method="POST" id="SaveDataCollectionform">
                <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
                    <tr>
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td> <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Pre Assessment</td><td style="font-weight:bold; background-color:#006400;color:white" width="16%">Time</td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Initials</td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Post</td> <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Initials</td></tr>
                    <td  colspan="" align="right" style="text-align:right;">
                        <table width="100%">
                            <tr>
                                <td>
                                    Temp
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Resp
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    GI
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Cardiac
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Edema
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Mental
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Mobility
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Access
                                </td>

                            </tr>


                        </table>


                    </td>




                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Pre_Assessment" name="Temp_Pre_Assessment" value="'; echo $row['Temp_Pre_Assessment'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Pre_Assessment" name="Resp_Pre_Assessment" value="'; echo $row['Resp_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Pre_Assessment" name="GI_Pre_Assessment" value="'; echo $row['GI_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Pre_Assessment" name="Cardiac_Pre_Assessment" value="'; echo $row['Cardiac_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Pre_Assessment" name="Edema_Pre_Assessment" value="'; echo $row['Edema_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Pre_Assessment" name="Mental_Pre_Assessment" value="'; echo $row['Mental_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Pre_Assessment" name="Mobility_Pre_Assessment" value="'; echo $row['Mobility_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>


                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Pre_Assessment" name="Access_Pre_Assessment" value="'; echo $row['Access_Pre_Assessment'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Time" name="Temp_Time" value="'; echo $row['Temp_Time'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Time" name="Resp_Time" value="'; echo $row['Resp_Time'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Time" name="GI_Time" value="'; echo $row['GI_Time'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Time" name="Cardiac_Time" value="'; echo $row['Cardiac_Time'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Time" name="Edema_Time" value="'; echo $row['Edema_Time'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Time" name="Mental_Time" value="'; echo $row['Mental_Time'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Time" name="Mobility_Time" value="'; echo $row['Mobility_Time'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Time" name="Access_Time" value="'; echo $row['Access_Time'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials" name="Temp_Initials" value="'; echo $row['Temp_Initials'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Initials" name="Resp_Initials" value="'; echo $row['Resp_Initials'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Initials" name="GI_Initials" value="'; echo $row['GI_Initials'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Initials" name="Cardiac_Initials" value="'; echo $row['Cardiac_Initials'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Initials" name="Edema_Initials" value="'; echo $row['Edema_Initials'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Initials" name="Mental_Initials" value="'; echo $row['Mental_Initials'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Initials" name="Mobility_Initials" value="'; echo $row['Mobility_Initials'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Initials" name="Access_Initials" value="'; echo $row['Access_Initials'];;echo '"></span>
                                </td>
                            </tr>
                        </table>   

                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Post" name="Temp_Post" value="'; echo $row['Temp_Post'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Post" name="Resp_Post" value="'; echo $row['Resp_Post'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Post" name="GI_Post" value="'; echo $row['GI_Post'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Post" name="Cardiac_Post" value="'; echo $row['Cardiac_Post'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Post" name="Edema_Post" value="'; echo $row['Edema_Post'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Post" name="Mental_Post" value="'; echo $row['Mental_Post'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Post" name="Mobility_Post" value="'; echo $row['Mobility_Post'];;echo '"></span>
                                </td>
                            </tr>


                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Post" name="Access_Post" value="'; echo $row['Access_Post'];;echo '"></span>
                                </td>
                            </tr>

                        </table> 


                    </td>


                    <td>
                        <table width="100%">
                            <!---->
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials2" name="Temp_Initials2" value="'; echo $row['Temp_Initials2'];;echo '"></span>

                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Resp_Initials2" name="Resp_Initials2" value="'; echo $row['Resp_Initials2'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="GI_Initials2" name="GI_Initials2" value="'; echo $row['GI_Initials2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Cardiac_Initials2" name="Cardiac_Initials2" value="'; echo $row['Cardiac_Initials2'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Initials2" name="Edema_Initials2" value="'; echo $row['Edema_Initials2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Initials2" name="Mental_Initials2" value="'; echo $row['Mental_Initials2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Initials2" name="Mobility_Initials2" value="'; echo $row['Mobility_Initials2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Initials2" name="Access_Initials2" value="'; echo $row['Access_Initials2'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>
                    </tr>
                   
                </table> 
                
                <table>
                    <tr>
                    <td>
                    <center> <input type="submit" value="Save Data" class="art-button-green"></center>
                    <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                    <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                    <input type="hidden" name="SaveDataCollectionbtn">
                    </td>
                    </tr>
                </table>
            </form>
        </section>
        
        <h4>Obs. charts</h4>
        <section>
            <form id="Observation_Charts" name="Observation_Charts" method="POST" action="Savedialysisclinicalnotes_Edit.php">
                <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
                    <tr>
                        <td width="6%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">TIME</td> <td width="6%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">BP</td><td style="font-weight:bold; background-color:#006400;color:white" width="6%">HR</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">QB</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">QD</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">AP</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">VP</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">FldRmvd</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Heparin</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Saline</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">UFR</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">TMP</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">BVP/LP</td> <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Access</td> <td style="font-weight:bold; background-color:#006400;color:white" width="10%">Notes</td></tr>
                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Time_1" name="Time_1" value="'; echo $row['Time_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Time_2" name="Time_2" value="'; echo $row['Time_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Time_3" name="Time_3" value="'; echo $row['Time_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_4" name="Time_4" value="'; echo $row['Time_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_5" name="Time_5" value="'; echo $row['Time_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_6" name="Time_6" value="'; echo $row['Time_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_7" name="Time_7" value="'; echo $row['Time_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>


                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_1" name="BP_1" value="'; echo $row['BP_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BP_2" name="BP_2" value="'; echo $row['BP_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="BP_3" name="BP_3" value="'; echo $row['BP_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="BP_4" name="BP_4" value="'; echo $row['BP_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="BP_5" name="BP_5" value="'; echo $row['BP_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="BP_6" name="BP_6" value="'; echo$row['BP_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="BP_7" name="BP_7" value="'; echo $row['BP_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="HR_1" name="HR_1" value="'; echo $row['HR_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="HR_2" name="HR_2" value="'; echo $row['HR_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="HR_3" name="HR_3" value="'; echo $row['HR_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="HR_4" name="HR_4" value="'; echo $row['HR_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="HR_5" name="HR_5" value="'; echo $row['HR_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="HR_6" name="HR_6" value="'; echo $row['HR_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="HR_7" name="HR_7" value="'; echo $row['HR_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QB_1" name="QB_1" value="'; echo $row['QB_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="QB_2" name="QB_2" value="'; echo $row['QB_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="QB_3" name="QB_3" value="'; echo $row['QB_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QB_4" name="QB_4" value="'; echo $row['QB_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QB_5" name="QB_5" value="'; echo $row['QB_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QB_6" name="QB_6" value="'; echo $row['QB_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QB_7" name="QB_7" value="'; echo $row['QB_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>



                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QD_1" name="QD_1" value="'; echo $row['QD_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="QD_2" name="QD_2" value="'; echo $row['QD_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="QD_3" name="QD_3" value="'; echo $row['QD_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QD_4" name="QD_4" value="'; echo $row['QD_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QD_5" name="QD_5" value="'; echo $row['QD_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QD_6" name="QD_6" value="'; echo $row['QD_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="QD_7" name="QD_7" value="'; echo $row['QD_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="AP_1" name="AP_1" value="'; echo $row['AP_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="AP_2" name="AP_2" value="'; echo $row['AP_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="AP_3" name="AP_3" value="'; echo $row['AP_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="AP_4" name="AP_4" value="'; echo $row['AP_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="AP_5" name="AP_5" value="'; echo $row['AP_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="AP_6" name="AP_6" value="'; echo $row['AP_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="AP_7" name="AP_7" value="'; echo $row['AP_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="VP_1" name="VP_1"value="'; echo $row['VP_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="VP_2" name="VP_2" value="'; echo $row['VP_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="VP_3" name="VP_3" value="'; echo $row['VP_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="VP_4" name="VP_4" value="'; echo $row['VP_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="VP_5" name="VP_5" value="'; echo $row['VP_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="VP_6" name="VP_6" value="'; echo $row['VP_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="VP_7" name="VP_7" value="'; echo $row['VP_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    
                    
                    <!-------------------------------------mwisho hapa bana--------------------------------->
                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="FldRmvd_1" name="FldRmvd_1" value="'; echo $row['FldRmvd_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="FldRmvd_2" value="'; echo $row['FldRmvd_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="FldRmvd_3" value="'; echo $row['FldRmvd_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="FldRmvd_4" value="'; echo $row['FldRmvd_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>
                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="FldRmvd_5" value="'; echo $row['FldRmvd_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="FldRmvd_6" value="'; echo $row['FldRmvd_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="FldRmvd_7" value="'; echo $row['FldRmvd_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Heparin_1" value="'; echo $row['Heparin_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Heparin_2" value="'; echo $row['Heparin_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="Heparin_3" value="'; echo $row['Heparin_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Heparin_4" value="'; echo $row['Heparin_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Heparin_5" value="'; echo $row['Heparin_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Heparin_6" value="'; echo $row['Heparin_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Heparin_7" value="'; echo $row['Heparin_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Saline_1" value="'; echo $row['Saline_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Saline_2" value="'; echo $row['Saline_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="Saline_3" value="'; echo $row['Saline_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Saline_4" value="'; echo $row['Saline_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Saline_5" value="'; echo $row['Saline_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Saline_6" value="'; echo $row['Saline_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Saline_7" value="'; echo $row['Saline_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="UFR_1" value="'; echo $row['UFR_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="UFR_2" value="'; echo $row['UFR_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="UFR_3" value="'; echo $row['UFR_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="UFR_4" value="'; echo $row['UFR_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="UFR_5" value="'; echo $row['UFR_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="UFR_6" value="'; echo $row['UFR_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="UFR_7" value="'; echo $row['UFR_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td>           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="TMP_1" value="'; echo $row['TMP_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="TMP_2" value="'; echo $row['TMP_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="TMP_3" value="'; echo $row['TMP_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="TMP_4" value="'; echo $row['TMP_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="TMP_5" value="'; echo $row['TMP_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="TMP_6" value="'; echo $row['TMP_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="TMP_7" value="'; echo $row['TMP_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="BVP_1" value="'; echo $row['BVP_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="BVP_2" value="'; echo $row['BVP_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="BVP_3" value="'; echo $row['BVP_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="BVP_4" value="'; echo $row['BVP_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="BVP_5" value="'; echo $row['BVP_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="BVP_6" value="'; echo $row['BVP_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="BVP_7" value="'; echo $row['BVP_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Access_1" value="'; echo $row['Access_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Access_2" value="'; echo $row['Access_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="Access_3" value="'; echo $row['Access_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Access_4" value="'; echo $row['Access_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Access_5" value="'; echo $row['Access_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Access_6" value="'; echo $row['Access_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Access_7" value="'; echo $row['Access_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Notes_1" value="'; echo $row['Notes_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Notes_2" value="'; echo $row['Notes_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="Notes_3" value="'; echo $row['Notes_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Notes_4" value="'; echo $row['Notes_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Notes_5" value="'; echo $row['Notes_5'];echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Notes_6" value="'; echo $row['Notes_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="chuchu_damuN" name="Notes_7" value="'; echo $row['Notes_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>


                    </tr>
                    

                </table>
                <table>
                   <tr>
                    <td>
                    <center> 
                        <input type="submit" value="Save Data" class="art-button-green"> 
                        <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                        <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                        <input type="hidden" name="SaveObservationChartbtn">
                    </center>
                    </td>

                    </tr> 
                </table>

            </form>
        </section>


        <h4>Medic. charts</h4>
        <section>
            <form id="Medication_Charts" name="Medication_Charts" method="POST" action="Savedialysisclinicalnotes_Edit.php">
                <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
                    <tr>
                        <td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Drug/Ancillary </td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Indication/comment</td>  <td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Dose</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Route</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Time</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Initials</td>    <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Drug/Ancillary</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Indication/Comment</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Dose</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Route</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Time</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Initials</td></tr>
                    <td>           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Ancillary_1" name="Ancillary_1" value="'; echo $row['Ancillary_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Ancillary_2" name="Ancillary_2" value="'; echo $row['Ancillary_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Ancillary_3" name="Ancillary_3" value="'; echo $row['Ancillary_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_4" name="Ancillary_4" value="'; echo $row['Ancillary_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_5" name="Ancillary_5" value="'; echo $row['Ancillary_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_6" name="Ancillary_6" value="'; echo $row['Ancillary_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_7" name="Ancillary_7" value="'; echo $row['Ancillary_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Indication_1" name="Indication_1" value="'; echo $row['Indication_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Indication_2" name="Indication_2" value="'; echo $row['Indication_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Indication_3" name="Indication_3" value="'; echo $row['Indication_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_4" name="Indication_4" value="'; echo $row['Indication_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_5" name="Indication_5" value="'; echo $row['Indication_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_6" name="Indication_6" value="'; echo $row['Indication_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_7" name="Indication_7" value="'; echo $row['Indication_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Dose_1" name="Dose_1" value="'; echo $row['Dose_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Dose_2" name="Dose_2" value="'; echo $row['Dose_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dose_3" name="Dose_3" value="'; echo $row['Dose_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_4" name="Dose_4" value="'; echo $row['Dose_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_5" name="Dose_5" value="'; echo $row['Dose_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_6" name="Dose_6" value="'; echo $row['Dose_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_7" name="Dose_7" value="'; echo $row['Dose_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Route_1" name="Route_1" value="'; echo $row['Route_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Route_2" name="Route_2" value="'; echo $row['Route_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Route_3" name="Route_3" value="'; echo $row['Route_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_4" name="Route_4" value="'; echo $row['Route_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_5" name="Route_5" value="'; echo $row['Route_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_6" name="Route_6" value="'; echo $row['Route_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_7" name="Route_7" value="'; echo $row['Route_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Time_chart_1" name="Time_chart_1" value="'; echo $row['Time_chart_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Time_chart_2" name="Time_chart_2" value="'; echo $row['Time_chart_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Time_chart_3" name="Time_chart_3" value="'; echo $row['Time_chart_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_chart_4" name="Time_chart_4" value="'; echo $row['Time_chart_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_chart_5" name="Time_chart_5" value="'; echo $row['Time_chart_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_chart_6" name="Time_chart_6" value="'; echo $row['Time_chart_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_chart_7" name="Time_chart_7" value="'; echo $row['Time_chart_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Initials_charts_1" name="Initials_charts_1" value="'; echo $row['Initials_charts_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Initials_charts_2" name="Initials_charts_2" value="'; echo $row['Initials_charts_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Initials_charts_3" name="Initials_charts_3" value="'; echo $row['Initials_charts_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_charts_4" name="Initials_charts_4" value="'; echo $row['Initials_charts_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_charts_5" name="Initials_charts_5" value="'; echo $row['Initials_charts_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_charts_6" name="Initials_charts_6" value="'; echo $row['Initials_charts_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_charts_7" name="Initials_charts_7" value="'; echo $row['Initials_charts_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td>           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Ancillary_2_1" name="Ancillary_2_1" value="'; echo $row['Ancillary_2_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Ancillary_2_2" name="Ancillary_2_2" value="'; echo $row['Ancillary_2_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Ancillary_2_3" name="Ancillary_2_3" value="'; echo $row['Ancillary_2_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_2_4" name="Ancillary_2_4" value="'; echo $row['Ancillary_2_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_2_5" name="Ancillary_2_5" value="'; echo $row['Ancillary_2_5'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_2_6" name="Ancillary_2_6" value="'; echo $row['Ancillary_2_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Ancillary_2_7" name="Ancillary_2_7" value="'; echo $row['Ancillary_2_7'];;echo '"></span>
                                </td>
                            </tr>


                        </table>
                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Indication_1_1" name="Indication_1_1" value="'; echo $row['Indication_1_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Indication_1_2" name="Indication_1_2" value="'; echo $row['Indication_1_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Indication_1_3" name="Indication_1_3" value="'; echo $row['Indication_1_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_1_4" name="Indication_1_4" value="'; echo $row['Indication_1_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_1_5" name="Indication_1_5" value="'; echo $row['Indication_1_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_1_6" name="Indication_1_6" value="'; echo $row['Indication_1_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Indication_1_7" name="Indication_1_7" value="'; echo $row['Indication_1_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Dose_1_1" name="Dose_1_1" value="'; echo $row['Dose_1_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Dose_1_2" name="Dose_1_2" value="'; echo $row['Dose_1_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Dose_1_3" name="Dose_1_3" value="'; echo $row['Dose_1_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_1_4" name="Dose_1_4" value="'; echo $row['Dose_1_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_1_5" name="Dose_1_5" value="'; echo $row['Dose_1_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_1_6" name="Dose_1_6" value="'; echo $row['Dose_1_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dose_1_7" name="Dose_1_7" value="'; echo $row['Dose_1_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Route_1_1" name="Route_1_1" value="'; echo $row['Route_1_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Route_1_2" name="Route_1_2" value="'; echo $row['Route_1_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Route_1_3" name="Route_1_3" value="'; echo $row['Route_1_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_1_4" name="Route_1_4" value="'; echo $row['Route_1_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_1_5" name="Route_1_5" value="'; echo $row['Route_1_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_1_6" name="Route_1_6" value="'; echo $row['Route_1_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Route_1_7" name="Route_1_7" value="'; echo $row['Route_1_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Time_1_1" name="Time_1_1" value="'; echo $row['Time_1_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Time_1_2" name="Time_1_2" value="'; echo $row['Time_1_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Time_1_3" name="Time_1_3" value="'; echo $row['Time_1_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_1_4" name="Time_1_4" value="'; echo $row['Time_1_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_1_5" name="Time_1_5" value="'; echo $row['Time_1_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_1_6" name="Time_1_6" value="'; echo $row['Time_1_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Time_1_7" name="Time_1_7" value="'; echo $row['Time_1_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Initials_1" name="Initials_1" value="'; echo $row['Initials_1'];;echo '"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Initials_2" name="Initials_2" value="'; echo $row['Initials_2'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Initials_3" name="Initials_3" value="'; echo $row['Initials_3'];;echo '"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_4" name="Initials_4" value="'; echo $row['Initials_4'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_5" name="Initials_5" value="'; echo $row['Initials_5'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_6" name="Initials_6" value="'; echo $row['Initials_6'];;echo '"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_7" name="Initials_7" value="'; echo $row['Initials_7'];;echo '"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>


                    </tr>

                    

                </table> 
                
                <table>
                   <tr>
                    <td>
                    <center> 
                        <input type="submit" value="Save Data" class="art-button-green">
                        <input type="hidden" name="Registration_ID" value="'; echo $dialysis_ID; ;echo '">
                        <input type="hidden" name="Payment_Cache_ID" value="'; echo $Payment_Cache_ID; ;echo '">
                        <input type="hidden" name="SaveMedicationChartbtn">
                    </center>
                    </td>
                    </tr> 
                    
                </table>
            </form>
        </section>
    </div>
</fieldset>
</center>
            
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/jquery.steps.css">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery.notify.min.js"></script> 
<script src="css/jquery.form.js"></script> 
<script src="css/lib/modernizr-2.6.2.min.js"></script>
<script src="css/lib/jquery.cookie-1.3.1.js"></script>
<script src="css/build/jquery.steps.js"></script>



<script>
    $(document).ready(function(){
      $container = $("#container").notify();  
        
    });
</script>

<script>
    function create(template, vars, opts) {
      return $container.notify("create", template, vars, opts);
    }
</script>




';
include("./includes/footer.php");
;echo '
<script>
        $("#example-tabs").steps({
            headerTag: "h4",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            enableFinishButton: false,
            enablePagination: false,
            enableAllSteps: true,
            titleTemplate: "#title#",
            cssClass: "tabcontrol"
        });

</script>


<script>
//   
    $(\'#SaveVitalsform\').submit(function () {
         var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });


    $(\'#MachineAccess\').submit(function () {
          var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });


    $(\'#Heparainform\').submit(function () {
         var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });



    $(\'#AccessOrdersform\').submit(function () {
          var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });

    $(\'#SaveNotesfrm\').submit(function () {
          var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });


    $(\'#SaveDataCollectionform\').submit(function () {
          var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });




    $(\'#Observation_Charts\').submit(function () {
         var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });

    $(\'#Medication_Charts\').submit(function () {
         var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });
    
    $(\'#DialysisOrdersform\').submit(function () {
         var st=\'\';
         var status = confirm("Are you sure you want to save this ?");
            if(status == false){
            return false;
            }else{
            $(this).ajaxSubmit({
            success: function (data) {

                st=data;
                create("default", {title: \'Success\', text: st});
                
            },
           
            error: function (data) {
                st=data;
                create("default", {title: \'Success\', text: st});
            }

            
        });
        

          return false;  
                
           
            }
    });
</script>
<script>
    $(document).ready(function () {
        $("#showdataConsult").dialog({autoOpen: false, width: \'90%\', title: \'SELECT  ITEM TO ORDER\', modal: true, position: \'middle\'});
    });
    function addItems(Registration_ID) {
        var url2 = \'order_type=external&Consultation_Type=Pharmacy\' + \'&Registration_ID=\' + Registration_ID + \'&consultation_ID='; echo  $consultation_id ;echo '\';
//var url2 = \'order_type=external&Consultation_Type=Pharmacy\' + \'&Registration_ID=\' + Registration_ID + \'&External_Payment_Cache_ID='; echo  $Payment_Cache_ID ;echo '&consultation_ID='; echo  $consultation_id ;echo '\';

        if (Registration_ID == null || Registration_ID == \'\') {
            alert(\'Select a patient to order items\');
        }
        $.ajax({
            type: \'GET\',
            url: \'doctoritemselectajax.php\',
            data: url2,
            cache: false,
            beforeSend: function (xhr) {
                $(\'#verifyprogress\').show();
                $(\'#myConsult\').html(\'\');
            },
            success: function (html) {
                $(\'#myConsult\').html(html);
                $("#showdataConsult").dialog("open");
            }, complete: function (jqXHR, textStatus) {
                $(\'#verifyprogress\').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $(\'#verifyprogress\').hide();
            }
        });
    }
</script>

<script>
    function consultChange(consultation_type) {
        var url2 = \'order_type=external&Consultation_Type=\' + consultation_type + \'&Registration_ID='; echo  $Registration_ID ;echo '&consultation_ID='; echo  $consultation_id ;echo '\';

        $.ajax({
            type: \'GET\',
            url: \'doctoritemselectajax.php\',
            data: url2,
            cache: false,
            success: function (html) {
                $(\'#myConsult\').html(html);
            }
        });
    }
</script>
<script>
    function doneDiagonosisselect() {
        $("#showdataConsult").dialog("close");
    }
</script>
<script>
    function preview(obj,date){
        var atendaceDate = date;
        url = "dialysispatient_review.php?Registration_ID="+obj+"&AtendaceDate="+atendaceDate;
        window.open(url,\'_blank\');
    }
</script>

';?>
