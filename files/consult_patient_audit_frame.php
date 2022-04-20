<?php

@session_start();
include("./includes/connection.php");
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $Date_From = mysqli_real_escape_string($conn,$_GET['Date_From']);  //
    $Date_To = mysqli_real_escape_string($conn,$_GET['Date_To']);
    $processstatus = mysqli_real_escape_string($conn,$_GET['processstatus']);
} else {
    $employee_id = 0;
     $Date_From ='';  //
    $Date_To = '';
}

$temp = 1;

//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];


//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end

$data = '';


$selectEmployee = "SELECT Employee_Name FROM tbl_employee e WHERE Employee_ID='$employee_id'";
$empname = mysqli_fetch_assoc(mysqli_query($conn,$selectEmployee))['Employee_Name'];

$filter = "  AND ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To'  AND Saved='yes'";

if(!empty($processstatus) && $processstatus !='All'){
    if($processstatus=='Signed-Off'){
       $filter .= "  AND ppl.Process_Status = 'signedoff'";  
    }elseif ($processstatus=='Not-Signed-off') {
       $filter .= "  AND ppl.Process_Status != 'signedoff'";  
    }
   
}

$qr = "SELECT DISTINCT(Registration_ID) FROM tbl_consultation c 
       JOIN tbl_consultation_history ch ON  ch.Consultation_ID=c.Consultation_ID
       JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID
       WHERE  ch.employee_ID='$employee_id' $filter
         ";
//die($qr);
echo "<div class='daterange'>" . $empname . "<span> </span></div><br/>";
$temp = 1;
$select_Filtered_Patients_Reg = mysqli_query($conn,$qr) or die(mysqli_error($conn));
//$data .= (mysqli_num_rows($select_Filtered_Patients));exit;
while ($rowRegID = mysqli_fetch_array($select_Filtered_Patients_Reg)) {

    //GET PATIENTS
    $qrpatient = "SELECT pr.Registration_ID,ch.cons_hist_Date,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,s.Guarantor_Name,ppl.Patient_Payment_Item_List_ID,c.Consultation_ID FROM tbl_consultation c 
       JOIN tbl_consultation_history ch ON  ch.Consultation_ID=c.Consultation_ID
       JOIN tbl_patient_payment_item_list ppl ON  ppl.Patient_Payment_Item_List_ID=c.Patient_Payment_Item_List_ID
       JOIN tbl_patient_registration pr ON  pr.Registration_ID=c.Registration_ID
       JOIN tbl_sponsor s ON  s.Sponsor_ID=pr.Sponsor_ID
       WHERE  ch.employee_ID='$employee_id' AND c.Registration_ID='" . $rowRegID['Registration_ID'] . "' $filter
         ";

   // echo $qrpatient.'<br/>';

    $select_Filtered_Patients = mysqli_query($conn,$qrpatient) or die(mysqli_error($conn));

    if (mysqli_num_rows($select_Filtered_Patients) > 0) {

        $data .= '<table width ="100%" >';
        $data .= " <thead><tr ><th style='width:5%;'>SN</td><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>REG NUMBER</b></td>
                                <td><b>TRANS DATE</b></td>
				<td><b>SENT BY</b></td>
				</tr>
                                </thead>";

        while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
            //AGE FUNCTION
            $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
            // if($age == 0){
            $get_emp = mysqli_query($conn,"SELECT Employee_Name FROM  tbl_patient_payment_item_list pl 
                                 JOIN tbl_patient_payments pp ON pl.Patient_Payment_ID=pp.Patient_Payment_ID 
                                 JOIN tbl_employee e ON e.Employee_ID = pp.Employee_ID 
                                 WHERE pl.Patient_Payment_Item_List_ID ='" . $row['Patient_Payment_Item_List_ID'] . "'
                                 ") or die(mysqli_error($conn));

            $sent_by = mysqli_fetch_assoc($get_emp)['Employee_Name'];

            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

            $data .= "<tr><td>" . $temp . "</td>";
            $data .= "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
            $data .= "<td>" . $row['Guarantor_Name'] . "</td>";
            $data .= "<td>" . $age . "</td>";
            $data .= "<td>" . $row['Gender'] . "</td>";
            $data .= "<td>" . $row['Phone_Number'] . "</td>";
            $data .= "<td>" . $row['Registration_ID'] . "</td>";
            $data .= "<td>" . $row['Transaction_Date_And_Time'] . "</td>";
            $data .= "<td>" .$sent_by . "</td>";
            $data .= "</tr>";

            $temp++;
        }

        $data .= "</table><hr/>";
    }
}


echo $data;
