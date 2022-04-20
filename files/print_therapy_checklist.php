<?php
session_start();
include("./includes/connection.php");

if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = '';
} 
$htm ='<table align="center" width="100%">
<tr><td style="text-align:center" colspan="6"><img src="./branchBanner/branchBanner.png"></td></tr>
</table>';
$Id = $_GET['Id'];
$Item_list =$_GET['Item_list'];
//get patient details
$select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from  tbl_patient_registration pr, tbl_sponsor sp where  pr.Sponsor_ID = sp.Sponsor_ID and  Registration_ID = '$Id'") or die(mysqli_error($conn));
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

$select_data = mysqli_query($conn, "SELECT * FROM tbl_nm_therapychecklist  WHERE Payment_Item_Cache_List_ID='$Item_list' AND Registration_ID='$Id' ") or die(mysqli_error($conn));

if(mysqli_num_rows($select_data)>0){
    while($row = mysqli_fetch_assoc($select_data)){
        $therapychecklist = explode(',', $row['therapychecklist']);
        $created_at = $row['created_at'];
        $Therapy = explode(',', $row['Therapy']);
        
        if($therapychecklist[0]=="Yes"){
            $consentform= "checked = 'checked'";
        }else if($therapychecklist[0]=="No"){
            $consentform1= "checked= 'checked'";
        }

        if($therapychecklist[1]=="Yes"){
            $instructionform= "checked = 'checked'";
        }else if($therapychecklist[1]=="No"){
            $instructionform1= "checked= 'checked'";
        }

        if($therapychecklist[2]=="Yes"){
            $precaution= "checked = 'checked'";
        }else if($therapychecklist[2]=="No"){
            $precaution1= "checked= 'checked'";
        }

        if($therapychecklist[3]=="Yes"){
            $latter= "checked = 'checked'";
        }else if($therapychecklist[3]=="No"){
            $latter1= "checked= 'checked'";
        }
        if($therapychecklist[4]=="Yes"){
            $contraception= "checked = 'checked'";
        }else if($therapychecklist[4]=="No"){
            $contraception1= "checked= 'checked'";
        }else if($therapychecklist[4]=="Nill"){
            $contraception11= "checked= 'checked'";
        }

        if($therapychecklist[5]=="Yes"){
            $pregnance= "checked = 'checked'";
        }else if($therapychecklist[5]=="No"){
            $pregnance1= "checked= 'checked'";
        }else if($therapychecklist[5]=="Nill"){
            $pregnance11= "checked= 'checked'";
        }
        if($therapychecklist[6]=="Yes"){
            $carbimazole= "checked = 'checked'";
        }else if($therapychecklist[6]=="No"){
            $carbimazole1= "checked= 'checked'";
        }
        if($therapychecklist[7]=="Yes"){
            $propranolol= "checked = 'checked'";
        }else if($therapychecklist[7]=="No"){
            $propranolol1= "checked= 'checked'";
        }
        if($therapychecklist[8]=="Yes"){
            $opthalmopath= "checked = 'checked'";
        }else if($therapychecklist[8]=="No"){
            $opthalmopath1= "checked= 'checked'";
        }
        if($therapychecklist[9]=="Yes"){
            $smoking= "checked = 'checked'";
        }else if($therapychecklist[9]=="No"){
            $smoking1= "checked= 'checked'";
        }
        if($therapychecklist[11]=="Yes"){
            $follow= "checked = 'checked'";
        }else if($therapychecklist[11]=="No"){
            $follow1= "checked= 'checked'";
        }
        if($therapychecklist[12]=="Yes"){
            $opthalmologist= "checked = 'checked'";
        }else if($therapychecklist[12]=="No"){
            $opthalmologist1= "checked= 'checked'";
        }

        if($therapychecklist[10]=="Yes"){
            $prednisone= "checked = 'checked'";
        }else if($therapychecklist[10]=="No"){
            $prednisone1= "checked= 'checked'";
        }
$htm.='
<fieldset>
<legend align="center"><b>RADIO - ACTIVE IODINE THERAPY FORM HYPERTHYROIDISM</b>
</legend>
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
<td>'. $created_at.' </td>
</tr>

</table>
    <table width="100%">
        <caption style="text-align: center;"><h4>DAY OF THERAPY CHECKLIST</h4></caption>
        <tr>
            <td>
               <b> Consent Form : </b>'. $therapychecklist[0].'
                
                
            </td>
            </tr>
            <tr>
            <td>
               <b> Patient Information and instruction form </b>'.$therapychecklist[1].'
                
            </td>
        </tr>
        <tr>
            <td>
                <b>Advise on precautions given: </b>'.$therapychecklist[2].'
                
            </td>
            </tr>
            <tr>
            <td>
                <b>Treatment letter signed: </b>'.$therapychecklist[3].'
               
                
            </td>
        </tr>
        <tr>
            <td>
               <b> Contraception: </b>'.$therapychecklist[3].'
                
                
            </td>
            </tr>
            <tr>
            <td>
               <b> Pregnancy Test: </b>'.$therapychecklist[3].'
               
            </td>
        </tr>
        <tr>
            <td colspan="">
               <b> Patient stopped Carbimazole: </b>'.$therapychecklist[4].'
                
                
            </td>
        </tr>
        <tr>
            <td colspan="">
                <b>Propranolol for 1 month prescribed: </b>'.$therapychecklist[5].'
                <span>
                    
                   <b> Dose: </b>'. $Therapy[1].'mg
                   </span>
                </td>
            </tr>
            <tr>
                <td>
                   <b> If asthmatic Varapamil prescribed 40mg daily for 1 month: </b>'.$therapychecklist[6].'
                </span>
                
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <b>Opthalmopathy: </b>'.$therapychecklist[7].'
                <span>
                   
                <b>  If YES: Controlled by Opthalmologist?: </b>'.$therapychecklist[8].'
                    
                </span>
                
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <b> Patient Smoking: </b>'.$therapychecklist[9].'
                <span>
                    
                   <b> If yes; prednisone given? :</b>'.$therapychecklist[10].'
                    
                   <b> Dose: '.$Therapy[2].' (0.3mg/kg starting second day after RAI for 4 weeks)
                </span>
                
            </td>
        </tr>
        <tr>
            <td>
               <b> Follow up date given?: </b>'.$therapychecklist[11].'
               
                
            </td>
            </tr>
            <tr>
            <td>
                Date:
                <b>
                   '.$Therapy[3] .'                                        

                </b>
                
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