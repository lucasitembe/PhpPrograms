<?php
@session_start();
include("./includes/connection.php");

if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_To = '';
}

if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}

$filter = "";
$filter2 = "";

if (isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != "" && $_GET['Patient_Number'] != null) {
    $Patient_Number = $_GET['Patient_Number'];
    $filter2 = " AND pr.Registration_ID = '$Patient_Number' ";
} else {
    $Patient_Number = "";
}

if(isset($_GET['Search_Value']) && $_GET['Search_Value'] != "" && $_GET['Search_Value'] != null) {
    $Search_Value = $_GET['Search_Value'];
    $filter = " AND pr.Patient_Name LIKE '%$Search_Value%' ";
} else {
    $Search_Value = "";
}

$filter = " $filter $filter2 ";

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}      


if (isset($_GET['Type_Of_Check_In'])) {
    $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
} else {
    $Type_Of_Check_In = 'All';
}

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
} else {
    $Employee_ID = '0';
}
?>
<link rel="stylesheet" href="fixHeader.css">
 

<legend style="background-color:#006400;color:white;padding:5px;" align='center'><b>LIST OF CHECKED IN PATIENTS</b></legend>
<table class="fixTableHead" width=100% id='myList'>
    <thead>
        <tr>
            <td width=5%><b>SN</b></td>
            <td width=15%><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>SPONSOR</b></td>
            <td width=10% style='text-align: center;'><b>PHONE#</b></td>
            <td width=10%><b>CHECK-IN TYPE</b></td>
            <td width=10%><b>VISIT TYPE</b></td>
            <td width=10%><b>CHECKED-IN DATE</b></td>
            <td width=10%><b>EMPLOYEE NAME</b></td>
            <td width="10%"><b>AUTHORIZATION NO</b></td>
        </tr>
    </thead>


    <?php
    $temp = 0;
    $sql = "SELECT sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, ci.AuthorizationNo,ci.visit_type, emp.Employee_Name
                                            from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                                            pr.Registration_ID = ci.Registration_ID and
                                            ci.Employee_ID = emp.Employee_ID and
                                            pr.Sponsor_ID = sp.Sponsor_ID and ";
    //get list of checked in patients
    if ($Type_Of_Check_In == 'All') {
        if ($Sponsor_ID == 0) {
            if ($Employee_ID == '0') {
                if (isset($_GET['Search_Value'])) {
                    // die($select);
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'  
                                                $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Employee_ID = '$Employee_ID' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            }
        } else {
            if ($Employee_ID == 0) {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'  
                                                $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Employee_ID = '$Employee_ID' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            }
        }
    } else {
        if ($Sponsor_ID == 0) {
            if ($Employee_ID == '0') {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            }
        } else {
            if ($Employee_ID == 0) {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Search_Value'])) {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and 
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In' $filter 
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                }
            }
        }
    }

    $num = mysqli_num_rows($select);

    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $visit_type = $row['visit_type'];
            if ($visit_type == 1) {
                $visit_type = "Normal visity";
            } else if ($visit_type == 2) {
                $visit_type = "Emegency";
            } else if ($visit_type == 3) {
                $visit_type = "Refferal";
            } else {
                $visit_type = "";
            }
            echo "<tr>
                    <td>" . ++$temp . "</td>
                    <td>" . $row['Patient_Name'] . "</td>
                    <td>" . $row['Registration_ID'] . "</td>
                    <td>" . $row['Guarantor_Name'] . "</td>
                    <td>" . $row['Phone_Number'] . "</td>
                    <td>" . $row['Type_Of_Check_In'] . "</td>
                    <td>" . $visit_type . "</td>
                    <td>" . $row['Check_In_Date_And_Time'] . "</td>
                    <td>" . $row['Employee_Name'] . "</td>
                    <td>" . $row['AuthorizationNo'] . "</td>
                </tr>";
        }
        echo "</table>";
        ?>


        <?php
    } else {
        // this for tracking a patient asiekuwa na sponsor wa aina hiyo
        if($Sponsor_ID != 0 && ($Patient_Number !="" || $Search_Value != "")) {
            $select_mgonjwa = mysqli_query($conn, "SELECT Patient_Name,Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID = '$Patient_Number' AND Patient_Name LIKE '%$Search_Value%'");
            $select_mgonjwa2 = mysqli_query($conn, "SELECT Patient_Name,Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID = '$Patient_Number' AND Patient_Name LIKE '%$Search_Value%'");
            // die($select_mgonjwa);
            $mgonjwa_available = mysqli_num_rows($select_mgonjwa);
            $Sponsor_ID = mysqli_fetch_assoc($select_mgonjwa)['Sponsor_ID'];

            // $check_if_not_discharged = mysqli_query($conn, "SELECT Admission_ID FROM ");
            $sponsor_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Guarantor_Name FROM `tbl_sponsor` WHERE Sponsor_ID = '$Sponsor_ID'"))['Guarantor_Name'];

            if ($mgonjwa_available > 0) {
                $Patient_Name = mysqli_fetch_assoc($select_mgonjwa2)['Patient_Name'];
                echo "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>The patient {$Patient_Name} found but with sponsor {$sponsor_name}.</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
            } else {
                echo "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>This patient is not registered in eHMS.</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
            }
        }
        else if($Patient_Number != "" && $Patient_Number != null && !(empty($Patient_Number))) {
            $select_mgonjwa = mysqli_query($conn, "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Patient_Number' AND Patient_Name LIKE '%$Search_Value%'");
            // die($select_mgonjwa);
            $mgonjwa_available = mysqli_num_rows($select_mgonjwa);

            // $check_if_not_discharged = mysqli_query($conn, "SELECT Admission_ID FROM ");

            if ($mgonjwa_available > 0) {
                $Patient_Name = mysqli_fetch_assoc($select_mgonjwa)['Patient_Name'];
                $find_if_still_admitted = mysqli_query($conn, "SELECT Admission_ID FROM `tbl_check_in_details` WHERE `Registration_ID`= '$Patient_Number' AND ToBe_Admitted = 'yes' AND Admit_Status = 'admitted' ORDER BY Check_In_Details_ID DESC LIMIT 1");
                $find_if_still_admitted_row = mysqli_num_rows($find_if_still_admitted);

                // $find_if_discharged = mysqli_query($conn, "SELECT Admission_ID FROM `tbl_check_in_details` WHERE `Registration_ID`= '$Patient_Number' AND ToBe_Admitted = 'yes' AND Admit_Status = 'discharged' ORDER BY Check_In_Details_ID DESC LIMIT 1");
                // $find_if_discharged_row = mysqli_num_rows($find_if_discharged);

                if ($find_if_still_admitted_row > 0) {
                    $Admission_ID = mysqli_fetch_assoc($find_if_still_admitted)['Admission_ID'];
                    $select_info = mysqli_query($conn, "SELECT Hospital_Ward_ID,Bed_Name,Admission_Date_Time,Admission_Employee_ID FROM `tbl_admission` WHERE `Admision_ID`='$Admission_ID' AND Admission_Status <> 'Discharged'");

                    while ($infos = mysqli_fetch_assoc($select_info)) {
                        $Hospital_Ward_ID = $infos['Hospital_Ward_ID'];
                        $Bed_Name = $infos['Bed_Name'];
                        $Admission_Date_Time = $infos['Admission_Date_Time'];
                        $Admission_Employee_ID = $infos['Admission_Employee_ID'];
                    }
                    if ($Hospital_Ward_ID != '' && $Hospital_Ward_ID != null && !(empty($Hospital_Ward_ID))) {
                        $Hospital_Ward_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'"))['Hospital_Ward_Name'];
                        $nurse_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Admission_Employee_ID'"))['Employee_Name'];

                        echo "
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>Patient with Number {$Patient_Number} ({$Patient_Name}) is already admitted by {$nurse_name} in ward {$Hospital_Ward_Name} , Bed: {$Bed_Name} at {$Admission_Date_Time}.</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>";
                    } else {
                        echo "
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>It seems {$Patient_Name} is not yet checked in. Please check in him first.</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>";
                    }
                } else {
                    echo "
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style='text-align: center;color: red;font-size: 20px;width: 50%;''><b>It seems {$Patient_Name} is not yet checked in. Please check in him first.</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>";
                }
            } else {
                echo "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>This patient is not registered in eHMS.</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
            }
        } else {
                echo "
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style='text-align: center;color: red;font-size: 20px;width: 50%;'><b>No data found.</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
        echo "</table>";
    }

    
    

    mysqli_close($conn);
?>
<!-- <td></td> -->
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
	$(document).ready(function() {
        $('#myList').DataTable({
            "bJQueryUI": true
        });
	});
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="css/jquery-ui.js"></script>
