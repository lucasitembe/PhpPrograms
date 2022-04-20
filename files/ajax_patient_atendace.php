<?php
    include("includes/connection.php");
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;

    if(isset($_POST['filterData'])){
        $Patient_ID = trim($_POST['Registration_ID']);
        $Patient_Name = trim($_POST['Patient_Name']);
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);

        $Filter = "";
        if($Patient_Name != ""){
            $Filter = "AND tbl_patient_registration.Patient_Name = ".$Patient_Name;
        }

        if($Patient_ID != ""){
            $Filter = "AND  tbl_patient_registration.Registration_ID = ".$Patient_ID;
        }

        $output = "";
        $index = 1;
        $Patient_Number = 0;
        $query = "SELECT 'cache' as Status_From,tbl_item_list_cache.Payment_Cache_ID,tbl_items.Item_Subcategory_ID,tbl_patient_registration.Sponsor_ID,tbl_item_list_cache.Transaction_Date_And_Time,tbl_patient_registration.Patient_Name,tbl_item_list_cache.Patient_Payment_ID,tbl_patient_registration.Registration_ID,tbl_sponsor.Guarantor_Name,tbl_item_list_cache.Atendace_Number,tbl_item_list_cache.Atendace_Date FROM `tbl_payment_cache`,tbl_patient_registration,tbl_item_list_cache,tbl_items,tbl_sponsor
        WHERE tbl_item_list_cache.Payment_Cache_ID = tbl_payment_cache.Payment_Cache_ID AND tbl_items.Item_ID = tbl_item_list_cache.Item_ID AND
        tbl_payment_cache.Registration_ID = tbl_patient_registration.Registration_ID AND tbl_patient_registration.Sponsor_ID = tbl_sponsor.Sponsor_ID AND
        tbl_item_list_cache.Check_In_Type = 'Laboratory'AND tbl_item_list_cache.Service_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' and tbl_item_list_cache.Status = 'Paid' GROUP BY tbl_item_list_cache.Payment_Cache_ID ORDER BY tbl_item_list_cache.Atendace_Number";

        $query_result = mysqli_query($conn,$query) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $output .= '<tr>
                            <td>'.$index.'</td>
                            <td>'.$rows['Patient_Name'].'</td>
                            <td>'.$rows['Registration_ID'].'</td>
                            <td>'.$rows['Guarantor_Name'].'</td>
                            <td>'.$rows['Atendace_Number'].'</td>
                            <td>'.$rows['Atendace_Date'].'</td>';
                        if((int)$rows['Atendace_Number'] == 0){
                            $output .= '<td><input type="button" id="'.$rows['Payment_Cache_ID'].'"value="ACCEPT" class="art-button-green" onclick="getNumber(this)"></td>';
                        } 
                        else {
                           $output .= '<td><a href="laboratory_sample_collection_details.php?Status_From='.$rows['Status_From'].'&patient_id='.$rows['Registration_ID'].'&payment_id='.$rows['Payment_Cache_ID'].'&Required_Date='. substr($rows['Transaction_Date_And_Time'], 0,11).'&Date_From=&Date_To=&Sponsor=&subcategory_ID="><button style="background:green;">Check in</button></a></td>'; 
                        }
            $output .= '</tr>';
            $index ++;
        }
        $output .='<script>
                    </script>';

        echo($output);
    }

    if(isset($_POST['filterDataPatientWaiting'])){
        $Patient_ID = trim($_POST['Registration_ID']);
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);

        $Filter = "";

        if($Patient_ID != ""){
            $Filter = "AND  tbl_patient_registration.Registration_ID = ".$Patient_ID;
        }

        $output = "";
        $index = 1;
        $Patient_Number = 0;
        $query = "SELECT *, tbl_patient_registration.Patient_Name FROM `tbl_patient_atendace`,tbl_patient_registration WHERE tbl_patient_registration.Registration_ID = tbl_patient_atendace.Patient_ID AND tbl_patient_atendace.Atendace_Date BETWEEN '$Start_Date' AND '$End_Date' AND tbl_patient_atendace.Status = 'Old'";

        $query_result = mysqli_query($conn,$query) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $date1 = new DateTime($rows['Check_in_time']);
        		$date2 = new DateTime($rows['Atendace_Date']);
        		$diff = $date1 -> diff($date2);
        		$age = $diff->y." Years, ";
        		$age .= $diff->m." Months, ";
        		$age .= $diff->d." Days";
            $output .= '<tr>
                            <td>'.$index.'</td>
                            <td>'.$rows['Patient_Name'].'</td>
                            <td>'.$rows['Patient_ID'].'</td>
                            <td>'.$rows['Patient_Atendance_Number'].'</td>
                            <td>'.$rows['Atendace_Date'].'</td>
                            <td>'.$rows['Check_in_time'].'</td>
                            <td>'.$age.'</td>
                      </tr>';
            $index ++;
        }

        echo($output);
    }

    if(isset($_POST['getNumber'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);
        $Payment_Cache_ID = trim($_POST['Payment_Cache_ID']);
        $querys = "SELECT MAX(`Atendace_Number`) AS NUMBER FROM `tbl_item_list_cache` WHERE `Transaction_Date_And_Time` BETWEEN '$Start_Date' AND '$End_Date'";
        $query_result = mysqli_query($conn,$querys) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $Number = 1 + (int)$rows['NUMBER'];
            $query = "UPDATE `tbl_item_list_cache` SET `Atendace_Number`='$Number',`Atendace_Date`='$Current_Date_Time' WHERE `Payment_Cache_ID`='$Payment_Cache_ID'";
            $update = mysqli_query($conn,$query) or die(mysqli_error($conn));
            if($update){
                echo $Number;
            }else{
                echo 'Fail';
            }
        }
    }

    if(isset($_POST['getPatientNumber'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);
        $Patient_ID = trim($_POST['Patient_ID']);
        
        $querys = "SELECT MAX(`Patient_Atendance_Number`) AS Attendace_Number FROM `tbl_patient_atendace` WHERE `Status`='New' AND `Atendace_Date` BETWEEN '$Start_Date' AND '$End_Date'";
        $query_result = mysqli_query($conn,$querys) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $Number = 1 + (int)$rows['Attendace_Number'];
            
            if($Patient_ID == ""){
                $updateQuery = "INSERT INTO `tbl_patient_atendace`(`Patient_Atendance_Number`, `Status`) VALUES ('$Number','New')";
            }else{
                $updateQuery = "INSERT INTO `tbl_patient_atendace`(`Patient_ID`,`Patient_Atendance_Number`, `Status`) VALUES ('$Patient_ID','$Number','New')";
            }
            $update = mysqli_query($conn,$updateQuery) or die("notExist=>".mysqli_error($conn));
            if($update){
                echo (int)$Number;
            }else{
                echo 'Fail';
            }
        }
    }

    if(isset($_POST['filterAttendaceData'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);

        $output = "";
        $index = 1;
        $Patient_Number = 0;
        $query = "SELECT `Atendace_ID`,`Patient_Atendance_Number`, `Atendace_Date`,`Patient_ID` FROM `tbl_patient_atendace` WHERE `Status`='New' AND `Atendace_Date` BETWEEN '$Start_Date' AND '$End_Date'";

        $query_result = mysqli_query($conn,$query) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $output .= '<tr>
                            <td>'.$index.'</td>
                            <td>'.$rows['Patient_ID'].'</td>
                            <td>'.$rows['Patient_Atendance_Number'].'</td>
                            <td>'.$rows['Atendace_Date'].'</td>
                            <td><input type="button" id="'.$rows['Atendace_ID'].'"value="Check In" class="art-button-green" onclick="checkinPatient(this)"></td>
                      </tr>';
            $index ++;
        }
        echo($output);
    }

    
     if(isset($_POST['checkIfExist'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);
        $Patient_ID = trim($_POST['Patient_ID']);
        
        $querys = "SELECT Atendace_ID,Patient_ID FROM `tbl_patient_atendace` WHERE `Status`='New' AND `Atendace_Date` BETWEEN '$Start_Date' AND '$End_Date' AND Atendace_ID = '$Patient_ID'";
        $query_result = mysqli_query($conn,$querys) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            if(trim($rows['Patient_ID']) == ""){
                echo 'new=>'.trim($rows['Atendace_ID']);            
            }else{
                echo "return=>".trim($rows['Patient_ID'])."=>".trim($rows['Atendace_ID']);
            }
        }
    }
    
      if(isset($_POST['filterAttendaceDataToDoctor'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);

        $output = "";
        $index = 1;
        $Patient_Number = 0;
        $query = "SELECT `Atendace_ID`,`Patient_Atendance_Number`, `Check_in_time`,`Patient_ID` FROM `tbl_patient_atendace` WHERE `Status` IN ('Old','doctorpage') AND `Check_in_time` BETWEEN '$Start_Date' AND '$End_Date'";

        $query_result = mysqli_query($conn,$query) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $output .= '<tr>
                            <td>'.$index.'</td>
                            <td>'.$rows['Patient_ID'].'</td>
                            <td>'.$rows['Patient_Atendance_Number'].'</td>
                            <td>'.$rows['Check_in_time'].'</td>';
            
            if((int)$rows['Patient_Atendance_Number'] == 0){
                $output .= '<td><input type="button" id="'.$rows['Atendace_ID'].'"value="Chukua Namba" class="art-button-green" onclick="getPatientNumber(this)"></td>';
            }
            
            $output .= '</tr>';
            $index ++;
        }
        echo($output);
    }
    
    if(isset($_POST['getPatientNumberold'])){
        $Start_Date = trim($_POST['Date_From']);
        $End_Date = trim($_POST['Date_To']);
        $Atendace_ID = trim($_POST['Atendace_ID']);
        
        $querys = "SELECT MAX(`Patient_Atendance_Number`) AS Attendace_Number FROM `tbl_patient_atendace` WHERE `Status`='doctorpage' AND `Check_in_time` BETWEEN '$Start_Date' AND '$End_Date'";
        $query_result = mysqli_query($conn,$querys) or die(mysqli_error($conn));
        while($rows = mysqli_fetch_array($query_result)){
            $Number = 1 + (int)$rows['Attendace_Number'];
            $updateQuery = "UPDATE `tbl_patient_atendace` SET `Patient_Atendance_Number`='$Number',`Status`='doctorpage' WHERE `Atendace_ID`='$Atendace_ID'";
            $update = mysqli_query($conn,$updateQuery) or die("notExist=>".mysqli_error($conn));
            if($update){
                echo (int)$Number;
            }else{
                echo 'Fail';
            }
        }
    }
