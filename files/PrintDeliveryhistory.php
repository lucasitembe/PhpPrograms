<?php

include("includes/connection.php");
$patient_ID = mysqli_real_escape_string($conn,$_GET['patient_ID']);
    $selectQ= mysqli_query($conn,"SELECT * FROM tbl_antenatal_records WHERE Patient_ID='$patient_ID'");
    $result=  mysqli_fetch_assoc($selectQ);
     $lmp=$result['LMP']; 
     $edd=$result['EDD']; 
     $fdate=$result['EXAM_DATE'];
     $bgroup=$result['BLOOD_GROUP'];
     $bp=$result['BP'];
     $oedema=$result['OEDEMA'];
     $breast=$result['BREASTS'];
     $hb=$result['HB'];
     $lungs=$result['LUNGS'];
     $rh=$result['RH'];
     $abdomen=$result['ABDOMEN'];
     $pmtct0=$result['PMTCT_1'];
     $pmtct1=$result['PMTCT_2']; 
     $pmtct2=$result['PMTCT_3']; 
     $urine=$result['URINE'];
     $ppr=$result['PPR'];
     $remarks=$result['REMARKS'];
     $tt=$result['TT'];
     $today=$result['DATE_VAL'];
     $examine=$result['EXAM_BY'];
     
    $employee=  mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='$examine'");
    $row= mysqli_fetch_assoc($employee);
    $name=$row['Employee_Name'];

  
      $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>ANTE-NATAL RECORD</b></td>
		</tr>
                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    
   
    //$htm.=$Query;
    //$htm.= "SELECT * FROM tbl_patient_registration JOIN tbl_payment_cache ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN tbl_item_list_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID WHERE tbl_payment_cache.Registration_ID='".$patient_ID."' AND tbl_item_list_cache.Payment_Cache_ID='".$payment_ID."' AND Check_In_Type='Laboratory'";
    $patientData=mysqli_query($conn,"SELECT Date_Of_Birth,Patient_Name,Gender,Registration_ID FROM tbl_patient_registration WHERE Registration_ID='$patient_ID'");
    $myptntData=  mysqli_fetch_assoc($patientData);      
    $Date_Of_Birth = $myptntData['Date_Of_Birth'];
    $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age.= $diff->m." Months";
    $disp.="<center><div style='margin-left:200px'>
       <b>Patient Name:".$myptntData['Patient_Name']."</b><br />
       <b>Age:".$age."</b><br />
       <b>Sex:".$myptntData['Gender']."</b><br />
       <b>Patient No:".$myptntData['Registration_ID']."</b><br />
       
       </center></div><br /><br />";
     
     
     
   $disp.='<table  class="" border="0"  align="left" style="width:100%">
        <tr>
            <td class="powercharts_td_left" style="text-align:right">
                LMP
            </td>
            <td width="40%">
                <span>'.$lmp.'</span>
            </td>
            
            
            <td  style="text-align:right;">DATE OF FIRST EXAM</td>
            <td  width="40%" colspan="2">

                <span>'.$fdate.'</span>
            </td>
        </tr>
        
        <tr>
            <td class="powercharts_td_left" style="text-align:right">
                E.D.D
            </td>
            <td width="40%">
                <span>'.$edd.'</span>
            </td>
            
            
            <td  style="text-align:right;">BLOOD GR</td>
            <td  width="40%" colspan="2">

                <span>'.$bgroup.'</span>
            </td>
        </tr>
        
        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                BP
            </td>
            <td width="40%">
                <span>'.$bp.'</span>
            </td>
            
            
            <td  style="text-align:right;">OEDEMA</td>
            <td  width="40%" colspan="2">

                <span>'.$oedema.'</span>
            </td>
        </tr>
        
        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                BREASTS
            </td>
            <td >
                <span>'.$breast.'</span>
            </td>
            <td  colspan="" align="right" style="text-align:right;">
                HB
            </td>
            <td>
                <span>'.$hb.'</span>
            </td> 
            
        </tr>
        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                LUNGS
            </td>
            <td>
                <span>'.$lungs.'</span>
            </td>
            <td  colspan="" align="right" style="text-align:right;">
                Rh
            </td>
            <td>
                <span>'.$rh.'</span>
            </td> 
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                ABDOMEN
            </td>
            <td>
                <span>'.$abdomen.'</span>
            </td>
            <td  colspan="" align="right" style="text-align:right;">
              PMTCT  0<br /><br />
                1<br /><br />
                2<br /><br />
            </td>
            <td>
                <span>'.$pmtct0.'</span>
                <span>'.$pmtct1.'</span>
                <span>'.$pmtct2.'</span>
            </td> 
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                URINE
            </td>
            <td>
                <span>'.$urine.'</span>
            </td>
            <td  colspan="" align="right" style="text-align:right;">
               PPR
            </td>
            <td>
                <span>'.$ppr.'</span>
            </td> 
        </tr>

        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                REMARKS
            </td>
            <td>
                <span> '.$remarks.'</span>
            </td>
            <td  colspan="" align="right" style="text-align:right;">
                
            </td>
            <td>';
                
                   if($tt=='TT1'){
                    $disp.=' 
                        <span>TT1</span>';
                          
                   }elseif ($tt=='TT2') {
                       $disp.= '<span>TT2</span>';
                }else if($tt=='TT3'){
                    $disp.= '<span>TT3</span>';
                    
                }else if($tt=='TT4'){
                    $disp.= '
                        <span id="spantt4">TT4</span>
                       ';
                }else if($tt=='TT5'){
                    $disp.= '
                        <span id="spantt5">TT5</span';
                }
                
            $disp.='</td> 
        </tr>
        <tr>
            <td width="20%" class="powercharts_td_left" style="text-align:right">
                DATE:
            </td>
            <td>
                <span> '.$today.'</span>
            </td>
            
            <td  colspan="" align="right" style="text-align:right;">
               EXAM BY
            </td>
            <td>
                <span>'.$name.'</span>
            </td>
        </tr>
        
        
    </table>';

include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
$mpdf = new mPDF('c', 'A3-L');

$mpdf->WriteHTML($disp);
$mpdf->Output();
exit;
?>