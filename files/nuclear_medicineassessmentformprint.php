<?php
include("./includes/connection.php");
session_start();
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = '';
} 

if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if(isset($_GET['Registration_ID'])){
    $Registration_ID = $_GET['Registration_ID'];
}else{
    $Registration_ID = 0;
}

if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = 0;
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Surgery = $new_Date;
} 
//get patient details
$select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from  tbl_patient_registration pr, tbl_sponsor sp where  pr.Sponsor_ID = sp.Sponsor_ID and  Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Member_Number = $data['Member_Number'];
    }
}

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1 -> diff($date2);
$Age = $diff->y." Years, ";
$Age .= $diff->m." Months, ";
$Age .= $diff->d." Days";

$htm ='<table align="center" width="100%">
<tr><td style="text-align:center" colspan="6"><img src="./branchBanner/branchBanner.png"></td></tr>
</table>
<b>RADIO - ACTIVE IODINE THERAPY FOR HYPERTHYROIDISM</b>
<h5 align="center"> ASSESSMENT FORM</h5>
<table width="100%"> 
<tr><td  width="9%" style="text-align: right;"><b>Patient Name</b></td>
<td>'. $Patient_Name.' </td>
<td width="9%" style="text-align: right;"><b>Sponsor Name</b></td>
<td>'. $Guarantor_Name.' </td>
<td style="text-align: right;"><b>Gender</b> </td>
<td>'. $Gender.' </td>
<td style="text-align: right;"><b>Age</b></td>
<td>'. $Age.' </td>
<td style="text-align:right;" ><b>Procedure Date </b></td>
<td>'. $Surgery_Date.' </td>
</tr>

</table>
';
        $select_assessment = mysqli_query($conn, "SELECT * FROM tbl_nm_assessmentform WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    //    die(mysqli_num_rows($select_assessment));
        if(mysqli_num_rows($select_assessment)>0){
            while($row = mysqli_fetch_assoc($select_assessment)){
                $clinicalhistory = explode(',',$row['clinicalhistory']);
                $Employee_ID = $row['Employee_ID'];
                $Assessment_ID = $row['Assessment_ID'];
                // $therapychecklist = $row['therapychecklist'];
                $therapychecklist = explode(',', $row['therapychecklist']);
                if($therapychecklist[0]=='normalYes'){
                    $Normal = "checked='checked'";
                }else{
                    $Normal1 = "checked='checked'";
                }
                if($therapychecklist[1]=="Yes"){
                    $Proptosis="checked='checked'";
                }else{
                    $Proptosis1="checked='checked'";
                }
                $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID ='$Employee_ID'"))['Employee_Name'];
    
   $htm.=' <table class="table">
        <tr>
            <td><h4>CLINICAL HISTORY AND EXAMINATION: </h4></td>
            
        </tr>
        <tr>
            <td>
                <b for="">Occupation</b>
                '. $clinicalhistory[0].'
            </td>
            <td>
                <b for="">Children at Home:</b>
                '. $clinicalhistory[1].'
            </td>
        </tr>';
   
            if($sex =="Female"){
                  $htm.='  <tr>
                        <td>
                            <b for="">Contraception Status</b>
                           '.$clinicalhistory[0].'
                        </td>
                        <td>
                            <b for="">Breast feeding status:</b>
                            name="clinicalhistory[]">
                        </td>
                    </tr> ';         
        }
       
       $htm.=' <tr>
            <td colspan="2">
                <b >Main Complaints:</b>
                '. $clinicalhistory[2].'
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b >Previous medication history:</b>
               '.$clinicalhistory[3].'
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b >Current medications and duration:</b>
                '.$clinicalhistory[4].'
            </td>
        </tr>
        <tr>
            <td>
                <b >Weight</b>
               '. $clinicalhistory[5].'
                <b >Height</b>
               '. $clinicalhistory[6].'
            </td>
            <td>
                <b >Blood Pressure:</b>
               '. $clinicalhistory[7].'
                <b >Pulse rate:</b>
               '. $clinicalhistory[8].'
            </td>
        </tr>
       
        <tr>
            <td>
                <label for="">Oedema</label>
                '. $clinicalhistory[9].'
                <label for="">Tremor</label>
                '. $clinicalhistory[10].'
            </td>
            <td>
                <label for="">Refrexes:</label>
                '. $clinicalhistory[11].'
                <label for="">Myopathy:</label>
                '. $clinicalhistory[12].'
            </td>
        </tr>
        <tr>
            <td>
                <b for="">Respiratory</b>
                '. $clinicalhistory[13].'

            </td>
            <td>
                <label for="">CVS:</label>
                '. $clinicalhistory[14].'
            </td>
        </tr>
        <tr>
            <td colspan="2">Eye complints and signs: (mark/ or describe if applicable)</td>
        </tr>
        <tr>
            <td>
                <b for="">Normal:</b>
                '.$therapychecklist[0].'
               
                <b for="">Proptosis: </b>'.$therapychecklist[1].'
                    if No Explain '. $therapychecklist[2].'
                    
            </td>
            <td>
                <b for="">Colour perception:</b>
                '. $clinicalhistory[16].'
                <b for="">Dry eye/tearing:</b>
                '. $clinicalhistory[17].'
            </td>
        </tr>
        <tr> <td colspan="2"><h4>THYROID SCAN BLOOD RESULTS:<h4></td></tr>
        <tr>
            <td>
                <b for="">Tyroid size:</b>
                '. $clinicalhistory[18].' 
            </td>
            <td>
                <b for="">Distribution of activity:</b>
                '. $clinicalhistory[19].'
                <b for="">Nodules:</b>
                '. $clinicalhistory[20].'
            </td>
        </tr>
        <tr>
            <td>
                <label for="">99TcO2 Uptake:</label>
                '. $clinicalhistory[21].'
                <label for="">Free T4:</label>
                '. $clinicalhistory[22].'
            </td>
            <td>
                <label for="">Free T3::</label>
                '. $clinicalhistory[22].'
                <label for="">TSH:</label>
                '. $clinicalhistory[23].'
            </td>
        </tr> 
    </table>

    <table class="table">
        <tr>
            <td><h4>PLAN OF MANAGEMENT: </h4></td>
            
        </tr>
        <tr>
            <td colspan="">'. $therapychecklist[2].'</td>
        </tr>
        <tr>        
            <td>
                <label for="">Carbimazole:</label>
                '. $clinicalhistory[24].'
                <label for="">Beta blockers:</label>
                '. $clinicalhistory[25].'
            </td>
           
        </tr>
        <tr>        
            <td>
                <label for="">Steroids:</label>
                '. $clinicalhistory[27].'
                <label for="">Eye medications:</label>
                '. $clinicalhistory[28].'
            </td>
            
        </tr>
        <tr>
        <td colspan="">Radioactive Iodine Therapy</td>
        </tr>
        <tr>
             <td>
                <b for="">Planned Date:</b>
                '. $clinicalhistory[26].' 
                <b for="">Dose:</b>
                '. $clinicalhistory[29].' 
            </td>
        </tr>
        
        <tr>        
            <td>
                <b for="">Others:</b>
                '. $clinicalhistory[30].'              
            
                <b for="">Approved By:</b>
                '. $Employee_Name.' 
            </td>
        </tr>
    </table>';
    }
     }   
        $select_managmentplan = mysqli_query($conn, "SELECT * FROM tbl_nm_managementplan WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_managmentplan)>0){
            while($row = mysqli_fetch_assoc($select_managmentplan)){
                $managementplan = explode(',',$row['managementplan']);
            
  $htm.=' <table class="table">
        <caption>RADIO-ACTIVE IODINE (I-131) THERAPY</caption>
        <tr>
            <td>
                <label for="">Pregnancy test</label>
                '. $managementplan[0].'

            </td>
            <td>
                <label for="">DATE:</label>
                '. $managementplan[1].'
            </td>
        </tr>
        <tr>
            <td>
                <label for="">Carbimazole:</label>
                '. $managementplan[2].'

            </td>
            <td>
                <label for="">I-131 DOSE GIVEN:</label>
                '. $managementplan[3].'
            </td>
        </tr>
        <tr>
            <td>
                <label for="">FOLLOW UP DATE</label>
                '. $managementplan[4].'
            </td>
            <td>
                <label for="">Doctor:</label>
                '. $managementplan[5].'
            </td>
        </tr>
       
    </table>';
            }}

include("./MPDF/mpdf.php");
$mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($htm,2);

$mpdf->Output('mpdf.pdf','I');
exit;
?>