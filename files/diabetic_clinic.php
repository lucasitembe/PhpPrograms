<?php
include("./includes/header.php");
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
?>
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>    
<script>
    
    function goBack(){
        window.history.back();
    }
</script>
  <?php
  $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;

   ?>
<fieldset style='height: 650px;overflow-y: scroll'> 
    <legend align="center" style="text-align:center"><b>DIABETIC CLINIC</b>
        <br/>
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header">
                    <!-- <h4 class="box-title">Previous Saved diabetic clinic data</h4> -->
                </div>
                <div class="box-body">
                    
                    <?php
                    $gender = strtolower($Gender);
                        if(isset($_GET['created_at'])){
                            $created_at = $_GET['created_at'];                        
                            $diabetes = mysqli_query($conn," SELECT * FROM diabetic_clinic 
                                                                WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at')") 
                                                                or die(mysqli_error($conn));

            if(mysqli_num_rows($diabetes)>0)  {  
            
        
        $html .= '
        <div class="table-responsive" width="100%">
                <style>
                    #headers{
                        background: #D7DBDD;
                    }
                </style>
                <table class="table table-responsive table-condensed table-hover ">
                    <thead>
                        <tr><th id="headers" colspan="19">DIABETES CLINIC</th></tr>
                        <tr>
                            <th>#:</th>
                            <th>HOSPITAL NO.</th>
                            <th>TYPE</th>
                            <th>Year of Diagnosis</th>
                            <th>Occupation</th>
                            <th>Closest Hospital</th>
                            <th>Physical activity</th>
                            <th>Kind of Treatment</th>
                            <th>Self-injecting</th>
                            <th>Special Needs</th>
                            <th>Body Weight</th>
                            <th>Height</th>
                            <th>BMI</th>
                            <th>RBG</th>
                            <th>BP</th>
                            <th>Other Diagnosis</th>
                            <th>Since</th>
                            <th>Treatment</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>';
                            $num = 1;                                               
                            while ($row = mysqli_fetch_array($diabetes)) {
                            $diabetic_clinic_no = $row["diabetic_clinic_no"];
                            $clinic_type = $row["clinic_type"];
                            $year_of_diagnosis = $row["year_of_diagnosis"];
                            $occupation = $row["occupation"];
                            $physical_activity = $row["physical_activity"];
                            $kind_of_treatment = $row["kind_of_treatment"];
                            $self_injecting = $row["self_injecting"];
                            $body_weight = $row["body_weight"];
                            $height = $row["height"];
                            $closest_hospital = $row["closest_hospital"];
                            $bim = $row["bim"];
                            $rbg = $row["rbg"];
                            $bp = $row["bp"];
                            $other_diagnosis = $row["other_diagnosis"];
                            $since = $row["since"];
                            $treatment = $row["treatment"];
                            $special_needs = $row["special_needs"];
                
    $html .= '          <tr>
                                <td><center>' .$num++; $html .= '</center></td>
                                <td><center>' .$diabetic_clinic_no; $html .= '</center></td>
                                <td><center>' .$clinic_type; $html .= '</center></td>
                                <td><center>' .$year_of_diagnosis; $html .= '</center></td>
                                <td><center>' .$occupation; $html .= '</center></td>
                                <td><center>' .$closest_hospital; $html .= '</center></td>
                                <td><center>' .$physical_activity; $html .= '</center></td>
                                <td><center>' .$kind_of_treatment; $html .= '</center></td>
                                <td><center>' .$self_injecting; $html .= '</center></td>
                                <td><center>' .$special_needs; $html .= '</center></td>
                                <td><center>' .$body_weight; $html .= '</center></td>
                                <td><center>' .$height; $html .= '</center></td>
                                <td><center>' .$bim; $html .= '</center></td>
                                <td><center>' .$rbg; $html .= '</center></td>
                                <td><center>' .$bp; $html .= '</center></td>
                                <td><center>' .$other_diagnosis; $html .= '</center></td>
                                <td><center>' .$since; $html .= '</center></td>
                                <td><center>' .$treatment; $html .= '</center></td>
                                <td><center>' .$created_at; $html .= '</center></td>
                        </tr>';
                    }
$html .='            </tbody>
                </table>    
        </div>  ';
 
}


$created_at = $_GET['created_at']; 

            $regular_check_result = mysqli_query($conn,"SELECT * FROM regural_check WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at')");
            if(mysqli_num_rows($regular_check_result)>0){
            
                $html .='<hr>
                        <table class="table table-bordered table-hover text-center table-hover">
                            <thead>
                                <tr><th id="headers" colspan="12" >Regular Check-up</th></tr>
                                <tr>
                                    <th>#:</th>
                                    <th>HB</th>
                                    <th>HbA1c</th>
                                    <th>Microalb</th>
                                    <th>BUN</th>
                                    <th>Crea</th>
                                    <th>ESR</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>';$num = 1;
                            while($rows = mysqli_fetch_assoc($regular_check_result)){
                                $hb = $rows["hb"];
                                $hba1c = $rows["hba1c"];
                                $microalb = $rows["microalb"];
                                $bun = $rows["bun"];
                                $crea = $rows["crea"];
                                $esr = $rows["esr"];
                                $created_at = $rows['created_at'];
                                
                                
                $html .='       <tr>
                                    <td> '.$num++; $html .='</td>                               
                                    <td> '.$hb; $html .=' </td>                                
                                    <td> '.$hba1c; $html .=' </td>                               
                                    <td> '.$microalb; $html .=' </td>                                
                                    <td> '.$bun; $html .=' </td>                                
                                    <td> '.$crea; $html .=' </td>                                
                                    <td> '.$esr; $html .=' </td>
                                    <td >'.$created_at; $html .='</td>
                                </tr>';
                            }
            $html .='         </tbody>
                        <table>
                ';
             }
             
             $created_at = $_GET['created_at']; 

             $fundoscopy_result = mysqli_query($conn, "SELECT * FROM tbl_fundoscopy WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at') ") or die(mysqli_error($conn));
             
             if(mysqli_num_rows($fundoscopy_result)> 0){

                $html .='<hr>
                        <table class="table table-bordered table-hover  text-center">
                            <thead>
                            <tr><th colspan="4" id="headers">FUNDOSCOPY</th></tr>
                            <tr>
                                <th>#.<th>
                                <th>Fundoscopy, Vibration test, and other special investigation. </th>
                                <th>Created at.</th>
                            </tr>
                            </thead>
                            <tbody>';$num =1;
                                while($fundoscopys = mysqli_fetch_assoc($fundoscopy_result)){
                                    $fundoscopy_test = $fundoscopys["fundoscopy_test"];
                                    $created_at = $fundoscopys['created_at'];
                                    
                $html .='
                                <tr>
                                    <td>' .$num++; $html .=' </td>
                                    <td>'.$fundoscopy_test; $html .=' </td>
                                    <td>' .$created_at; $html .='</td>                                    
                                </tr>';
                }$html .='
                            <tbody>
                        <table>
                ';
             }
            
             $created_at = $_GET['created_at']; 
             $diabetes_educ = mysqli_query($conn, "SELECT * FROM diabetes_education WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at')");
             if(mysqli_num_rows($diabetes_educ)> 0){
            
                $html .='<hr>
                        <table class="table table-bordered table-hover  text-center">
                            <thead>
                                <tr><th id="headers" colspan="16">Diabetic Education</th></tr>
                                <tr>
                                    <th>#:</th>
                                    <th>General</th>
                                    <th>Diet</th>
                                    <th>Injection Technique</th>
                                    <th>Urine Testing</th>
                                    <th>Hyper - Hypoglycemia</th>
                                   <th>Foot Care</th> 
                                   <th>Late Complication</th>
                                   <th>Drugs</th>
                                   <th>Created At.</th>
                                </th>
                            </thead>
                            <tbody>'; $num = 1;
                    while($education_diabetes = mysqli_fetch_assoc($diabetes_educ)){
                                    $general = $education_diabetes["general"];
                                    $diet = $education_diabetes["diet"];
                                    $injection_technigue = $education_diabetes["injection_technigue"];
                                    $urine_testing = $education_diabetes["urine_testing"];
                                    $hyper_hypoglycemic = $education_diabetes["hyper_hypoglycemic"];
                                    $foot_care = $education_diabetes["foot_care"];
                                    $late_complication = $education_diabetes["late_complication"];
                                    $drugs = $education_diabetes["drugs"];
                                    $created_at= $education_diabetes['created_at'];
                                   

                $html .='       <tr>
                                    <td> '.$num++; $html .='</td>                                     
                                    <td>'.$general; $html .=' </td>                                    
                                    <td>'.$diet; $html .=' </td>                                    
                                    <td>'.$injection_technigue; $html .=' </td>                                    
                                    <td>'.$urine_testing; $html .=' </td>                                    
                                    <td>'.$hyper_hypoglycemic; $html .=' </td>                                    
                                    <td>'.$foot_care; $html .=' </td>                                    
                                    <td>'.$late_complication; $html .=' </td>                                    
                                    <td>'.$drugs; $html .=' </td>
                                    <td>' .$created_at; $html .='</td>                                
                                </tr>';
                    }
                $html .='   </tbody>
                        <table>
                ';
             
            }
             $created_at = $_GET['created_at']; 
             $admission_clinic = mysqli_query($conn, "SELECT * FROM tbl_admission_clinic WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at')");
             if(mysqli_num_rows($admission_clinic)> 0){
             
               

                $html .='<hr>
                        <table class="table table-bordered table-hover text-center">
                            <tr><th id="headers" colspan="3">ADMISSIONS </th></tr>
                            <tr>
                                <th>#:</th>
                                <th>Diadgonosis</th>
                                <th>Created At</th>
                            <tr>';$num = 1;
                while($admission_clinics = mysqli_fetch_assoc($admission_clinic)){
                                $admission_clinic_test = $admission_clinics["admission_diagnosis"];
                                $created_at = $admission_clinics['created_at'];
                                

            $html .='       <tr>
                                <td> '.$num++; $html .='</td> 
                                <td> '.$admission_clinic_test; $html .=' </td>
                                <td> '.$created_at; $html .='</td> 
                            </tr>';
                              }   
  $html .='            <table>
                ';
             
            }

             $created_at = $_GET['created_at']; 
             $follow_up_visits = mysqli_query($conn, "SELECT * FROM follow_up_visit WHERE Registration_ID = '$Registration_ID' AND DATE(created_at) = DATE('$created_at') ");
          
             if(mysqli_num_rows($follow_up_visits)> 0){
            
               
                $html .='<hr>
                        <table class="table table-bordered table-hover  text-center">
                            <thead>
                                <tr><th id="headers" colspan="8">Follow-up Visits</th></tr>
                                <tr>
                                    <th>#:</th>
                                    <th>Bwt</th>
                                    <th>BP</th>
                                    <th>RBG</th>
                                    <th>Clinic Notes</th>
                                    <th>Created at.</th>
                                </tr>
                            </thead>
                            <tbody>';$num = 1;
            while($follow_up = mysqli_fetch_assoc($follow_up_visits)){
                            $bwt = $follow_up["bwt"];
                            $bp = $follow_up["bp"];
                            $rbg = $follow_up["rbg"];
                            $clinical_notes = $follow_up["clinical_notes"];
                            $created_at = $follow_up['created_at'];
                            
                    $html .='   <tr>
                                    <td> '.$num++; $html .='</td>                                     
                                    <td> '.$bwt; $html .=' </td>                                    
                                    <td> '.$bp; $html .=' </td>                                    
                                    <td> '.$rbg; $html .=' </td>                                    
                                    <td> '.$clinical_notes; $html .=' </td>
                                    <td>' .$created_at; $html .=' </td>
                                </tr>';
                                  }       
                $html .='   </tbody>
                        <table>
                ';
             
             }
            }
echo $html;
