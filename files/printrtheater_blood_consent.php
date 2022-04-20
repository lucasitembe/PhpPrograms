<style>
    p{
        text-align: justify;
    }
</style>
<?php
//session_start();
include("./includes/connection.php");
session_start();
$Consent_ID = $_GET['Consent_ID'];
$Registration_ID = $_GET['Registration_ID'];

if (!empty($Registration_ID)) {
            $select_Patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE   Registration_ID = '$Registration_ID'");
                        while ($row = mysqli_fetch_array($select_Patient)) {
                        $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                    //     $Date_Of_Birth = $row['Date_Of_Birth'];
                        $Gender = $row['Gender'];
                        $Patient_sgnature=$row['patient_signature'];
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
// esign/patients_signature signature/uploadwitnessign
     }

     

    $select_conset_detail = mysqli_query($conn,"SELECT * FROM tbl_consert_blood_forms_details WHERE   Registration_ID = '$Registration_ID' AND Consent_ID = '$Consent_ID'");
    if(mysqli_num_rows($select_conset_detail) > 0){
        while ($row = mysqli_fetch_assoc($select_conset_detail)) {
            $consent_by = $row['consent_by'];
            $Signed_at = $row['Signed_at'];
            $consent_amputation = $row['consent_amputation'];
            $behalf = $row['behalf'];
    
        }
    }


     $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
     $Patientsignature="<img src='../esign/patients_signature/$Patient_sgnature' style='height:25px'>";
     $witnessignature="<img src='../signaturesignatur/$witnessign' style='height:25px'>";


            $htm .= '
                <table align="center" width="100%" style="color:black;text-align:center; text; font-family: serif;">
                
                            <tr><td style="text-align:center; width: 100%;"><img src="./branchBanner/branchBanner.png"></td></tr>
                            <tr><td style="text-align:center; font-size: 16px;"><b>FOMU YA RIDHAA YA KUONGEZEWA DAMU - BLOOD TRANSFUSION</b></td></tr>
                            <tr>
                                <td><hr style="width: 100%;"></td>
                            </tr>
                            <tr><td style="text-align:center ;font-size: 15px;">JIna la Mgonjwa: <b>'.$Patient_Name.'</b>&nbsp;&nbsp;|&nbsp;Umri: <b>'.$age.'</b> &nbsp;&nbsp;|&nbsp;Jinsia: <b>'.$Gender.'</b></td>               
                            </tr>
                            <tr>
                            <td style="text-align:center;font-size: 15px;">Namba ya Faili: <b>'.$Registration_ID.'</b></td>               
                            </tr>
                            <tr>
                            <td><hr style="width: 100%;"></td>
                        </tr>

                        </table>';
                
            $htm .= '
                        <table align="center" width="100%" style="color:black;text-align:center; text; font-family: serif;font-size: 14px;">
                        <tr>
                        <td style="text-align: justify;"> 
                            <p>Ninaelewa kwamba, ni haki yangu kupewa taarifa zinazohusu huduma ninazopewa na kufanya maamuzi juu ya kuongezewa damu kama sehemu ya matibabu yangu kama ilishauriwa na daktari wangu.
                            <p>   
                        </td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <th> 				
                        MATIBABU PENDEKEZWA 
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: justify;"> 
                            <p>Baada ya kujadiliana na daktari, nimeelewa kwamba kuongezewa damu au mazao ya damu ni muhimu kama sehemu ya matibabu yangu. Ili kufanikisha hili nimeelekezwa kwamba sampuli ya damu itachukuliwa kutoka kwenye mwili wangu na kupelekwa maabara kwa ajili ya kupima wingi wa damu, kundi la damu, pamoja na mlinganisho wa damu yangu na damu nitakayoongezewa. Pia nimeelezwa kwamba uongezewaji wa damu utafanyika kwa utaalamu wa hali ya juu na kwamba damu nitakayoonezewa ni salama kwa kiasi kikubwa.
                            <p>   
                        </td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                    <th> 				
                        MATIBABU MBADALA 
                        </th>
                    </tr>
                    <tr>
                    <td style="text-align: justify;"> 
                        <p>Ninafahamu  kwamba kuna mbadala wa kuongezewa damu ikiwepo kukataa kuongezewa damu. Mbadala mwengine waweza kuwa:-.
                        <p><b>></b> Kuongezewa vitu visivyotokana na damu, mfano vitamini mbalimbali, vitakavyosaidia mwili wangu kutengeneza damu.
                        <p><b>></b> Dawa nyinginezo ili kusaidia mfumo wangu wa damu au kuounguza madhara ya upungufu wa damu.
                        <p><br>
                        <p>Naelewa kwamba tiba mbadala zina ufanisi mdogo na zinafanya kazi polepole kuliko kuongezewa damu au mazao ya damu. Naelewa kwamba kukataa kuongezewa damu kunaweza kupelekea madhara makubwa kwenye moyo wangu au viungo vingine kutokana na upungufu wa damu, na kunawezza kusababisha shambulio la moyo (Heart attack), kiharusi (Stroke), na madhara mengine ikiwemo kifo.
                    </td>
                </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <th> 				
                        MADHARA 
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: justify;"> 
                            <p>Naelewa kwamba licha ya utaalamu na umakini wa madaktari na wahudumu wengine wa afya, kuna uwezekano wa kupata madhara yatokanayo na kuongezewa damu. Madhara mengi ni madogo na yanatibika kirahisi, kuna uwezekano mdogo wa kifo au kupata ulemavu wa kudumu endapo nitapata madhara makubwa yatokanayo na kuongezewa damu. Ninafahamu pia kwamba kuna uwezekano wa kupata madhara kwenye mapafu ambayo ni madhara adimu ila ni makubwa kutokana na mjibizo wa mwili dhidi ya damu anayoongezewa mtu.
                        </td>      
                    </tr>                      
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td> 
                            <p>Nimeelezwa kwamba japo kuna uwezekano mdogo wa kupata madhara yatokanayo na kuongezewa damu, wataalamu wa afya wamesomea na wanafahamu namna ya kuzitambua dalili za madhara hayo endapo madhara hayo yatatokea kwa bahati mbaya.
                        <td>
                    </tr>
                    <tr>
                        td><br></td>
                    </tr>    
                    <tr>
                        <td style="text-align: justify;">
                            <p>Naelewa kwamba damu itakayotumika imekusanywa na kituo cha ukusanyaji damu kutokana kwa wachangiaji wa hiari kwa utaratibu uliowekwa wa kitaalamu na kwamba damu imepimwa magonjwa yanayoweza kuwambukizwa kwa kupitia huduma ya kuongezewa damu kwamba kuna uwezekano mdogo sana wa kuongezewa damu yenye maambukizi. Pamoja na taratibu zote hizi kuna uwezekano mdogo wa maambukizi ya Bacteria, Malaria, Hoka ya Ini B na C (Hepatitis B&C), Kaswende na Virusi ya Ukimwi (HIV) na maambukizi mengineyo kulingana na teknolojia inayotumika na Mpango wa Taifa wa Damu Salama katika upimaji.
                            <p><br>
                            <p>Naelewa kwamba iwapo nahitaji kuongezewa damu au mazao ya damu kwa dharula, hali hiyo ya dharula inaweza kupelekea kutumika kwa damu ambayo haijafanyiwa vipimo kamili kama kundi la damu na mlinganisho wa damu yangu na nitakayoongezewa (Compatibility Testing). Hata hivyo damu hiyo itakuwa ile inayoweza kutumika bila kuwa na madhara au ina uwezekano mdogo wa kusababisha madhara.
                            <p><br>  
                        </td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <th> 				
                        KURIDHIA AU KUTORIDHIA 
                        </th>
                    </tr>
                    <tr>
                        <td style="text-align: justify;"> 
                            <p>Naelewa kwamba nina haki na nafasi ya kuongea na daktari anayenitibu kuhusu tiba ya kuongezewa damu au mazao ya damu na kujadili kwa undani kuhusu faida na madhara yake pamoja na njia mbadala zilizopo na madhara yake. Kwa kutia saini hapa chini ninathibitisha kwamba nimeelewa faida, madhara na tiba mbadala zilizopo, na kwamba nimepata wasaa wa kuuliza maswali na kueleweshwa vyema.
                        </td>
                    </tr>           
                        </table>';

                // $htm .="<table align='center' width='100%' style='color:black;text-align:center; text; font-family: serif;font-size: 14px;'>
                //         <tr>
                //             <th colspan='4' style='text-align: center;'>MAKUBALIANO YA RIDHAA </th>
                //         </th>
                //         </tr>";          

                    if($consent_amputation == 'Agree'){
                        $consent_amputation1 = "checked='checked'";
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
                    if ($consent_by == 'Mgonjwa'){
                        $signed_by = $Patient_Name;
                        }else{
                        $signed_by = $behalf;
                        }
                
// echo $consent_amputation;

                $htm .="
                <table align='center' width='100%' style='color:black;text-align:center; text; font-family: serif;font-size: 14px;'>

                <tr>
                    <th>MAKUBALIANO YA RIDHAA</th>
                </tr>
                <tr>
                <td colspan='4'><hr width='100%'></td>
            </tr>
                <tr>
                    <td colspan='4'><input type='checkbox' $consent_amputation1 ><b>NINAKUBALI &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_amputation2 >NINAKATAA</b> &nbsp; Kupata huduma ya kuongezewa damu kutokana na maelezo hapo juu.</td>
                </tr>
                <tr>
                    <td colspan='4'><hr width='100%'></td>
                </tr>
                <tr>
                    <th colspan='4' style='text-align: center;'>Imeridhiwa Na: <input type='checkbox' $consent_by_1 >Mgonjwa &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_2 >Mlezi  &nbsp;&nbsp;&nbsp;<input type='checkbox' $consent_by_3 >Director </th>
                </tr>
                <tr>
                    <td colspan='4'><br></td>
                </tr>
            <tr>
            <td style='font-weight: bold;'>".$signed_by."
            </td>
            <td  style='text-align:center; '>".$Patientsignature."</td>
            <td  width='25%' style='text-align:center; font-weight: bold;'>".$Signed_at." </td>
            <td  style='text-align:center; '> </td>
            </tr>
            <tr>
            <td>
            Jina la ".$consent_by." 
            </td>
            <td style='text-align:center; '>Sahihi</td>
            <td style='text-align:center; '>Tarehe</td>
            <td style='text-align:center; '></td>
            </tr>
                </table>";
    
    
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