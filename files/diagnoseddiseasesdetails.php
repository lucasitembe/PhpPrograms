<?php
    include("./includes/connection.php");     
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<?php
    //get disease detaits
    $filter = '';
$filterIn = '';
if (isset($_GET['Date_From']) && !empty($_GET['Date_From'])) {
    $fromDate = $_GET['Date_From'];
    $toDate = $_GET['Date_To'];
    $bill_type = $_GET['bill_type'];
    $disease_ID = $_GET['disease_ID'];
    $diagnosis_type = $_GET['diagnosis_type'];
    $start_age = $_GET['start_age'];
    $end_age = $_GET['end_age'];
    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' and  d.disease_ID='" . $disease_ID . "'  and  diagnosis_type='" . $diagnosis_type . "' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate'  and  d.disease_ID='" . $disease_ID . "'  and  diagnosis_type='" . $diagnosis_type . "' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'";
   
}
    
    
    
    $get_disease = mysqli_query($conn,"select disease_name from tbl_disease where disease_ID = '$disease_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_disease);
    if($no > 0){
        while($data = mysqli_fetch_array($get_disease)){
            $disease_name = $data['disease_name'];
        }
    }else{
        $disease_name = '';
    }
?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>

<center>
    <table width=90% border="0"   style='overflow-y: scroll; background-color:white;'>
        <tr>
            <td style='text-align: right;'><b>Disease Name ~ </b></td>
            <td><?php echo $disease_name; ?></td>
            <td style='text-align: right;' width=10%><b>Start Date ~ </b></td>
            <td style="text-align: left; border: 1px #ccc solid;width: 15%;">
                <?php echo $fromDate; ?>
            </td>
            <td style='text-align: right;' width=10%><b>End Date ~ </b></td>
            <td style="text-align: left; border: 1px #ccc solid;width: 15%">
                <?php echo $toDate; ?>
            </td>
        </tr>
    </table>
	
    <br/>
    <fieldset style="background-color:white; height:380px; overflow-y: scroll" id='Disease_Details'>
                <!--<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>DIAGNOSED DISEASES DETAILS</b></legend>-->
                <table width="100%"  style='overflow-y: scroll;height: 40px' border="1" id='Disease_Fieldset'>
                    <thead><tr>
                        <th width="7%">SN</th>
                        <th >PATIENT NAME</th>
                        <th >PATIENT#</th>
                        <th >GENDER</th>
                        <th >AGE</th>
                        <th >LOCATION</th>
                        <th >PATIENT TYPE</th>
                        <th >DR DIAGNOSED</th>
                    </tr></thead>
                    
                    <?php
                    $temp = 1;
                      
                    if($bill_type=='All'){
                        //Outpatient
                        $sql="select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, pr.Region
                            from tbl_disease_consultation dc, tbl_patient_registration pr, tbl_employee emp,
                                tbl_consultation con where
                            pr.Registration_ID = con.Registration_ID and
                            con.consultation_ID = dc.consultation_ID and
                            emp.Employee_ID = con.Employee_ID and
                            dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' and
                            TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age' and
                            dc.disease_ID = '$disease_ID' and
                            diagnosis_type = '$diagnosis_type' order by pr.Patient_Name";
                        
                        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $no = mysqli_num_rows($result);
                       
                            while($row = @mysqli_fetch_array($result)){
                                
                                //calculate age
                                $date1 = new DateTime($Today);
                                $date2 = new DateTime($row['Date_Of_Birth']);
                                $diff = $date1 -> diff($date2);
                                $age = $diff->y." Years, ";
                                $age .= $diff->m." Months, ";
                                $age .= $diff->d." Days";
                                
                                echo "<tr><td >".$temp."</td>";
                                echo "<td >".$row['Patient_Name']."</td>";
                                echo "<td style='text-align: left;'>".$row['Registration_ID']."</td>";
                                echo "<td style='text-align: left;'>".$row['Gender']."</td>";
                                echo "<td style='text-align: left;'>".$age."</td>";
                                echo "<td style='text-align: left;'>".$row['Region']."</td>";
                                echo "<td style='text-align: left;'>Outpatient</td>";
                                echo "<td style='text-align: left;'>".$row['Employee_Name']."</td>";
                                echo "</tr>";
                                $temp++;
                            }
                        //End Outpatient
                            
                            //Inpatient
                        $sql="select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, pr.Region
                            from tbl_ward_round_disease wd , tbl_patient_registration pr, tbl_employee emp,
                                tbl_ward_round wr where
                            
                            pr.Registration_ID = wr.Registration_ID and
                            wr.Round_ID = wd.Round_ID and
                            emp.Employee_ID = wr.Employee_ID and
                            wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and
                            TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age' and
                            wd.disease_ID = '$disease_ID' and
                            diagnosis_type = '$diagnosis_type' order by pr.Patient_Name";
                        
                        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $no = mysqli_num_rows($result);
                       
                            while($row = @mysqli_fetch_array($result)){
                                
                                //calculate age
                                $date1 = new DateTime($Today);
                                $date2 = new DateTime($row['Date_Of_Birth']);
                                $diff = $date1 -> diff($date2);
                                $age = $diff->y." Years, ";
                                $age .= $diff->m." Months, ";
                                $age .= $diff->d." Days";
                                
                                echo "<tr><td >".$temp."</td>";
                                echo "<td >".$row['Patient_Name']."</td>";
                                echo "<td style='text-align: left;'>".$row['Registration_ID']."</td>";
                                echo "<td style='text-align: left;'>".$row['Gender']."</td>";
                                echo "<td style='text-align: left;'>".$age."</td>";
                                echo "<td style='text-align: left;'>".$row['Region']."</td>";
                                echo "<td style='text-align: left;'>Inpatient</td>";
                                echo "<td style='text-align: left;'>".$row['Employee_Name']."</td>";
                                echo "</tr>";
                                $temp++;
                            }
                        //End Inpatient
                    }else if($bill_type=='Outpatient'){
                        //Outpatient
                        $sql="select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, pr.Region
                            from tbl_disease_consultation dc, tbl_patient_registration pr, tbl_employee emp,
                                tbl_consultation con where
                            
                            pr.Registration_ID = con.Registration_ID and
                            con.consultation_ID = dc.consultation_ID and
                            emp.Employee_ID = con.Employee_ID and
                            dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' and
                            TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age' and
                            dc.disease_ID = '$disease_ID' and
                            diagnosis_type = '$diagnosis_type' order by pr.Patient_Name";
                        
                        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $no = mysqli_num_rows($result);
                       
                            while($row = @mysqli_fetch_array($result)){
                                
                                //calculate age
                                $date1 = new DateTime($Today);
                                $date2 = new DateTime($row['Date_Of_Birth']);
                                $diff = $date1 -> diff($date2);
                                $age = $diff->y." Years, ";
                                $age .= $diff->m." Months, ";
                                $age .= $diff->d." Days";
                                
                                echo "<tr><td >".$temp."</td>";
                                echo "<td >".$row['Patient_Name']."</td>";
                                echo "<td style='text-align: left;'>".$row['Registration_ID']."</td>";
                                echo "<td style='text-align: left;'>".$row['Gender']."</td>";
                                echo "<td style='text-align: left;'>".$age."</td>";
                                echo "<td style='text-align: left;'>".$row['Region']."</td>";
                                echo "<td style='text-align: left;'>Outpatient</td>";
                                echo "<td style='text-align: left;'>".$row['Employee_Name']."</td>";
                                echo "</tr>";
                                $temp++;
                            }
                        //End Outpatient
                    }else if($bill_type=='Inpatient'){
                            //Inpatient
                        $sql="select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, pr.Region
                            from tbl_ward_round_disease wd , tbl_patient_registration pr, tbl_employee emp,
                                tbl_ward_round wr where
                            
                            pr.Registration_ID = wr.Registration_ID and
                            wr.Round_ID = wd.Round_ID and
                            emp.Employee_ID = wr.Employee_ID and
                            wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and
                            TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '$start_age' AND '$end_age' and
                            wd.disease_ID = '$disease_ID' and
                            diagnosis_type = '$diagnosis_type' order by pr.Patient_Name";
                        
                        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $no = mysqli_num_rows($result);
                       
                            while($row = @mysqli_fetch_array($result)){
                                
                                //calculate age
                                $date1 = new DateTime($Today);
                                $date2 = new DateTime($row['Date_Of_Birth']);
                                $diff = $date1 -> diff($date2);
                                $age = $diff->y." Years, ";
                                $age .= $diff->m." Months, ";
                                $age .= $diff->d." Days";
                                
                                echo "<tr><td >".$temp."</td>";
                                echo "<td >".$row['Patient_Name']."</td>";
                                echo "<td style='text-align: left;'>".$row['Registration_ID']."</td>";
                                echo "<td style='text-align: left;'>".$row['Gender']."</td>";
                                echo "<td style='text-align: left;'>".$age."</td>";
                                echo "<td style='text-align: left;'>".$row['Region']."</td>";
                                echo "<td style='text-align: left;'>Inpatient</td>";
                                echo "<td style='text-align: left;'>".$row['Employee_Name']."</td>";
                                echo "</tr>";
                                $temp++;
                            }
                        //End Inpatient
                    }
                        
                        
                       
                    ?>
                </table>
            </fieldset>

</center>
