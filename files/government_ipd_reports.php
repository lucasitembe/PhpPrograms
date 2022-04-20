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

	  $fromDate=mysqli_real_escape_string($conn,$_POST['fromDate']);
    $toDate=mysqli_real_escape_string($conn,$_POST['toDate']);
    @$start_age=mysqli_real_escape_string($conn,$_POST['start_age']);
    @$end_age=mysqli_real_escape_string($conn,$_POST['end_age']);
    $Ward_ID =mysqli_real_escape_string($conn,$_POST['Ward_ID']);
    @$diagnosis_type =mysqli_real_escape_string($conn,$_POST['diagnosis_type']);
    @$Disease_Cat_Id =mysqli_real_escape_string($conn,$_POST['Disease_Cat_Id']);
    @$ipd_report_category =mysqli_real_escape_string($conn,$_POST['ipd_report_category']);
    @$search_top_n_diseases = $_POST['search_top_n_diseases'];
    @$filter_top_n_diseases = $_POST['filter_top_n_diseases'];
    @$diagnosis_type=$_POST['diagnosis_type'];
	  @$Filter_Category=$_POST['Filter_Category'];
	  $filter = ' ';
    $filter_ward = " ";
    $selected_ward=$Ward_ID;
    if($Ward_ID != 'all'){
      $filter_ward =" WHERE hw.Hospital_Ward_ID = $Ward_ID ";
    }
    if($diagnosis_type!='all'){
    if($diagnosis_type=="differential"){
        $diagnosis_type="diferential_diagnosis";
    }else{
    $diagnosis_type=($diagnosis_type=="final")?"diagnosis":"provisional_diagnosis";
    }
    $filterDiagnosis=" AND wrd.diagnosis_type IN ('$diagnosis_type')";
}else{
    $filterDiagnosis=" AND wrd.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
}

//search for the top n diseases
if(trim($search_top_n_diseases)!=='' && $filter_top_n_diseases=='yes'){
    @$bill_type = $_POST['bill_type'];
    //$filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' ";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' '";

    $sqlinpatient = "SELECT DISTINCT d.disease_name, d.disease_ID, d.disease_code FROM tbl_ward_round wr, tbl_disease d, tbl_ward_round_disease wrd WHERE d.disease_ID = wrd.disease_ID AND wr.Round_ID = wrd.Round_ID AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' AND '$toDate'  ORDER BY d.disease_ID ";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqlinpatient) or die(mysqli_error($conn));
    $diseasesData=array();
    $sn=1;

    echo "<div style='background-color:white;font-size:12px;'>";
        echo "<table width='100%;' class='patientList'>";
            echo "<thead>";
            echo "<tr><th>SN</th><th>Disease Name</th><th>ICD</th><th>quantity</th></tr>";
            echo "</thead>";
            echo "<tbody>";

    while ($row = mysqli_fetch_array($result)){
        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(wrd.disease_ID) AS amount FROM tbl_ward_round wr, tbl_ward_round_disease wrd, tbl_patient_registration pr WHERE wr.Round_ID=wrd.Round_ID AND pr.Registration_ID = wr.Registration_ID AND pr.Date_Of_Birth !='0000-00-00' $filterDiagnosis  AND wrd.disease_ID='" . $row['disease_ID'] . "' $filter  AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' AND '$toDate'"))['amount'];

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
            if($diseasesData[$i]['final_quantity'] == 0)continue;
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

    if($ipd_report_category == 'ipd_attendance'){

        echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
                <tr >
                  <th style='width:10%;' rowspan='3'>#</th>
                  <th style='width:50%;' rowspan='3'>Ward</th>
                  <th style='width:40%;' colspan='2'><b>Number Of Patients</b></th>
                  <th style='width:10%;' rowspan='3'><b>Total</b></th>
                </tr>
                <tr>
                  <td colspan='2'>Age From $start_age - $end_age (Yrs) </td>                  
                </tr>
                
                <tr>
                  <td>Male</td><td>Female</td>
                </tr>
              </thead><tbody>";
              // store the sum of the wards results
            
            $total_total_male_count=0;
            $total_total_female_count=0;
            $grand_total_attendance=0;
              //store the attendance summery
            $sn=1;

            $total_attendance_male =0;
            $total_attendance_female =0;

      $select_wards=mysqli_query($conn,"SELECT hw.Hospital_Ward_ID, hw.Hospital_Ward_Name FROM  tbl_hospital_ward hw $filter_ward ");
      while ($ward_results=mysqli_fetch_assoc($select_wards)){
        $Ward_ID=$ward_results['Hospital_Ward_ID'];
        $Hospital_Ward_Name=$ward_results['Hospital_Ward_Name'];
        if($Hospital_Ward_Name == 'Mortuary')continue;
        
          $total_male_count=0;
          $total_female_count=0;
      	$total_attendance=0;


    	//new admission
    	$select_new_attendance=mysqli_query($conn,"SELECT Gender, pr.Registration_ID, ad.Admission_Date_Time FROM tbl_admission ad,tbl_patient_registration pr WHERE ad.Admission_Date_Time BETWEEN '$fromDate' AND '$toDate' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= '$start_age' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= '$end_age' AND pr.Registration_ID=ad.Registration_ID AND ad.Hospital_Ward_ID=$Ward_ID GROUP BY pr.Registration_ID ") or die(mysqli_error($conn));

    	while($result_data=mysqli_fetch_assoc($select_new_attendance)){
            
            // male attendance 
            if($result_data['Gender'] =='Male'){
                $total_male_count++;
            }
            // female attendance 
            if($result_data['Gender'] =='Female'){
                $total_female_count++;
            }
        } 

    	
            $total_attendance =$total_male_count + $total_female_count;
            $total_attendance_male +=total_male_count;
            $total_attendance_female +=total_female_count;

         echo "<tr>";
            echo "<td>".$sn++."</td>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList("."n".");'>".@$Hospital_Ward_Name."</a></td>";
            echo "<td>".$total_male_count."</td>";
            echo "<td>".$total_female_count."</td>";
            
            echo "<td>".$total_attendance."</td>";
        echo "</tr>";

      
        $total_total_male +=$total_male_count;
        $total_total_female +=$total_female_count;
        $grand_total_attendance += $total_male_count + $total_female_count;
      }
      if($selected_ward =='all'){
        echo "<tr><td><b>#</b></td><td><b>Total</b></td><td>$total_total_male </td><td>$total_total_female</td><td>{$grand_total_attendance}</td></tr>";
      }
      $total_attendance= $total_total_male + $total_total_female ;

      echo "</table><br><br>";
  		echo "<center><table width='60%' style='font-size:18px;background-color:white;' >";
  		echo "<thead>";
  		echo "<tr><th colspan='2'>IPD Attendance Summary</th></tr>";
  		echo "</thead>";
  		echo "<tbody>";
  		echo "<tr><td width='80%'>Total Attendance</td><td width='20%'>".$grand_total_attendance."</td></tr>";
  		echo "<tr><td width='80%'>Total Male Attendance</td><td width='20%'>".($total_total_male)."</td></tr>";
  		echo "<tr><td width='80%'>Total Female Attendance</td><td width='20%'>".($total_total_female)."</td></tr>";
  		
    }
if($ipd_report_category==='ipd_diagnosis'){
  @$Disease_Cat_Id=$_POST['Disease_Cat_Id'];
  @$Ward_ID=$_POST['$Ward_ID'];
  @$bill_type=$_POST['bill_type'];
  @$disease_report_type=$_POST['disease_report_type'];
  $filter =" ";
  //die("check ".$selected_ward);
  if($selected_ward != 'all'){
        $filter=" AND ad.Hospital_Ward_ID=$selected_ward ";
        $filterIn=" AND hw.Hospital_Ward_ID=$selected_ward ";
    }
    $filterDiseaseCategory=" ";
    if($Disease_Cat_Id!='all'){
        $filterDiseaseCategory=" AND dc.disease_category_ID=$Disease_Cat_Id ";
    }
//summurized report
 if(isset($Filter_Category) && $Filter_Category=="yes"){ ?>
<div id="type1_report" style="display:block; background-color:white;">
<?php
    echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='3'>Diagnosis</th>
                <th style='width:40%;' colspan='2'><b>Number Of Patients</b></th>
                <th style='width:10%;' rowspan='3'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='2'>Age From $start_age -- $end_age (Yrs) </td>
                
                <td></td></tr>
             <tr>
                <td>Male</td><td>Female</td>
             </tr>
         </thead>";
    $filterDiseaseCategory=" ";
    if($Disease_Cat_Id!='all'){
        $filterDiseaseCategory=" AND dc.disease_category_ID=$Disease_Cat_Id ";
    }
    
    //Feeding the data start
    $diagnosisRow=4;
    //Total counts
    $total_less_male_count=0;
    $total_less_female_count=0;
    $total_greater_male_count=0;
    $total_greater_female_count=0;
    $grand_total=0;
    $num_count=1;
    $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d,tbl_ward_round_disease wrd , tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND wrd.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiagnosis $filterDiseaseCategory ");
    //die(mysqli_num_rows($select_diagnosis));
       while ($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_name=$row['disease_name'];
        $disease_ID=$row['disease_ID'];
        $total_male_count=0;
        $total_female_count=0;
        
        /************************* diagosis attendance starts*************************/
        
        $query_result = mysqli_query($conn, "SELECT  Gender, pr.Registration_ID, wrd.Round_Disease_Date_And_Time, pr.Date_Of_Birth, wrd.disease_ID  FROM `tbl_patient_registration` pr, tbl_ward_round_disease wrd,tbl_ward_round wr WHERE pr.Registration_ID=wr.Registration_ID AND wrd.Round_ID=wr.Round_ID AND wrd.disease_ID='$disease_ID' AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= '$start_age' AND TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= '$end_age' $filterDiagnosis GROUP BY Gender,pr.Registration_ID");


        while($result_data=mysqli_fetch_assoc($query_result)){
            // male attendance 
            if($result_data['Gender'] =='Male'){
                $total_male_count++;
            }
            // female attendance 
            if($result_data['Gender'] =='Female'){
                $total_female_count++;
            }
            
        }

        $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Ward_ID'=>$Ward_ID,'patient_type'=>$bill_type,'diagnosis_type'=>$diagnosis_type);
        $patients_object=json_encode($patients_details);
        $subtotal=$total_male_count+$total_female_count;
        if($subtotal===0){continue;}

        //display each row diagnosis attendance
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");' style='display:block;'>".$num_count.". ".$disease_name."</a></td>";
            echo "<td>".$total_male_count."</td>";
            echo "<td>".$total_female_count."</td>";            
            echo "<td>".($subtotal)."</td>";
        echo "</tr>";
        $patients_details=array();

                //calculate subtotal for diagnosis
                $total_males_count+=$total_male_count;
                $total_females_count+=$total_female_count;
                // $total_greater_male_count+=$greater_male_count;
                // $total_greater_female_count+=$greater_female_count;
                $num_count++;
        }
        //calculate grand total for diagnosis
    $grand_total+=$total_males_count+$total_females_count;
    //display total diagnosis attendance
        echo "<tr><td colspan='4'><hr></td></tr>";
        echo "<tr>";
            echo "<td style='text-align:left;'> Total </td>";
            echo "<td>".$total_males_count."</td>";
            echo "<td>".$total_females_count."</td>";            
            echo "<td>".$grand_total."</td>";
        echo "</tr>";
        echo "<tr><td colspan='4'><hr></td></tr>";
        echo "</table>";
?>
</div>
<div id="type2_report" style="display:none;background:white;">
<?php

    echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='4'>Diagnosis</th>
                <th style='width:40%;' colspan='6'><b>Number Of Patients</b></th>
                <th style='width:10%;' rowspan='4'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='6'>Age From $start_age - $end_age (Yrs)</td>
                
             </tr>
             <tr><td colspan='3'>new</td><td colspan='3'>return</td></tr>
             <tr>
                <td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td>
             </tr>
         </thead>";
$sub_total_less_male_count_new=0;
$sub_total_less_male_count_return=0;
$sub_total_less_female_count_new=0;
$sub_total_less_female_count_return=0;
$sub_total_greater_male_count_new=0;
$sub_total_greater_male_count_return=0;
$sub_total_greater_female_count_new=0;
$sub_total_greater_female_count_return=0;

$grand_total_new=0;
$grand_total_return=0;
$grand_total=0;
$num_count=1;
$select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name FROM tbl_disease d,tbl_ward_round_disease wrd , tbl_disease_category dc,tbl_disease_subcategory ds WHERE dc.disease_category_ID=ds.disease_category_ID AND wrd.disease_ID=d.disease_ID AND d.subcategory_ID=ds.subcategory_ID AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' AND '$toDate' $filterDiagnosis $filterDiseaseCategory ");
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $total_less_male_count_new=0;
        $total_less_male_count_return=0;
        $total_less_female_count_new=0;
        $total_less_female_count_return=0;
        $total_greater_male_count_new=0;
        $total_greater_male_count_return=0;
        $total_greater_female_count_new=0;
        $total_greater_female_count_return=0;

        /******************** new diagonsis attendance  starts *****************************/
            $select_new=mysqli_query($conn,"SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) <= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_ward_round wr, tbl_ward_round_disease wrd,  tbl_patient_registration pr WHERE wr.Registration_ID IN(SELECT ci.Registration_ID FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID AND pr.Registration_Date = ci.Visit_Date AND   ci.Check_In_Date_And_Time BETWEEN '$fromDate' and '$toDate') AND wr.Registration_ID = pr.Registration_ID AND wr.Round_ID=wrd.Round_ID AND wrd.disease_ID=$disease_ID  $filterDiagnosis  AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' and '$toDate'  GROUP BY wr.Registration_ID") or die(mysqli_error($conn));

            while($result_data=mysqli_fetch_assoc($select_new)){
                //male new attendance age less than start_age
                if($result_data['sex'] =='less_male'){$total_less_male_count_new++;}
                //female new attendance age less than start_age
                if($result_data['sex'] =='less_female'){$total_less_female_count_new++;}
                //male new attendance age greater than end_age
                if($result_data['sex'] =='greator_male'){$total_greater_male_count_new++;}
                //female new attendance age greator than end_age
                if($result_data['sex'] =='greator_female'){$total_greater_female_count_new++;}
            }
            /****************************** new diagnosis attendance ends ****************************/

            /**************************** return diagnosis attendance ********************************/

            $select_return=mysqli_query($conn,"SELECT (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_ward_round wr, tbl_ward_round_disease wrd,  tbl_patient_registration pr WHERE wr.Registration_ID = pr.Registration_ID AND wr.Round_ID=wrd.Round_ID AND wrd.disease_ID=$disease_ID  $filterDiagnosis AND wrd.Round_Disease_Date_And_Time  BETWEEN '$fromDate' and '$toDate'") or die(mysqli_error($conn));

            while($result_data=mysqli_fetch_assoc($select_return)){
                //male return attendance age less than start_age
                if($result_data['sex'] =='less_male'){$total_less_male_count_return++;}
                //female return attendance age less than start_age
                if($result_data['sex'] =='less_female'){$total_less_female_count_return++;}
                //male return attendance age greater than end_age
                if($result_data['sex'] =='greator_male'){$total_greater_male_count_return++;}
                //female return attendance age greator than end_age
                if($result_data['sex'] =='greator_female'){$total_greater_female_count_return++;}
            }
            $total_less_male_count_return=$total_less_male_count_return-$total_less_male_count_new;
            $total_less_female_count_return=$total_less_female_count_return-$total_less_female_count_new;
            $total_greater_male_count_return=$total_greater_male_count_return-$total_greater_male_count_new;
            $total_greater_female_count_return=$total_greater_female_count_return-$total_greater_female_count_new;
            /*********************** diagnosis attendance end *******************************/

            $subtotal=$total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return;
            if($subtotal===0){continue;}
            $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Ward_ID'=>$Ward_ID,'patient_type'=>$bill_type,'diagnosis_type'=>$diagnosis_type);
            $patients_object=json_encode($patients_details);

            //display each row diagonsis attendance
            echo "<tr >";
                echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'  style='display:block;'>".$num_count.". ".$disease_name."</a></td>";
                echo "<td>".$total_less_male_count_new +$total_greater_male_count_new ."</td>";
                echo "<td>".$total_less_female_count_new +$total_greater_female_count_new."</td>";
                echo "<td>".($total_less_male_count_new+$total_less_female_count_new+$total_greater_male_count_new+$total_greater_female_count_new)."</td>";

                echo "<td>".$total_less_male_count_return +$total_greater_male_count_return."</td>";
                echo "<td>".$total_less_female_count_return +$total_greater_female_count_return."</td>";
                echo "<td>".($total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_return+$total_greater_female_count_return)."</td>";
               
                echo "<td>".($subtotal)."</td>";
            echo "</tr>";
            //  calculate subtotal for diagnosis
                $sub_total_less_male_count_new+=$total_less_male_count_new;
                $sub_total_less_female_count_new+=$total_less_female_count_new;
                $sub_total_less_male_count_return+=$total_less_male_count_return;
                $sub_total_less_female_count_return+=$total_less_female_count_return;
                $sub_total_greater_male_count_new+=$total_greater_male_count_new;
                $sub_total_greater_female_count_new+=$total_greater_female_count_new;
                $sub_total_greater_male_count_return+=$total_greater_male_count_return;
                $sub_total_greater_female_count_return+=$total_greater_female_count_return;
                $num_count++;
    }
    //calculate grand total for diagnosis
    $grand_total=$sub_total_less_male_count_new+$sub_total_less_male_count_return+$sub_total_less_female_count_new+$sub_total_less_female_count_return+$sub_total_greater_male_count_new+$sub_total_greater_male_count_return+$sub_total_greater_female_count_new+$sub_total_greater_female_count_return;
    //display the total row diagnosis
    echo "<tr><td colspan='8'><hr></td></tr>";
    echo "<tr>";
        echo "<td style='text-align:left;'> Total</td>";
        echo "<td>".$sub_total_less_male_count_new +$sub_total_greater_male_count_new."</td>";
        echo "<td>".$sub_total_less_female_count_new +$sub_total_greater_female_count_new."</td>";
        echo "<td>".($sub_total_less_male_count_new+$sub_total_less_female_count_new+$sub_total_greater_male_count_new+$sub_total_greater_female_count_new)."</td>";

        echo "<td>".$sub_total_less_male_count_return+$sub_total_greater_male_count_return."</td>";
        echo "<td>".$sub_total_less_female_count_return+$sub_total_greater_female_count_return."</td>";
        echo "<td>".($sub_total_less_male_count_return+$sub_total_less_female_count_return+$sub_total_greater_male_count_return+$sub_total_greater_female_count_return)."</td>";
        
            echo "<td>".$grand_total."</td>";
        echo "</tr>";
        echo "<tr><td colspan='8'><hr></td></tr>";
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
