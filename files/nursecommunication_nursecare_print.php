<?php
 include("./includes/connection.php");
 
        $temp = 1;
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }if(isset($_GET['start'])){
		$start_date = $_GET['start'];
    }
    if(isset($_GET['end'])){
            $end_date = $_GET['end'];
    } if(isset($_GET['consultation_ID'])){
            $consultation_ID = $_GET['consultation_ID'];
    }
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND DATE(Days)=DATE(NOW()) ORDER BY Days DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND Days BETWEEN '$start_date' AND '$end_date' ORDER BY Days DESC";
 }
 
 if(empty($start_date) && empty($end_date)){
     $betweenDate="<br/><br/>Today ".  date('Y-m-d');
 }else{
     $betweenDate="<br/><br/>FROM</b> " . $start_date . " <b>TO</b> " . $end_date ;
 }
 
  $select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
$age = date_diff(date_create($DOB), date_create('today'))->y;

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>NURSING CARE PLAN " .$betweenDate.""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
         . "</p>";


 $htm.= '<center><table width =100% border="0" id="nurse_care">';       
        $htm.= '<thead>
               <tr>
                <td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE</b></td>
                <td><b>NURSING DIAGNOSIS</b></td>
                <td><b>OBJ. OF CARE</b></td>
                <td><b>EXP. OUTCOME</b></td>
                <td><b>INTERVENTION</b></td>
                <td><b>EVALUATION</b></td>
                <td><b>PREP BY</b></td>
              </tr>
             </thead>   
                ';

  $select_testing_Qry = "SELECT Days,Nurse_Care,Objective_Care,Expect_Outcome,Nursing_Intervate,Evaluation,Employee_Name FROM tbl_patient_nursecare n JOIN tbl_employee e ON e.Employee_ID=n.employee_ID    WHERE $filter";
	
    $select_testing_record = mysqli_query($conn,$select_testing_Qry) or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_testing_record)){
                $htm.= "<tr ><td id='thead'>".$temp."</td>";
		$htm.= "<td style='width:10%'>".$row['Days']."</td>";
		$htm.="<td >".$row['Nurse_Care']."</td>";
	        $htm.="<td >".$row['Objective_Care']."</td>";
	        $htm.="<td >".$row['Expect_Outcome']."</td>";
		$htm.="<td >".$row['Nursing_Intervate']."</td>";	
		$htm.="<td >".$row['Evaluation']."</td>";
                $htm.="<td >".$row['Employee_Name']."</td>";
                $htm.= "</tr>";
                //$htm .=" <tr><td colspan='7'><hr width='100%'/></td><tr/>";
	 $temp++;
     } 
      $htm.= "</table></center>";




include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();
exit;
?>