<?php 
include("./includes/connection.php");
    if(isset($_POST['patient_id'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $patient_id = $_POST['patient_id'];

        echo '
        <table class="table" style="background-color:#fff">
        <tr>
            <th>Date</th>
            <th>8am</th>
            <th>10am</th>
            <th>12md</th>
            <th>2pm</th>
            <th>4pm</th>
            <th>6pm</th>
            <th>8pm</th>
            <th>10pm</th>
            <th>12mn</th>
            <th>2am</th>
            <th>4am</th>
            <th>6am</th>
        </tr>
        ';
        $get_turning_data = mysqli_query($conn,"SELECT chart_date,chart_id FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id AND chart_date BETWEEN '$start_date' AND '$end_date' GROUP BY chart_date ORDER BY chart_id DESC") or die(mysqli_error($conn));
        while($patient_detail_rows = mysqli_fetch_assoc($get_turning_data)){
            $chart_date = $patient_detail_rows['chart_date'];
            $chart_id = $patient_detail_rows['chart_id'];
        echo '<tr>
        <td >'.$chart_date.'</td>
        ';
        $get_time_and_key = mysqli_query($conn,"SELECT chart_date,chart_time,chart_key, created_at FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id AND chart_date = '$chart_date' AND chart_date BETWEEN '$start_date' AND '$end_date' ORDER BY chart_id DESC") or die(mysqli_error($conn));
        while($patient_time_key_rows = mysqli_fetch_assoc($get_time_and_key)){
            $chart_time = $patient_time_key_rows['chart_time'];
            $chart_key = $patient_time_key_rows['chart_key'];
            $chart_date1 = $patient_time_key_rows['chart_date'];
            $chart_created = $patient_time_key_rows['created_at'];

            if($chart_time == "8am"){$first = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "10am"){$second = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "12md"){$third = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "2pm"){$fourth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "4pm"){$fifth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "6pm"){$sixth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "8pm"){$seventh = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "10pm"){$eighth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "12mn"){$ninth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "2am" ){$tenth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "4am"){$eleventh = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
            else if($chart_time == "6am"){$twelth = '<strong>'. $chart_key.'</strong> ('.$chart_created.')';}
        }

             echo '
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
            echo ' </table><br />
            <table>
                <tr><a target="_blank" href="turning_chart_pdf.php?patient_id='.$patient_id.'" class="art-button-green"><b>Preview in PDF</b></a></tr>
            </table>
            ';
    }
?>