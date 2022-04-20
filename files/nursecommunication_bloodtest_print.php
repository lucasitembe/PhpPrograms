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
        
 $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND DATE(date)=DATE(NOW()) ORDER BY date DESC";
 
 if(!empty($start_date) && !empty($end_date)){
     $filter=" Registration_ID='$Registration_ID' AND consultation_ID='".$consultation_ID."' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY date DESC";
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
$htm.="<p align='center'><b>BLOOD GLUCOSE TESTING RECORD " .$betweenDate.""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
         . "</p>";


$htm.= '<center><table width ="100%" class="vital" border="0" id="bloodtest">';
$htm.= '<thead>
        <tr> 
          <th style="width:5%;"><b>SN</b></th>
          <th ><b>Days</b></th>
	  <th ><b>Date & Time </b></th>
	  <th ><b>Type</b></th>
	  <th ><b>Meal Time</b></th> 
	  <th ><b>Glucose</b></th>
	  <th ><b>Notes</b></th>
        </tr>
        <tr>
          <td colspan="7"><hr width="100%"/></td>
        <tr/>
      </thead>';


	$testing_record = "SELECT * FROM tbl_testing_record  WHERE $filter";
	//die($testing_record);
		
	
    $select_testing_record = mysqli_query($conn,$testing_record) or die(mysqli_error($conn));  
    
    
    while($row = mysqli_fetch_array($select_testing_record)){
        $htm.= "<tr >
               <td>".$temp."</td>";
        $htm.= "<td >".$row['Days']."</td>";
        $htm.= "<td >".$row['date']."</td>";
        $htm.= "<td >".$row['type']."</td>";
        $htm.= "<td >".$row['meal']."</td>";
        $htm.= "<td >".$row['Glucose']."</td>";	
        $htm.= "<td >".$row['notes']."</td>";
        $htm.= "</tr>";
        $htm.= '<tr>
                    <td colspan="7"><hr width="100%"/></td>
                  <tr/>';
	 $temp++;
     }  

 $htm.= "</table></center>";




include("MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4-L');

$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;