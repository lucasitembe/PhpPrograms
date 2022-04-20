<?php session_start();
include("./includes/connection.php");
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = '';
}
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}
if (isset($_GET['Reason_ID'])) {
    $Reason_ID = $_GET['Reason_ID'];
} else {
    $Reason_ID = 0;
}
if (isset($_GET['gender'])) {
    $gender = $_GET['gender'];
} else {
    $gender = '';
}
if ($gender == 0) {
    $gender_query = "";
    $gender = "All";
} else {
    $gender = $_GET['gender'];
    $gender_query = "AND pr.Gender='$gender'";
}
if ($Reason_ID != 0) {
    $select_reason = mysqli_query($conn, "SELECT Reason_Name FROM tbl_pf3_reasons WHERE Reason_ID = '$Reason_ID'");
    $Reason_Name = mysqli_fetch_assoc($select_reason)['Reason_Name'];
} else {
    $Reason_Name = "All";
}

if ($Date_From != '' && $Date_To != '') {;
    echo '
        <input type="button" value="Preview" style="float: right;" onclick="print();" class="art-button-green">
		<table width=100% id="myList"  class="daterange">
	    <thead>
            <tr>
                <td width=4%><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width=8%><b>PATIENT#</b></td>
                <td width=6%><b>GENDER</b></td>
                <td width=5%><b>AGE</b></td>
                <td width=10%><b>SPONSOR</b></td>
                <td width=8%><b>PHONE#</b></td>
                <td width=18%><b>REASON</b></td>
                <td width=15%><b>CHECKED IN DATE</b></td>
                <td width=10%><b>EMPLOYEE NAME</b></td>
            </tr>
		</thead>
	    ';
    $temp = 0;
    $Destination = '';
    $Today = date('Y-m-d');
    if ($Reason_ID == 0) {
        $select = mysqli_query($conn, "select pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,c.Check_In_Date_And_Time, pr.Registration_ID, Guarantor_Name, pr.Phone_Number, 
								p.Reason_Name, c.Visit_Date, emp.Employee_Name from
								tbl_check_in c, tbl_employee emp, tbl_patient_registration pr, 
								tbl_pf3_reasons p, tbl_sponsor sp, tbl_pf3_patients pp where 
								c.Check_In_ID = pp.Check_In_ID and
								pp.Registration_ID = pr.Registration_ID and
								c.Employee_ID = emp.Employee_ID and
								pp.Reason_ID = p.Reason_ID and
								sp.Sponsor_ID = pr.Sponsor_ID and
								c.Check_In_Date_And_Time between '$Date_From' and '$Date_To' $gender_query") or die(mysqli_error($conn));
    } else {
        $select = mysqli_query($conn, "select pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,c.Check_In_Date_And_Time, pr.Registration_ID, Guarantor_Name, pr.Phone_Number, 
								p.Reason_Name, c.Visit_Date, emp.Employee_Name from
								tbl_check_in c, tbl_employee emp, tbl_patient_registration pr, 
								tbl_pf3_reasons p, tbl_sponsor sp, tbl_pf3_patients pp where 
								c.Check_In_ID = pp.Check_In_ID and
								pp.Registration_ID = pr.Registration_ID and
								c.Employee_ID = emp.Employee_ID and
								pp.Reason_ID = p.Reason_ID and
								sp.Sponsor_ID = pr.Sponsor_ID and
								c.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
								p.Reason_ID = '$Reason_ID' $gender_query") or die(mysqli_error($conn));
    }
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
            if ($age == 0) {
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->m . " Months";     
            }
            if ($age == 0) {
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->d . " Days";
            };
            echo "<tr>
				    <td>" . ++$temp . "</td>
				    <td>" . $row['Patient_Name'] . "</td>
                                    <td>" . $row['Registration_ID'] . "</td>
                                    <td>" . $row['Gender'] . "</td>
				    <td>" . $age . "</td>
				    <td>" . $row['Guarantor_Name'] . "</td>
				    <td>" . $row['Phone_Number'] . "</td>
				    <td>" . $row['Reason_Name'] . "</td>
				    <td>" . $row['Check_In_Date_And_Time'] . "</td>
				    <td>" . $row['Employee_Name'] . "</td>
				</tr>";
        }
    };
    echo '	</table>

';
};
