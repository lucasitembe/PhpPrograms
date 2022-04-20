<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '0000/00/00 00:00:00';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '0000/00/00 00:00:00';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = '';
}

//get Sponsor
if ($Sponsor_ID == 0) {
    $Sponsor = 'All';
} else {
    $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Sponsor = $row['Guarantor_Name'];
        }
    } else {
        $Sponsor = '0';
    }
}

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = mysqli_real_escape_string($conn,$_GET['Employee_ID']);
} else {
    $Employee_ID = '';
}

//get employee
if ($Employee_ID == '0') {
    $Emp_Name = 'All';
} else {
    $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Emp_Name = $row['Employee_Name'];
        }
    }
}

if (isset($_GET['Section'])) {
    $Section = mysqli_real_escape_string($conn,$_GET['Section']);
} else {
    $Section = '';
}
//create Report_Type
if (strtolower($Section) == 'cashcredit') {
    $Report_Type = 'Cash And Credit';
} else if (strtolower($Section) == 'cash') {
    $Report_Type = 'Cash';
} else if (strtolower($Section) == 'credit') {
    $Report_Type = 'Credit';
} else {
    $Report_Type = 'Cancelled';
}

if (isset($_GET['Patient_Type'])) {
    $Patient_Type = mysqli_real_escape_string($conn,$_GET['Patient_Type']);
} else {
    $Patient_Type = '';
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
} else {
    $Hospital_Ward_ID = 'none';
}

//Get Ward Title
if ($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0') {
    //SELECT WARD
    $slct_ward = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
    $n_ward = mysqli_num_rows($slct_ward);
    if ($n_ward > 0) {
        while ($dzt = mysqli_fetch_array($slct_ward)) {
            $Ward = $dzt['Hospital_Ward_Name'];
            $Ward_Title = $dzt['Hospital_Ward_Name'] . " ";
        }
    } else {
        $Ward = '';
        $Ward_Title = '';
    }
} else if ($Hospital_Ward_ID == '0') {
    $Ward = 'ALL WARDS';
    $Ward_Title = 'ALL WARDS ';
} else {
    $Ward = '';
    $Ward_Title = '';
}
//get employee
//if (isset($_GET['clinic']) && $_GET['clinic'] != "ALL") {
//    $clinic_id=$_GET['clinic'];
//    $clinics = "select * from tbl_clinic where Clinic_ID = '$clinic_id'";
//    $clinic = mysqli_query($conn,$clinics);
//    $num = mysqli_num_rows($clinic);
//    if ($num > 0) {
//        while ($row = mysqli_fetch_array($select)) {
//            $Clinic_Name = $row['Clinic_Name'];
//        }
//    }
//} else {
//    $Clinic_Name = "ALL";
//}
//generate filter
$filter = "pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and ";
if (isset($_GET['clinic']) && $_GET['clinic'] != "ALL") {
    $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
   // $filter .= "('$Clinic_ID' IN (SELECT Clinic_ID FROM tbl_clinic_employee WHERE Employee_ID=ppl.Consultant_ID) OR ppl.Clinic_ID='$Clinic_ID') and ";
    $filter .= "ppl.Clinic_ID='$Clinic_ID' and ";
    $clinics = "select * from tbl_clinic where Clinic_ID = '$Clinic_ID'";
    $clinic = mysqli_query($conn,$clinics);
    $num = mysqli_num_rows($clinic);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($clinic)) {
            $Clinic_Name = $row['Clinic_Name'];
        }
    }
}else {
   $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
    $Clinic_Name = "ALL";
}

//dont complicate, understand slowly prog
if ($Patient_Type == 'Inpatient') {
    if (strtolower($Section) == 'cash') {
        $filter .= "Billing_Type = 'Inpatient Cash'  and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    }

    //filter according to ward selected
    if ($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0') {
        $filter .= " pp.Hospital_Ward_ID = '$Hospital_Ward_ID' and ";
    }
} else if ($Patient_Type == 'Outpatient') {
    if (strtolower($Section) == 'cash') {
        $filter .= "Billing_Type = 'Outpatient Cash' and Pre_Paid = '0' and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "(Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
} else {
    if (strtolower($Section) == 'cash') {
        $filter .= "((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0')  or (Billing_Type = 'Inpatient Cash' and pp.payment_type='pre')) and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "Transaction_status = 'cancelled' and ";
    } else {
        //$filter .= "Transaction_status <> 'cancelled' and ";
        $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit' or (Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
}

if ($Sponsor_ID != 0) {
    $filter .= "pp.Sponsor_ID = '$Sponsor_ID' and ";
}

if ($Employee_ID != 0) {
    $filter .= "pp.Employee_ID = '$Employee_ID' and ";
}
?>
<a href="revenuecollection.php?Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Currency_ID=<?php echo $Currency_ID; ?>&GeneralLedgerReport=GeneralLedgerReportThisPage"><button type="button" style="height:27px!important;color:#FFFFFF!important"class="art-button-green">BACK</button></a>
<style>
    table,tr,td{
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<fieldset>
    <center>
        <table width="50%">
            <tr><td style="text-align: center;"><b>REVENUE COLLECTION SUMMARY REPORT FROM </b><?php echo $Start_Date; ?><b> TO </b><?php echo $End_Date; ?></td></tr>
            <tr><td style="text-align: center;"><b>REPORT TYPE : </b><?php echo strtoupper($Report_Type); ?>,&nbsp;&nbsp;&nbsp;<b>SPONSOR : </b><?php echo strtoupper($Sponsor); ?></td></tr>
            <tr><td style="text-align: center;"><b>CLINIC : </b><?php echo strtoupper($Clinic_Name); ?>.&nbsp;&nbsp;&nbsp;</td></tr>
        </table>
    </center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Transactions_Area'>
    <?php 
        if($Section=="CashCredit"){
            include "cash_and_credit_main_dept.php";
        }else if($Section=="Cash"){
            include "cash_main_dept.php"; 
        }else if($Section=="Credit"){
            include "credit_main_dept.php";
        }else if($Section=="Cancelled"){
            include "cancelled_main_dept.php";
        }
    ?>
</fieldset>
<fieldset>
    <?php 
        echo "<input type='submit' name='submit' value='Preview' class='art-button-green' style='float:right' onclick='Preview_Report(\"{$Section}\");'>";
    ?>
    <table width="100%">
        <tr>
            <td style="text-align: center; color: #0079AE;"><b><i>CLICK MAIN DEPARTMENT NAME TO VIEW DETAILS </i></b></td>
            <td width="25%" style="text-align: right;display: none">
                <input type="button" class="art-button-green" value="PREVIEW" onc_lick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>
<script type="text/javascript">
    function Preview_Report(Section){
        var Sponsor = '<?=$Sponsor;?>';
        var Start_Date = '<?=$Start_Date;?>';
        var End_Date = '<?=$End_Date;?>';
        var Report_Type = '<?=$Report_Type;?>';
        var clinic = '<?=$_GET["clinic"];?>';
        var Hospital_Ward_ID = '<?=$Hospital_Ward_ID?>';
        var Patient_Type = '<?=$Patient_Type?>';
        
        if(Section == 'CashCredit'){
            window.open("CashCredit_Main_Dept_Preview_Report.php?Sponsor="+Sponsor+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Report_Type="+Report_Type+"&clinic="+clinic+"&Hospital_Ward_ID="+Hospital_Ward_ID+"&Patient_Type="+Patient_Type);
        }
        if(Section == 'Cash'){
            window.open("Cash_Main_Dept_Preview_Report.php?Sponsor="+Sponsor+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Report_Type="+Report_Type+"&clinic="+clinic+"&Hospital_Ward_ID="+Hospital_Ward_ID+"&Patient_Type="+Patient_Type);
        }
        if(Section == 'Credit'){
            window.open("Credit_Main_Dept_Preview_Report.php?Sponsor="+Sponsor+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Report_Type="+Report_Type+"&clinic="+clinic+"&Hospital_Ward_ID="+Hospital_Ward_ID+"&Patient_Type="+Patient_Type);
        }
        if(Section == 'Cancelled'){
            window.open("Cancelled_Main_Dept_Preview_Report.php?Sponsor="+Sponsor+"&Start_Date="+Start_Date+"&End_Date="+End_Date+"&Report_Type="+Report_Type+"&clinic="+clinic+"&Hospital_Ward_ID="+Hospital_Ward_ID+"&Patient_Type="+Patient_Type);
        }
    }
</script>
<?php
include("./includes/footer.php");
