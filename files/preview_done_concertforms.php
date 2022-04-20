<?php 
// if (isset($_GET['Registration_ID'])) {
//     $Registration_ID = $_GET['Registration_ID'];
// }
?>

<?php
session_start();
include("./includes/connection.php");
session_start();

if (isset($_GET['Registration_ID']) && isset($_GET['date_done'])){
    
   $Registration_ID = $_GET['Registration_ID'];
   $date_done = $_GET['date_done'];

   $select_Patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE   Registration_ID = '$Registration_ID'");
            while ($row = mysqli_fetch_array($select_Patient)) {
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
        //     $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Patient_sgnature=$row['patient_signature'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
}

   
   $sql_search_procedure_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items td,tbl_Theater_procedure_concert ad WHERE td.Item_ID=ad.Item_ID AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
   if(mysqli_num_rows($sql_search_procedure_result)>0){
   $count_sn=1;
   while($procedure_rows=mysqli_fetch_assoc($sql_search_procedure_result)){  
       $Product_Name=$procedure_rows['Product_Name'];
       $select3.="
       <ul>
       <li>
       $Product_Name
       </li>
       </ul>
          
          ";  
   }
   }
$select_conset_detail = mysqli_query($conn,"SELECT * FROM tbl_consert_forms_details WHERE   Registration_ID = '$Registration_ID' AND date='$date_done'");
while ($row = mysqli_fetch_array($select_conset_detail)) {
    $doctorname=$row['doctor-ID'];
    $under=$row['under'];
    $discusion=$row['discusion'];
    $nfk=$row['next_of_kin'];
    $phone=$row['phone'];
    $consetby=$row['consent_by'];
    $des=$row['doctor-ID'];
    $onbehalf=$row['on_behalf_name'];
    $relation=$row['relation'];
    $desgnation=$row['designation'];
    $witnessign=$row['on_behalf_sgnature']; 
    $datedone=$row['date'];
}
$select_doctor = mysqli_query($conn,"SELECT * FROM tbl_employee WHERE   Employee_ID = '$doctorname'");

   while ($row = mysqli_fetch_array($select_doctor)) {
      
      $doctortype=$row['Employee_Name'];
      $employee_signature=$row['employee_signature'];
      
  }
// esign/patients_signature signature/uploadwitnessign
     

}
  
     
     $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
     $Patientsignature="<img src='../esign/patients_signature/$Patient_sgnature' style='height:25px'>";
     $witnessignature="<img src='../signaturesignatur/$witnessign' style='height:25px'>";

$htm .= '
    
    
    <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 18px;">
    
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td colspan="3" style="text-align:center"><b>INFORMED CONSENT FOR PROCEDURES</b></td></tr>
                <tr><td style="text-align:center"><b>NAME: '.$Patient_Name.'</b></td></tr>
                <tr>
                <td style="text-align:center"><b>AGE: '.$age.'</b></td>                
                </tr>
                <tr>
                <td style="text-align:center"><b>GENDER: '.$Gender.'</b></td>               
                </tr>
                <tr>
                <td style="text-align:center"><b>FILE NUMBER: '.$Registration_ID.'</b></td>               
                </tr>
                <tr>
                            
                </tr>
                           
                </tr>
            </table>';
    
$htm .= '
            <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 16px;">
            <tr>
            <td colspan="4"> 
            <p> <b>NOTE:</b> Procedures refer to surgical, diagnostic, or any invasive treatments.
            <ol>
                <li>
                In case of minors, to be signed by parent/ guardian and in case of altered mentation or physical disabilities to be signed by the next of kin.
                </li>
                <li>
                For any emergency of an unaccompanied incapable or unconscious patient, to be signed by the medical director.
                </li>
                <li>
                To be signed before the procedure is performed, unless it’s a life-threatening situation were a verbal consent will be taken.
                </li>
            </ol>
               </p>
            </td> 
            </tr>
            <tr>
            <td colspan="4"> 				
                
           
            </td>
            </tr>
            <tr>
            <td>
            Consent by: '.$consetby.'
             </td>
            </tr>
            <tr>
            <td colspan="4"> 
            <p>
            I hereby authorize Dr.'.$doctortype.' of Bugando Medical Center his/her team to perform the procedure named:<br/>
                '.$select3.'
            </p>
            </td>
            </tr>
            <tr>
            <td colspan="4"> 				
                
             <b>Under; </b>'.$under.' <br/><br/>
             My doctor/team has discussed with me the following:<br/>
            </td>
            </tr>
            <tr>
            <td colspan="4"> 
            '.$discusion.'
         
            <td>
            </tr>
            <tr>
            
<td colspan="4">
<ol>
<li> 
I also further consent for other procedures to be performed by the above doctor for other unforeseen conditions related to this existing illness with regards to the above-mentioned procedure.
</li>
<li>
I also consent the above doctor/team the use of contrast or any other medication needed or advisable to support the procedure mentioned above.
</li>
<li>
No photographs or videos are to be obtained by any staff of Bugando Medical Center during the entire procedure.
</li>
<li>
I consent the use of blood or blood products if deemed necessary in the course of my treatment. All the risks, need, benefits and alternatives of transfusion have been clearly explained to me.
</li>
<li>
In case of any remove of body parts, I authorize Bugando Medical Center to dispose them in a suitable manner. If by any means I need the parts, and they are not infectious or have potential of causing health hazards, they should be delivered for burial to:
    <ul>
    <li>
    

    Name of person: '.$nfk.'
    </li>
    <li>
    Contacts: '.$phone.'
    </li>
    </ul>
    
	
</li>
<li>
Nobody part removed in this procedure is to be used for any scientific or teaching purposes.
</li>
</ol>
</td>
</tr>
<tr>
<td colspan="4">
<p> 

To the best of my knowledge, I confirm that the information above has been explained to me. I have understood well and I am satisfied. I confidently undersign this to consent.
</p>
</td>
</tr>
<tr>
<td colspan="4">
 
</td>
</tr>
</tr>
<tr>
<td style="text-align:center; ">
'.$Patient_Name.'
</td>
<td  style="text-align:center; ">'.$Patientsignature.'</td>
<td  width="40%" style="text-align:center; ">'.$datedone.' </td>
<td  style="text-align:center; "> </td>
</tr>
<tr>
<td  style="text-align:center; ">
Patient 
</td>
<td style="text-align:center; ">Signature</td>
<td style="text-align:center; ">Date</td>
<td style="text-align:center; "></td>
</tr>
<tr>
<td style="text-align:center; ">'.$onbehalf.'</td>
<td  style="text-align:center; ">'.$witnessignature.'</td>
<td  style="text-align:center; ">'.$datedone.' </td>
<td  style="text-align:center; "> </td>
</tr>
<tr>
<td style="text-align:center; ">On behalf</td>
<td style="text-align:center; ">Signature</td>
<td style="text-align:center; ">Date</td>
<td style="text-align:center; "></td>
</tr>
<tr>
<td style="text-align:center;">Dr. '.$doctortype.'
</td>
<td  style="text-align:center; ">'.$signature.'</td>
<td  style="text-align:center; ">'.$datedone.' </td>
<td  style="text-align:center; "> </td>
</tr>
<tr>
<td style="text-align:center; ">Physician`s Name</td>
<td style="text-align:center; ">Signature</td>
<td style="text-align:center; ">Date</td>
<td style="text-align:center; "></td>
</tr>
    </table>';
        
                   
    
    // $htm .= "</table>";
	include("./MPDF/mpdf.php");
    $Employee_Name=$_SESSION['userinfo']['Employee_Name'];
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;