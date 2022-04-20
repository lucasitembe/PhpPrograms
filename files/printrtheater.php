<?php
//session_start();
include("./includes/connection.php");
session_start();

if (isset($_GET['Registration_ID'])) {
   $Registration_ID = $_GET['Registration_ID'];
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

   echo $date2= date('d, D, M, Y');
   echo $time= date('h:m:s');
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
$select_conset_detail = mysqli_query($conn,"SELECT * FROM tbl_consert_forms_details WHERE   Registration_ID = '$Registration_ID' ORDER BY Consent_ID DESC LIMIT 1");
while ($row = mysqli_fetch_array($select_conset_detail)) {
    $doctorname=$row['doctor_ID'];
    $next_of_kin=$row['next_of_kin'];
    $phone=$row['phone'];
    $designation=$row['designation'];
    $on_behalf_name=$row['on_behalf_name'];
    $Amptutation_of=$row['Amptutation_of'];
    $Language=$row['Language'];
    $Responsible_dr=$row['Responsible_dr'];
    $patient_witness=$row['patient_witness'];
    $photography_on_surgery=$row['photography_on_surgery'];
    $presense_of_students=$row['presense_of_students'];
    $consent_amputation=$row['consent_amputation'];
    $Consent_ID=$row['Consent_ID'];
    $procedure_taken=$row['procedure_taken'];
    $Consent_ID =$row['Consent_ID'];
    $relation =$row['relation'];
    $date =$row['date'];
    $consent_by =$row['consent_by'];

    // $surgeon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$doctorname'"))['Employee_Name'];
    // $surgeon2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Responsible_dr'"))['Employee_Name'];
}
$select_doctor = mysqli_query($conn,"SELECT * FROM tbl_employee WHERE   Employee_ID = '$doctorname'");
{
   while ($row = mysqli_fetch_array($select_doctor)) {
      
      $doctortype=$row['Employee_Name'];
      $employee_signature=$row['employee_signature'];
      
  }
}
// esign/patients_signature signature/uploadwitnessign
     }

     
     $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
     $Patientsignature="<img src='../esign/patients_signature/$Patient_sgnature' style='height:25px'>";
     $witnessignature="<img src='../signaturesignatur/$witnessign' style='height:25px'>";

if($Language == 'En'){
            $htm .= '
                
                
                <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;">
                
                            <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                            <tr><td colspan="3" style="text-align:center; font-size: 18px;"><b>CONSENT FORM FOR PROCEDURES/SURGERIES</b></td></tr>
                            <tr><td style="text-align:center ;font-size: 15px;">Name: <b>'.$Patient_Name.'</b>&nbsp;&nbsp;&nbsp;Age: <b>'.$age.'</b> &nbsp;&nbsp;&nbsp;Gender: <b>'.$Gender.'</b></td>               
                            </tr>
                            <tr>
                            <td style="text-align:center;font-size: 15px;">File Number: <b>'.$Registration_ID.'</b></td>               
                            </tr>
                            <tr>
                                        
                            </tr>
                                    
                            </tr>
                        </table>';
                
            $htm .= '
                        <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 14px;">
                            <tr>
                                <td colspan="4"> 
                                    <p>
                                    I hereby authorize <b>Dr. '.$doctortype.'</b> of Lugalo Military Hospital his/her team to perform the procedure which involves: <b>'.$procedure_taken.'</b>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">			
                                    <p>I have been explained the reasons for undergoing this procedure/surgery and all available options and potential complications have been explained and I have understood them well.<br>
                                    I consent to the Procedure/Surgery after understanding the above:-
                                </td>
                            </tr>
                            <tr>
                                <td width="1%" style="font-weight: bold; text-align: right;">1.</td><td colspan="3">
                                    I understand that during the Procedure/Surgery, the need may arise to undergo a procedure other than that which has been explained. I consent to undergoing any necessary Procedure/Surgery as will be deemed fit by this Surgical team.
                                </td>
                            </tr> 
                            <tr>
                                <td width="1%" style="font-weight: bold; text-align: right;">2.</td><td colspan="3">
                                    I consent to be given anesthetic drugs by the concerned team after being explained the Pros and Cons of the anesthetic drugs.
                                </td>
                            </tr> 
                            <tr>
                                <td width="1%" style="font-weight: bold; text-align: right;">3.</td><td colspan="3">
                                    I also consent to be transfused blood as deemed neccessary.
                                </td>
                            </tr> 
                            <tr>
                                <td width="1%" style="font-weight: bold; text-align: right;">4.</td><td colspan="3">
                                    I will agree to all outcomes which may arise as a result of this surgery.
                                </td>
                            </tr>  
                            <tr>
                                <td width="1%" style="font-weight: bold; text-align: right;">5.</td><td colspan="5">
                                    I also have been explained that BMC being a teaching Institution, there will be students present during the course of the Procedure/Surgery and that may be pictures taken for teaching/research purposes.
                                </td>
                            </tr>            
                        </table>';

                $htm .='<table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 14px">
                            <tr>
                            <td colspan="4"><hr width="100%"></td>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align: center;">CONSENT AGREEMENTS </th>
                        </th>
                        </tr>';
                        if($presense_of_students == 'Agree'){
                            $presense_of_students = "checked='checked'";
                        }elseif($presense_of_students == 'Disagree'){
                            $presense_of_students2 = "checked='checked'";
                        }
                        if($photography_on_surgery == 'Agree'){
                            $photography_on_surgery = "checked='checked'";
                        }elseif($photography_on_surgery == 'Disagree'){
                            $photography_on_surgery2 = "checked='checked'";
                        }            
                        if($consent_amputation == 'Agree'){
                            $consent_amputation = "checked='checked'";
                        }elseif($consent_amputation == 'Disagree'){
                            $consent_amputation2 = "checked='checked'";
                        }

                        if($consent_by == 'Patient'){
                            $consent_by_1 = "checked='checked'";
                        }elseif($consent_by == 'Guardian/Proxy'){
                            $consent_by_2 = "checked='checked'";
                        }elseif($consent_by == 'Director'){
                            $consent_by_3 = "checked='checked'";
                        }
                $htm .="<tr>
                        <td colspan='4'> 
                        ".$discusion."
                        <td>
                        </tr>
                        <tr>
                            <td colspan='4' style='text-align: center;'><b>Presense of Students: </b><input type='checkbox' $presense_of_students >Agree &nbsp;&nbsp;&nbsp;<input type='checkbox' $presense_of_students2 >Disagree &nbsp;&nbsp;&nbsp;&nbsp;
                            <b>Photography for research/teaching: </b><input type='checkbox' $photography_on_surgery >Agree &nbsp;&nbsp;<input type='checkbox' $photography_on_surgery2 >Disagree</td></tr>
                            <tr>
                            <tr>
                                <td colspan='4'><hr width='100%'></td>
                            </tr>
                            <tr>
                                <th colspan='4' style='text-align: center;'>CONSENT FOR AMPUTATION </th>
                            </tr>
                            <tr>
                                <td colspan='4'>I have been explained the reasons for undergoing an amputation. I have been explained the Pros and Cons for undergoing an amputation of my body part. I understand that I have to undergo an Amputation of<b> $Amptutation_of </b></td>
                            </tr>
                            <tr>
                                <td colspan='4' style='text-align: center;'><b>Consent for Amputation:: </b><input type='checkbox' $consent_amputation >Agree &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_amputation2 >Disagree</td></tr>
                            <tr>
                                <td colspan='4'><hr width='100%'></td>
                            </tr>
                            <tr>
                                <th colspan='4' style='text-align: center;'>Consent Signed by: <input type='checkbox' $consent_by_1 >Patient &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_2 >Guardian / Proxy  &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_3 >Director </th>
                            </tr>";
                
                $htm .='
            <tr>
            <td colspan="4">
            
            </td>
            </tr>
            </tr>
            <tr>
            <td style="font-weight: bold;">
            '.$Patient_Name.'
            </td>
            <td  style="text-align:center; ">'.$Patientsignature.'</td>
            <td  width="25%" style="text-align:center; ">'.$date.' </td>
            <td  style="text-align:center; "> </td>
            </tr>
            <tr>
            <td>
            Name of Patient 
            </td>
            <td style="text-align:center; ">Signature</td>
            <td style="text-align:center; ">Date</td>
            <td style="text-align:center; "></td>
            </tr>
            <tr>
            <td style="font-weight: bold;">'.$on_behalf_name.'</td>
            <td  style="text-align:center; ">'.$witnessignature.'</td>
            <td  style="text-align:center; ">'.$date.' </td>
            <td  style="text-align:center; "> </td>
            </tr>
            <tr>
            <td style="text-align:; ">Name of Guardian/Proxy</td>
            <td style="text-align:center; ">Signature</td>
            <td style="text-align:center; ">Date</td>
            <td style="text-align:center; "></td>
            </tr>
            <tr>
            <td style=" font-weight: bold;">Dr. '.$doctortype.'
            </td>
            <td  style="text-align:center; ">'.$signature.'</td>
            <td  style="text-align:center; ">'.$date.' </td>
            <td  style="text-align:center; "> </td>
            </tr>
            <tr>
            <td style="text-align:; ">'.$designation.'</td>
            <td style="text-align:center; ">Signature</td>
            <td style="text-align:center; ">Date</td>
            <td style="text-align:center; "></td>
            </tr>
                </table>';
    }elseif ($Language == 'Sw') {
        if (isset($_GET['Registration_ID'])) {
            $Registration_ID = $_GET['Registration_ID'];
                    $select_Patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE   Registration_ID = '$Registration_ID'");
                    while ($row = mysqli_fetch_array($select_Patient)) {
                    $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                //     $Date_Of_Birth = $row['Date_Of_Birth'];
                    $Gender = $row['Gender'];
                    $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1 -> diff($date2);
                $age = " Miaka ".$diff->y;
                $age .= ", Miezi ".$diff->m;
                $age .= ", Na Siku ".$diff->d;


                if($Gender == 'Male'){
                    $Gender = 'Mwanaume';
                }elseif($Gender == 'Female'){
                    $Gender = 'Mwanamke';
                }
            
            }
               }       $htm .= '
                            
                            
                    <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;">
                    
                                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                                <tr><td colspan="3" style="text-align:center; font-size: 18px;"><b>FOMU YA RIDHAA YA UPASUAJI/TIBA (CONSENT FORM)</b></td></tr>
                                <tr><td style="text-align:center ;font-size: 15px;">Jina la Mgonjwa: <b>'.$Patient_Name.'</b>&nbsp;&nbsp;&nbsp;Umri: <b>'.$age.'</b> &nbsp;&nbsp;&nbsp;Jinsia: <b>'.$Gender.'</b></td>               
                                </tr>
                                <tr>
                                <td style="text-align:center;font-size: 15px;">Namba ya Faili: <b>'.$Registration_ID.'</b></td>               
                                </tr>
                                <tr>
                                            
                                </tr>
                                        
                                </tr>
                            </table>';
                    
                $htm .= '
                            <table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 14px;">
                                <tr>
                                    <td colspan="4"> 
                                        <p>
                                        Ninatoa ridhaa/ruhusa kwa madaktari na/au kwa kushirikiana na wahudumu wengine ili kutoa tiba au kufanya upasuaji ufuatao amabo unahusu: <b>'.$procedure_taken.'</b>
                                        <p>
                                        Namna na lengo la tiba/upasuaji limeelezwa vyema. Na zaidi faida, matokeo na madhara ya huo upasuaji, yameelezwa vyema kwangu na nimeelewa na kuridhia binafsi bila shuruti 
                                    </td>
                                </tr>
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">1.</td><td colspan="3">
                                    Ninafahamu katika upasuaji huu, halu inaweza kutokea inayolazimu upasuaji/tiba tofauti na ile iliyopendekezwa awali ikafanyika. Ninaruhusu tiba/upasuaji huo kufanyika kama itaonekana kuwa inafaa baada ya kuridhiwa na wataalamu wa upasuaji.
                                    </td>
                                </tr> 
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">2.</td><td colspan="3">
                                    Ninaruhusu kupewa dawa ya usingizi (Anaesthesia) yaani nusu kaputi na timu husika ya wataalamu wa dawa za usingizi. 
                                    </td>
                                </tr> 
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">3.</td><td colspan="3">
                                    Pia, nakiri kuwa faida na madhara ya dawa hizo za usingizi nimeelezwa kwa kina na nimeridhia binafsi baada ya kuelewa. 
                                    </td>
                                </tr> 
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">4.</td><td colspan="3">
                                    Ninaruhusu kupewa damu ikiwa itaonekana ni muhimu na lazima kuongezewa.
                                    </td>
                                </tr>  
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">5.</td><td colspan="5">
                                    Ninakubaliana na matokeo yoyote ya tiba/upasuaji huu. 
                                    </td>
                                </tr>
                                <tr>
                                    <td width="1%" style="font-weight: bold; text-align: right;">6.</td><td colspan="5">
                                    Mgonjwa aelezwe kuwa BMC inatumika kwa kufundishia Wanafunzi wa fani ya afya.  
                                    </td>
                                </tr>            
                            </table>';

                    $htm .='<table align="center" width="100%" style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 14px">
                                <tr>
                                <td colspan="4"><hr width="100%"></td>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align: center;">MAKUBALIANO YA RIDHAA</th>
                            </th>
                            </tr>';
                            if($presense_of_students == 'Agree'){
                                $presense_of_students = "checked='checked'";
                            }elseif($presense_of_students == 'Disagree'){
                                $presense_of_students2 = "checked='checked'";
                            }
                            if($photography_on_surgery == 'Agree'){
                                $photography_on_surgery = "checked='checked'";
                            }elseif($photography_on_surgery == 'Disagree'){
                                $photography_on_surgery2 = "checked='checked'";
                            }            
                            if($consent_amputation == 'Agree'){
                                $consent_amputation = "checked='checked'";
                            }elseif($consent_amputation == 'Disagree'){
                                $consent_amputation2 = "checked='checked'";
                            }

                            if($consent_by == 'Mgonjwa'){
                                $consent_by_1 = "checked='checked'";
                            }elseif($consent_by == 'Mlezi'){
                                $consent_by_2 = "checked='checked'";
                            }elseif($consent_by == 'Director'){
                                $consent_by_3 = "checked='checked'";
                            }
                    $htm .="<tr>
                            <td colspan='4'> 
                            ".$discusion."
                            <td>
                            </tr>
                            <tr>
                                <td colspan='2'><b>Uwepo wa wanafunzi wakati wa tiba/upasuaji: </b><br><input type='checkbox' $presense_of_students >Ninaruhusu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' $presense_of_students2 >Siruhusu &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan='2'><b>Picha/Taarifa inayohusiana na Operesheni/Upasuaji/matibabu haya kutumika kwa tafiti zaidi na/au kufundishia ikiwa wataalam wataona inafaa kufanya hivyo bila shuruti na kwa usiri(Confidentiality) wa mgonjwa: </b><br><input type='checkbox' $photography_on_surgery >Ninakubali &nbsp;&nbsp;<input type='checkbox' $photography_on_surgery2 >Sikubali</td></tr>
                                <tr>
                                <tr>
                                    <td colspan='4'><hr width='100%'></td>
                                </tr>
                                <tr>
                                    <th colspan='4' style='text-align: center;'> AMPUTATION </th>
                                </tr>
                                <tr>
                                    <td colspan='4'><b><input type='checkbox' $consent_amputation >Ninakubali &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_amputation2 >Sikubali </b>Kuondolewa/kukatwa kiungo changu cha mwili ambacho ni <b> $Amptutation_of </b></td></tr>
                                <tr>
                                    <td colspan='4'><hr width='100%'></td>
                                </tr>
                                <tr>
                                    <th colspan='4' style='text-align: center;'>Consent Signed by: <input type='checkbox' $consent_by_1 >Mgonjwa &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_2 >Mlezi  &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_3 >Director </th>
                                </tr>";
                    
                    $htm .='
                <tr>
                <td colspan="4">
                
                </td>
                </tr>
                </tr>
                <tr>
                <td style="font-weight: bold;">
                '.$Patient_Name.'
                </td>
                <td  style="text-align:center; ">'.$Patientsignature.'</td>
                <td  width="25%" style="text-align:center; ">'.$date.' </td>
                <td  style="text-align:center; "> </td>
                </tr>
                <tr>
                <td>
                Jina la Mgonjwa 
                </td>
                <td style="text-align:center; ">Saini ya Mgonjwa</td>
                <td style="text-align:center; ">Tarehe</td>
                <td style="text-align:center; "></td>
                </tr>
                <tr>
                <td style="font-weight: bold;">'.$on_behalf_name.'</td>
                <td  style="text-align:center; ">'.$witnessignature.'</td>
                <td  style="text-align:center; ">'.$date.' </td>
                <td  style="text-align:center; "> </td>
                </tr>
                <tr>
                <td style="text-align:; ">Jina la Mlezi</td>
                <td style="text-align:center; ">Saini ya Mlezi</td>
                <td style="text-align:center; ">Tarehe</td>
                <td style="text-align:center; "></td>
                </tr>
                <tr>
                <td style=" font-weight: bold;">Dr. '.$doctortype.'
                </td>
                <td  style="text-align:center; ">'.$signature.'</td>
                <td  style="text-align:center; ">'.$date.' </td>
                <td  style="text-align:center; "> </td>
                </tr>
                <tr>
                <td style="text-align:; ">'.$designation.'</td>
                <td style="text-align:center; ">Saini ya Daktari</td>
                <td style="text-align:center; ">Tarehe</td>
                <td style="text-align:center; "></td>
                </tr>
                    </table>';
    }
    
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