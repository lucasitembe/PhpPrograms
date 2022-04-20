<?php
    @session_start();
    include("./includes/connection.php");
  
        $Session_ID = $_GET['Session_ID'];
        $session_date = date("Y-m-d", strtotime($_GET['session_date']));
        $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
        $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);
        
        if(isset($_GET['session_date'])){$display='True';$filter=' ';}

        if (!empty($Patient_Name)) {
            $filter .="AND pr.Patient_Name like '%$Patient_Name%' ";
        }
        
        if (!empty($Patient_Number)) {
            $filter .="AND pr.Registration_ID = '$Patient_Number' ";
        }

        if($Session_ID=='All'){
            $session_sql = 'SELECT * FROM tbl_dialysis_session_time_setup';
        }else{
            $session_sql = "SELECT * FROM tbl_dialysis_session_time_setup WHERE session_time_setup_id='$Session_ID'";
        }

    ?>

    <?php
    if($display=='True'){
     $session_results = mysqli_query($conn,$session_sql);
     while ($session_rows = mysqli_fetch_assoc($session_results)) {
       $session_description= $session_rows['session_description']; 
       $start_time= $session_rows['start_time']; 
       $end_time= $session_rows['end_time']; 

       if((explode(":",$start_time)[0])>(explode(":",$end_time)[0])){
        $session_date_1 = date("Y-m-d",strtotime($session_date)+86400);
        $Date_From=$session_date.' '.$start_time;
        $Date_To=$session_date_1.' '.$end_time;
       }else{
        $Date_From=$session_date.' '.$start_time;
        $Date_To=$session_date.' '.$end_time;
       }

       $filter_date = "AND dd.session_time BETWEEN '".$Date_From."' AND '".$Date_To."'";
     
    $html =' <center><h3>'.$session_description.'</h3><h6>(<i>From '.$Date_From.' To '.$Date_To.' </i>)</h6></center><hr>';
    $html .= '<center><table width ="100%" id="patients-list_">';
    $html .= '<thead>
                <tr>
                    <td><b>SN</b></td>
                    <td><b>REG&nbsp;NO</b></td>
                    <td><b>PATIENT&nbsp;NAME</b></td>
                    <td><b>AGE</b></td>
                    <td><b>SEX</b></td>
                    <td><b>Diagnosis(DX)</b></td>
                    <td width="25%"><b>MANAGEMENT</b></td>
                    <td><b>PRE VITALS</b></td>
                    <td><b>POST VITALS</b></td>
                    <td><b>WEIGHT</b></td>
                    <td><b>REMARKS</b></td>
                </tr>
            </thead>
            <tbody>';


    $sn=1;
    $sql = "SELECT pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Registration_ID,Dry_Weight,Weight_Gain,Weight_removal,Management,Remarks,Diagnosis,bpPre_sit,bpPre_stand,Pulse_pre,Respiration_pre,Temperature_pre,Weight_Pre_sit,Weight_Pre_stand,bpPost_sit,bpPost_stand,Pulse_post,Respiration_post,Temperature_post,Weight_Post_sit,Weight_Post_stand"
    . " FROM tbl_patient_registration AS pr,tbl_dialysis_vitals as dd WHERE pr.Registration_ID =dd.Patient_reg$filter $filter_date";
    $mysql_select=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(mysqli_num_rows($mysql_select)>0){
    while($row = mysqli_fetch_assoc($mysql_select)){
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y."&nbsp;Years,<br>" .$diff->m."&nbsp;Months,<br>" .$diff->d."&nbsp;Days.";

        $html .='
            <tr>
                <td>'.$sn.'</td>
                <td>'.$row['Registration_ID'].'</td>
                <td>'.ucwords(strtolower($row['Patient_Name'])).'</td>
                <td>'.$age.'</td>
                <td>'.$row['Gender'].'</td>
                <td>'.$row['Diagnosis'].'</td>
                <td>'.$row['Management'].'</td>
                <td>
                    BP&nbsp;sit&nbsp;-&nbsp;'.$row['bpPre_sit'].'&nbsp;mmHg <br>
                    BP&nbsp;stand&nbsp;-&nbsp;'.$row['bpPre_stand'].'&nbsp;mmHg <br>
                    P&nbsp;-&nbsp;'.$row['Pulse_pre'].'&nbsp;bpm <br>
                    R&nbsp;-&nbsp;'.$row['Respiration_pre'].'&nbsp;b/pm <br>
                    T&nbsp;-&nbsp;'.$row['Temperature_pre'].'&nbsp;<sup>o</sup>C <br>
                    Pre&nbsp;wt&nbsp;-&nbsp;'.$row['Weight_Pre_stand'].'&nbsp;Kg <br>
                </td>
                <td>
                    BP&nbsp;sit&nbsp;-&nbsp;'.$row['bpPost_sit'].'&nbsp;mmHg <br>
                    BP&nbsp;stand&nbsp;-&nbsp;'.$row['bpPost_stand'].'&nbsp;mmHg <br>
                    P&nbsp;-&nbsp;'.$row['Pulse_post'].'&nbsp;bpm <br>
                    R&nbsp;-&nbsp;'.$row['Respiration_post'].'&nbsp;b/pm <br>
                    T&nbsp;-&nbsp;'.$row['Temperature_post'].'&nbsp;<sup>o</sup>C <br>
                    Post&nbsp;wt&nbsp;-&nbsp;'.$row['Weight_Post_stand'].'&nbsp;Kg <br>
                </td>
                <td>
                    Dry&nbsp;Weight&nbsp;-&nbsp;'.$row['Dry_Weight'].'&nbsp;Kg <br>
                    Weight&nbsp;Gain&nbsp;-&nbsp;'.$row['Weight_Gain'].'&nbsp;Kg <br>
                    Weight&nbsp;Removal&nbsp;-&nbsp;'.$row['Weight_removal'].'&nbsp;Kg <br>
                </td>
                <td>'.$row['Remarks'].'</td>
            </tr>
                ';
            $sn++;
        }
    }else{
        $html .='<td colspan="10"><center>No data available in table</center></td>';
    }

   $html .='</tbody></table></center>';
   echo $html;
    }//end sesssion while

}else{//user hajaselect session date
    $html = '<center><table width ="100%" id="patients-list">';
    $html .= "<thead>
                <tr>
                    <td><b>SN</b></td>
                    <td><b>REG NO</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>AGE</b></td>
                    <td><b>SEX</b></td>
                    <td><b>DX</b></td>
                    <td><b>MANAGEMENT</b></td>
                    <td><b>PRE VITALS</b></td>
                    <td><b>POST VITALS</b></td>
                    <td><b>WEIGHT</b></td>
                    <td><b>REMARKS</b></td>
                </tr>
            </thead>
            <tbody> </tbody>
            </table></center>
            ";
            echo $html;
}

