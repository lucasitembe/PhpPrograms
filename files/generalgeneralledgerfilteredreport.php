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
if (isset($_GET['finance_department_id'])) {
    $finance_department_id = $_GET['finance_department_id'];
} else {
    $finance_department_id = 'none';
}


//get finance department name
$finance_department_name="";
$sql_select_finance_department_name_result=mysqli_query($conn,"SELECT finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_finance_department_name_result)>0){
   $finance_department_name=mysqli_fetch_assoc($sql_select_finance_department_name_result)['finance_department_name']; 
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
        $filter .= "Billing_Type = 'Inpatient Cash' and pp.payment_type='pre' and Transaction_status <> 'cancelled' and ";
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
        $filter .= "(Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
} else {
    if (strtolower($Section) == 'cash') {
//        $filter .= "(Billing_Type = 'Outpatient Cash' or (Billing_Type = 'Inpatient Cash')) and Transaction_status <> 'cancelled' and ";
            $filter .= "((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0')  or (Billing_Type = 'Inpatient Cash' and pp.payment_type='pre')) and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "Transaction_status = 'cancelled' and ";
    } else {
        //$filter .= "Transaction_status <> 'cancelled' and ";
//        $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
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
<!--<a href="generalgeneralledgerfilteredmai_departmentreport.php?Start_Date=<?php echo $Start_Date; ?>&End_Date=<?php echo $End_Date; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Currency_ID=<?php echo $Currency_ID; ?>&GeneralLedgerReport=GeneralLedgerReportThisPage"><button type="button" style="height:27px!important;color:#FFFFFF!important"class="art-button-green">BACK</button></a>-->
<a href='<?= "generalgeneralledgerfilteredmai_departmentreport.php?Section=$Section&Patient_Type=$Patient_Type&Start_Date=$Start_Date&End_Date=$End_Date&Sponsor_ID=$Sponsor_ID&Employee_ID=$Employee_ID&Hospital_Ward_ID=$Hospital_Ward_ID&clinic=$Clinic_ID&GeneralLedgerCashAndCreditReport=GeneralLedgerCashAndCreditReportThisPage" ?>' class="art-button-green">BACK</a>

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
            <tr><td style="text-align: center;"><b>DEPARTMENT : </b><?php echo strtoupper($finance_department_name); ?>.&nbsp;&nbsp;&nbsp;</td></tr>
        </table>
    </center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Transactions_Area'>
    <?php
    if (strtolower($Section) == 'cashcredit') {
        //include("./generalgeneralledgerfilteredmai_departmentreport.php");
        include("./General_Cash_Credit.php");
    } else if (strtolower($Section) == 'cash') {
        include("./General_Cash.php");
    } else if (strtolower($Section) == 'credit') {
        include("./General_Credit.php");
    } else if (strtolower($Section) == 'cancelled') {
        include("./General_Cancelled.php");
    } else if($Section == 'other_sources'){
        include("./Other_Sources.php");
    }else {
        include("./General_Credit.php");
    }
    ?>

</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: center; color: #0079AE;"><b><i>CLICK CATEGORY NAME TO VIEW DETAILS </i></b></td>
            <td width="25%" style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>
<div id="Category_Details">

</div>
<div id="Sub_Category_Details">

</div>

<div id="Items_Details_Display">

</div>

<script type="text/javascript">
    function Preview_Report() {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Section = '<?php echo $Section; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
        var clinic = '<?php echo $Clinic_ID; ?>';
        window.open("generalledgergeneralpreviewreport.php?Sponsor_ID=" + Sponsor_ID + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Employee_ID=" + Employee_ID + "&Patient_Type=" + Patient_Type + "&Section=" + Section + "&Hospital_Ward_ID=" + Hospital_Ward_ID +"&clinic="+clinic+"&GeneralLedgerPreviewReport=GeneralLedgerPreviewReportThisPage&finance_department_id=<?= $finance_department_id ?>", "_blank");
    }
</script>


<script type="text/javascript">
    function Preview_Category_Details(Section, Item_Category_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
        var clinic = '<?php echo $Clinic_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            dataPreview = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById('Category_Details').innerHTML = dataPreview;
                $("#Category_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectPreview.open('GET', 'General_Preview_Category_Details.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Employee_ID=' + Employee_ID + '&Patient_Type=' + Patient_Type + '&Item_Category_ID=' + Item_Category_ID + '&Section=' + Section + '&Hospital_Ward_ID=' + Hospital_Ward_ID+"&clinic="+clinic+"&finance_department_id=<?= $finance_department_id ?>", true);
        myObjectPreview.send();

    }
    function Preview_Other_Sources(customer_type){
      var Start_Date = '<?php echo $Start_Date; ?>';
      var End_Date = '<?php echo $End_Date; ?>';
      $.ajax({
        url:'fetch_other_sources_ledger_details.php',
        type:'post',
        data:{Start_Date:Start_Date,End_Date:End_Date,customer_type:customer_type},
        success:function(results){
          $("#Category_Details").html(results);
        }
      });
      $("#Category_Details").dialog("open");
    }
    function Preview_Other_Sources_Report(customer_type){
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        window.open('preview_other_sources_report.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&customer_type='+customer_type);
    }
</script>

<script type="text/javascript">
    function Preview_Sub_Category_Details(Section, Item_Subcategory_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
         var clinic = '<?php echo $Clinic_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectSubPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSubPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSubPreview.overrideMimeType('text/xml');
        }

        myObjectSubPreview.onreadystatechange = function () {
            dataSubPreview = myObjectSubPreview.responseText;
            if (myObjectSubPreview.readyState == 4) {
                document.getElementById('Sub_Category_Details').innerHTML = dataSubPreview;
                $("#Sub_Category_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectSubPreview.open('GET', 'General_Preview_Sub_Category_Details.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Employee_ID=' + Employee_ID + '&Patient_Type=' + Patient_Type + '&Item_Subcategory_ID=' + Item_Subcategory_ID + '&Section=' + Section + '&Hospital_Ward_ID=' + Hospital_Ward_ID+"&clinic="+clinic+"&finance_department_id=<?= $finance_department_id ?>", true);
        myObjectSubPreview.send();
    }
</script>

<script type="text/javascript">
    function Preview_Sub_Category_Report(Section, Item_Category_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
        var clinic = '<?php echo $Clinic_ID; ?>';
        window.open("previewsubcategoriesreport.php?Sponsor_ID=" + Sponsor_ID + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Employee_ID=" + Employee_ID + "&Patient_Type=" + Patient_Type + "&Section=" + Section + "&Item_Category_ID=" + Item_Category_ID + "&Hospital_Ward_ID=" + Hospital_Ward_ID+"&clinic="+clinic + "&PreviewSubCategoriesReport=PreviewSubCategoriesReportThisPage&finance_department_id=<?= $finance_department_id ?>", "_blank");
    }
</script>

<script type="text/javascript">
    function Preview_Items_Details(Section, Item_Subcategory_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
         var clinic = '<?php echo $Clinic_ID; ?>';
        window.open("previewgeneralitemsdetails.php?Section=" + Section + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Sponsor_ID=" + Sponsor_ID + "&Employee_ID=" + Employee_ID + "&Patient_Type=" + Patient_Type + "&Item_Subcategory_ID=" + Item_Subcategory_ID + "&Hospital_Ward_ID=" + Hospital_Ward_ID +"&clinic="+clinic+"&PreviewItemsDetails=PreviewItemsDetailsThisPage&finance_department_id=<?= $finance_department_id ?>", "_blank");
    }
</script>

<script type="text/javascript">
    function Display_Items_Details(Section, Item_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
         var clinic = '<?php echo $Clinic_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectItemsDet = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectItemsDet = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectItemsDet.overrideMimeType('text/xml');
        }

        myObjectItemsDet.onreadystatechange = function () {
            dataDisp = myObjectItemsDet.responseText;
            if (myObjectItemsDet.readyState == 4) {
                document.getElementById('Items_Details_Display').innerHTML = dataDisp;
                $("#Items_Details_Display").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectItemsDet.open('GET', 'General_Display_Items_Details.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID + '&Employee_ID=' + Employee_ID + '&Patient_Type=' + Patient_Type + '&Item_ID=' + Item_ID + '&Section=' + Section + '&Hospital_Ward_ID=' + Hospital_Ward_ID+"&clinic="+clinic+"&finance_department_id=<?= $finance_department_id ?>", true);
        myObjectItemsDet.send();
    }
</script>

<script type="text/javascript">
    function Preview_Items_Display(Section, Item_ID) {
        var Start_Date = '<?php echo $Start_Date; ?>';
        var End_Date = '<?php echo $End_Date; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        var Patient_Type = '<?php echo $Patient_Type; ?>';
        var Hospital_Ward_ID = '<?php echo $Hospital_Ward_ID; ?>';
         var clinic = '<?php echo $Clinic_ID; ?>';
        window.open("previewgeneralitems.php?Section=" + Section + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Sponsor_ID=" + Sponsor_ID + "&Employee_ID=" + Employee_ID + "&Patient_Type=" + Patient_Type + "&Item_ID=" + Item_ID + "&Hospital_Ward_ID=" + Hospital_Ward_ID+"&clinic="+clinic + "&PreviewItemsDetails=PreviewItemsDetailsThisPage&finance_department_id=<?= $finance_department_id ?>", "_blank");
    }
</script>

<script type="text/javascript">
    function Close_Items_Details() {
        $("#Sub_Category_Details").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Sub_Category_Details() {
        $("#Category_Details").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Items_Display() {
        $("#Items_Details_Display").dialog("close");
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>
    $(document).ready(function () {
        $("#Category_Details").dialog({autoOpen: false, width: '85%', height: 550, title: '<?php echo $Ward_Title; ?>~ TRANSACTIONS DETAILS', modal: true});
        $("#Sub_Category_Details").dialog({autoOpen: false, width: '85%', height: 550, title: '<?php echo $Ward_Title; ?>~ TRANSACTIONS DETAILS', modal: true});
        $("#Items_Details_Display").dialog({autoOpen: false, width: '85%', height: 550, title: '<?php echo $Ward_Title; ?>~ TRANSACTIONS DETAILS', modal: true});
    });
</script>
<?php
include("./includes/footer.php");
?>
