<style type="text/css">
    .patientList td,th{
        text-align: center;
		font-size:15px;
		background-color:white;
    }
</style>
<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterClinic= ' ';
$filterIn = ' ';
$filterSub = ' ';
$filterDiagnosis=' ';
@$fromDate =$_POST['fromDate']; 
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$SubCategory = $_POST['SubCategory'];
@$search_top_n_diseases = $_POST['search_top_n_diseases'];
@$filter_top_n_diseases = $_POST['filter_top_n_diseases'];
@$opd_report_category=$_POST['opd_report_category'];
@$Clinic_ID=$_POST['Clinic_ID'];
@$diagnosis_type=$_POST['diagnosis_type'];
@$disease_report_type=$_POST['disease_report_type'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age'];

if($Clinic_ID!='all'){
    //$filter=" AND c.Clinic_ID=$Clinic_ID ";
    $filter=" WHERE Clinic_ID=$Clinic_ID ";
    $filterClinic=" AND Clinic_ID=$Clinic_ID ";
    $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
}
if($diagnosis_type!='all'){
    if($diagnosis_type=="differential"){
        $diagnosis_type="diferential_diagnosis";
    }else{
    $diagnosis_type=($diagnosis_type=="final")?"diagnosis":"provisional_diagnosis";
    }
    $filterDiagnosis=" AND dc.diagnosis_type IN ('$diagnosis_type')";
}else{
    $filterDiagnosis=" AND dc.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
}
//search for the top n diseases
if(trim($search_top_n_diseases)!=='' && $filter_top_n_diseases=='yes'){
    @$bill_type = $_POST['bill_type'];
    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' ";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' '";

        $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d where
                                    d.disease_ID = dc.disease_ID $filterDiagnosis $filter
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));
    $diseasesData=array();
    $sn=1;
    echo "<div style='background-color:white;font-size:12px;'>";
        echo "<table width='100%;' class='patientList'>";
            echo "<thead>";
            echo "<tr><th>SN</th><th>Disease Name</th><th>ICD</th><th>quantity</th></tr>";
            echo "</thead>";
            echo "<tbody>";

    while ($row = mysqli_fetch_array($result)) {
        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_consultation c,tbl_patient_registration pr where
                                    c.Registration_ID = pr.Registration_ID AND
                                    c.consultation_ID = dc.consultation_ID AND
                                    pr.Date_Of_Birth !='0000-00-00'  $filterDiagnosis $filterClinic and
                                    dc.disease_ID='" . $row['disease_ID'] . "' $filter"))['amount'];
        if($no_diagnosis == 0)continue;
        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));

    }
    array_multisort($diseasesData,SORT_DESC);
    if(mysqli_num_rows(($result))){
		$top_diseases=(mysqli_num_rows($result)<$search_top_n_diseases)? mysqli_num_rows($result):$search_top_n_diseases;
        for ($i=0; $i<$top_diseases; $i++) {
            echo "<tr><td>{$sn}</td><td style='text-align:left;'>{$diseasesData[$i]['disease_name']}</td><td style='text-align:center;'>{$diseasesData[$i]['disease_code']}</td><td style='text-align:center;'>{$diseasesData[$i]['final_quantity']}</td></tr>";
            $sn++;
        }
    }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    if($bill_type=="Inpatient"){

    }
}

//opd attendance
    if($opd_report_category==='opd_attendance' && $Filter_Category==='yes'){
    	 echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='4'>Clinic</th>
                <th style='width:40%;' colspan='6'><b>Number Of Patients</b></th>
                <th style='width:10%;' rowspan='4'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='6'>From Age $start_age -- To Age $end_age  </td>
             </tr>
             <tr>
                <td colspan='3'>new</td><td colspan='3'>return</td></tr>
             <tr>
                <td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td>
             </tr>
         </thead><tbody>";
		 $select_clinic=mysqli_query($conn,"SELECT Clinic_ID,Clinic_Name FROM tbl_clinic $filter");
		$totalAttendance=0;
		$totalNewAttendance=0;
		$totalReturnAttendance=0;
		$totalMaleNewAttendance=0;
		$totalFemaleNewAttendance=0;
		$totalMaleReturnAttendance=0;
		$totalFemaleReturnAttendance=0;

        $total_from_male_count_return=0;
		$total_from_female_count_return=0;
		// $total_to_age_male_count_return=0;
        // $total_to_age_female_count_return=0;

        $grand_total_from_male_count_new=0;
        $grand_total_from_male_count_return=0;
        $grand_total_from_female_count_new=0;
        $grand_total_from_female_count_return=0;
        // $grand_total_to_age_male_count_new=0;
        // $grand_total_to_age_male_count_return=0;
        // $grand_total_to_age_female_count_new=0;
        // $grand_total_to_age_female_count_return=0;

		while($row=mysqli_fetch_assoc($select_clinic)){
			$Clinic_ID=$row['Clinic_ID'];
			$Clinic_Name=$row['Clinic_Name'];

        $from_male_count_new=0;
        $from_female_count_new=0;
        // $to_age_male_count_new=0;
        // $to_age_female_count_new=0;

        $total_from_male_count_new=0;
        $total_from_male_count_return=0;
        $total_from_female_count_new=0;
        $total_from_female_count_return=0;
        // $total_to_age_male_count_new=0;
        // $total_to_age_male_count_return=0;
        // $total_to_age_female_count_new=0;
        // $total_to_age_female_count_return=0;

		$total_attendance=0;
         //new attendance
         $from_male_count_new=mysqli_num_rows(mysqli_query($conn,"SELECT c.Registration_ID FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Male' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= '$start_age' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= '$end_age' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1)
"));

         $from_female_count_new=mysqli_num_rows(mysqli_query($conn,"SELECT c.Registration_ID FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Female' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $start_age AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1)
"));
//          $to_age_male_count_new=mysqli_num_rows(mysqli_query($conn,"SELECT c.Registration_ID FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Male' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) = $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1)
// "));
//          $to_age_female_count_new=mysqli_num_rows(mysqli_query($conn,"SELECT c.Registration_ID FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Female' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) = $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1)
// "));

         //return attendance
         $from_male_count_return=mysqli_query($conn,"SELECT c.Registration_ID, count(c.Registration_ID) as count FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Male' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= '$start_age'  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= '$end_age' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1)
");		if($from_male_count_return)
            while ($row=mysqli_fetch_assoc($from_male_count_return)) {
                $total_from_male_count_return+=$row['count'];
            }
         $from_female_count_return=mysqli_query($conn,"SELECT c.Registration_ID, count(c.Registration_ID) as count FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Female'AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= '$start_age'  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= '$end_age' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1)
");	 if($from_female_count_return)
            while ($row=mysqli_fetch_assoc($from_female_count_return)) {
                $total_from_female_count_return+=$row['count'];
            }
//          $to_age_male_count_return=mysqli_query($conn,"SELECT c.Registration_ID, count(c.Registration_ID) as count FROM tbl_consultation c, tbl_patient_registration pr WHERE c.Registration_ID=pr.Registration_ID AND pr.Gender='Male' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) = $end_age GROUP BY Registration_ID HAVING ( COUNT(c.Registration_ID) > 1)
// ");	 	 if($to_age_male_count_return)
//             while ($row=mysqli_fetch_assoc($to_age_male_count_return)) {
//                 $total_to_age_male_count_return+=$row['count'];
//             }
//          $to_age_female_count_return=mysqli_query($conn,"SELECT c.Registration_ID, count(c.Registration_ID) as count FROM tbl_consultation c, tbl_patient_registration pr WHERE C.Registration_ID=pr.Registration_ID AND pr.Gender='Female' AND c.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND c.Clinic_ID=$Clinic_ID  AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) = $end_age GROUP BY Registration_ID HAVING ( COUNT(c.Registration_ID) > 1)
// ");	 	 if($to_age_female_count_return)
//             while ($row=mysqli_fetch_assoc($to_age_female_count_return)) {
//                 $total_to_age_female_count_return+=$row['count'];
//             }

            $select_=mysqli_query($conn,"SELECT Registration_ID FROM tbl_check_in WHERE Registration_ID NOT IN(SELECT Registration_ID FROM tbl_admission WHERE Admission_Date_Time BETWEEN '$fromDate' AND '$toDate') AND Check_In_Date_And_Time BETWEEN '$fromDate' AND '$toDate'");


			$total_attendance=$from_male_count_new+$from_female_count_new+$total_from_male_count_return+$total_from_female_count_return;
            echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList("."n".");'>".$Clinic_Name."</a></td>";
            echo "<td>".$from_male_count_new."</td>";
            echo "<td>".$from_female_count_new."</td>";
            echo "<td>".($from_male_count_new+$from_female_count_new)."</td>";
            echo "<td>".$total_from_male_count_return."</td>";
            echo "<td>".$total_from_female_count_return."</td>";
            echo "<td>".($total_from_male_count_return+$total_from_female_count_return)."</td>";
            // echo "<td>".$to_age_male_count_new."</td>";
            // echo "<td>".$to_age_female_count_new."</td>";
            // echo "<td>".($to_age_male_count_new+$to_age_female_count_new)."</td>";
            // echo "<td>".$total_to_age_male_count_return."</td>";
            // echo "<td>".$total_to_age_female_count_return."</td>";
            // echo "<td>".($total_to_age_male_count_return+$total_to_age_female_count_return)."</td>";
            echo "<td>".$total_attendance."</td>";
        echo "</tr>";
		$totalAttendance+=$total_attendance;
		$totalNewAttendance+=$from_male_count_new+$from_female_count_new;
		$totalReturnAttendance+=$total_from_male_count_return+$total_from_female_count_return;
		$totalMaleNewAttendance+=$from_male_count_new+$to_age_male_count_new;
		$totalFemaleNewAttendance+=$from_female_count_new+$to_age_female_count_new;
		$totalMaleReturnAttendance+=$total_from_male_count_return+$total_to_age_male_count_return;
		$totalFemaleReturnAttendance+=$total_from_female_count_return+$total_to_age_female_count_return;

		$grand_total_from_male_count_new+=$from_male_count_new;
        $grand_total_from_male_count_return+=$total_from_male_count_return;
        $grand_total_from_female_count_new+=$from_female_count_new;
        $grand_total_from_female_count_return+=$total_from_female_count_return;
        // $grand_total_to_age_male_count_new+=$to_age_male_count_new;
        // $grand_total_to_age_male_count_return+=$total_to_age_male_count_return;
        // $grand_total_to_age_female_count_new+=$to_age_female_count_new;
        // $grand_total_to_age_female_count_return+=$total_to_age_female_count_return;
	}
	echo"<tr><td colspan='14'><hr></td></tr>";
	echo "<tr><td style='text-align:left;'>Total</td><td>{$grand_total_from_male_count_new}</td><td>{$grand_total_from_male_count_return}</td><td>".($grand_total_from_male_count_new+$grand_total_from_male_count_return)."</td><td>{$grand_total_from_female_count_new}</td><td>{$grand_total_from_female_count_return}</td><td>".($grand_total_from_female_count_new+$grand_total_from_female_count_return)."</td><td>{$totalAttendance}</td></tr>";
	echo"<tr><td colspan='14'><hr></td></tr>";
        echo "</tbody></table>";
		echo "<br><br>";
		echo "<center><table width='60%' style='font-size:18px;background-color:white;' >";
		echo "<thead>";
		echo "<tr><th colspan='2'>OPD Attendance Summary</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		echo "<tr><td width='80%'>Total Attendance</td><td width='20%'>{$totalAttendance}</td></tr>";
		echo "<tr><td width='80%'>Total New Attendance</td><td width='20%'>".($totalNewAttendance)."</td></tr>";
		echo "<tr><td width='80%'>Total Return Attendance</td><td width='20%'>".($totalReturnAttendance)."</td></tr>";
		echo "<tr><td width='80%'>Total Male New Attendance</td><td width='20%'>".($totalMaleNewAttendance)."</td></tr>";
		echo "<tr><td width='80%'>Total Female New Attendance</td><td width='20%'>".($totalFemaleNewAttendance)."</td></tr>";
		echo "<tr><td width='80%'>Total Male Return Attendance</td><td width='20%'>".($totalMaleReturnAttendance)."</td></tr>";
		echo "<tr><td width='80%'>Total Female Return Attendance</td><td width='20%'>".($totalFemaleReturnAttendance)."</td></tr>";

    //this is commented due to inconsitence with the above data, once solved must be ucommented
    /*
		echo "<tr><th colspan='2'>Type Of Patients</th></tr>";

		$patient_type=mysqli_query($conn,"select ci.visit_type,count(ci.visit_type) as count from tbl_check_in ci, tbl_patient_registration pr where pr.Registration_ID=ci.Registration_ID AND pr.Date_Of_Birth !='0000-00-00' AND ci.Check_In_Date_And_Time BETWEEN '$fromDate' AND '$toDate' group by visit_type order by ci.visit_type ASC")or die(mysqli_error($conn));
		$total_normal=0;
		while($row=mysqli_fetch_assoc($patient_type)){
			if($row['visit_type']==1){
				$total_normal+=$row['count'];
			echo "<tr><td width='80%'>Normal</td><td width='20%'>".$row['count']."</td></tr>";
			}
			if($row['visit_type']==2)
			echo "<tr><td width='80%'>Emergency</td><td width='20%'>".$row['count']."</td></tr>";
			if($row['visit_type']==3)
			echo "<tr><td width='80%'>Referal</td><td width='20%'>".$row['count']."</td></tr>";
		} */
		echo "</tbody>";
		echo "</table></center>";
	}
	if($opd_report_category==='opd_diagnosis'){
    @$Disease_Cat_Id=$_POST['Disease_Cat_Id'];
    @$Clinic_ID=$_POST['Clinic_ID'];
    @$bill_type=$_POST['bill_type'];
    @$disease_report_type=$_POST['disease_report_type'];
    if($Clinic_ID!='all'){
        $filter=" AND c.Clinic_ID=$Clinic_ID ";
        $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
    }
	$filterDiseaseCategory=" ";
	if($Disease_Cat_Id!='all'){
        $filterDiseaseCategory=" AND dc.disease_category_ID=$Disease_Cat_Id ";
    }
 ?>

<?php if(isset($Filter_Category) && $Filter_Category=="yes"){?>
<div id="type1_report" style="display:none; background-color:white;">
<?php
    /********************This summurized report has been commented due to error evolving and currently it's function less*********** */

    // echo "<br> <hr><table width='100%' class='patientList'>";
    //     echo "<thead>
    //          <tr >
    //             <th style='width:50%;' rowspan='3'>Diagnosis</th>
    //             <th style='width:40%;' colspan='2'><b>Number Of Patients</b></th>
    //             <th style='width:10%;' rowspan='3'><b>Total</b></th>
    //          </tr>
    //          <tr>
    //             <td colspan='2'>Age from  $start_age --   $end_age (Yrs) </td>                
    //             <td></td>
    //          </tr>
             
    //          <tr>
    //             <td>Male</td><td>Female</td>
    //          </tr>
    //      </thead>";

    // //Feeding the data start
    // $diagnosisRow=4;
    
    // $total_from_male_count=0;
    // $total_from_female_count=0;
    // $total_male = 0;
    // $total_female =0;
    // $grand_total=0;
    // $num_count=1;
    // $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d, tbl_disease_consultation dco, tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND dco.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND dco.Disease_Consultation_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiseaseCategory ");
    //    while ($row=mysqli_fetch_assoc($select_diagnosis)){
    //     $disease_name=$row['disease_name'];
    //     $disease_ID=$row['disease_ID'];
    //     $total_males = 0;
    //     $total_females = 0;
        
    //     /*******************************Start of diagnosis attendance betwwen male and female****************** */
    //     $select_new = mysqli_query($conn, "SELECT COUNT(c.Registration_ID) AS total_patient, `Gender`, dc.disease_ID FROM `tbl_patient_registration` pr,tbl_disease_consultation dc,tbl_consultation c WHERE dc.disease_ID = '$disease_ID' AND pr.`Registration_ID`=c.`Registration_ID` AND dc.consultation_ID=c.consultation_ID AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >='$start_age' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())<='$end_age' $filterDiseaseCategory GROUP BY `Gender`") or die(mysqli_error($conn));
    //             while($result_data=mysqli_fetch_assoc($select_new)){
                    
    //                 if($result_data['Gender'] =='Male'){$total_males=$result_data['total_patient'];}
    //                 if($result_data['Gender'] =='Female'){$total_females=$result_data['total_patient'];}
                    
    //             }
    //     /*******************************End of diagnosis attendance betwwen male and female****************** */
    //     $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type,'diagnosis_type'=>$diagnosis_type);
    //     $patients_object=json_encode($patients_details);
    //     $subtotal = $male_count + $female_count;
	// 	if($subtotal===0){continue;}

    //     //display each row diagnosis attendance meshack modified
    //     echo "<tr>";
    //         echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");' style='display:block;'>".$num_count.". ".$disease_name."</a></td>";
    //         echo "<td>".$total_males."</td>";
    //         echo "<td>".$total_females.";;;;;;</td>";
    //         echo "<td>".($male_count+$female_count)."</td>";
    //         echo "<td>".($subtotal)."</td>";
    //     echo "</tr>";
    //     $patients_details=array();
    //         $total_male = 0;
    //         $total_female =0;
                
    //             $total_male += $male_count;
    //             $total_female+= $female_count;
    //             $num_count++;
    //     }
    //     //calculate grand total for diagnosis meshack modified
    //     $grand_total +=$total_male + $total_female;
    // //display total diagnosis attendance
    //     echo "<tr><td colspan='4'><hr></td></tr>";
    //     echo "<tr>";
    //         echo "<td style='text-align:left;'> Total </td>";
    //         echo "<td>".$total_male."</td>";
    //         echo "<td>".$total_female."</td>";           
    //         echo "<td>".$grand_total."</td>";
    //     echo "</tr>";
    //     echo "<tr><td colspan='4'><hr></td></tr>";
    //     echo "</table>";
    /*********end of comment of summusized report*******/ 
?>
</div>

<div id="type2_report" style="display:none;background:white;">
<?php

    echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='3'>Diagnosis </th>
                <th style='width:40%;' colspan='2'><b>Number Of Patients----</b></th>
                <th style='width:10%;' rowspan='3'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='2'>Age from  $start_age -- $end_age (Yrs) </td>                
             </tr>             
             <tr>
                <td>Male</td><td>Female</td>
             </tr>
         </thead>";

$sub_total_male = 0;
$sub_total_female = 0;
$grand_total=0;
$num_count=1;
$select_diagnosis=mysqli_query($conn,"SELECT d.disease_ID, d.disease_name FROM tbl_disease d, tbl_disease_consultation dco, tbl_disease_category dc, tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND dco.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND dco.Disease_Consultation_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiseaseCategory GROUP BY d.disease_ID") or die(mysqli_error($conn));
  $num_of_rows=mysqli_num_rows($select_diagnosis);  
  
  if($num_of_rows>0){
while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];

        $total_males = 0;
        $total_females = 0;
        
        /*******************************Start of diagnosis attendance betwwen male and female****************** */
        $select_new = mysqli_query($conn, "SELECT COUNT(c.Registration_ID) AS total_patient, `Gender`, dc.disease_ID FROM `tbl_patient_registration` pr,tbl_disease_consultation dc,tbl_consultation c WHERE dc.disease_ID = '$disease_ID' AND pr.`Registration_ID`=c.`Registration_ID` AND dc.consultation_ID=c.consultation_ID AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >='$start_age' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())<='$end_age' $filterDiseaseCategory GROUP BY `Gender`") or die(mysqli_error($conn));
                while($result_data=mysqli_fetch_assoc($select_new)){
                    
                    if($result_data['Gender'] =='Male'){$total_males=$result_data['total_patient'];}
                    if($result_data['Gender'] =='Female'){$total_females=$result_data['total_patient'];}
                    
                }
        /*******************************End of diagnosis attendance betwwen male and female****************** */
        $subtotal=$total_males +$total_females;
        if($subtotal===0){continue;}
        $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type,'diagnosis_type'=>$diagnosis_type);
        $patients_object=json_encode($patients_details);
        //display each row diagonsis attendance meshack modified
        echo "<tr >";
                echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'  style='display:block;'>".$num_count.". ".$disease_name."</a></td>";
                
                echo "<td>".$total_males."</td>";
                echo "<td>".$total_females."</td>";
                echo "<td>".($subtotal)."</td>";
        echo "</tr>";

                //  calculate subtotal for diagnosis
                $sub_total_male_count+=$total_males;
                $sub_total_females_count+=$total_females;
                $num_count++;
            }}
            //calculate grand total for diagnosis extended mode meshack modified
            $grand_total=$sub_total_male_count+$sub_total_females_count;
            //display the total row diagnosis
            echo "<tr><td colspan='4'><hr></td></tr>";
            echo "<tr>";
                echo "<td style='text-align:left;'> Total</td>";
                    echo "<td>".$sub_total_male_count+=$total_males."</td>";
                    echo "<td>".$sub_total_females_count+=$total_females."</td>";                
                    echo "<td>".$grand_total."</td>";
                echo "</tr>";
                echo "<tr><td colspan='4'><hr></td></tr>";
            echo "</table>";
            }
        }
       
?>
</div>
<script type="text/javascript">
    /*$('#disease_category').on('change',function(){
        alert('change');
    });*/
$('#clinic_report_type').on('change',function(){
        //alert('change '+$('#clinic_report_type').val());
    });
$('#disease_report_type').on('change',function(){
        if($("#disease_report_type").val()==='type1'){
            $("#type2_report").hide();
            $("#type1_report").show();
        }
        if($("#disease_report_type").val()==='type2'){
            $("#type1_report").hide();
            $("#type2_report").show();
        }
    });

</script>
<script type="text/javascript">
    $(document).ready(function(){
        if($("#disease_report_type").val()==='type1'){
            $("#type2_report").hide();
            $("#type1_report").show();
        }
        if($("#disease_report_type").val()==='type2'){
            $("#type1_report").hide();
            $("#type2_report").show();
        }
    });
</script>
