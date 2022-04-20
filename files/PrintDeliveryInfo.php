<?php

include("includes/connection.php");
$patient_ID = mysqli_real_escape_string($conn,$_GET['patient_ID']);

$selectQ = mysqli_query($conn,"SELECT * FROM tbl_delivery_information WHERE Patient_ID='$patient_ID'");
$result = mysqli_fetch_assoc($selectQ);
$delivery_Date = $result['delivery_Date'];
$Stage_1 = $result['Stage_1'];
$delivery_methode = $result['delivery_methode'];
$artificial_reason = $result['artificial_reason'];
$Stage_2 = $result['Stage_2'];
$placenta_removed = $result['placenta_removed'];
$stage3 = $result['Stage_3'];
$completely_removed = $result['completely_removed'];
$Remarks = $result['Remarks'];
$Blood_lost = $result['Blood_lost'];
$Ergometrine = $result['Ergometrine'];
$Perineum = $result['Perineum'];
$Baby_sex = $result['Baby_sex'];
$Bp_after_delivery = $result['Bp_after_delivery'];
$Baby_weight = $result['Baby_weight'];
$Apgar_1 = $result['Apgar_1'];
$Apgar_5 = $result['Apgar_5'];
$ARV = $result['ARV'];
$Risk = $result['Risk'];
$BCG = $result['BCG'];
$Polio = $result['Polio'];
$Delivered_By = $result['Delivered_By'];

$employee = mysqli_query($conn,"SELECT Employee_Name,Employee_Title FROM tbl_employee WHERE Employee_ID='$Delivered_By'");
$row = mysqli_fetch_assoc($employee);
$name = $row['Employee_Name'];
$title = $row['Employee_Title'];
    $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>DELIVERY INFORMATION</b></td>
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
     
$disp.= '<table  class="" border="0"  align="left">
    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Delivery Date &amp; Time:
        </td>
        <td width="40%">
            <span>' . $delivery_Date . '</span>
        </td>
        <td  style="text-align:right;">Summary:First Stage 1 Time:</td>
        <td colspan="2">

            <span>' . $Stage_1 . '</span>

        </td>
    </tr>


    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Method of delivery:
        </td>
        <td >';

if ($delivery_methode == 'Ruptured Point') {
    $disp.='<span>Ruptured point</span>';
} elseif ($delivery_methode == 'Articial rupture') {
    $disp.='<span style="">Artificial rupture</span> <br />'
            . '<span>' . $artificial_reason . '</span>
                              ';
}

$disp.='</td>
        <td  colspan="" align="right" style="text-align:right;">
            Second stage 2 Time&amp;Min
        </td>
        <td>
            <span>' . $Stage_2 . '</span>
        </td> 
    </tr>


    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Placenta removed:Date and Time
        </td>
        <td>
            <span>' . $placenta_removed . '</span>
        </td>
        <td  colspan="" align="right" style="text-align:right;">
            Third stage 3 Time&amp;Min
        </td>
        <td>
            <span>' . $stage3 . '</span>
        </td> 
    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Placenta and membrane completely removed
        </td>
        <td>';

if ($completely_removed == 'Yes') {
    $disp.='<span id="spanyesremoved">YES </span>
                                          ';
} else if ($completely_removed == 'No') {
    $disp.='
                        <span id="spannotremoved">NO</span>
                        ';
}

'</td>
        <td  colspan="" align="right" style="text-align:right;">
            Remarks
        </td>
        <td>
            <span>' . $Remarks . '</span>
        </td> 
    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Blood lost:
        </td>
        <td>
            <span>' . $Blood_lost . '</span>
        </td>
        <td  colspan="" align="right" style="text-align:right;">
            Ergometrine/Syntometrine/Oxtocin:
        </td>
        <td>
            <span>' . $Ergometrine . '</span>
        </td> 
    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Perineum:
        </td>
        <td>';

if ($Perineum == 'Intact') {
    $disp.='
                                    <span>Intact</span>
                                    
                                 ';
} elseif ($Perineum == 'Tear') {
    $disp.= '<span>Tear</span>';
} elseif ($Perineum == 'Episiotomy') {
    $disp.= '<span id="spanEpisiotomy">Episiotomy</span>';
}

$disp.='</td>
        <td  colspan="" align="right" style="text-align:right;">
            Baby Sex
        </td>
        <td>';

if ($Baby_sex == 'Male') {
    $disp.= '
                                     <span>Male</span>
                                    ';
} elseif ($Baby_sex == 'Female') {

    $disp.= ' <span>Female</span> ';
}

$disp.='</td> 
    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            BP after delivery(MmHg):
        </td>
        <td>
            <span>' . $Bp_after_delivery . '</span>
        </td>
        <td  colspan="" align="right" style="text-align:right;">
            Weight(Kgs):
        </td>
        <td>
            <span>' . $Baby_weight . '</span>
        </td> 
    </tr>


    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Apgarscore after 1 minute:
        </td>
        <td>
            <span>' . $Apgar_1 . '</span>
        </td>
        <td  colspan="" align="right" style="text-align:right;">
            Apgarscore after 5 minutes
        </td>
        <td>
            <span>' . $Apgar_5 . '</span>
        </td> 
    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Given ARV if mother is PMTCTI
        </td>
        <td>';

if ($ARV == 'Yes') {
    $disp.='
                                    <span>Yes</span>
                                   ';
} elseif ($ARV == 'No') {
    $disp.= '<span> No </span>
                                    ';
}

$disp.='</td>
        <td  colspan="" align="right" style="text-align:right;">
            Put tick where appropriate
        </td>
        <td>';

if ($Risk == 'belowWeight') {
    $disp.= '<span id="spanbelow">Bring to hospital weight below 25kg </span>
                         ';
} elseif ($Risk == 'higherTemperature') {
    $disp.= '<span id="spantemp">Temperature more than 38c</span>';
} elseif ($Risk == 'unableSuck') {
    $disp.= '<span id="spansuck">Baby unable to suck</span>';
} elseif ($Risk == 'belowApgar') {
    $disp.= '<span id="spanapgar"> Apgarscore is below 5 after 5min</span> <br />
                                ';
}

$disp.='</td> 
    </tr>

    <tr>
        <tdclass="powercharts_td_left" style="text-align:right">
            BCG Vaccine:
        </td>
        <td>';


if ($BCG == 'Yes') {
    $disp.= '
                                    <span id="spanbcgyes">Yes</span>
                                  ';
} elseif ($BCG == 'No') {
    $disp.= '                    <span id="spanbcgno">No</span>
                                    ';
}

$disp.='</td>
        <td  colspan="" align="right" style="text-align:right;">
            Polio "O":
        </td>
        <td>';

if ($Polio == 'Yes') {
    $disp.='<span>Yes</span>';
} elseif ($Polio == 'No') {
    $disp.=' <span>No</span>';
}

$disp.='</td> 
    

    </tr>

    <tr>
        <td class="powercharts_td_left" style="text-align:right">
            Prepared By
        </td>
        <td>
            <span>' . $name . '</span>
        </td>
        <td  colspan="" align="right" style="text-align:right;">
            Designation
        </td>
        <td>
            <span>' . $title . '</span>
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