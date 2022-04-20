<?php

session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') { 
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $patient_ID=  mysql_real_escape_string($_POST['patient_ID']);
        $todaydate = mysql_real_escape_string($_POST['todaydate']);
        $wt = mysql_real_escape_string($_POST['wt']);
        $bp = mysql_real_escape_string($_POST['bp']);
        $alb = mysql_real_escape_string($_POST['alb']);
        $sug = mysql_real_escape_string($_POST['sug']);
        $ut = mysql_real_escape_string($_POST['ut']);
        $pos = mysql_real_escape_string($_POST['pos']);
        $fh = mysql_real_escape_string($_POST['fh']);
        $oedema = mysql_real_escape_string($_POST['oedema']);
        $hb = mysql_real_escape_string($_POST['hb']);
        $ga = mysql_real_escape_string($_POST['ga']);
        $remarks = mysql_real_escape_string($_POST['remarks']);
//        $sign = mysql_real_escape_string($_POST['sign']);
        
        $insert = mysql_query("INSERT INTO tbl_deliverychart (Patient_ID,Attend_Date,wt,bp,urine_alb,urine_sug,ut_fh,pos,fh,oedema,hb,ga,remarks,Employee)
        VALUES ('$patient_ID','$todaydate','$wt','$bp','$alb','$sug','$ut','$pos','$fh','$oedema','$hb','$ga','$remarks','$Employee_ID')");
        if ($insert) {
            $select=mysql_query("SELECT * FROM tbl_deliverychart WHERE Patient_ID='$patient_ID'");
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
            
            while ($row=mysql_fetch_assoc($select)){
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
                $employee=  mysql_query("SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='".$row['Employee']."'");
                $row2= mysql_fetch_assoc($employee);
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