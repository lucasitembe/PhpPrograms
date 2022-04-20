<?php
include("./includes/connection.php");
require_once  './includes/ehms.function.inc.php';
@session_start();
$Item_ID = 0;
//echo $_GET['payment_cache_ID'];

$is_external_order=false;

if(isset($_GET['order_type']) && $_GET['order_type']=='external'){
   $is_external_order=true;
}

if (isset($_GET['Consultation_Type'])) {
    $Consultation_Type = $_GET['Consultation_Type'];


    $LaboratorySelect = '';
    $ProcedureSelect = '';
    $SurgerySelect = "";
    $RadiologySelect = "";
    $OthersSelect = "";
    $PharmacySelect = "";
    $NuclearmedicineSelect="";// nuclear msk

    if ($Consultation_Type == 'Surgery') {
        $SurgerySelect = "selected";
    }
    if ($Consultation_Type == 'Laboratory') {
        $LaboratorySelect = "selected";
    }
    if ($Consultation_Type == 'Procedure') {
        $ProcedureSelect = "selected";
    }if ($Consultation_Type == 'Radiology') {
        $RadiologySelect = "selected";
    }
    if ($Consultation_Type == 'Others') {
        $OthersSelect = "selected";
    }
    // nuclear msk
    // if ($Consultation_Type == 'Nuclearmedicine') {
    //     $NuclearmedicineSelect = "selected";
    // }
    if ($Consultation_Type == 'Treatment') {
        $Consultation_Type = 'Pharmacy';
        $PharmacySelect = "selected";
    }

    $payment_cache_ID = 0;
    if (strtolower($Consultation_Type) == 'procedure') {
        //echo $Consultation_Type;exit;
        $Consultation_Condition = "((d.Department_Location='Dialysis') OR
        (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
        (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
        (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
        (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
        (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
        (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
        (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery')
        OR(d.Department_Location='Procedure'))";

        $Consultation_Condition2 = "((Consultation_Type='Dialysis') OR
        (Consultation_Type='Physiotherapy') OR (Consultation_Type='Optical')OR
        (Consultation_Type='Dressing')OR(Consultation_Type='Maternity')OR
        (Consultation_Type='Cecap')OR(Consultation_Type='Dental')OR(Consultation_Type='Ear') OR
        (Consultation_Type='Hiv') OR(Consultation_Type='Eye') OR(Consultation_Type='Maternity') OR
        (Consultation_Type='Rch') OR(Consultation_Type='Theater') OR
        (Consultation_Type='Family Planning')  OR (Consultation_Type='Procedure'))";

        //echo "SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2";exit;
    } else {
        $Consultation_Condition = "d.Department_Location = '$Consultation_Type'";
        $Consultation_Condition2 = "Consultation_Type='$Consultation_Type'";
    }
}

$req_op_prov_dign = $_SESSION['hospitalConsultaioninfo']['req_op_prov_dign'];

if (isset($_GET['consultation_ID'])) {
    $consultation_id = $_GET['consultation_ID'];
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}


if (isset($_GET['payment_cache_ID'])) {
    $payment_cache_ID = $_GET['payment_cache_ID'];
}else if (isset($_GET['External_Payment_Cache_ID']) && !empty ($_GET['External_Payment_Cache_ID'])) {
    $payment_cache_ID = $_GET['External_Payment_Cache_ID'];
} else {
    //die("sasd");
    $select_pcid = "SELECT payment_cache_ID FROM tbl_payment_cache WHERE consultation_id = '$consultation_id' AND  Check_In_ID='$Check_In_ID'";
    $Ppcid_result = mysqli_query($conn,$select_pcid) or die(mysqli_error($conn));

    if (@mysqli_num_rows($Ppcid_result) > 0) {
        $Ppcid_row = mysqli_fetch_assoc($Ppcid_result);
        $payment_cache_ID = $Ppcid_row['payment_cache_ID'];
        // echo $payment_cache_ID;
    }
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
?>

<!--START HERE-->

<?php
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
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and
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
        $years = $diff->y . " Years ";
        $months = $diff->m . ' Months ';
        $days = $diff->d . ' Days ';
        $hrs = $diff->h . ' Hours';
        if ($diff->y == 0) {
            $years = '';
        }
        if ($diff->m == 0) {
            $months = '';
        }
        if ($diff->d == 0) {
            $days = '';
        }
        if ($diff->h == 0) {
            $hrs = '';
        }
        $age = $years . $months . $days . $hrs;
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
?>

<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])) {
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
                        where pp.Patient_Payment_ID = " . $_GET['Patient_Payment_ID'] . "
                        and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    $Check_In_ID = $row['Check_In_ID'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
} else {
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    $Check_In_ID = '';
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
}
?>


<!--ON SUBMIT THIS EXECUTES-->
<?php
$inserted = TRUE;
if (isset($_POST['submitted'])) {
    if ($_POST['submitted'] == 0) {

        $Payment_Date_And_Time = '(SELECT NOW())';
        $Receipt_Date = Date('Y-m-d');
        $Transaction_status = 'pending';
        $Transaction_type = 'indirect cash';
        $Sponsor_Name = $Guarantor_Name;
        if ($_POST['bill_type'] == 'Cash') {
            $Billing_Type = 'Outpatient Cash';
        } else {
            $Billing_Type = 'Outpatient Credit';
        }

        $branch_id = $_SESSION['userinfo']['Branch_ID'];

        $insert_query = "INSERT INTO tbl_payment_cache(Registration_ID, Employee_ID, consultation_id,Check_In_ID, Payment_Date_And_Time,
        Folio_Number, Sponsor_ID, Sponsor_Name, Billing_Type, Receipt_Date, Transaction_status, Transaction_type, branch_id)
        VALUES ('$Registration_ID', '$Employee_ID', '$consultation_id','$Check_In_ID', '$Payment_Date_And_Time',
        '$Folio_Number', '$Sponsor_ID', '$Sponsor_Name', '$Billing_Type', '$Receipt_Date',
        '$Transaction_status', '$Transaction_type','$branch_id')";

        if (!mysqli_query($conn,$insert_query)) {
            die(mysqli_error($conn));
            $inserted = FALSE;
        }
        $payment_cache_ID = mysqli_insert_id($conn);
    }

    if ($inserted) {
        //Inserting Item List
        $Sponsor_Name = $Guarantor_Name;
        $Check_In_Type = $Consultation_Type;
        $Item_ID = $_GET['Item_ID'];
        $bill_type = $_POST['bill_type'];

        if ($bill_type == 'Cash') {
            $Price = "(SELECT Selling_Price_Cash FROM tbl_items WHERE Item_ID = $Item_ID )";
        } else {
            if (strtoupper($Sponsor_Name) == 'NHIF') {
                $Price = "(SELECT Selling_Price_NHIF FROM tbl_items WHERE Item_ID = $Item_ID )";
            } else {
                $Price = "(SELECT Selling_Price_Credit FROM tbl_items WHERE Item_ID = $Item_ID )";
            }
        }
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Quantity = $_POST['quantity'];
        $Patient_Direction = "others";
        $Consultant = $_SESSION['userinfo']['Employee_Name'];
        $Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
        $Status = 'active';
        $Transaction_Date_And_Time = '(SELECT NOW())';
        $Process_Status = 'inactive';
        $Doctor_Comment = $_POST['comments'];
        $Transaction_Type = $bill_type;
        $Service_Date_And_Time = $_POST['Service_Date_And_Time'];
        $Discount = $_POST['Discount'];

        $doctors_selected_clinic=$_SESSION['doctors_selected_clinic'];
        $finance_department_id=$_SESSION['finance_department_id'];
        $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
            Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,Service_Date_And_Time,Clinic_ID,finance_department_id)
            VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID',
            '$Status','$payment_cache_ID', $Transaction_Date_And_Time,
            '$Process_Status', '$Doctor_Comment','$Sub_Department_ID','$Transaction_Type','$Service_Date_And_Time','$doctors_selected_clinic','$finance_department_id')";

        if (mysqli_query($conn,$insert_query2)) {
            $url = "doctoritemselect.php?Consultation_Type=$Consultation_Type&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&consultation_id=$consultation_id&Registration_ID=$Registration_ID&payment_cache_ID=$payment_cache_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
            ?>
            <script type='text/javascript'>
                document.location = '<?php echo $url; ?>';</script>
            <?php
        } else {
            die(mysqli_error($conn));
        }
    } else {

    }
}
?>

<!-- new date function (Contain years, Months and days)-->
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->








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
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID




                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and
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
}
?>


<center>
    <table width='100%' style='background: #006400 !important;color: white;'>
        <tr>
            <td>
        <center>
            <b>DOCTORS WORKPAGE : <?php
                if (isset($_GET['Consultation_Type'])) {
                    echo strtoupper($Consultation_Type);
                }
                ?></b>
        </center>
        </td>
        </tr>
        <tr>
            <td>
        <center>
            <b><?php echo strtoupper($Patient_Name) . ', ' . strtoupper($Gender) . ', (' . $age . '), ' . strtoupper($Guarantor_Name); ?></b>
        </center>
        </td>
        </tr>
    </table>
</center>

<!-- @mfoy dn 07-03-2019 reason popup-->
<div id="reasonDiv" style="display:none;">
    <b></b>(<small> You can add new reason, if not found. </small>)<hr>
    <select name="reason" id="reason" class="form-control">
        <option value="0">Select reason</option>
        <?php
            $qry = mysqli_query($conn,"SELECT * FROM tbl_duration_control_reason");
            while ($reason=mysqli_fetch_assoc($qry)){ ?>
            <option value="<?= $reason['reason_id'] ?>"><?= $reason['reason'] ?></option>
            <?php } ?>
    </select>
    <input type="hidden" value="" id="mystatust">
    <input type="hidden" value="" id="itemid">
    <br><br>
    <input type="button"onclick="update_reason();" class="art-button-green" value="SUBMIT"><input type="button" class="art-button-green" input type="text" onclick="add_reason();" style="text-align: right;" value="ADD NEW REASON" id="add_reason">
</div>

<div id="addReasonDiv" style="display:none;">
    <b>Add new reason</b>(<small> added only if reason doesn't exist.</small>)<hr>
    <input type="text" name="new_reason" id="new_reason" class="form-control">
    <br><br>
    <input type="button" class="art-button-green" onclick="add_new_reason()" value="ADD">
</div>
<!-- end 07-03-2019 -->

<!--Global Item_ID-->
<script>
    var Item_ID;</script>


<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        //getPrice();
        var ItemListType = document.getElementById('Type').value;
        getItemListType(ItemListType);
        document.getElementById('Price').value = '';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemList.php?Item_Category_Name=' + Item_Category_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data1 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data1;
    }
</script>

<script type='text/javascript'>
    function getItemListType() {
        var Item_Category_Name = document.getElementById("Item_Category").value;
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        //alert(Item_Category_Name);
        //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Approval').disabled = 'disabled';
                document.getElementById('Approval_Comment').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Approval_Bill.php?Item_Category_Name=' + Item_Category_Name, true);
        myObject.send();
    }
</script>
<!-- end of filtering-->




<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        if (document.getElementById('direction').value == 'Direct To Doctor Via Nurse Station' || document.getElementById('direction').value == 'Direct To Doctor') {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?Type_Of_Check_In=' + Type_Of_Check_In + '&direction=doctor', true);
            mm.send();
        }
        else {
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?direction=clinic', true);
            mm.send();
        }
    }
    function AJAXP3() {
        var data3 = mm.responseText;
        document.getElementById('Consultant').innerHTML = data3;
    }
</script>


<!--##########################################################################################
Scripts from the doctorsitem selection
-->



<script type="text/javascript" language="javascript">

    function getLocationQueSize() {

        var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
        var Item_ID = parent.Item_ID;
        if (window.XMLHttpRequest) {
            mm5 = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
            mm5.overrideMimeType('text/xml');
        }
        mm5.onreadystatechange = AJAXP5; //specify name of function that will handle server response....
        mm5.open('GET', 'getLocationQueSize.php?Sub_Department_ID=' + Sub_Department_ID + '&Item_ID=' + Item_ID, true);
        mm5.send();
    }
    function AJAXP5() {
        var data5 = mm5.responseText;
        document.getElementById('QueuSize').value = data5;
    }

    //function to get the Item Queu Size
    function getItemQueuSize() {
        var Consultation_Type = "<?php echo $_GET['Consultation_Type'] ?>";
        if (Consultation_Type != 'Others') {
            var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
            var Item_ID = parent.Item_ID;
            if (window.XMLHttpRequest) {
                mm5 = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
                mm5.overrideMimeType('text/xml');
            }
            mm5.onreadystatechange = AJAXPQ; //specify name of function that will handle server response....
            mm5.open('GET', 'getItemQueuSize.php?Item_ID=' + Item_ID, true);
            mm5.send();
        }
    }
    function AJAXPQ() {
        var data5 = mm5.responseText;
        document.getElementById('QueuSize').value = data5;
    }

    //function to get the Item Balance
    function getItemBalance() {
        var Item_ID = parent.Item_ID;
        if (window.XMLHttpRequest) {
            mm5 = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
            mm5.overrideMimeType('text/xml');
        }
        mm5.onreadystatechange = AJAXPB; //specify name of function that will handle server response....
        mm5.open('GET', 'getItemBalance.php?Item_ID=' + Item_ID, true);
        mm5.send();
    }
    function AJAXPB() {
        var data5 = mm5.responseText;
        document.getElementById('QueuSize').value = data5;
    }










</script>


<script type='text/javascript'>

    function check_discount() {
        var Item_ID = parent.Item_ID;
        var Product_Name = Item_ID;
        var bill_type = document.getElementById('bill_type').value;
        var Billing_Type;
        if (Product_Name == '' || Product_Name == null) {
            alert("You must select an item to get it's price before discount.");
            document.getElementById("Discount").value = 0;
            document.getElementById("Discount").focus();
            return false;
        }
        if (bill_type == 'Credit') {
            alert("You cannot make discounts for credit patients.")
            document.getElementById("Discount").value = 0;
            document.getElementById("Discount").focus();
            return true;
        } else {
            Calculate_Amount();
        }
    }

</script>


<script type='text/javascript'>


    function getPrice() {

        var Item_ID = parent.Item_ID;
        var Product_Name = Item_ID;
        var bill_type = document.getElementById('bill_type').value;
        var Billing_Type;

        if ($('#Item_Name').val() != '' && bill_type == 'Cash') {
            if (!confirm(" The Bill type selected, patient will pay cash. Do you want to continue?")) {
                $('#bill_type').val('Credit');
                exit;
            }
        }

        if (Product_Name != '') {
            //alert('I am in');
            if (bill_type == 'Cash') {
                var Billing_Type = 'Outpatient Cash';
            } else if (bill_type == 'Credit') {
                var Billing_Type = 'Outpatient Credit';
            }

            var sponsor = '<?php echo $Guarantor_Name; ?>';
            var item_supported = $('#item_supported_' + Item_ID).val();//document.getElementById().value;
            if (item_supported == 'no' && (bill_type == '' || bill_type == 'Credit')) {
                alert('Item is not supported by ' + sponsor + ' Please select cash for billing type');
                $('#bill_type').css('border', '1px solid red');
                $('input:radio').prop('checked', false);
                $('#patientOrigialBillingType').val('Credit');
                parent.Item_ID = '';
                document.getElementById("Item_Name").value = '';
                document.getElementById("Item_ID").value = '';
                document.getElementById("Price").value = '';
                document.getElementById("Amount").value = '';
                return false;
            }

            //alert(Billing_Type);
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            if (window.XMLHttpRequest) {
                mm2 = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm2 = new ActiveXObject('Micrsoft.XMLHTTP');
                mm2.overrideMimeType('text/xml');
            }
            mm2.onreadystatechange = AJAXP4; //specify name of function that will handle server response....
            mm2.open('GET', 'Get_Items_Price.php?Item_ID=' + Product_Name + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name, true);
            mm2.send();
        }

        function AJAXP4() {
            if (mm2.readyState == 4) {
                var data4 = mm2.responseText;
                document.getElementById('Price').value = data4;
                Calculate_Amount();
            }
        }

    }

</script>


<script type='text/javascript'>

    //function to calculate the amount

    function addCommas(nStr) {
          nStr += '';
          x = nStr.split('.');
          x1 = x[0];
          x2 = x.length > 1 ? '.' + x[1] : '';
          var rgx = /(\d+)(\d{3})/;
          while (rgx.test(x1)) {
              x1 = x1.replace(rgx, '$1' + ',' + '$2');
          }
          return x1 + x2;
      }
      
    function Calculate_Amount() {
        var price = document.getElementById('Price').value;
        var Discount = document.getElementById("Discount").value;
        price = price.replace(',', '')
        var quantity = parseInt(document.getElementById('Quantity').value);
        if (isNaN(price)) {
            price = 0;
        }
        if (isNaN(quantity)) {
            quantity = 1;
        }
        quantity == parseInt(quantity);
        var ammount = 0;
        //ammount = price * quantity;
//        if (isNaN(ammount)) {
//            ammount = 0;
//        }

        if (isNaN(Discount)) {
            Discount = 0;
        }

        var Discount = document.getElementById("Discount").value;
        var NetAmount = (price - Discount) * quantity;
        document.getElementById('Amount').value = addCommas(NetAmount);
        //change the border color
        document.getElementById("Quantity").style.borderColor = "";
        //alert(ammount)
    }
</script>


<script type='text/javascript'>
    //the function to select the items
    function searchItemList() {
        Item_Category_ID = document.getElementById('Item_Category_ID').value;
        Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
        test_name = document.getElementById('test_name').value;
        document.getElementById('doctordetaileddiagnosisselect_Iframe').src = './doctordetaileddiagnosisselect_Iframe.php?disease_name=' + disease_name + '&disease_category_ID=' + disease_category_ID + '&subcategory_ID=' + subcategory_ID + '&consultation_id=<?php echo $consultation_id; ?>&Consultation_Type=<?php echo $Consultation_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
    }


</script>





<script type='text/javascript'>
    function changeFineSearch() {
        var Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
        var Item_Category_ID = document.getElementById('Item_Category_ID').value;
        document.location = "doctordetaileditemselect.php?Consultation_Type=<?php
                echo $Consultation_Type;
                ?>&consultation_id=<?php
                echo $consultation_id;
                ?>&Registration_ID=<?php
                echo $Registration_ID;
                ?>&Patient_Payment_Item_List_ID=<?php
                echo $Patient_Payment_Item_List_ID;
                ?>&Patient_Payment_ID=<?php
                echo $Patient_Payment_ID;
                ?>&Item_Category_ID=" + Item_Category_ID + "&Item_Subcategory_ID=" + Item_Subcategory_ID + "&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
    }
</script>

<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getSubcategory(Item_Category_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemSubcategory.php?Item_Category_ID=' + Item_Category_ID, true);
        mm.send();
    }
    function AJAXP2() {
        var data1 = mm.responseText;
        document.getElementById('Item_Subcategory_ID').innerHTML = data1;
    }

    //function to search items
    function searchItem() {
        Item_Category_ID = document.getElementById('Item_Category_ID').value;
        Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
        test_name = document.getElementById('test_name').value;
        var Sponsor_ID=$("#new_sponsor_to_bill").val();
        $.ajax({
            type: 'GET',
            url: 'doctordetaileditemselect_Iframe.php',
            data: "sponsor_id=" + Sponsor_ID + "&test_name=" + test_name + "&Item_Subcategory_ID=" + Item_Subcategory_ID + "&Item_Category_ID=" + Item_Category_ID + "&Consultation_Type=<?php echo $Consultation_Type; ?>&Patient_Payment_ID=<?php
                echo $Patient_Payment_ID;
                ?>&consultation_id=<?php echo $consultation_id; ?>&Consultation_Type=<?php echo $Consultation_Type; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage",
            cache: false,
            success: function (html) {
                document.getElementById('frmaesearch').innerHTML = html;
            }
        });
    }

</script>
<input type="text" id='relevant_clinical_comment_store' hidden="hidden">
<script>
    function update_reason(){
    // start @mfoy dn
    var reason_id = $("#reason option:selected").val();
    var reason = $("#reason option:selected").text();
    var itemid =$("#itemid").val();
    var mystatust =$("#mystatust").val();

    // alert('mystatust='+mystatust+'itemid='+itemid);exit;

    if(reason_id==''||reason_id=='null'||reason_id==null||reason==''||reason=='null'||reason==null){
        alert('Reason must be selected.');
    }else{
        $('#reasonDiv').dialog("close");
        multiple_add_items(itemid,mystatust);
    }
}
    function add_reason(){
         // start @mfoy dn
         $('#addReasonDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'ADD NEW REASON'
        });
        // end @07/03/3019
    }

    function add_new_reason(){
         // start @mfoy dn
         var new_reason = document.getElementById('new_reason').value;
         new_reason = new_reason.replace(/ +(?= )/g,'');//remove multple spaces

        // console.log('rerere= '+new_reason);
        if(new_reason==''||new_reason==' '||new_reason==null||new_reason==undefined){
            alert("Reason can't be empty.");
        }else{
        $.ajax({
            type: 'POST',
            url: 'ajax_add_new_duration_control_reason.php',
            data: {reason:new_reason},
            success: function (result) {
                $("#reason").html(result);
                $('#addReasonDiv').dialog("close");
            }
        });
        }
    }

    function alert_items_control(Item_ID,mystatust){
        //START added on 18/02/2018 @Mfoy dn
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
//         alert('mystatust='+mystatust+' Item_ID='+Item_ID);exit;
        var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
            if (Sub_Department_ID == '' || Sub_Department_ID == null) {
                alert('Select Item Location');
                document.getElementById("Sub_Department_ID").focus();
                document.getElementById("Sub_Department_ID").style.borderColor = 'red';
                $("#"+Item_ID).prop("checked",false);
                exit;
            }
             var relevant_clinical_comment_store=$("#relevant_clinical_comment_store").val();
            if(relevant_clinical_comment_store==""||relevant_clinical_comment_store==null){
                 relevant_clinical_comment_store=$("#comments").val();
                 $("#relevant_clinical_comment_store").val(relevant_clinical_comment_store)
            }else{
                relevant_clinical_comment_store=$("#relevant_clinical_comment_store").val();
                 $("#comments").val(relevant_clinical_comment_store)
            }

            comments = document.getElementById('comments').value;
             if (comments == '' || comments == null) {
                alert("You must specify the relevant clinical notes for the items that you are going select");
                document.getElementById("comments").focus();
                document.getElementById("comments").style.borderColor = 'red';
                $("#"+Item_ID).prop("checked",false);
                exit;
            }

        $.ajax({
            type: 'POST',
            url: 'ajax_item_check_alert_control.php',
            data: {Sponsor_ID:Sponsor_ID,Item_ID:Item_ID,Registration_ID:Registration_ID},
            success: function (result) {
                var myJSONObj = JSON.parse(result);
                var code = myJSONObj.code;
                if(code=="200"){
                    var alert = '       Duration Control Alert!\n\nOnly '+myJSONObj.pased_days+' day(s) passed since the last time this item was taken on '+myJSONObj.past_date+'.\nMinimum days required is/are '+myJSONObj.min_days+' day(s). Would you like to continue anyway?\n';
                    if(confirm(alert)){
                        // start @07/03/3019
                        $('#reasonDiv').dialog({
                            modal: true,
                            width: '30%',
                            resizable: true,
                            draggable: true,
                            title: 'SELECT REASON'
                        });
                        // end @07/03/3019

                        $('#itemid').val(Item_ID);
                        $('#mystatust').val(mystatust);

                        // multiple_add_items(Item_ID,mystatust);
                    }else{
                        exit;//do not continue
                    }
                }else{
                    multiple_add_items(Item_ID,mystatust);
                }
            }
        });
        //END added on 18/02/2018 @Mfoy dn

        }

    function multiple_add_items(Item_ID,mystatust){
         var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
            if (Sub_Department_ID == '' || Sub_Department_ID == null) {
                alert('Select Item Location');
                document.getElementById("Sub_Department_ID").focus();
                document.getElementById("Sub_Department_ID").style.borderColor = 'red';
                $("#"+Item_ID).prop("checked",false);
                exit;
            }
            var specialist_order='Any';
            var relevant_clinical_comment_store=$("#relevant_clinical_comment_store").val();
            if(relevant_clinical_comment_store==""||relevant_clinical_comment_store==null){
                 relevant_clinical_comment_store=$("#comments").val();
                 $("#relevant_clinical_comment_store").val(relevant_clinical_comment_store)
            }else{
                relevant_clinical_comment_store=$("#relevant_clinical_comment_store").val();
                 $("#comments").val(relevant_clinical_comment_store)
            }

            comments = document.getElementById('comments').value;
             if (comments == '' || comments == null) {
                alert("You must specify the relevant clinical notes for the items that you are going select");
                document.getElementById("comments").focus();
                document.getElementById("comments").style.borderColor = 'red';
                $("#"+Item_ID).prop("checked",false);
                exit;
            }
        if(check_provisional_diagnosis(Item_ID,mystatust, specialist_order)){
          // $("#comments").val("   ");
          
            if(specialist_order=='No'){
                alert("This service can be ordered by Specialist/ Super Specialist Only. Consult specialist/Super Specialist please!!..");
                exit;
            }
          relevant_clinical_comment_store=$("#relevant_clinical_comment_store").val();
          $("#comments").val(relevant_clinical_comment_store);
          setTimeout(function(){ sendOrRemoveItem(); }, 200);
          //sendOrRemoveItem();

        }

    }
</script>
<script>
    function searchItemAfterAdd() {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        var Sponsor_ID=$("#new_sponsor_to_bill").val();
        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', "doctordetaileditemselect_Iframe.php?sponsor_id=" + Sponsor_ID + "&Consultation_Type=<?php echo $Consultation_Type; ?>&Patient_Payment_ID=<?php
                echo $Patient_Payment_ID;
                ?>&consultation_id=<?php echo $consultation_id; ?>&Consultation_Type=<?php echo $Consultation_Type; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID; ?>", true);
        mm.send();
        function AJAXP2() {
            var data1 = mm.responseText;
            document.getElementById('frmaesearch').innerHTML = data1;
        }
    }
</script><?php // echo $Sponsor_ID; ?>
<!--##########################################################################################-->




<!-- end of selection-->



<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}

//echo 'payment_cache_ID ='.$payment_cache_ID;exit;
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->



<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->

    <fieldset>
        <center>
            <form method='post' action=''>
                <table style='width: 100%;' class="patinfo">
                    <td style="width:50%">
                        <table>
                            <tr style='display: none'>
                                <td style="text-align: right;">Bill Type</td>
                                <td>
                                    <select id='bill_type' name='bill_type' onchange='getPrice()' required='required' style='width: 400px;text-align: left;'>

                                        <?php
                                        if (strtolower($Guarantor_Name) == "cash"   || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
                                            echo "<option selected='selected'>Cash</option>

                    ";
                                        } else {
                                            echo "<option selected='selected'>Credit</option>
                            <option>Cash</option>
                      ";
                                        }
                                        ?>

                                    </select>
                                    <input type="hidden" id="patientOrigialBillingType" value="">
                                </td>
                            </tr>
                            <tr>
                                <td style="width:200px"><b style="color:green"><span style="color:red">*</span>Sponsor Type</b></td>
                                <td>
                                    <select id="new_sponsor_to_bill"  style='width: 400px;text-align: left;' onclick="searchItem(this.value)">
                                        <?php

                                         ?>
                                        <option value="<?= $Sponsor_ID ?>"><?= $Guarantor_Name ?></option>
                                        <?php
                                            if(strtolower(getPaymentMethod($Sponsor_ID))=='credit'){
                                                    $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor WHERE payment_method='cash'") or die(mysqli_error($conn));
                                                    if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                                       while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                                          $Sponsor_ID_ch=$sponsor_rows['Sponsor_ID'];
                                                          $Guarantor_Name_ch=$sponsor_rows['Guarantor_Name'];

                                                          echo "<option value='$Sponsor_ID_ch'>$Guarantor_Name_ch</option>";
                                                       }
                                                    }
                                               }
                                            ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Category</td>
                                <td>
                                    <select id='Item_Category_ID' name='Item_Category_ID' onchange='getSubcategory(this.value)' required='required' style='width: 100%'>
                                        <option>All</option>
                                        <?php
                                        $qr = '';

                                        if ($Consultation_Type == 'Pharmacy') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Item_Type='Pharmacy' group by ic.Item_Category_ID LIMIT 200";
                                        }
                        if ($Consultation_Type == 'Radiology') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Item_Type='Radiology' group by ic.Item_Category_ID LIMIT 200";
                                        }

                                        if ($Consultation_Type == 'Laboratory') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Consultation_Type='Laboratory' group by ic.Item_Category_ID";
                                        }
                                        if ($Consultation_Type == 'Surgery') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Consultation_Type='Surgery' group by ic.Item_Category_ID";
                                        }
                                        if ($Consultation_Type == 'Nuclearmedicine') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Consultation_Type='Nuclearmedicine' group by ic.Item_Category_ID";
                                        } elseif ($Consultation_Type == 'Procedure') {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
                        WHERE i.Consultation_Type='Procedure' group by ic.Item_Category_ID";
                                        } else {
                                            $qr = "SELECT * FROM tbl_item_category as ic
                        join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
                        WHERE iss.Item_Subcategory_ID IN
                        (SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
                        group by ic.Item_Category_ID
                        ";

                                            //echo $qr;exit;
                                        }
                                        $result = mysqli_query($conn,$qr);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <?php if (strtolower($row['Item_Category_Name']) == 'laboratory') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>">
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                                <?php
                                            }
                                            if (strtolower($row['Item_Category_Name']) == 'imaging') {
                                                ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php }if (strtolower($row['Item_Category_Name']) == 'MINOR SURGERY SERVICES') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php }if (strtolower($row['Item_Category_Name']) == 'procedures') { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="<?php echo $row['Item_Category_ID'] ?>" >
                                                    <?php echo $row['Item_Category_Name'] ?>
                                                </option>
                                            <?php } ?>
                                        <?php }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Subcategory</td>
                                <td>
                                    <select id='Item_Subcategory_ID' name='Item_Subcategory_ID' onchange='searchItem(this.value)' onclick='getPrice()' required='required' style='width: 100%'>
                                        <option>All</option>
                                        <?php
                                        if ($Consultation_Type == 'Pharmacy') {
                                            $qr = "SELECT * FROM tbl_item_subcategory as iss
                    WHERE iss.Item_Subcategory_ID IN
                    (SELECT Item_Subcategory_ID FROM tbl_items where Item_Type='Pharmacy')
                    group by iss.Item_Subcategory_ID";
                                        } else {
                                            $qr = "SELECT * FROM tbl_item_subcategory as iss
                    WHERE iss.Item_Subcategory_ID IN
                    (SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
                    group by iss.Item_Subcategory_ID";
                                        }



                                        $result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <option value='<?php echo $row['Item_Subcategory_ID'] ?>'>
                                                <?php echo $row['Item_Subcategory_Name'] ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Item Name</td>
                                <td>
                                    <input type='text' oninput='searchItem()'  placeholder='-----Search an item-----' id='test_name' name='test_name'>

                                </td>
                            </tr>

                        </table>
                    </td>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td colspan="6">
                                    <select onchange="consultChange(this.value)" id="consType" style="padding:5px;margin:5px;font-size:18px;font-weight:100;width:100%;text-align: center  ">
                                        <option <?php echo $PharmacySelect; ?>>Pharmacy</option>
                                        <option <?php echo $LaboratorySelect; ?>>Laboratory</option>
                                        <option <?php echo $RadiologySelect; ?>>Radiology</option>
                                        <option <?php echo $SurgerySelect; ?>>Surgery</option>
                                        <option <?php echo $ProcedureSelect; ?>>Procedure</option>
                                        <!-- <option <?php echo $NuclearmedicineSelect; ?> value="Nuclearmedicine">Nuclear Medicine</option> -->
                                        <option <?php echo $OthersSelect; ?>>Others</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;width:20%;">Service Date</td>
                                <?php if (strtolower($Consultation_Type) == 'surgery') { ?>
                                    <td style=''>
                                        <input type='text' id='date' name='Service_Date_And_Time'>
                                    </td>

                                    <td style="text-align:right;">Time</td>
                                    <td style=''>
                                        <select name="service_hour" id="service_hour" required>
                                            <option></option>
                                            <option>Morning</option>
                                            <option>Evening</option>
                                        </select>
                                    </td>
                                    <!-- <td style="text-align:right;">Min</td>
                                    <td style=''>
                                        <input type='text' style="width:100%;" id='service_min' name='service_min'>
                                    </td> -->
                                    <?php
                                } else {
                                    ?>
                                    <td style=''>
                                        <input type='text' id='date' name='Service_Date_And_Time' value="<?php echo date('Y-m-d H:m:s') ?>">
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><?php
                                    if ($Consultation_Type == 'Pharmacy') {
                                        if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                                            echo 'Pat. Presc';
                                        } else {
                                            echo 'Dosage';
                                        }
                                    } else if($Consultation_Type == 'Procedure'){
                                        echo "Relevant Clinical Data";
                                    }else{
                                        echo 'Relevant Clinical Notes';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($Consultation_Type == 'Pharmacy') {
                                        if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
                                            echo "Route:<select id='route' name='route' class='patprec'>";
                                            echo '<option></option><option>PO</option><option>IM</option><option>IV</option><option>SC</option></select>';

                                            echo "Frequency:<select id='frequency' name='frequency' class='patprec'>";
                                            echo '<option></option><option>OD</option><option>BD</option><option>TDS</option><option>QID</option><option>STAT</option></select>';

                                            echo "Duration:<select id='duration' name='duration' class='patprec'>";
                                            echo '<option></option><option>1/7</option><option>2/7</option><option>3/7</option><option>4/7</option><option>5/7</option><option>6/7</option><option>7/7</option><option>1/52</option><option>2/52</option><option>3/52</option><option>4/52</option><option>5/52</option><option>1/12</option><option>2/12</option><option>3/12</option><option>4/12</option></select><br/>';
                                            echo 'Dosage: <input type="text" style="width:80%" id="dosage" class="patprec"/>';

                                            echo '<input type="hidden" style="width:80%" id="comments" value=""/>';
                                        } else {
                                            echo "<textarea id='comments' name='comments' required='required' rows='2' cols='100' ></textarea>";
                                        }
                                    } else {
                                        echo "<textarea id='comments' name='comments' required='required' rows='2' cols='100' ></textarea>";
                                    }
                                    ?>

                                </td>

                                <?php if (strtolower($Consultation_Type) == 'laboratory' || strtolower($Consultation_Type) == 'surgery'|| strtolower($Consultation_Type) == 'procedure') { ?>
                                    <td>PRIORITY</td>
                                    <td>
                                        <select name="Priority" id="Priority" required="required">
                                            <option>Routine</option>
                                            <option>Emergency</option>            

                                            <!-- <option>Normal</option>
                                            <option>Urgent</option>
                                            <option>Low</option> -->
                                        </select>
                                    </td>
                                    <?php
                                }

                                if (strtolower($Consultation_Type) == 'procedure' || strtolower($Consultation_Type) == 'surgery') {
                                    ?>
                                    <td style="width:20%;text-align: right ">PERFORMED BY</td>
                                    <td>
                                        <select name="procedurelocation" id="procedurelocation" required="required">
                                            <option value="" selected="selected">Select</option>
                                            <option>Others</option>
                                            <option>Me</option>
                                        </select>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </td>
                </table>
            </form>
        </center>
        <center>
            <table width='100%' class="dataItem">
                <tr>
                    <td width='35%'  >

                        <script type='text/javascript'>
                            function getItemsList(Item_Category_ID) {
                                document.getElementById("Search_Value").value = '';
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID, true);
                                myObject.send();
                            }

                            function getItemsListFiltered(Item_Name) {
                                document.getElementById("Price").value = '';
                                document.getElementById("Item_Name").value = '';
                                document.getElementById("Quantity").value = '';
                                var Item_Category_ID = document.getElementById("Item_Category_ID").value;
                                if (Item_Category_ID == '' || Item_Category_ID == null) {
                                    Item_Category_ID = 'All';
                                }

                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                //alert(data);

                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        //document.getElementById('Approval').disabled = 'disabled';
                                        document.getElementById('Items_Fieldset').innerHTML = data;
                                    }
                                }; //specify name of function that will handle server response........
                                myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
                                myObject.send();
                            }


                        </script>
                        <!--HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH-->
                        <!--This script adds item to tbl_item_list_cache -->

                        <script type='text/javascript'>
                            function sendOrRemoveItem() {

                                //
                                // ya kupeleka reason na reason_id ya kuoder item before time kwenye tbl_item_list_cache
                                // start @mfoy dn
                                //

                                var reason_check = $("#reason option:selected").text();
                                var reason_id_check = $("#reason option:selected").val();
                                if(reason_id_check!=0){
                                    var reason_id = reason_id_check;
                                    var reason = reason_check;
                                }else{
                                    var reason_id ="";
                                    var reason = "";
//                                    alert("Select Reason");
//                                    $('#reasonDiv').dialog({
//                                        modal: true,
//                                        width: '30%',
//                                        resizable: true,
//                                        draggable: true,
//                                        title: 'SELECT REASON'
//                                    });
//                                    exit;
                                }

                                // end @mfoy dn


                                var Item_Name = document.getElementById("Item_Name").value;
                                var Consultation_Type = "<?php echo $_GET['Consultation_Type'] ?>";
                                if (Item_Name == '' || Item_Name == null) {
                                    alert("Please,you must select an item.");
                                    document.getElementById("Item_Name").focus();
                                    document.getElementById("Item_Name").style.borderColor = 'red';
                                    return false;
                                } else {

                                    //if (Consultation_Type != 'Others') {
                                        var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
                                        if (Sub_Department_ID == '' || Sub_Department_ID == null) {
                                            alert('Select Item Location');
                                            document.getElementById("Sub_Department_ID").focus();
                                            document.getElementById("Sub_Department_ID").style.borderColor = 'red';
                                            exit;
                                        }
                                    //}

                                    var bill_type = document.getElementById("bill_type").value;
                                    var action = "ADD";
                                    var Registration_ID = "<?php echo $_GET['Registration_ID']; ?>";
                                    var Patient_Payment_ID = "<?php echo (isset($_GET['Patient_Payment_ID'])?$_GET['Patient_Payment_ID']:''); ?>";
                                    // var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

                                    if ((Consultation_Type.toLowerCase() == 'procedure') || (Consultation_Type.toLowerCase() == 'surgery' && document.getElementById("procedurelocation").value == '')) {
                                        if (Consultation_Type.toLowerCase() == 'procedure') {
                                            var Priority_for_procedure=  $("#Priority").val();
                                           // alert(Priority_for_procedure);
                                            if(document.getElementById("procedurelocation").value == ''){
                                                 alert('Select who performs the procedure');
                                               document.getElementById("procedurelocation").focus();
                                               document.getElementById("procedurelocation").style.borderColor = 'red';
                                               exit;
                                            }
                                            if(Priority_for_procedure=""){
                                                alert("SELECT PRIORITY");
                                            }

                                        } else {
                                            alert('Select who performs the surgery');
                                            document.getElementById("procedurelocation").focus();
                                            document.getElementById("procedurelocation").style.borderColor = 'red';
                                            exit;
                                        }

                                    }
                                    var Procedure_Location =<?php if ((strtolower($Consultation_Type) == 'procedure')) { ?>document.getElementById("procedurelocation").value;
    <?php
} else {
    echo '0;'
    ?>;
<?php } ?>

                                    var balance =<?php if ((strtolower($Consultation_Type) == 'pharmacy')) { ?>document.getElementById("Balance").value;
    <?php
} else {
    echo '\'nil;\''
    ?>;
<?php } ?>

                                    if (Procedure_Location == '0') {
                                        Procedure_Location = '';
                                        //alert(Procedure_Location);
                                    }



                                    var quantity = document.getElementById('Quantity').value;


                                    if (quantity == '' || quantity == null) {
                                        quantity = 1;
                                    }

                                    if (parseInt(quantity) < 1 && Consultation_Type.toLowerCase() != 'pharmacy') {
                                        document.getElementById('Quantity').value = 1;
                                        quantity = 1;
                                    }

                                    var price = document.getElementById('Price').value;
                                    var comments = document.getElementById('comments').value;
                                    var Priority =<?php if ((strtolower($Consultation_Type) == 'laboratory') || (strtolower($Consultation_Type) == 'surgery')||(strtolower($Consultation_Type) == 'procedure')) { ?> document.getElementById("Priority").value;
    <?php
} else {
    echo '0;'
    ?>;
<?php } ?>

                                    if (Priority == '0') {
                                        Priority = '';
                                        //alert(Procedure_Location);
                                    }

                                    if (balance != 'nil') {
                                        if (parseInt(balance) < 1) {
                                            if(!confirm('The item is not availlable in the pharmacy chosen. Do you want to continue?')){
                                                exit;
                                            }
                                        }
                                    }

                                    if ($('#item_allow_zero_' + Item_ID).val() != 'yes') {
                                        if (parseFloat(price) > 0) {

                                        } else {
                                            alert('This Item has not been set its price.Please tell the person incharge to set its price before continuing.');
                                            exit;
                                        }
                                    }

<?php
if ($Consultation_Type == 'Pharmacy') {
    if ($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') {
        ?>
                                            var route = document.getElementById('route').value;
                                            var frequency = document.getElementById('frequency').value;
                                            var duration = document.getElementById('duration').value;
                                            var dosage = document.getElementById('dosage').value;
                                            var is_error = false;
                                            $('.patprec').each(function () {
                                                var myPrecVal = $(this).val();
                                                if (myPrecVal == '') {
                                                    $(this).css('border', '1px solid red');
                                                    is_error = true;
                                                } else {
                                                    $(this).attr('style', '');
                                                }
                                            });
                                            if (is_error) {
                                                alert("The reded field are required.");
                                                exit;
                                            } else {
                                                comments = document.getElementById('comments').value = 'Route:' + route + '; Frequency:' + frequency + '; Duration:' + duration + '; Dosage:' + dosage;
                                            }

                                            // alert(route+' '+duration+' '+frequency+' '+dosage);
        <?php
    }
}
?>

                                    if (Consultation_Type.toLowerCase() == 'pharmacy') {
                                        if (comments == '' || comments == null) {
                                            alert("You must specify the dosage for this item.");
                                            document.getElementById("comments").focus();
                                            document.getElementById("comments").style.borderColor = 'red';
                                            return false;
                                        }
                                    }else{
                                        if (comments == '' || comments == null) {
                                            alert("You must specify the relevant clinical notes for this item");
                                            document.getElementById("comments").focus();
                                            document.getElementById("comments").style.borderColor = 'red';
                                            return false;
                                        }
                                    }

                                    var Service_Date_And_Time = document.getElementById('date').value;
                                    var service_hour = '';
                                    var service_min = '';
                                    var Discount = document.getElementById('Discount').value;
                                   if (Consultation_Type.toLowerCase() == 'surgery') {
                                       service_hour = document.getElementById('service_hour').value;
                                    //    service_min = document.getElementById('service_min').value;
                                       if (Service_Date_And_Time == '' || Service_Date_And_Time == null) {
                                           alert("You must specify surgery date.");
                                           document.getElementById("date").focus();
                                           document.getElementById("date").style.borderColor = 'red';
                                           return false;
                                       } else if (service_hour == '' || service_hour == null) {
                                        //    if (confirm('You are about to add surgery without specifying time it will took. Are you sure you want to continue?')) {

                                        //    } else {
                                        //        return false;
                                        //    }
                                            alert("You must specify surgery time.");
                                           document.getElementById("service_hour").focus();
                                           document.getElementById("service_hour").style.borderColor = 'red';
                                           return false;
                                       }
                                   }

                                    if (Item_ID != '') {
                                        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                        if (window.XMLHttpRequest) {
                                            myObject = new XMLHttpRequest();
                                        }
                                        else if (window.ActiveXObject) {
                                            myObject = new ActiveXObject('Microsoft.XMLHTTP');
                                            myObject.overrideMimeType('text/xml');
                                        }



                                        if (bill_type != '' && Sub_Department_ID != '') {
                                            var Sponsor_ID=$("#new_sponsor_to_bill").val();
                                            myObject.onreadystatechange = sendOrRemove_AJAX; //                                                     {----------------- @Mfoy dn ----------------}
                                            myObject.open('GET', 'sendOrRemove.php?order_type=<?php echo $is_external_order?>&action=' + action + '&reason=' + reason + '&reason_id=' + reason_id + '&quantity=' + quantity + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Consultation_Type=<?php echo $_GET['Consultation_Type']; ?>&Sub_Department_ID=' + Sub_Department_ID + '&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID='+Sponsor_ID+'&Item_ID=' + Item_ID + '&bill_type=' + bill_type + '&Guarantor_Name=' + Guarantor_Name + '&Registration_ID=' + Registration_ID + '&comments=' + comments + '&Discount=' + Discount + '&Priority=' + Priority + '&Service_Date_And_Time=' + Service_Date_And_Time + '&service_hour=' + service_hour + '&service_min=' + service_min + '&Procedure_Location=' + Procedure_Location+'&External_Payment_Cache_ID=<?= isset($_GET['External_Payment_Cache_ID'])?$_GET['External_Payment_Cache_ID']:''; ?>', true);
                                            myObject.send();
                                            document.getElementById("Item_Name").style.borderColor = '';
                                            document.getElementById("comments").style.borderColor = '';

                                            // @Mfoy dn
                                            $("#reason option:selected").prop("selected", false);

                                        } else {
                                            var message = "choose";
                                            if (bill_type == '') {
                                                check_ID.checked = false;
                                                message = message + ' bill type';
                                            }
                                            if (Sub_Department_ID == '') {
                                                check_ID.checked = false;
                                                message = message + ' ,location';
                                            }
                                            message = message + " first";
                                            alert(message);
                                        }
                                    }


                                }
                                function sendOrRemove_AJAX() {
//                               var data = myObject.responseText;
//                                    if (myObject.readyState == 4) {
//                                        alert(data);
//                              }
                                    searchItemAfterAdd();
                                    refreshDoctorCache();
                                    document.getElementById("comments").value = '';
                                    // document.getElementById("Sub_Department_ID").value = '';
                                    var Consultation_Type = "<?php echo $_GET['Consultation_Type'] ?>";
                                    if (Consultation_Type.toLowerCase() == 'procedure' || Consultation_Type.toLowerCase() == 'surgery') {
                                        document.getElementById("procedurelocation").value = '';
                                    }

                                    if (Consultation_Type.toLowerCase() == 'pharmacy') {
                                        document.getElementById("Balance").value = '';
                                    }

                                    document.getElementById("Quantity").value = '';
                                    if (Consultation_Type.toLowerCase() != 'pharmacy') {
                                        document.getElementById("Status").value = '';
                                        document.getElementById("QueuSize").value = '';
                                    }

                                    document.getElementById("Item_Name").value = '';
                                    document.getElementById("Item_ID").value = '';
                                    document.getElementById("Price").value = '';
                                    document.getElementById("Amount").value = '';
                                    $('input:radio').prop('checked', false);
                                    //var item_supported = $('#item_supported_' + Item_ID).val();
                                    //if (item_supported == 'no') {
                                    var origBillType = $('#patientOrigialBillingType').val();
                                    $('#bill_type').val(origBillType);
//                                    }
                                }


                            }
                            //function to get item balance
                            function Get_ItemM_Balance() {
                                Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                    <?php if ($Consultation_Type == 'Laboratory' || $Consultation_Type == 'Radiology' || $Consultation_Type == 'Nuclearmedicine'  || $Consultation_Type == 'Surgery' || $Consultation_Type == 'Procedure') { ?> <?php } else { ?> document.getElementById('Balance').value = data; <?php } ?>
                                    }
                                }; //specify name of function that will handle server response........

                                myObject.open('GET', 'Get_Items_Balance.php?Item_ID=' + Item_ID + '&Sub_Department_ID=' + Sub_Department_ID, true); //TO DO Change Get_Items_Price to Get_Item_Price
                                myObject.send();
                            }

                            function Get_Item_Status() {
                                if (window.XMLHttpRequest) {
                                    myObject = new XMLHttpRequest();
                                } else if (window.ActiveXObject) {
                                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                                }
                                myObject.onreadystatechange = function () {
                                    data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                                        parent.document.getElementById('Status').value = data;
                                        parent.getItemQueuSize();
                                        //alert(data);
                                    }
                                }; //specify name of function that will handle server response........

                                myObject.open('GET', 'Get_Items_status.php?Item_ID=' + Item_ID, true);
                                myObject.send();
                            }
                        </script>



                        <!--HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH -->
                        <table width = 100%>
                            <tr>
                                <td style="border:1px  solid #ccc;">
                                    <fieldset>
                                        <table width="100%">
                                            <tr>
                                                <td style="border:1px  solid #ccc;">
                                                    <div  style="width:100%; height:320px; overflow-y:scroll;overflow-x:hidden" id='frmaesearch'>
                                                        <?php
                                                        include "doctordetaileditemselect_Iframe.php";
                                                        ?>

                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="border:1px  solid #ccc;">
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;border:1px  solid #ccc;' colspan=2>
                                    <!-- ITEM DESCRIPTION START HERE -->
                            <center>
                                <table width=100%>
                                    <tr>
                                        <td><b>Item Name</b></td>
                                        <?php //if ($Consultation_Type != 'Others') { ?>
                                            <td style="width: 100px;"><b>Location</b></td>
                                        <?php //} ?>
<!--<td>Discount</td>-->
                                        <td><b>Price</b></td>
                                        <td style="width: 80px;"><b>
                                                <?php
                                                if (strtolower($Consultation_Type) == "pharmacy") {
                                                    echo "Balance";
                                                } else {
                                                    echo "Status";
                                                }
                                                ?>
                                            </b></td>
                                        <?php //if (strtolower($Consultation_Type) == "pharmacy") {   ?>
                                        <td><b>Qty</b></td>
                                        <?php //}
                                        ?>

                                        <?php if (strtolower($Consultation_Type) != "pharmacy") { ?>
                                            <td><b>Queue</b></td>
                                        <?php }
                                        ?>
                                        <td style="width: 75px;"><b>Amount</b></td>

                                    </tr>
                                    <form action='' method='POST'>
                                        <tr>
                                            <td width=35%>
                                                <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                                <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                                <input type='hidden' name='Discount' id='Discount' placeholder='Discount' onkeyup='check_discount()' value="0">
                                            </td>
                                            <?php //if ($Consultation_Type != 'Others') { ?>
                                                <td>
                                                    <select style='width:110px;padding:4px;' name='Sub_Department_ID' id='Sub_Department_ID' required='required' onchange="getLocationQueSize();<?php if ($Consultation_Type == 'Laboratory' && $Consultation_Type == 'Radiology' && $Consultation_Type == 'Nuclearmedicine') { ?>Get_Item_Status();<?php } else { ?>Get_ItemM_Balance();<?php } ?>">

                                                        <?php
                                                        if($Consultation_Type=="Others"){
                                                             $qr = "SELECT * FROM tbl_department d,tbl_sub_department s
                                where
                                d.Department_ID = s.Department_ID and s.Sub_Department_Status='active'
                                 ";
                                                        }else{
                                                             $qr = "SELECT * FROM tbl_department d,tbl_sub_department s
                                where
                                d.Department_ID = s.Department_ID and s.Sub_Department_Status='active' and
                                $Consultation_Condition ";
                                                        }

                                                        $result = mysqli_query($conn,$qr);

                                                        if(mysqli_num_rows($result) > 1){
                                                          echo  '<option value="">Select</option> ';
                                                        }

                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $Sub_Department_Name = $row['Sub_Department_Name'];
                                                            ?>
                                                            <?php if ($Sub_Department_Name == 'Laboratory') { ?>
                                                                <option value='<?php echo $row['Sub_Department_ID']; ?>'>
                                                                    <?php echo $row['Sub_Department_Name']; ?>
                                                                </option>
                                                            <?php } else {
                                                                ?>
                                                                <option value='<?php echo $row['Sub_Department_ID']; ?>'>
                                                                    <?php echo $row['Sub_Department_Name']; ?>
                                                                </option>
                                                            <?php }
                                                            ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>


                                                </td>
                                            <?php //} ?>
    <!--<td>-->
                                            <!--    <!--Column for discount if needed-->
                                            <!--</td>-->
                                            <td>
                                                <input type='text' name='Price'style='width:60px;padding:4px;' id='Price' placeholder='Price' readonly='readonly'>
                                            </td>
                                            <td>
                                                <?php if (strtolower($Consultation_Type) == "pharmacy") { ?>
                                                    <input type='text' name='Balance' id='Balance' placeholder='Balance' readonly="readonly">
                                                <?php } else { ?>
                                                    <input type='text' name='Status' id='Status' placeholder='Status'  readonly='readonly'>
                                                <?php } ?>
                                            </td>
                                            <?php // if (strtolower($Consultation_Type) == "pharmacy") {   ?>
                                            <td>
                                                <input type='text' style='width:40px;padding:4px;' name='Quantity' id='Quantity' required='required' placeholder='Quantity' value='0' onchange='Calculate_Amount()' onkeyup='Calculate_Amount()' required='required'>
                                            </td>
                                            <?php // }
                                            ?>

                                            <?php if (strtolower($Consultation_Type) != "pharmacy") { ?>
                                                <td>
                                                    <input type='text' name='Price' id='QueuSize' placeholder='Queue' readonly='readonly'>
                                                </td>
                                            <?php }
                                            ?>
                                            <td>
                                                <input type='text' name='Amount' id='Amount' placeholder='Amount' readonly='readonly'>
                                            </td>
                                            <td style='text-align: center;'>
                                                <input type="button" name='submint' id="add" value='ADD' class='art-button-green' style='width: 8px;' onclick="sendOrRemoveItem()"/>

                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </center>
                            <!-- ITEM DESCRIPTION ENDS HERE -->
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <div  style="width:100%; height:250px; overflow-y:scroll;overflow-x:hidden" id='doctoritemcache'>
                            <?php include 'doctoritemcache.php'; ?>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td><b style="font-size:14px"><span style="color:red;">NOTE:</span>Doctor may change Sponsor type to other sponsor if that item is not supported by his/her sponsor.Patient will need to pay cash for that item</b></td>
                    <td style='text-align: right'>

                        <button type="button" onclick="doneDiagonosisselect()" class="art-button-green">DONE</button>

                    </td>
                </tr>
            </table>

            </td>
            </tr>
            </table>
        </center>
    </fieldset>
</form>

<script type='text/javascript'>
    function sendOrRemove(Item_ID, check_ID) {
        var bill_type = parent.document.getElementById("bill_type").value;
        var action;
        var Registration_ID = "<?php echo $_GET['Registration_ID']; ?>";
        var Patient_Payment_ID = "<?php (isset($_GET['Patient_Payment_ID'])?$_GET['Patient_Payment_ID']:''); ?>";
        var Sub_Department_ID = parent.document.getElementById("Sub_Department_ID").value;
        var quantity = parent.document.getElementById('quantity').value;
        var comments = parent.document.getElementById('comments').value;
        if (check_ID.checked == true) {
            action = "ADD";
        } else {
            action = "REMOVE";
        }
        if (Item_ID != '') {
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            if (action == 'REMOVE') {
                var Sponsor_ID=$("#new_sponsor_to_bill").val();
                myObject.onreadystatechange = sendOrRemove_AJAX; //specify name of function that will handle server response....
                myObject.open('GET', 'sendOrRemove.php?action=' + action + '&Consultation_Type=<?php echo $_GET['Consultation_Type']; ?>&Sub_Department_ID=' + Sub_Department_ID + '&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID='+Sponsor_ID+'&Item_ID=' + Item_ID + '&bill_type=' + bill_type + '&Guarantor_Name=' + Guarantor_Name + '&Registration_ID=' + Registration_ID+'&External_Payment_Cache_ID=<?= isset($_GET['External_Payment_Cache_ID'])?$_GET['External_Payment_Cache_ID']:''; ?>', true);
                myObject.send();
            } else {
                if (bill_type != '' && Sub_Department_ID != '') {
                    var Sponsor_ID=$("#new_sponsor_to_bill").val();
                    myObject.onreadystatechange = sendOrRemove_AJAX; //specify name of function that will handle server response....
                    myObject.open('GET', 'sendOrRemove.php?action=' + action + '&quantity=' + quantity + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Consultation_Type=<?php echo $_GET['Consultation_Type']; ?>&Sub_Department_ID=' + Sub_Department_ID + '&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID='+Sponsor_ID+'&Item_ID=' + Item_ID + '&bill_type=' + bill_type + '&Guarantor_Name=' + Guarantor_Name + '&Registration_ID=' + Registration_ID + '&comments=' + comments+'&External_Payment_Cache_ID=<?= isset($_GET['External_Payment_Cache_ID'])?$_GET['External_Payment_Cache_ID']:''; ?>', true);
                    myObject.send();
                } else {
                    var message = "choose";
                    if (bill_type == '') {
                        check_ID.checked = false;
                        message = message + ' bill type';
                    }
                    if (Sub_Department_ID == '') {
                        check_ID.checked = false;
                        message = message + ' ,location';
                    }
                    message = message + " first";
                    alert(message);
                }
            }
        }
    }
    function sendOrRemove_AJAX() {
    }
</script>
<script type="text/javascript" language="javascript">
    function checkNonSupportedItem(Item_ID) {
        var bill_type = document.getElementById("bill_type_" + Item_ID + "").value;
        if (bill_type == 'Credit') {
            var Sponsor_name = '<?php echo $Guarantor_Name; ?>';
            if (window.XMLHttpRequest) {
                supportObject = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                supportObject = new ActiveXObject('Micrsoft.XMLHTTP');
                supportObject.overrideMimeType('text/xml');
            }
            supportObject.onreadystatechange = function () {
                var supportResult = supportObject.responseText;
                //check if item is supported
                if (supportObject.readyState == 4) {
                    if (supportResult == 'not supported') {
                        var choice = confirm("This Item Is Not Supported By " + Sponsor_name + "\n Do You Want To Proceed ?");
                        if (choice) {
                        } else {
                            document.getElementById("bill_type_" + Item_ID + "").innerHTML = "<option></option><option>Cash</option><option>Credit</option>"
                        }
                    }
                }
            }; //specify name of function that will handle server response....

            supportObject.open('GET', 'checkNonSupportedItem.php?Item_ID=' + Item_ID + "&Sponsor_name=" + Sponsor_name, true);
            supportObject.send();
        }
    }


    //function to get item status
    function Get_Item_Status(Item_ID) {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                parent.document.getElementById('Status').value = data;
                parent.getItemQueuSize();
                //alert(data);
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_status.php?Item_ID=' + Item_ID, true);
        myObject.send();
    }

//function to get item balance
    function Get_Item_Balance(Item_ID) {
        var Consultation_Type = "<?php echo $_GET['Consultation_Type'] ?>";
        if (Consultation_Type != 'Others') {
            Sub_Department_ID = parent.document.getElementById('Sub_Department_ID').value;
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    parent.document.getElementById('Balance').value = data;
                }
            }; //specify name of function that will handle server response........

            myObject.open('GET', 'Get_Items_Balance.php?Item_ID=' + Item_ID + '&Sub_Department_ID=' + Sub_Department_ID, true); //TO DO Change Get_Items_Price to Get_Item_Price
            myObject.send();
        }
    }

    //function to get item name
    function Get_Item_Name(Item_ID) {
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                parent.document.getElementById('Item_Name').value = data;
<?php if ($Consultation_Type != 'Laboratory' && $Consultation_Type != 'Radiology' && $Consultation_Type != 'Nuclearmedicine' && $Consultation_Type != 'Surgery' && $Consultation_Type != 'Procedure' && $Consultation_Type != 'Others') { ?>
                    Get_Item_Balance(Item_ID);
<?php } else { ?>
                    Get_Item_Status(Item_ID);
<?php } ?>
            }
        }; //specify name of function that will handle server response........

        myObject.open('GET', 'Get_Items_Name.php?Item_ID=' + Item_ID, true);
        myObject.send();
    }
     //check if the item is allowed for the patient package
    function check_package_status(Item_ID){
        var package_result = true;

		var Sponsor_ID = document.getElementById('new_sponsor_to_bill').value
    		$.ajax({
    			url:"check_item_package_status.php",
    			type:'post',
            dataType:'json',
            data:{Item_ID:Item_ID,Registration_ID:"<?=$Registration_ID;?>",Patient_Payment_Item_List_ID:'<?=$Patient_Payment_Item_List_ID;?>',Sponsor_ID:Sponsor_ID},
            async:false,
            cache:false,
    		    success:function(result){
                    if(result.message == 'excluded'){
                    alert(result.Product_Name+' IS EXCLUDED FROM '+result.package_name+' PACKAGE')
                    package_result = false;
                    }else if(result.message == 'outside_sponsor'){
                        //alert('CHECK SPONSOR TYPE\n PATIENT WILL PAY FOR THIS ITEM');
                    }else if(result.message=='NotAuthorized'){
                        alert("This patient was not authorized. Please contact medical Record to authorize the patient, before to continue");
                        package_result = false;
                    }
                }
    	    });
    		return package_result;
    }

    function Get_Item_Price(Item_ID) {
    	if(check_package_status(Item_ID) == false){
    		return false;
    	}
        // alert('<?php echo $Guarantor_Name; ?>');
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                parent.document.getElementById('Price').value = data;
                parent.Calculate_Amount();
                //alert(data);
                Get_Item_Name(Item_ID);
            }
        }; //specify name of function that will handle server response........

        var Billing_Type = parent.document.getElementById("bill_type").value;
        if (Billing_Type == 'Cash') {
            Billing_Type = "Outpatient Cash";
        } else {
            Billing_Type = "Outpatient Credit";
        }
        //var Guarantor_Name = "<?php //echo $_GET['Guarantor_Name'];                                ?>";
        var Sponsor_ID=$("#new_sponsor_to_bill").val();
        myObject.open('GET', 'Get_Items_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID='+Sponsor_ID+'&Billing_Type=' + Billing_Type, true);
        myObject.send();
    }



    //function to chek if some form fields are filled
    function checkForm(Item_ID) {
        var sponsor = '<?php echo $Guarantor_Name; ?>';
        var item_supported = document.getElementById('item_supported_' + Item_ID).value;
        parent.Item_ID = Item_ID;
        var bill_type = parent.document.getElementById("bill_type").value;
        if (bill_type == null || bill_type == "") {
            alert("Please select bill type");
            parent.document.getElementById("bill_type").focus();
            return false;
        } else if (item_supported == 'no' && (bill_type == '' || bill_type == 'Credit')) {
            alert('Item is not supported by ' + sponsor + ' Please select cash for billing type');
            $('#bill_type').css('border', '1px solid red');
            $('input:radio').prop('checked', false);
            $('#patientOrigialBillingType').val('Credit');
            parent.Item_ID = '';
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            document.getElementById("Price").value = '';
            document.getElementById("Amount").value = '';
            return false;
        }

        Get_Item_Price(Item_ID);
    }

    //function to check if there is any provisional diagnosis selected before selecting laboratory test name
    function check_provisional_diagnosis(Item_ID, canAdd, specialist_order) {
        var exists = false;

        $('#bill_type option').each(function () {
            if (this.value == "Credit") {
                exists = true;
            }
        });
        
        if(specialist_order=='No'){
            alert("This service can be ordered by Specialist/ Super Specialist Only. Consult specialist/Super Specialist please!!..");
            exit;
        }

        if (($('#Item_Name').val() != '' || $('#Item_Name').val() == '') && $('#bill_type').val() == 'Cash' && exists == true) {
            if (!confirm("The Bill type selected, patient will pay cash. Do you want to continue?")) {
                $('#bill_type').val('Credit');
                exit;
            }
        }

        if (canAdd=='true') {
            if (confirm('There is another unprocessed item (same). Do you want to add another one?')) {

            } else {
                exit;
            }
        }

        document.getElementById("comments").value = '';
        document.getElementById("comments").focus();
        var provisional_diagnosis = $(".provisional_diagnosis").val();
        // alert('fsad');
        var Consultation_Type = "<?php echo $_GET['Consultation_Type']; ?>";
        if (Consultation_Type.toLowerCase() == "pharmacy") {
            document.getElementById("Quantity").value = 0;
        }

        //check the consultation_type
        if (Consultation_Type.toLowerCase() == "laboratory") {

<?php
if ($req_op_prov_dign == '1') {
    ?>

                if (provisional_diagnosis == '' || provisional_diagnosis == null) {
                    var order_type='<?php echo $is_external_order?>';
                  if(order_type=='1'){
                        checkForm(Item_ID); //call the function to check the form
                      return true;
                  }else{
                    alert("You cannot select a laboratory test name without providing any provisional diagnosis.\nPlease go back to select a provisional diagnosis before specifying a laboratory test name.");
                    return false;
                  }
                } else {
                    checkForm(Item_ID); //call the function to check the form
                    return true;
                }
    <?php
} else {
    ?>
                checkForm(Item_ID); //call the function to check the form
                return true;
    <?php
}
?>
        } else {
            //alert(Item_ID);
            checkForm(Item_ID); //call the function to check the form
            return true;
        }
    }





</script>
<script type='text/javascript'>
    function removeitem(Payment_Item_Cache_List_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'removeitemcahe.php?Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4) {
            searchItemAfterAdd();
            refreshDoctorCache();
        }
    }



    function check_item(Payment_Item_Cache_List_ID, paymentCacheID) {
        var payment_cache_ID = paymentCacheID;
        //alert(Payment_Item_Cache_List_ID+" pc="+payment_cache_ID);
        var Registration_ID = "<?php echo $Registration_ID; ?>";
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = function AJAXP1() {
            var data = mm.responseText;
            if (mm.readyState == 4) {
                if (data.toLowerCase() == 'processed') {
                    alert("This Item Has Been Processed.\nYou Cannot Remove It.");
                } else {

                    var Confirm_Remove = confirm("Are You Sure You Want To Remove This Item?");
                    if (Confirm_Remove) {
                        removeitem(Payment_Item_Cache_List_ID);
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }; //specify name of function that will handle server response....
        mm.open('GET', 'check_item.php?Payment_Cache_ID=' + payment_cache_ID + '&Registration_ID=' + Registration_ID + '&Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID, true);
        mm.send();
    }

</script>
<script>
    function updateDoctorItemCOmment(ppil, comm) {
        //alert(ppil+' '+comm);exit;
        $.ajax({
            type: 'POST',
            url: 'requests/updateDoctorItemCOmment.php',
            data: 'ppil=' + ppil + '&comm=' + comm,
            success: function (html) {
                //alert(html);
                //document.getElementById('doctoritemcache').innerHTML=html;
            }, error: function (x, y, z) {
                alert(z);
            }
        });
    }



    //document.location.reload(true);
</script>
<script>
    function refreshDoctorCache() {
        $.ajax({
            type: 'GET',
            url: 'doctoritemcache.php',
            data: 'consultation_id=<?php echo $consultation_id; ?>&Consultation_Type=<?php echo $Consultation_Type; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID; ?>&Guarantor_Name=<?php echo $Guarantor_Name ?>&External_Payment_Cache_ID=<?= isset($_GET['External_Payment_Cache_ID'])?$_GET['External_Payment_Cache_ID']:''; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage',
            success: function (html) {
                document.getElementById('doctoritemcache').innerHTML = html;
            }, error: function (x, y, z) {
                alert(z);
            }
        });
    }
    //document.location.reload(true);
</script>
<script>
    $('#date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date').datetimepicker({value: '', step: 30});</script>

<script>
    $("#service_hour,#service_min").bind("keydown", function (event) {

        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: Ctrl+C
                                (event.keyCode == 67 && event.ctrlKey === true) ||
                                // Allow: Ctrl+V
                                        (event.keyCode == 86 && event.ctrlKey === true) ||
                                        // Allow: home, end, left, right
                                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                                    // let it happen, don't do anything
                                    return;
                                } else {
                                    // Ensure that it is a number and stop the keypress
                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            });</script>
<script>
      
       
</script>
