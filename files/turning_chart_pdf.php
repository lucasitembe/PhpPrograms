<?php 
include("./includes/connection.php");
session_start();

$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

if(isset($_GET['patient_id'])){
            //select patient details
            $patient_id = $_GET['patient_id'];
        $data.= '
        <center><img src="branchBanner/branchBanner.png" width="100%" ></center>
        <h3 style="text-align:center">TURNING CHART</h3>';

        $get_pateint_details = mysqli_query($conn,"SELECT Registration_ID,Patient_Name,Gender,Date_Of_Birth FROM tbl_patient_registration WHERE Registration_ID = $patient_id") or die(mysqli_error($conn));
        while($patient_detail_rows = mysqli_fetch_assoc($get_pateint_details)){
          $Registration_ID = $patient_detail_rows['Registration_ID'];
          $Patient_Name = $patient_detail_rows['Patient_Name'];
          $Gender = $patient_detail_rows['Gender'];
          $Date_Of_Birth = $patient_detail_rows['Date_Of_Birth'];
        }
        $data.= '
        <table class="table table-bordered" style="background-color:#fff; width:100%">
        <tr>
            <th>Hosp No:</th>
            <td>'.$Registration_ID.'</td>
            <th>Name:</th>
            <td>'.$Patient_Name.'</td>
            <th>Sex:</th>
            <td>'.$Gender.'</td>
            <th>Date of Birth:</th>
            <td>'.$Date_Of_Birth.'</td>
        </tr>';
        $select_bed_result = mysqli_query($conn, "SELECT Bed_Name, ad.ward_room_id, ad.Hospital_Ward_ID, hw.Hospital_Ward_Name, wr.room_name,  ad.Registration_ID FROM tbl_admission ad ,tbl_patient_registration pr, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE ad.Registration_ID=$patient_id AND ad.ward_room_id = wr.ward_room_id AND ad.Hospital_Ward_ID =hw.Hospital_Ward_ID") or die(mysqli_error($conn));
            while($bed_row = mysqli_fetch_assoc($select_bed_result)){
                $ward = $bed_row['Hospital_Ward_Name'];
                $bed = $bed_row['Bed_Name'];
                $room = $bed_row['room_name'];
            }
            $data.=' <tr>
                <th>Ward:</th>
                <td>'.$ward.'</td>
                <th>ROOM:</th>
                <td>'.$room.'</td>
                <th>Bed No:</th>
                <td>'.$bed.'</td>
            </tr>
        </table><br>
        ';

        $data.= '<table class="table" style="background-color:#fff; width:100%">
        <tr>
            <th width=4%>Date</th>
            <th width=8%>8am</th>
            <th width=8%>10am</th>
            <th width=8%>12md</th>
            <th width=8%>2pm</th>
            <th width=8%>4pm</th>
            <th width=8%>6pm</th>
            <th width=8%>8pm</th>
            <th width=8%>10pm</th>
            <th width=8%>12mn</th>
            <th width=8%>2am</th>
            <th width=8%>4am</th>
            <th width=8%>6am</th>
        </tr>
        ';
        $get_turning_data = mysqli_query($conn,"SELECT chart_date,chart_time,chart_id FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id GROUP BY chart_date ORDER BY chart_id DESC") or die(mysqli_error($conn));
        while($patient_detail_rows = mysqli_fetch_assoc($get_turning_data)){
            $chart_date = $patient_detail_rows['chart_date'];
            $chart_id = $patient_detail_rows['chart_id'];
            $chart_time1 = $patient_detail_rows['chart_time'];
        $data.= '<tr>
        <td >'.$chart_date.'</td>
        ';
            
            $count_hr=0;
            $first = "";
            $second = "";
            $third = "";
            $fourth = "";
            $fifth = "";
            $sixth = "";
            $seventh = "";
            $eighth = "";
            $ninth = "";
            $tenth = "";
            $eleventh = "";
            $twelth = "";
        while($count_hr<12){
            if($count_hr==0){
                $chart_time="8am";
            }
            if($count_hr==1){
                $chart_time="10am";
            }
            if($count_hr==2){
                $chart_time="12md";
            }
            if($count_hr==3){
                $chart_time="2pm";
            }
            if($count_hr==4){
                $chart_time="4pm";
            }
            if($count_hr==5){
                $chart_time="6pm";
            }
            if($count_hr==6){
                $chart_time="8pm";
            }
            if($count_hr==7){
                $chart_time="10pm";
            }
            if($count_hr==8){
                $chart_time="12mn";
            }
            if($count_hr==9){
                $chart_time="2am";
            }
            if($count_hr==10){
                $chart_time="4am";
            }
            if($count_hr==11){
                $chart_time="6am";
            }

            $get_time_and_key = mysqli_query($conn,"SELECT chart_date,chart_time,chart_key, created_at FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id AND chart_date = '$chart_date' AND chart_time='$chart_time' ORDER BY chart_id DESC") or die(mysqli_error($conn));
            while($patient_time_key_rows = mysqli_fetch_assoc($get_time_and_key)){
            $chart_time = $patient_time_key_rows['chart_time'];
            $chart_key = $patient_time_key_rows['chart_key'];
            $chart_date1 = $patient_time_key_rows['chart_date'];
            $chart_created = $patient_time_key_rows['created_at'];
       
            if($chart_time == "8am"){$first = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                else if($chart_time == "10am"){$second = '<strong>'. $chart_key.'</strong> ('.$chart_created.')' ;}
                else if($chart_time == "12md"){$third = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "2pm"){$fourth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "4pm"){$fifth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "6pm"){$sixth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "8pm"){$seventh = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "10pm"){$eighth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "12mn"){$ninth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "2am"){$tenth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "4am"){$eleventh = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
                else if($chart_time == "6am"){$twelth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            

        }
            $count_hr++;
        }
        
        $data.= '
        <td>'.$first.'</td>  
        <td>'.$second.'</td>
        <td>'.$third.'</td>
        <td>'.$fourth.'</td>
        <td>'.$fifth.'</td>
        <td>'.$sixth.'</td>
        <td>'.$seventh.'</td>
        <td>'.$eighth.'</td>
        <td>'.$ninth.'</td>
        <td>'.$tenth.'</td>
        <td>'.$eleventh.'</td>
        <td>'.$twelth.'</td>

        </tr>
        ';
        }
        $data.= ' </table>
        ';
        

            include("MPDF/mpdf.php");
            $mpdf = new mPDF('', 'A4');
            $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
            $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
            // LOAD a stylesheet
            $stylesheet = file_get_contents('patient_file.css');
            $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
            $mpdf->WriteHTML($data, 2);

            $mpdf->Output('mpdf.pdf','I');

}
?>