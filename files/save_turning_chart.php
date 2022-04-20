<?php
include("./includes/connection.php");
    if(isset($_POST['Registration_id'])){
        $Registration_id = mysqli_real_escape_string($conn,$_POST['Registration_id']);
        $date = mysqli_real_escape_string($conn,$_POST['date']);
        $time = mysqli_real_escape_string($conn,$_POST['time']);
        $key = mysqli_real_escape_string($conn,$_POST['key']);

        $insert_into_turning_table = "INSERT INTO `tbl_turning_chart_details` (`Patient_Registration_id`, `chart_date`, `chart_time`, `chart_key`)
        VALUE ('$Registration_id','$date','$time','$key')";
        $save = mysqli_query($conn,$insert_into_turning_table);
        if($save){

            echo '
            <table class="table" style="background-color:#fff">
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
            $get_turning_data = mysqli_query($conn,"SELECT chart_date,chart_time,chart_id FROM tbl_turning_chart_details WHERE Patient_Registration_id = $Registration_id GROUP BY chart_date ORDER BY chart_id DESC") or die(mysqli_error($conn));
            while($patient_detail_rows = mysqli_fetch_assoc($get_turning_data)){
                $chart_date = $patient_detail_rows['chart_date'];
                $chart_id = $patient_detail_rows['chart_id'];
                $chart_time1 = $patient_detail_rows['chart_time'];
            echo '<tr>
            <td width=4%>'.$chart_date.'</td>
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

                $get_time_and_key = mysqli_query($conn,"SELECT chart_date,chart_time,chart_key, created_at FROM tbl_turning_chart_details WHERE Patient_Registration_id = $Registration_id AND chart_date = '$chart_date' AND chart_time='$chart_time' ORDER BY chart_id DESC") or die(mysqli_error($conn));
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
            
            echo '
            <td width=8%>'.$first.'</td>  
            <td width=8%>'.$second.'</td>
            <td width=8%>'.$third.'</td>
            <td width=8%>'.$fourth.'</td>
            <td width=8%>'.$fifth.'</td>
            <td width=8%>'.$sixth.'</td>
            <td width=8%>'.$seventh.'</td>
            <td width=8%>'.$eighth.'</td>
            <td width=8%>'.$ninth.'</td>
            <td width=8%>'.$tenth.'</td>
            <td width=8%>'.$eleventh.'</td>
            <td width=8%>'.$twelth.'</td>

            </tr>
            ';
            }
            echo ' 
            </table><br />
            <table>
                <tr><a target="_blank" href="turning_chart_pdf.php?patient_id='.$Registration_id.'" class="art-button-green"><b>Preview in PDF</b></a></tr>
            </table>
            ';

        }else{
            echo "Data Not Saved";
        }
    }else{
        echo "fail";
    }
?>