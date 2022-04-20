<?php
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn, "
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $_GET['Registration_ID'] . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name = '';
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

$age = date_diff(date_create($DOB), date_create('today'))->y;

isset($_GET['Admision_ID']) ? $Admision_ID = $_GET['Admision_ID'] : $Admision_ID = '';
isset($_GET['consultation_ID']) ? $consultation_ID = $_GET['consultation_ID'] : $consultation_ID = '';
isset($_GET['Registration_ID']) ? $Registration_ID = $_GET['Registration_ID'] : $Registration_ID = '';
isset($_GET['Admision_ID']) ? $Admission_ID = $_GET['Admision_ID'] : $Admission_ID = '';

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $employeeName = $_SESSION['userinfo']['Employee_Name'];
} else {
    $employeeName = '';
}

?>

