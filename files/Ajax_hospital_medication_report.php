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
    $filterbilltype='';
    @$fromDate =$_POST['fromDate'];
    @$toDate=$_POST['toDate'];
    @$Filter_Category=$_POST['Filter_Category'];
    @$diagnosis_time=$_POST['diagnosis_time'];
    @$medication_category=$_POST['medication_category'];
    @$start_age=$_POST['start_age'];
    @$end_age=$_POST['end_age'];
    @$Medication_ID=$_POST['Medication_ID'];
    @$diagnosis_type=$_POST['diagnosis_type'];
    @$Clinic_ID=$_POST['Clinic_ID'];
    $Gender = $_POST['Gender'];
    $filterGender='';
    if($Gender != ''){
        $filterGender =" AND Gender= '$Gender'";
    }else{
        $filterGender = '';
    }
    $filtermedication=" ";
	if($Medication_ID !='all'){
       $filtermedication =" AND ilc.Item_ID=$Medication_ID ";
    }
   
     $filterbyclinic="";
	if($Clinic_ID != 'all'){
     $filterbyclinic=" AND ilc.Clinic_ID='$Clinic_ID'";
    }else {
        $filterbyclinic ="";
    }

    

    
    if($medication_category=="opd_medication"){
        $filterbilltype=" AND Billing_Type LIKE '%Outpatient%' ";
    }else if($medication_category=="ipd_medication"){
        $filterbilltype=" AND Billing_Type LIKE '%Inpatient%'";
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
                <th style='width:5%;'>#</th>
                <th style='width:18%; text-align:center;'>Patient Name </th>
                <th style='width:15;'>Reg: #</th>
                <th style='width:5%;'>Age</th>
                <th style='width:5;'>Sex</th>
                <th style='width:20%;'>Sponsor </th>                
                <th style='width:25%;'>Diagnosis</th>   
                <th style='width:10%;'>View</th>             
             </tr>
           
         </thead>
         </tbody>";
         $num=1;
         $select_patient = mysqli_query($conn, "SELECT Gender,Patient_Name, pc.Sponsor_ID, Round_ID, pc.Registration_ID, (DATEDIFF(CURRENT_TIMESTAMP(),DATE(pr.Date_Of_Birth))/365.2425 ) AS Age, ilc.Payment_cache_ID, consultation_id FROM  tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_patient_registration pr WHERE  pc.Registration_ID = pr.Registration_ID  AND   ilc.Payment_cache_ID=pc.Payment_cache_ID AND Dispense_Date_Time BETWEEN '$fromDate' and '$toDate' $filtermedication $filterbilltype $filterbyclinic  $filterGender ") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_patient)>0){
            while($row = mysqli_fetch_assoc($select_patient)){
                $Gender =$row['Gender'];
                $Patient_Name = $row['Patient_Name'];
                $consultation_id = $row['consultation_id'];
                $Registration_ID = $row['Registration_ID'];
                $Round_ID = $row['Round_ID'];
                $Age = $row['Age'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor s, tbl_payment_cache pc  WHERE pc.Sponsor_ID=s.Sponsor_ID AND pc.Sponsor_ID='$Sponsor_ID' AND consultation_id='$consultation_id' AND Registration_ID='$Registration_ID' " ))['Guarantor_Name'];
                
                if($medication_category=="opd_medication"){
                    $filter_table=" tbl_disease_consultation dcw";
                    $filterdisease = " AND  dcw.consultation_id= pc.consultation_id AND pc.consultation_id='$consultation_id'"; 
                }else if($medication_category=="ipd_medication"){
                    $filter_table=" tbl_ward_round_disease dcw";
                    $filterdisease =" AND dcw.Round_ID =pc.Round_ID AND pc.Round_ID='$Round_ID'";
                }else{
                    $filter_table=" tbl_disease_consultation dcw";
                    $filterdisease = " AND  dcw.consultation_id= pc.consultation_id AND pc.consultation_id='$consultation_id'";
                }
              
                $select_diagnosis = mysqli_query($conn, "SELECT disease_code,disease_name  FROM tbl_disease d,tbl_payment_cache pc, $filter_table WHERE d.disease_ID=dcw.disease_ID AND  diagnosis_type='diagnosis' $filterdisease") or die(mysqli_error($conn));
                $added_disease='';
                if(mysqli_num_rows($select_diagnosis)>0){                   
                    while($disease_rows=mysqli_fetch_assoc($select_diagnosis)){
                        $disease_code=$disease_rows['disease_code'];
                        $disease_name=$disease_rows['disease_name'];
                        $added_disease .= "$disease_code, ";
                    }
                }
                echo "<tr >
                        <td >$num</td>
                        <td >$Patient_Name </td>
                        <td >$Registration_ID</td>
                        <td >".intval($Age)."</td>
                        <td >$Gender</td>
                        <td >$Guarantor_Name </td>                
                        <td >$added_disease</td>  
                        <td ><input type='button' class='art-button-green' value='INVESTIGATION DONE' onclick='open_investigation_result($Registration_ID, \"$consultation_id\")'></td>               
                    </tr>";
                    $num++;
            }
        }else{
            echo "<tr><td colspan='8'>No Result Found</td></tr>";
        }
       
        echo "</tbody></table>";

        
        
        
        
        



        // echo "<tr>  
        // <td id='tdalign'>$num</td>
        // <td style='text-align:left;'>$Product_Name</td>";
        // <td style='text-align:left;'>$Registration_ID</td>
        // <td style='text-align:left;'>$Patient_Name</td>

 // die("SELECT Gender,Patient_Name,  pc.consultation_id,SUM(Edited_Quantity ) AS Edited_Quantity,Round_ID, pc.Registration_ID, Product_Name, ilc.Payment_cache_ID FROM  tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_patient_registration pr, tbl_items i   WHERE i.Item_ID=ilc.Item_ID AND pc.Registration_ID = pr.Registration_ID  AND   ilc.Payment_cache_ID=pc.Payment_cache_ID AND Dispense_Date_Time BETWEEN '$fromDate' and '$toDate' $filtermedication $filterbilltype $filterbyclinic GROUP BY i.Item_ID");




        // waiteng confirmation 
        $select_item = mysqli_query($conn, "SELECT Gender, Patient_Payment_Item_List_ID, pp.Registration_ID, (DATEDIFF(CURRENT_TIMESTAMP(),DATE(pr.Date_Of_Birth))/365.2425 ) AS Age  FROM tbl_patient_payments pp,  tbl_patient_payment_item_list pil, tbl_patient_registration pr   WHERE pp.Registration_ID = pr.Registration_ID  AND Item_ID='$Item_ID' AND Gender='Male'  AND pp.Patient_Payment_ID= pil.Patient_Payment_ID AND Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY pp.Registration_ID AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_item)>0){

            while($row = mysqli_fetch_assoc($select_item)){
                $Price = ($row['Price']);
                $Gender = $row['Gender'];
                $male_count = $row['male_count'];
                $Quantitys = $row['Quantity'];                       
                
            }
        }

        $female_count=0;
        $select_item = mysqli_query($conn, "SELECT Gender, Patient_Payment_Item_List_ID, COUNT(pp.Registration_ID) AS female_count, Price  FROM tbl_patient_payments pp,  tbl_patient_payment_item_list pil, tbl_patient_registration pr   WHERE pp.Registration_ID = pr.Registration_ID  AND Item_ID='$Item_ID' AND Gender='Female'  AND pp.Patient_Payment_ID= pil.Patient_Payment_ID AND Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY pp.Registration_ID AND TIMESTAMPDIFF($diagnosis_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_item)>0){
            $Patients= mysqli_num_rows($select_item);
           
           
            while($row = mysqli_fetch_assoc($select_item)){
                $Price = ($row['Price']);
                $Gender = $row['Gender'];
                $female_count = $row['female_count'];
                $Quantitys = $row['Quantity'];                       
                
            }
        }
?>
</div>
