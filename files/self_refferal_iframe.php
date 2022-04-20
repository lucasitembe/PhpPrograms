<?php
include("./includes/connection.php");
    if(isset($_POST['Region_Name'])){
        $Region_Name = $_POST['Region_Name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $Vid = $_POST['Vid'];
        
        //Male Selection++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        echo '
        <div class="col-md-6">
        <table class="table table-borderd" style="background-color:#fff">
            <thead>
            <tr><b>Male Patients</b></tr>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Checkin date</th>
                </tr>
            </thead>
            <tbody>';
            $count = 1;
            $sql_select_count_attendance_by_region_result=mysqli_query($conn,"SELECT Patient_Name,Gender,visit_type,Check_In_Date FROM tbl_patient_registration pr,tbl_check_in ch WHERE pr.`Registration_ID`=ch.`Registration_ID` AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Region='$Region_Name' AND visit_type = $Vid AND Gender = 'Male'") or die(mysqli_error($conn));
        while ($attendance_rows= mysqli_fetch_assoc($sql_select_count_attendance_by_region_result)){
            $total_attendance=$attendance_rows['total_attendance'];
            $Gender=$attendance_rows['Gender'];
            $visit_type=$attendance_rows['visit_type'];
            
                $Patient_Name_male = $attendance_rows['Patient_Name']; 
                $Check_In_Date = $attendance_rows['Check_In_Date'];
            
            echo '
                <tr>
                <td>'. $count.'</td>
                <td>'.$Patient_Name_male.'</td>
                <td>'.$Check_In_Date.'</td>
                </tr>
            ';
            $count++;
        }
        
        echo '</tbody>
            </table>
        </div>';



        //Female Selection++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        echo '
        <div class="col-md-6">
        <table class="table table-borderd" style="background-color:#fff">
            <thead>
                <tr><b>Female Patients</b></tr>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Checkin date</th>
                </tr>
            </thead>
            <tbody>
        ';
            $count = 1;
         $sql_select_count_attendance_by_region_result=mysqli_query($conn,"SELECT Patient_Name,Gender,visit_type,Check_In_Date FROM tbl_patient_registration pr,tbl_check_in ch WHERE pr.`Registration_ID`=ch.`Registration_ID` AND Check_In_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Region='$Region_Name' AND visit_type = $Vid AND Gender = 'Female'") or die(mysqli_error($conn));
            while ($attendance_rows= mysqli_fetch_assoc($sql_select_count_attendance_by_region_result)){
                $total_attendance=$attendance_rows['total_attendance'];
                $Gender=$attendance_rows['Gender'];
                $visit_type=$attendance_rows['visit_type'];
                
                    $Patient_Name_female = $attendance_rows['Patient_Name']; 
                    $Check_In_Date = $attendance_rows['Check_In_Date'];
                
                echo '
                    <tr>
                    <td>'.$count.'</td>
                    <td>'.$Patient_Name_female.'</td>
                    <td>'.$Check_In_Date.'</td>
                    </tr>
                ';
                $count++;
            }
            
            echo '</tbody>
                </table>
            </div>
            ';
    }

?>
