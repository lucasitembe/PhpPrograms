<?php
include("./includes/header.php");
include("./includes/connection.php");
require_once './includes/ehms.function.inc.php';
?>
<style>
    .servicesNameSel{
        background-color: white;
        color:black;
        display:block; 
    }
    .servicesNameSel:hover{
        cursor: pointer; 
    }
</style>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }	
</style> 

<?php
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

$nav='';
$divStyle='style="height: 130px;overflow-y: auto;overflow-x: hidden;background-color:white"';

if(isset($_GET['discharged'])){
   $nav='&discharged=discharged';
   $divStyle='style="height: 280px;overflow-y: auto;overflow-x: hidden;background-color:white"';
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>
        <?php
    }
}
?>

<a href="nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>" class="art-button-green" >BACK</a>
<?php
if (isset($_POST['submitservice'])) {
    $the_service_ID = $_POST['service_ID'];

    $Registration_ID = mysqli_real_escape_string($conn,$_POST['registration_ID']);
    $consultation_ID = mysqli_real_escape_string($conn,$_POST['consultation_ID']);
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

    $spID = '';
    //date_default_timezone_set('Africa/Nairobi');
    $given_time = date('Y-m-d H:i:s');
    if (isset($_POST['discontinue'])) {
        $discontinue = 'yes';
    } else {
        $discontinue = 'no';
    }
    $discontinue_reason = $_POST['discontinue_reason'];
    $remarks = $_POST['remarks'];

    if (empty($the_service_ID)) {
        echo '
                 <script>
                   alert(\'Select service to continue\');
                 </script>
                       ';
    } else {

        $insert_services_given = "
		INSERT INTO 
			tbl_inpatient_services_given(Service_ID, Time_Given, Nurse_Remarks, Employee_ID,consultation_ID, Registration_ID, Discontinue_Status, Discontinue_Reason) 
			VALUES('$the_service_ID', '$given_time', '$remarks', '$Employee_ID', '$consultation_ID', '$Registration_ID', '$discontinue', '$discontinue_reason')";

        $emp_ID = $Employee_ID;
        // echo  $insert_services_given;exit;
        $save_services_given = mysqli_query($conn,$insert_services_given) or die(mysqli_error($conn));
        $Given_Service_ID = '';
        if ($save_services_given) {
            $select_nurcy_services = "
                        SELECT Given_Service_ID FROM tbl_inpatient_services_given 
                                WHERE 
                                        Registration_ID = '$Registration_ID' AND 
                                        Employee_ID = '$Employee_ID' ORDER BY Given_Service_ID DESC LIMIT 1";
            $select_nurcy_services_qry = mysqli_query($conn,$select_nurcy_services) or die(mysqli_error($conn));
            $Given_Service_ID = mysqli_fetch_assoc($select_nurcy_services_qry)['Given_Service_ID'];
            bill($the_service_ID);
        }
    }
    ?>
    <script>
        alert("SAVED SUCCESSIFULLY");
        window.location = "InpatientNurseServices.php?<?php echo $_SERVER['QUERY_STRING'] ?>";
    </script>
    <?php
    //
}

function bill($Given_Service_ID) {
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

        if (strtolower($Sponsor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
            $Billing_Type = "Inpatient Cash";
        } else {
            $Billing_Type = "Inpatient Credit";
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

        if (strtolower($Sponsor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
            $Billing_Type = "Inpatient Cash";
        } else {
            $Billing_Type = "Inpatient Credit";
        }

        $has_no_folio = true;
    }

    $pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

   // if ($pre_paid == '0') {
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

            $queryName = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
            $Guarantor_Name = mysqli_fetch_assoc($queryName)['Guarantor_Name'];
            //get data from tbl_item_list_cache
            $Item_ID = $Given_Service_ID;
            $Discount = 0;
            $Price = getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name);
            $Quantity = 1;
            $Consultant = '';
            $Consultant_ID = $Employee_ID;


            //insert data to tbl_patient_payment_item_list
            if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
                $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time)
                                                        values('IPD Services','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','served','$Patient_Payment_ID',NOW())") or die(mysqli_error($conn));
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
//}

function getItemPrice($Item_ID, $Billing_Type, $Guarantor_Name) {
    $Item_ID = $Item_ID;
    $Billing_Type = $Billing_Type;
    $Guarantor_Name = $Guarantor_Name;

    $Price = 0;

    $Sponsor_ID = 0;

     $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
        
//    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    }

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

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = '';
}

$Dates = mysqli_query($conn,"SELECT NOW() as todaytime  ");
while ($row = mysqli_fetch_array($Dates)) {
    $todaytime = $row['todaytime'];
}
$Date_Of_Birth = $row['Date_Of_Birth'];


if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_ID = 0;
    }
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

include("./includes/Get_Patient_Check_In_Id.php");
include("./includes/Get_Patient_Transaction_Number.php");

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end

if ($Registration_ID != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT * 
			FROM 
				tbl_admission ad,
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				ad.Registration_ID = pr.Registration_ID AND 
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
            $DOB = $row['Date_Of_Birth'];

            //AGE FUNCTION
            $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
            // if($age == 0){

            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

//Billing type

$select = mysqli_query($conn,"select Billing_Type from tbl_patient_payments where Registration_ID = '" . $_GET['Registration_ID'] . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

if (mysqli_num_rows($select) > 0) {
    $rows = mysqli_fetch_array($select);
    $Billing_Type = $rows['Billing_Type'];
} else {
    if (strtolower($Sponsor) == 'cash') {
        $Billing_Type = "Inpatient Cash";
    } else {
        $Billing_Type = "Inpatient Credit";
    }
}

$timeNow=  mysqli_fetch_assoc(mysqli_query($conn,"SELECT TIME(NOW()) AS timeNow"))['timeNow'];
//End of billing type
?>

<center>
    <fieldset style="width:98%;margin-top:5px;">
        <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
                <b>NURSE SERVICES</b><br/>
                <?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"; ?></b>
        </legend>
 <?php if(empty($nav)){?>
        <form action='#' method='POST' name='myForm' id='myFormNurseSave' >
            <table width="100%">
                <tr>
                    <td colspan="2" width="100%"><hr></td>
                </tr>
                <tr>
                    <td width="40%">
                        <table border="0" width="100%">
                            <tr>
                                <td style="font-size:15px">
                                    <b>Select Service</b>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="searchSevices" onfocus="changeAlignment(this, this.value)" onblur="changeAlignmentBlur(this, this.value)" oninput="getservices(this,this.value)" style="padding:4px;width: 97%;text-align: center" placeholder="----------------Search service-----------------">
                                </td>  
                            </tr>
                        </table>    
                        <div id="get_nurse_services" style="width:100%;height:130px;overflow-x:hidden;overflow-y: scroll">  
                            <table border="0" width="100%">

                                <tr>
                                    <td>
                                        <table border="0" width="100%">
                                            <tr>
                                                <td>Action</td>
                                                <td>Service Name</td>
                                            </tr>
                                            <?php
                                            $select_services = "SELECT * FROM tbl_items WHERE Item_Type = 'Service'  order by Product_Name limit 100";

//  echo $select_services; exit;
                                            $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
                                            $discontinue_status = '';
                                            while ($items = mysqli_fetch_assoc($selected_services)) {
                                                $service_name = $items['Product_Name'];
                                                $service_ID = $items['Item_ID'];

                                                $select_service = "
                                                                    SELECT * FROM tbl_inpatient_services_given 
                                                                            WHERE Service_ID = '$service_ID' AND 
                                                                                  Registration_ID = '".$_GET['Registration_ID']."' AND
                                                                                  consultation_ID = '".$_GET['consultation_ID']."' AND
                                                                                  Discontinue_Status='yes'    
                                                                            ";
                                                $selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
                                                if (mysqli_num_rows($selected_service) == 0) {
                                                 echo "  <td><input type='radio' name='servieSel' onclick='Get_Last_Given_Time($service_ID)' class='supportedx' id='" . $service_ID . "' value='" . $service_ID . "'/></td>
                                                        <td style='color:red'><label for='" . $service_ID . "' class='servicesNameSel'>$service_name</label></td>
                                                           ";
                                                    echo '</tr>';
                                                }
                                            }
                                            ?>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>       
                    </td>
                    <td>
                        <!--Right panel-->
                        <br/>
                        <table>
                            <tr>
                                <td style="text-align:right;font-size:12px" width="13%" ><b>Last time given</b></td>
                                <td id="Last_Given_Time">
                                    <input size='50' type='text' value="" />
                                </td>
                                <td style="text-align:right;font-size:12px" width="13%"><b>Time Lapsed</b></td>
                                <td  id="Lapsed_Time">
                                    <input size="50" type='text'  value='' />
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;font-size:12px;" width="13%"><b>Time Now</b></td>
                                <td>
                                    <input type='text' name='given_time'  value='<?php echo $timeNow; ?>' />
                                    <input type='hidden'  id='nowTime' name='nowTime' />
                                </td>
                                <td style="text-align:right;font-size:12px" width="13%"><b>Remarks</b></td>
                                <td>
                                    <textarea name='remarks'   ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: center;' colspan="4">
                                    <br/>
                                    <input type='hidden' id="registration_ID" name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                                    <input type='hidden' name="consultation_ID" value='<?php echo $_GET['consultation_ID']; ?>'/>
                                    <b>Discontinue Service?</b> <input type='checkbox' name='discontinue'  id='discontinue' value="yes" onclick="RequireReason(this)" />&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="service_ID" id="service_ID" value="" />
                                    <input type="hidden" name="discontinue_reason" value="" id="discontinue_reason" value="" />
                                    <input type="hidden" name="discontinue_status" value="" id="discontinue_status" value="" />

                                    <input  type='submit' name='submitservice' value='SAVE' class='art-button-green' onclick="return confirm('Are you sure you want to save patient charges?')" />
                                    <input type='hidden' name='nursesubmitt' value='nursesubmitt' />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" width="100%"><br/><hr><br/></td>
                </tr>
            </table>
        </form>
 <?php }?>
    </fieldset>
    <br/>
    <fieldset>

        <div style="margin-bottom:4px" align="left">

            <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="start_date" placeholder="Start Date"/>
            <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="end_date" placeholder="End Date"/>&nbsp;
            <input type="button" value="Filter" style='text-align: center;width:15%;' class="art-button-green" onclick="filterPatient()">
            <a href="InpatientNurseServices_print.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>" id="printPreview" class="art-button-green" target="_blank" style="float:right">Preview</a>
            <input type="hidden" id="demo" value="">
        </div>

    </fieldset>
    <br/>
    <fieldset>
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
                        <td colspan="2" style="text-align:center"><button type="button" onclick="closeDialog()" class="art-button-green">Save and Close</button></td>
                    </tr>
                </table>
            </span>
        </div>
        <div id="Inpatient_Nurse_Medicine_Iframe"  <?php echo $divStyle ?>>
            <?php
            include 'InpatientNurseServicesIframe.php';
            ?>
        </div>
    </fieldset>

    <script language="javascript" type="text/javascript">
        function searchPatient() {
            ward_id = document.getElementById('nurseservice').value;
            document.getElementById().InnerHTML = "<iframe width='100%' height=320px src='nurseservice.php?ward_id=" + ward_id + "'></iframe>";
        }
    </script>
    <script>
        function filterPatient() {
            var start = document.getElementById('start_date').value;
            var end = document.getElementById('end_date').value;
            var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
            var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';

            if (start == '' || end == '') {
                alert("Please enter both dates");
                exit;
            }

            $('#printPreview').attr('href', 'InpatientNurseServices_print.php?start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID);


            document.getElementById('Inpatient_Nurse_Medicine_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


            $.ajax({
                type: "GET",
                url: "InpatientNurseServicesIframe.php",
                data: 'start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID,
                success: function (data) {
                    if (data != '') {
                        $('#Inpatient_Nurse_Medicine_Iframe').html(data);
                        $.fn.dataTableExt.sErrMode = 'throw';
                        $('#inpa_service').DataTable({
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
                document.getElementById('discontinue_status').value = 'no';
            }
            return true;
        }
    </script>
    <script type="text/javascript" language="javascript">
        function Get_Last_Given_Time(Service_ID) {
           supported(Service_ID);
           var demo=document.getElementById('demo').value; 
           if(demo==='yes'){
             if(window.confirm('This item is not supported by this sponsor,the patient must pay cash.Are you sure you want to continue?')){
//                return true;   
                }else{
                     window.location = "InpatientNurseServices.php?<?php echo $_SERVER['QUERY_STRING'] ?>";
                 }  
           }else{
               
               
           }
           
           var Registration_ID = <?php echo $Registration_ID; ?>;
           var consultation_ID = <?php echo $_GET['consultation_ID']; ?>;
            Get_Item_Price(Service_ID);



            document.getElementById("service_ID").value = Service_ID;

            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }


            mm.onreadystatechange = function () {
                var LastDate = mm.responseText;
                document.getElementById('Last_Given_Time').innerHTML = "<input size='50' type='text' disabled='disabled' value='" + LastDate + "' />";
                Get_Lapsed_Time(LastDate);
            };

            mm.open('GET', 'Service_Last_Given_Time.php?Service_ID=' + Service_ID + '&Reg_ID=' + Registration_ID+'&consultation_ID=' + consultation_ID, true);
            mm.send();
        }

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
                document.getElementById('Lapsed_Time').innerHTML = "<input size='50' type='text' disabled='disabled' value='" + Lapsed_Time + "' />";
            };

            lt.open('GET', 'Service_Lapsed_Time.php?LastTimeGiven=' + LastTimeGiven, true);
            lt.send();
        }


    </script>
    <script>
        function Get_Item_Price(Item_ID) {
           
           
//            alert('Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=<?php echo $Sponsor; ?>&Billing_Type=<?php echo $Billing_Type; ?>');
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function () {
                var data = myObject.responseText;
                if (myObject.readyState == 4) {
                    if (parseInt(data) == 0) {
                        alert('This Item has not been set its price.Please tell the person incharge to set its price before continuing.');
                        window.location = "InpatientNurseServices.php?<?php echo $_SERVER['QUERY_STRING'] ?>";
                    }

                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Guarantor_Name=<?php echo $Sponsor; ?>&Billing_Type=<?php echo $Billing_Type; ?>', true);
            myObject.send();
        }
        
        function supported(Item_ID){
            
          var registration_ID=document.getElementById('registration_ID').value;
          
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange=function(){
              if(xhttp.readyState==4&&xhttp.status==200){
                  
                 document.getElementById("demo").value = xhttp.responseText;  
              }
          }
          xhttp.open('GET', 'checkIfSupported.php?action&id=' +Item_ID+'&registration_ID=' + registration_ID, true);
          xhttp.send();
        
           
        }
    </script>
    <!-- SCRIPT OF GET CURRENT TIME-->
    <script type="text/javascript" language="javascript">
        var now;
        function getNow() {
            now = new Date();
        }
        function getTime(param) {
            if (window.XMLHttpRequest) {
                ajaxTimeObjt = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                ajaxTimeObjt.overrideMimeType('text/xml');
            }
            ajaxTimeObjt.onreadystatechange = function () {
                var data = ajaxTimeObjt.responseText;
                document.getElementById(param).value = data;
            }; //specify name of function that will handle server response....
            ajaxTimeObjt.open('GET', 'Get_Time.php', true);
            ajaxTimeObjt.send();
        }
    </script>
    <!--  END OF SCRIPT -->

    <script type="text/javascript" language="javascript">
        function userElapse(Item_ID) {

            //getTime('nowTime');
            getNow();
            counter = window.setInterval(function () {
                startTimeCount('nowTime')
            }, 1000);
        }
        function startTimeCount(param) {
            var a = new Date();
            var elapsed = (a - now) / 1000;
            document.getElementById("anID").value = convertTime(elapsed.toFixed(0));
        }

        function convertTime(myTime) {
            var newTime = 0;
            var mySec = myTime % 60;
            myTime = (myTime - mySec) / 60;

            var myMin = myTime % 60;
            myTime = (myTime - myMin) / 60;

            var myHrs = myTime % 24;
            myTime = (myTime - myHrs) / 24;

            newTime = myHrs + ':' + myMin + ':' + mySec;

            return newTime;
        }


    </script>

    <script src="css/jquery.datetimepicker.js"></script>
    <script>
        $('#start_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#start_date').datetimepicker({value: '', step: 30});

        $('#end_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#end_date').datetimepicker({value: '', step: 30});
    </script>

    <script>
        function getCurrent() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            window.clearInterval(counter);
            document.getElementById('t_medication').value = h + ":" + m + ":" + s;
        }
    </script>
    <script>
        function changeAlignment(instance, value) {
            if (value == '') {
                instance.placeholder = '';
                instance.style.textAlign = "left";
            }
        }
    </script>
    <script>
        function changeAlignmentBlur(instance, value) {
            if (value == '') {
                instance.placeholder = '----------------Search patient-----------------';
                instance.style.textAlign = "center";
            }
        }
    </script>
    <script>
        function getservices(instance, input) {
            if (window.XMLHttpRequest) {
                ajaxTimeObjt = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
                ajaxTimeObjt.overrideMimeType('text/xml');
            }
            ajaxTimeObjt.onreadystatechange = function () {
                var data = ajaxTimeObjt.responseText;
                //alert(data);
                document.getElementById('get_nurse_services').innerHTML = data;
            }; //specify name of function that will handle server response....
            ajaxTimeObjt.open('GET', 'inpatient_get_nurse_services.php?service_name=' + input + '&Registration_ID=<?php echo $_GET['Registration_ID'] ?>'+ '&consultation_ID=<?php echo $_GET['consultation_ID'] ?>' , true);
            ajaxTimeObjt.send();
        }
    </script>
    <script>
        $(document).ready(function () {
            $.fn.dataTableExt.sErrMode = 'throw';
            $('#inpa_service').DataTable({
                "bJQueryUI": true,
                "bFilter": false,
                "sPaginationType": "fully_numbers",
                "sDom": 't'

            });

            $("#Display_Discontinue_Details").dialog({autoOpen: false, width: '60%', title: 'DISCOUNTINUE DETAILS', modal: true});

            $('.ui-dialog-titlebar-close').click(function () {
                // Get_Transaction_List();
            });

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

    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <?php
    include("./includes/footer.php");
    ?>