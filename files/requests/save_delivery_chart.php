<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID=  mysqli_real_escape_string($conn,$_POST['patient_ID']);
        $todaydate = mysqli_real_escape_string($conn,$_POST['todaydate']);
        $wt = mysqli_real_escape_string($conn,$_POST['wt']);
        $bp = mysqli_real_escape_string($conn,$_POST['bp']);
        $alb = mysqli_real_escape_string($conn,$_POST['alb']);
        $sug = mysqli_real_escape_string($conn,$_POST['sug']);
        $ut = mysqli_real_escape_string($conn,$_POST['ut']);
        $pos = mysqli_real_escape_string($conn,$_POST['pos']);
        $fh = mysqli_real_escape_string($conn,$_POST['fh']);
        $oedema = mysqli_real_escape_string($conn,$_POST['oedema']);
        $hb = mysqli_real_escape_string($conn,$_POST['hb']);
        $ga = mysqli_real_escape_string($conn,$_POST['ga']);
        $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
//        $sign = mysqli_real_escape_string($conn,$_POST['sign']);
        
        $insert = mysqli_query($conn,"INSERT INTO tbl_deliverychart (Patient_ID,Attend_Date,wt,bp,urine_alb,urine_sug,ut_fh,pos,fh,oedema,hb,ga,remarks,Employee)
        VALUES ('$patient_ID','$todaydate','$wt','$bp','$alb','$sug','$ut','$pos','$fh','$oedema','$hb','$ga','$remarks','$Employee_ID')");
        if ($insert) {
            $select=mysqli_query($conn,"SELECT * FROM tbl_deliverychart WHERE Patient_ID='$patient_ID'");
            echo '  <tr style="text-weight:bold">
            <td>
                <b> DATE </b>
            </td>
            
            <td>
               <b> WT </b>
            </td>
            
            <td>
               <b> B.P </b>
            </td>
            
            <td>
               <b> URINE ALB </b>
            </td>
            <td>
                <b>URINE SUG</b>
            </td>
            
            <td>
               <b> UT FH </b>
            </td>
            
            <td>
              <b>  POS </b>
            </td>
            <td><b>FH</b></td>
            
            <td>
               <b> OEDEMA </b>
            </td>
            <td>
              <b>  HB </b>
            </td>
            
            <td>
               <b> GA </b>
            </td>
            
            <td>
               <b> REMARKS </b>
            </td>
            <td>
               <b> SIGN </b>
            </td>
        </tr>
        ';
            
            while ($row=mysqli_fetch_assoc($select)){
                echo '<tr><td>'.$row['Attend_Date'].'</td>';
                echo '<td>'.$row['wt'].'</td>';
                echo '<td>'.$row['bp'].'</td>';
                echo '<td>'.$row['urine_alb'].'</td>';
                echo '<td>'.$row['urine_sug'].'</td>';
                echo '<td>'.$row['ut_fh'].'</td>';
                echo '<td>'.$row['pos'].'</td>';
                echo '<td>'.$row['fh'].'</td>';
                echo '<td>'.$row['oedema'].'</td>';
                echo '<td>'.$row['hb'].'</td>';
                echo '<td>'.$row['ga'].'</td>';
                echo '<td>'.$row['remarks'].'</td>';
                $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='".$row['Employee']."'");
                $row2= mysqli_fetch_assoc($employee);
                $name=$row2['Employee_Name'];
                echo '<td>'.$name.'</td></tr>';
               
            }
            
            
            
            echo '<tr>
            <td>
                <input type="text" readonly="true" id="todaydate">
            </td>
            <td> 
             <input type="text" id="wt">
            </td>
            <td>
                <input type="text" id="bp">
            </td>
            
            <td>
                <input type="text" id="alb">
            </td>
            
            <td>
                <input type="text" id="sug">
            </td>
            
            <td>
                <input type="text" id="ut">
            </td>
            
            <td>
                <input type="text" id="pos">
            </td>
            
            <td>
                <input type="text" id="fh">
            </td>
            
            <td>
                <input type="text" id="oedema">
            </td>
            
            <td>
                <input type="text" id="hb">
            </td>
            
            <td>
                <textarea style="height:15px;width:100px" id="remarks">
                   
                </textarea>
            </td>
            
            <td>
                <input type="text" id="name" readonly="true">
            </td>
        </tr>
        
        <tr>
            <center>
                <td>
                    <input type="button" value="Save" id="savedata" class="art-button-green">
                </td>
             </center>
        </tr>
        ';
        } else {
            echo 'Data saving error';
        }
    }
}