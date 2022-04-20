<?php
include("./includes/connection.php");	

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$employee_id = $_GET['employee_id'];
$Shift = $_GET['Shift'];
$Ward_ID = $_GET['Ward_ID'];
$Ward_Type = $_GET['Ward_Type'];
$filter = '';
if(!empty($fromDate) && !empty($toDate)){
    $filter = " AND dh.Saved_Date_Time BETWEEN '$fromDate' AND '$toDate'";
}else{
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $fromDate = $Filter_Value.' 00:00';
    $toDate = $Current_Date_Time;

    $filter = " AND dh.Saved_Date_Time BETWEEN '$fromDate' AND '$toDate'";

}

$Sub_Department_Name = $_SESSION['Admission'];

$qr = "SELECT Hospital_Ward_ID, Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                            $ward_results = mysqli_query($conn,$qr);
                            if(mysqli_num_rows($ward_results)>0){
                                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                                    
                                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";
    
                                }
                            }

if($Shift != 'All'){
    $filter .= " AND dh.select_round = '$Shift'";
}

if($Shift != 'All'){
    $filter .= " AND dh.select_round = '$Shift'";
}

if($Ward_ID != 'All'){
    $filter .= " AND dh.duty_ward = '$Ward_ID'";
}

if($Ward_Type != 'All'){
    $filter .= " AND dh.Ward_Type = '$Ward_Type'";
}

$display_table = '';
                    echo '<center><table width =100% border=0  class="display">';
                    $display_table .="<thead>
                    <tr>
                            <th  width='3%'>SN</th>
                            <th style='text-align: left;' width='20%'><b>Time Handled</th>
                            <th style='text-align: left;'>Ward Name</th>
                            <th style='text-align: left;'>Handled  by</th>
                            <th style='text-align: left;' width='20%'>Receiced By</th>
                            <th style='text-align: left;' width='10%'>Shift</th>
                            <th style='text-align: left;' width='10%'>Ward Type</th>
                            <th style='text-align: left;' width='20%'>Round Made</th>
                    </tr>
                     </thead>";
        
			$display_table.="</tr>";
				
			$query_data=mysqli_query($conn,"SELECT dh.duty_ID, dh.Ward_Type, em.Employee_Name, dh.current_nurse, hw.Hospital_Ward_Name, dh.major_round, dh.duty_nurse, dh.duty_ward, dh.select_round, dh.Saved_Date_Time, dh.duty_nurse FROM tbl_nurse_duties dh, tbl_hospital_ward hw, tbl_employee em WHERE em.Employee_ID = dh.current_nurse AND dh.Process_Status = 'Submitted' AND hw.Hospital_Ward_ID = dh.duty_ward $filter ORDER BY dh.duty_ID DESC");
			
			$Sn = 1;
            // if(mysqli_num_rows($query_data)>0){
			while($result_query=mysqli_fetch_assoc($query_data)){

                                                 $Nurse_Handing =$result_query['Employee_Name'];
                                                 $ward_name=$result_query['Hospital_Ward_Name'];
                                                 $Shift = $result_query['select_round'];
                                                 $duty_ID=$result_query['duty_ID'];
                                                 $Ward_Type = $result_query['Ward_Type'];
                                                 $duty_handled = $result_query['Saved_Date_Time'];
                                                 $duty_nurse = $result_query['duty_nurse'];
                                                 $major_round = $result_query['major_round'];
                                                 $current_nurse = $result_query['current_nurse'];

                                                 if($current_nurse > 0){
                                                     $Nurse_Previous = $Nurse_Handing;
                                                 }else{
                                                     $Nurse_Previous = $current_nurse;
                                                 }

                                                 
                   $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$duty_nurse'"))['Employee_Name'];
              
				$display_table.="
                        
				<tr> 
					<td style='text-align:center;'><a href='#'>".$Sn."</a></td>
                    <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$duty_handled."</a></td>
                    <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$ward_name."</a></td>
					<td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$Nurse_Previous."</a></td>
                    <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$employee."</a></td>
                    <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$Shift."</a></td>
                    <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$Ward_Type."</a></td>
                <td><a href='preview_notes_saved.php?NurseDuty=True&duty_ID=".$duty_ID."&Date_From=".$fromDate."&Date_To=".$toDate."'>".$major_round."</a></td></tr>";

                $Sn++;
			}
        // }else{
        //     $display_table.="
        //     <tr> 
        //         <td style='text-align:center;' colspan='8'>NO AVAILABLE WARD HANDLING RECORD</td>
        //     </tr>";
        // }
						
			$display_table.="</table>";
			
			echo $display_table;
            mysqli_close($conn);

   