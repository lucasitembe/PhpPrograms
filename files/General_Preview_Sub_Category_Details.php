<?php
session_start();
include("./includes/connection.php");

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
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

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

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
    $Employee_ID = $_GET['Employee_ID'];
} else {
    $Employee_ID = '';
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
} else {
    $Hospital_Ward_ID = 'none';
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

if (isset($_GET['Patient_Type'])) {
    $Patient_Type = $_GET['Patient_Type'];
} else {
    $Patient_Type = '';
}

if (isset($_GET['Item_Subcategory_ID'])) {
    $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
} else {
    $Item_Subcategory_ID = 0;
}

if (isset($_GET['finance_department_id'])) {
    $finance_department_id = $_GET['finance_department_id'];
} else {
    $finance_department_id = '0';
}
//get finance department name
$finance_department_name="";
$sql_select_finance_department_name_result=mysqli_query($conn,"SELECT finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_finance_department_name_result)>0){
   $finance_department_name=mysqli_fetch_assoc($sql_select_finance_department_name_result)['finance_department_name']; 
}

//get category name
$select = mysqli_query($conn,"select Item_Subcategory_Name from tbl_item_subcategory where Item_Subcategory_ID = '$Item_Subcategory_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);
if ($no > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Item_Subcategory_Name = $data['Item_Subcategory_Name'];
    }
} else {
    $Item_Subcategory_Name = '';
}

//generate filter
$filter = "pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and ";
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
} else {
    $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
    $Clinic_Name = "ALL";
}
//dont complicate, understand slowly prog
if ($Patient_Type == 'Inpatient') {
    if (strtolower($Section) == 'cash') {
        $filter .= "Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre' and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "((Billing_Type = 'Inpatient Cash'  and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
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
        $filter .= "((Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
} else {
    if (strtolower($Section) == 'cash') {
        $filter .= "((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "Transaction_status = 'cancelled' and ";
    } else {
//        $filter .= "Transaction_status <> 'cancelled' and ";
          $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit' or Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
}

if ($Sponsor_ID != 0) {
    $filter .= "pp.Sponsor_ID = '$Sponsor_ID' and ";
}

if ($Employee_ID != 0) {
    $filter .= "pp.Employee_ID = '$Employee_ID' and ";
}
?>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;"><b>Start Date : </b></td>
            <td><?php echo $Start_Date; ?></td>
            <td style="text-align: right;"><b>End Date : </b></td>
            <td><?php echo $End_Date; ?></td>
            <td style="text-align: right;"><b>Report Type : </b></td>
            <td><?php echo $Report_Type; ?></td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>Sub Category : </b></td>
            <td><?php echo strtoupper($Item_Subcategory_Name); ?></td>
            <td style="text-align: right;"><b>Sponsor : </b></td>
            <td><?php echo strtoupper($Sponsor); ?></td>
            <td style="text-align: right;"><b>Employee Name : </b></td>
            <td><?php echo $Emp_Name; ?></td>			
        </tr>
        <tr>
            <td style="text-align: right;"><b>Clinic : </b></td>
            <td><?php echo strtoupper($Clinic_Name); ?></td>		
            <td style="text-align: right;"><b>Department : </b></td>
            <td><?php echo strtoupper($finance_department_name); ?></td>		
        </tr>            
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 320px; background-color: white;' id=''>
    <?php
    if (strtolower($Section) == 'cashcredit') {
        include("./General_Sub_Category_details_Cash_Credit.php");
    } else if (strtolower($Section) == 'cash') {
        include("./General_Sub_Category_details_Cash.php");
    } else if (strtolower($Section) == 'credit') {
        include("./General_Sub_Category_details_Credit.php");
    } else if (strtolower($Section) == 'cancelled') {
        include("./General_Sub_Category_details_Cancelled.php");
    } else {
        include("./General_Sub_Category_details_Credit.php");
    }
    ?>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: center; color: #0079AE;"><b><i>CLICK ITEM NAME TO VIEW DETAILS</i></b></td>
            <td width="25%" style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Items_Details('<?php echo strtolower($Section); ?>',<?php echo $Item_Subcategory_ID; ?>)">
                <input type="button" class="art-button-green" value="BACK" onclick="Close_Items_Details()">
            </td>
        </tr>
    </table>
</fieldset>