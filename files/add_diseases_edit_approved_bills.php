<?php 
include("./includes/connection.php");
$Editor = $_POST['Editor'];
$Disease_ID = $_POST['Disease_ID'];
$diagnosis_type = $_POST['diagnosis_type'];
$Bill_ID = $_POST['Bill_ID'];
$getconsultnt = mysqli_query($conn, "SELECT Employee_Name, c.employee_ID  FROM tbl_patient_payments pp,  tbl_check_in_details cd, tbl_consultation c, tbl_employee e  WHERE pp.Check_In_ID=cd.Check_In_ID AND Bill_ID='$Bill_ID' AND c.consultation_ID=cd.consultation_ID AND c.employee_ID =e.Employee_ID") or die(mysqli_error($conn));
if(mysqli_fetch_assoc($getconsultnt)>0){
    while($row =mysqli_fetch_assoc($getconsultnt)){
        $Employee_Name = $row['Employee_Name'];
        $Editor = $row['employee_ID'];
    }
}else{
    $Editor =$Editor;
    $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = $Editor"))['Employee_Name'];
}

$addfolio =mysqli_query($conn,"INSERT INTO tbl_edited_folio_diseases(Disease_ID,	Disease_Code, diagnosis_type,	Consultant_ID, Consultation_Time, Consultant_Name, Bill_ID) VALUES($Disease_ID, (SELECT disease_code FROM tbl_disease WHERE Disease_ID = $Disease_ID), '$diagnosis_type', $Editor, (SELECT NOW()), '$Employee_Name',$Bill_ID)") or die(mysqli_error($conn));
	if($addfolio){
        echo 1;
    }else{
        echo 0;
    }
 ?>