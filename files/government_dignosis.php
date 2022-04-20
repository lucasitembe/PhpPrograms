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
@$diagnosis_time=$_POST['diagnosis_time'];
@$diagnosis_report_category=$_POST['diagnosis_report_category'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age'];
@$disease_category=$_POST['disease_category'];
@$diagnosis_type=$_POST['diagnosis_type'];
@$Clinic_ID=$_POST['Clinic_ID'];
@$diagnosis_report_case = $_POST['diagnosis_report_case'];
//@$Hospital_Ward_ID=$_POST['Hospital_Ward_ID'];

//Clinic_ID:Clinic_ID,Hospital_Ward_ID:Hospital_Ward_ID
@$Diagnosis_ID = $_POST['Diagnosis_ID'];

if($Diagnosis_ID !='All'){
	$filter3=" AND d.disease_ID='$Diagnosis_ID' ";
}else{
       $filter3="";
      
}
 $filterDiseaseCategory=" ";
	if($disease_category!='all'){
       $filterDiseaseCategory=" AND dc.disease_category_ID=$disease_category ";
    }
  $filterdiagnosis_type1="";
	if($diagnosis_type!='all'){
      $filterdiagnosis_type1=" AND dc.diagnosis_type='$diagnosis_type'";
    }else {
        $filterdiagnosis_type1 =" ";
    }
$filterdiagnosis_type2="";
	if($diagnosis_type!='all'){
     $filterdiagnosis_type2=" AND wrd.diagnosis_type='$diagnosis_type'";
    }else {
        $filterdiagnosis_type2 ="";
    }
$filterdiagnosis_type3="";
	if($Clinic_ID != 'all'){
     $filterdiagnosis_type3="AND c.Clinic_ID='$Clinic_ID'";
//      echo "clinic ndani";
    }else {
        $filterdiagnosis_type3 ="";
    }
    

    @$diagnosis_report_category=$_POST['diagnosis_report_category'];
 ?>

<?php 
if($diagnosis_report_category=="opd_diagnosis"){
    $filter_table=" tbl_disease_consultation dcw ";
    // if($diagnosis_report_case != 'all'){
        
    //     if($diagnosis_report_case=='newcase'){
    //         $filter_case = " AND NOT EXISTS (SELECT 1    FROM tbl_disease_consultation dcw, tbl_consultation c , tbl_patient_registration pr   WHERE c.consultation_ID = dcw.consultation_ID AND  dcw.disease_ID=$disease_ID AND c.Registration_ID = pr.Registration_ID AND dsw.Disease_Consultation_ID>dc.Disease_Consultation_ID)";
    //     }else{
    //         $filter_case='';
    //     }
    
    // }
}else if($diagnosis_report_category=="ipd_diagnosis"){
    $filter_table=" tbl_ward_round_disease dcw ";
    
}else{
    
}
?>
<div id="type1_report" style="display:none; background-color:white;">

</div>
<div id="type2_report" style="background:white;">
<?php

    echo "<br> <hr><table width='100%' class='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='4'>Diagnosis</th>
                <th style='width:10%;'  rowspan='4'>Diagnosis code</th>
                <th style='width:40%;' colspan='12'><b>Number Of Patients</b></th>
             </tr>
             <tr>
                <td colspan='12'> Age From $start_age To $end_age</td>
             </tr>
            <tr></tr>
             <tr>
              <td>Male</td><td>Female</td><td>Total</td>
             </tr>
         </thead>";

       
        $total_male_count=0;
        $total_female_count=0;
        $disease_name="";
         $disease_ID="";
$num_count=1;

if($diagnosis_report_category=="opd_diagnosis" || $diagnosis_report_category=="ipd_diagnosis"){
$select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name,d.disease_code FROM tbl_disease d, tbl_disease_category dc, tbl_disease_subcategory ds,$filter_table  WHERE dcw.disease_ID=d.disease_ID AND dc.disease_category_ID=ds.disease_category_ID AND d.subcategory_ID=ds.subcategory_ID $filterDiseaseCategory $filter3");
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $disease_code=$row['disease_code'];
        $male_count=0;
        $female_count=0;
            /****************************** new diagnosis attendance ends ****************************/

            /**************************** return diagnosis attendance ********************************/
            /*$select_return=mysqli_query($conn," SELECT COUNT(pr.Registration_ID) AS Registration_ID, (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 )");*/
        if($diagnosis_report_category=="opd_diagnosis"){
                   
            $query_result=mysqli_query($conn,"SELECT pr.Gender,  c.Registration_ID FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID $filterdiagnosis_type3 AND c.Registration_ID = pr.Registration_ID $filterdiagnosis_type1  AND dc.disease_ID=$disease_ID AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'  ");
            while($result_data=mysqli_fetch_assoc($query_result)){
                $result_data['Gender'];
                $Registration_ID = $result_data['Registration_ID'];
                if($diagnosis_report_case=='newcase'){

                $resultQuery = mysqli_query($conn, "SELECT  c.Registration_ID  FROM tbl_disease_consultation dcw, tbl_consultation c , tbl_patient_registration pr   WHERE c.consultation_ID = dcw.consultation_ID AND  dcw.disease_ID='$disease_ID' AND c.Registration_ID = pr.Registration_ID AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                    $numrows = mysqli_num_rows($resultQuery);
                    if($numrows>1){
                        continue;
                    }else{
                         // male attendance age less than  start_age
                        if(strtolower($result_data['Gender']) =='male'){
                            $male_count++;
                        }
                        // female attendance age less than  start_age
                        if(strtolower($result_data['Gender']) =='female'){
                            $female_count++;
                        }
                    }
                }else{
                    // male attendance age less than  start_age
                    if(strtolower($result_data['Gender']) =='male'){
                        $male_count++;
                    }
                    // female attendance age less than  start_age
                    if(strtolower($result_data['Gender']) =='female'){
                        $female_count++;
                    }
                }
            }
        }else if($diagnosis_report_category=="ipd_diagnosis"){
            $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID $filterdiagnosis_type2 AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'"); 
            while($result_data=mysqli_fetch_assoc($query_result)){
             
                 $result_data['Gender'];
                 $Registration_ID = $result_data['Registration_ID'];
                 if($diagnosis_report_case=='newcase'){ 
                 $resultQuery = mysqli_query($conn, "SELECT  wr.Registration_ID  FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr   WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID AND wr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                     $numrows = mysqli_num_rows($resultQuery);
                     if($numrows > 1){
                         continue;
                     }else{
                          // male attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='male'){
                             $male_count++;
                         }
                         // female attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='female'){
                             $female_count++;
                         }
                     }
                 }else{
                     // male attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='male'){
                         $male_count++;
                     }
                     // female attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='female'){
                         $female_count++;
                     }
                 }
         
        }
            
        }else{
//             AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'"); 
            
        }
       
            $subtotal=$male_count+$female_count;
			if($subtotal===0){continue;}
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\",\"$diagnosis_report_case\");'  style='display:block;'>".$num_count.". ".$disease_name."</a></td>";
            echo "<td>".$disease_code."</td>";
            echo "<td>".$male_count."</td>";
            echo "<td>".$female_count."</td>";
            echo "<td>".($female_count+$male_count)."</td>";
        echo "</tr>";
                $total_male_count+=$male_count;
                $total_female_count+=$female_count;
                $num_count++;
        }
}else{
    
    $diagnosis_report_category="opd_diagnosis";
    $filter_table="tbl_disease_consultation dcw";
    
    $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name,d.disease_code FROM tbl_disease d, tbl_disease_category dc, tbl_disease_subcategory ds,$filter_table  WHERE dcw.disease_ID=d.disease_ID AND dc.disease_category_ID=ds.disease_category_ID AND d.subcategory_ID=ds.subcategory_ID $filterDiseaseCategory $filter3");
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $disease_code=$row['disease_code'];
        $male_count=0;
        $female_count=0;
            /****************************** new diagnosis attendance ends ****************************/

            /**************************** return diagnosis attendance ********************************/
            /*$select_return=mysqli_query($conn," SELECT COUNT(pr.Registration_ID) AS Registration_ID, (CASE WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'male' ) THEN 'greator_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'male' ) THEN 'less_male' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age AND pr.Gender = 'female' ) THEN 'greator_female' WHEN (TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age AND pr.Gender = 'female' ) THEN 'less_female' END)AS sex FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID  $filterDiagnosis $filter AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 )");*/
        if($diagnosis_report_category=="opd_diagnosis"){

            $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID $filterdiagnosis_type3 AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID $filterdiagnosis_type1 AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ");
        
             while($result_data=mysqli_fetch_assoc($query_result)){
                 $result_data['Gender'];
                 $Registration_ID = $result_data['Registration_ID'];
                 if($diagnosis_report_case=='newcase'){
 
                 $resultQuery = mysqli_query($conn, "SELECT  c.Registration_ID  FROM tbl_disease_consultation dcw, tbl_consultation c , tbl_patient_registration pr   WHERE c.consultation_ID = dcw.consultation_ID AND  dcw.disease_ID='$disease_ID' AND c.Registration_ID = pr.Registration_ID AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                     $numrows = mysqli_num_rows($resultQuery);
                     if($numrows>1){
                         continue;
                     }else{
                          // male attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='male'){
                             $male_count++;
                         }
                         // female attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='female'){
                             $female_count++;
                         }
                     }
                 }else{
                     // male attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='male'){
                         $male_count++;
                     }
                     // female attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='female'){
                         $female_count++;
                     }
                 }
         
            }
        }else if($diagnosis_report_category=="ipd_diagnosis"){
            $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID $filterdiagnosis_type2 AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'"); 
            while($result_data=mysqli_fetch_assoc($query_result)){
                 $result_data['Gender'];
                 $Registration_ID = $result_data['Registration_ID'];
                 if($diagnosis_report_case=='newcase'){
 
                 $resultQuery = mysqli_query($conn, "SELECT  wr.Registration_ID  FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr   WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID AND wr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                     $numrows = mysqli_num_rows($resultQuery);
                     if($numrows > 1){
                         continue;
                     }else{
                          // male attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='male'){
                             $male_count++;
                         }
                         // female attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='female'){
                             $female_count++;
                         }
                     }
                 }else{
                     // male attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='male'){
                         $male_count++;
                     }
                     // female attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='female'){
                         $female_count++;
                     }
                 }
         
        }
            
        }else{    
        }
       
            $subtotal=$male_count+$female_count;
			if($subtotal===0){continue;}
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\",\"$diagnosis_report_case\");'  style='display:block;'>".$num_count.". ".$disease_name."~~~OPD</a></td>";
            echo "<td>".$disease_code."</td>";
            echo "<td>".$male_count."</td>";
            echo "<td>".$female_count."</td>";
            echo "<td>".($female_count+$male_count)."</td>";
        echo "</tr>";
                $total_male_count+=$male_count;
                $total_female_count+=$female_count;
                $num_count++;
        }
        
        
        
        
        
        
        
//        vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
        $diagnosis_report_category="ipd_diagnosis";
    $filter_table="tbl_ward_round_disease dcw";
        $select_diagnosis=mysqli_query($conn,"SELECT DISTINCT d.disease_ID, d.disease_name,d.disease_code FROM tbl_disease d, tbl_disease_category dc, tbl_disease_subcategory ds,$filter_table  WHERE dcw.disease_ID=d.disease_ID AND dc.disease_category_ID=ds.disease_category_ID AND d.subcategory_ID=ds.subcategory_ID $filterDiseaseCategory $filter3");
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $disease_code=$row['disease_code'];
        $male_count=0;
        $female_count=0;
            /****************************** new diagnosis attendance ends ****************************/

            /**************************** return diagnosis attendance ********************************/
        if($diagnosis_report_category=="opd_diagnosis"){
            
            $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_disease_consultation dc, tbl_consultation c, tbl_patient_registration pr WHERE c.consultation_ID = dc.consultation_ID $filterdiagnosis_type3 AND c.Registration_ID = pr.Registration_ID AND dc.disease_ID=$disease_ID $filterdiagnosis_type1 AND c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'");
        
             while($result_data=mysqli_fetch_assoc($query_result)){
                 $result_data['Gender'];
                 $Registration_ID = $result_data['Registration_ID'];
                 if($diagnosis_report_case=='newcase'){
 
                 $resultQuery = mysqli_query($conn, "SELECT  c.Registration_ID  FROM tbl_disease_consultation dcw, tbl_consultation c , tbl_patient_registration pr   WHERE c.consultation_ID = dcw.consultation_ID AND  dcw.disease_ID='$disease_ID' AND c.Registration_ID = pr.Registration_ID AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                     $numrows = mysqli_num_rows($resultQuery);
                     if($numrows>1){
                         continue;
                     }else{
                          // male attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='male'){
                             $male_count++;
                         }
                         // female attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='female'){
                             $female_count++;
                         }
                     }
                 }else{
                     // male attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='male'){
                         $male_count++;
                     }
                     // female attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='female'){
                         $female_count++;
                     }
                 }
         
        }
        }else if($diagnosis_report_category=="ipd_diagnosis"){
            $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID $filterdiagnosis_type2 AND wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'"); 
         while($result_data=mysqli_fetch_assoc($query_result)){
                 $result_data['Gender'];
                 $Registration_ID = $result_data['Registration_ID'];
                 if($diagnosis_report_case=='newcase'){
 
                 $resultQuery = mysqli_query($conn, "SELECT  wr.Registration_ID  FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr   WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID AND wr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                     $numrows = mysqli_num_rows($resultQuery);
                     if($numrows > 1){
                         continue;
                     }else{
                          // male attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='male'){
                             $male_count++;
                         }
                         // female attendance age less than  start_age
                         if(strtolower($result_data['Gender']) =='female'){
                             $female_count++;
                         }
                     }
                 }else{
                     // male attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='male'){
                         $male_count++;
                     }
                     // female attendance age less than  start_age
                     if(strtolower($result_data['Gender']) =='female'){
                         $female_count++;
                     }
                 }
         
        }
            
        }else{
//             AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'"); 
            
        }
       
            $subtotal=$male_count+$female_count;
			if($subtotal===0){continue;}
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\", \"$diagnosis_report_case\");'  style='display:block;'>".$num_count.". ".$disease_name."~~~IPD</a></td>";
            echo "<td>".$disease_code."</td>";
            echo "<td>".$male_count."</td>";
            echo "<td>".$female_count."</td>";
            echo "<td>".($female_count+$male_count)."</td>";
        echo "</tr>";
                $total_male_count+=$male_count;
                $total_female_count+=$female_count;
                $num_count++;
        }
    
}
        echo "<tr><td colspan='8'><hr></td></tr>";
        echo "<tr>";
            echo "<td style='text-align:left;' colspan='2'> Total </td>";
            echo "<td>".( $total_male_count)."</td>";
            echo "<td>".( $total_female_count)."</td>";
            echo "<td>".( $total_male_count+$total_female_count)."</td>";
        echo "</tr>";
        echo "<tr><td colspan='8'><hr></td></tr>";
        echo "</table>";
?>
</div>
