<?php session_start();
include ("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '0';
}
if (isset($_GET['Item_ID'])) {
    $Item_ID = $_GET['Item_ID'];
} else {
    $Item_ID = '';
}
if (isset($_GET['Item_Description'])) {
    $Item_Description = $_GET['Item_Description'];
} else {
    $Item_Description = '';
}
if (isset($_GET['Amount'])) {
    $Amount = str_replace(',', '', $_GET['Amount']);
} else {
    $Amount = '';
}
if (isset($_GET['Quantity'])) {
    $Quantity = str_replace(',', '', $_GET['Quantity']);
} else {
    $Quantity = 1;
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
if (isset($_GET['Clinic_ID'])) {
    $Clinic_ID = $_GET['Clinic_ID'];
} else {
    $Clinic_ID = '';
}
if (isset($_GET['clinic_location_id'])) {
    $clinic_location_id = $_GET['clinic_location_id'];
} else {
    $clinic_location_id = '';
}
if (isset($_GET['clinic_location_id'])) {
    $clinic_location_id = $_GET['clinic_location_id'];
} else {
    $clinic_location_id = '';
}
if (isset($_GET['finance_department_id'])) {
    $finance_department_id = $_GET['finance_department_id'];
} else {
    $finance_department_id = '';
}
$Patient_Bill_ID = 0;
$Patient_Payment_ID = 0;
if (isset($_SESSION['userinfo'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Employee_ID = 0;
    $Branch_ID = 0;
}
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
if ($Registration_ID != null && $Registration_ID != '' && $Registration_ID != 0 && isset($_SESSION['userinfo'])) {
    $check = mysqli_query($conn, "SELECT Registration_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($check);
    if ($no == 0) {
        $slct = mysqli_query($conn, "SELECT Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slct);
        if ($nm == 0) {
            $inserts = mysqli_query($conn, "INSERT into tbl_check_in(Registration_ID, Visit_Date, Employee_ID, Check_In_Date_And_Time, Check_In_Status,
        								Branch_ID, Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In)
    									values ('$Registration_ID',(select now()),'$Employee_ID', (select now()),'saved',
    									'$Branch_ID',(select now()),(select now()),'Afresh')") or die(mysqli_error($conn));
        }
        $select_bill_id = mysqli_query($conn, "SELECT Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        if (mysqli_num_rows($select_bill_id) <= 0) {
            $insert = mysqli_query($conn, "INSERT into tbl_patient_bill(Registration_ID,Date_Time) values('$Registration_ID',(select now()))") or die(mysqli_error($conn));
            if ($insert) {
                $select = mysqli_query($conn, "SELECT Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' and Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select);
                if ($no > 0) {
                    while ($data = mysqli_fetch_array($select)) {
                        $Patient_Bill_ID = $data['Patient_Bill_ID'];
                    }
                    $insert2 = mysqli_query($conn, "INSERT into tbl_prepaid_details(Registration_ID,Employee_ID,Date_Time,Patient_Bill_ID)
                                                                                 values('$Registration_ID','$Employee_ID',(select now()),'$Patient_Bill_ID')") or die(mysqli_error($conn));
                }
            }
        } else {
            $Patient_Bill_ID = mysqli_fetch_assoc($select_bill_id) ['Patient_Bill_ID'];
            $check_for_pennding_bill_result = mysqli_query($conn, "SELECT Registration_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($check);
            if ($no <= 0) {
                $insert2 = mysqli_query($conn, "INSERT into tbl_prepaid_details(Registration_ID,Employee_ID,Date_Time,Patient_Bill_ID)
                                                                                 values('$Registration_ID','$Employee_ID',(select now()),'$Patient_Bill_ID')") or die(mysqli_error($conn));
            }
        }
    }
};
echo '	<table width="100%">
       <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td><b>ITEM DESCRIPTION</b></td>
            <td width="9%" style="text-align: right;"><b>QUANTITY</b></td>
            <td width="9%" style="text-align: right;"><b>AMOUNT</b></td>
            <td width="5%"></td>
        </tr>
';
mysqli_query($conn, "DELETE from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
$insert = mysqli_query($conn, "INSERT into tbl_direct_cash_cache(Item_ID, Item_Description, Registration_ID,Quantity,Employee_ID, Amount,Clinic_ID,finance_department_id,clinic_location_id)
							values('$Item_ID','$Item_Description','$Registration_ID','$Quantity','$Employee_ID','$Amount','$Clinic_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
$select = mysqli_query($conn, "SELECT dcc.Cache_ID, dcc.Item_Description, dcc.Amount, dcc.Quantity, i.Product_Name
							from tbl_direct_cash_cache dcc, tbl_items i where 
							dcc.Registration_ID = '$Registration_ID' and
							dcc.Item_ID = i.Item_ID and
							Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    $temp = 0;
    while ($data = mysqli_fetch_array($select)) {;
        echo '			<tr>
	            <td>';
        echo ++$temp;;
        echo '</td>
	            <td>';
        echo ucwords(strtolower($data['Product_Name']));;
        echo '</td>
	            <td>';
        echo $data['Item_Description'];;
        echo '</td>
                    <td>';
        echo (!empty($data['Quantity']) && $data['Quantity'] > 0) ? $data['Quantity'] : 1;
        echo '</td>
	            <td style="text-align: right;">';
        echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Amount'], 2) : number_format($data['Amount']));;
        echo '</td>
	            <td style="text-align: center;">
	            	<input type="button" value="X" onclick="Remove_Item(\'';
        echo $data['Product_Name'];;
        echo '\',';
        echo $data['Cache_ID'];;
        echo ');" style="color: red;">
	            </td>
	        </tr>
';
    }
};
echo '</table>';