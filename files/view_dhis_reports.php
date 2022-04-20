<?php
session_start();
include("../includes/connection.php");
if (isset($_POST['action'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $temp = 1;
    //today function
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
    if ($_POST['action'] == 'view') {
        $select = $_POST['select'];
        if ($select == 'mtotonamama') {
            echo '<center><table style="width:120%">';
            echo '<tr id="thead">
            <th style="width:5%;"><b>Na.</b></th>
            <th><b>Namba ya Kadi RCH-4</b></th>
             <th><b>Jina la mama</b></th>
                 <th><b>Umri</b></th>
                 <th><b>Jinsi</b></th>
                     <th><b>Tarehe ya Kujifungua</b></th>
                                 <th><b>Tarehe ya kuandikishwa postnatal</b></th>                                
                                 <th><b>Amejifungua Mara Ngapi(Para)</b></th>
                                 <th><b>Hali ya mama</b></th>
                                 <th><b>Hali ya mtoto</b></th>
                                 <th><b>Unyonyeshaji ndani ya saa moja</b></th>
                                 <th><b>Hali ya VVU kama inavyoonekana kwenye kadi</b></th>
                                 <th><b>Kipimo cha VVU wakati wa Postnatal</b></th>
                                 <th><b>Mahali alipojifungulia</b></th>
                                 <th><b>Kada ya aliyemzalisha</b></th>
                                 <th><b>BP</b></th>
                                 <th><b>HB</b></th>
                                 <th><b>Hali ya matiti</b></th>
                                 <th><b>Tumbo la Uzazi</b></th>
                                 <th><b>Rangi ya Lochia</b></th>
                                 <th><b>Hudhurio</b></th>
                                 <th><b>Tarehe ya Hudhurio</b></th>
                                 <th><b>Hali ya msamba</b></th>
                                 <th><b>Fistula</b></th>
                                 <th><b>Akili Timamu</b></th>
                                 <th><b>Uzazi wa mpango </b></th>
                                 <th><b>Hudhurio la mtoto</b></th>
                                 <th><b>Tarehe ya hudhurio la mtoto</b></th>
                                 <th><b>ARV</b></th>
                                 <th><b>Muda</b></th>
                                 <th><b>Ulishaji wa mtoto</b></th>
                                 <th><b>Aina ya dawa</b></th>
                                 <th><b>Idadi ya dawa</b></th>
                                 <th><b>Vitamin A</b></th>
                                 <th><b>Chanjo ya TT</b></th>
                                 <th><b>Joto la mtoto</b></th>
                                 <th><b>Chanjo</b></th>
                                 <th><b>Uzito wa mtoto(g/Kg)</b></th>
                                 <th><b>HB(g/dl au %)</b></th>
                                 <th><b>KMC</b></th>
                                 <th><b>Kitovu</b></th>
                                 <th><b>Ngozi</b></th>
                                 <th><b>Mdomo</b></th>
                                 <th><b>Macho</b></th>
                                 <th><b>Jaundice</b></th>
                                 <th><b>Uambukizo mkali</b></th>
                                 <th><b>Alikopelekwa</b></th>
                                 <th><b>Alikotokea</b></th>
                                 <th><b>Sababu ya rufaa/Maoni</b></th>
                            </tr>';
            $select_Filtered_Patients = mysqli_query($conn,
                    "SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name,pn.Mother_ID,
                    pn.RCH_4_Card_No,pn.Reg_Date,pn.Para,pn.Tarehe_ya_kujifungua,pn.Mahali_Alipojifungulia,pn.Kada_ya_mzalishaji,   pn.Hali_ya_mama,pn.Hali_ya_mtoto,pn.Unyonyeshaji_withn_1_hr,
                    pn.Hali_ya_VVU,pn.Kipimo_VVU,pn.Hudhurio,pn.Hudhurio_Date,pn.BP,pn.HB,pn.Matiti_hali,
                    pn.Uzazi_tumbo,pn.Rangi,pn.Msamba_hali,pn.Fistula,pn.Akili_Timamu,pn.Aina_Dawa,pn.Idadi_Dawa,pn.Idadi_Vitamin,pn.Chanjo_TT,pn.Family_planing,pn.Hudhurio_la_mtoto,
                    pn.tareh_ya_hururio_la_mtoto,pn.Mtoto_gender,pn.Joto,pn.Chanjo,pn.Uzito_wa_mtoto,pn.mtoto_HB,pn.KMC,pn.Kitovu,pn.Ngozi,pn.Mdomo,pn.Macho,pn.Jaundice,pn.Uambukizo_Mkali,
                    pn.ARV,pn.ARV_Muda,pn.Ulishaji_wa_mtoto,pn.Rufaa_To,pn.Rufaa_from,pn.Rufaa_reason
                		FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_postnal pn where
                		pr.sponsor_id = sp.sponsor_id and pn.Mother_ID=pr.Registration_ID and pn.Hudhurio_Date between '$fromDate' and '$toDate'  limit 500") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

//AGE FUNCTION
                $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Y, ";
                $age .= $diff->m . " M, ";

                echo "<tr><td width ='2%' id='thead'>" . $temp ."</td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['RCH_4_Card_No'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age ."</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] ."</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Tarehe_ya_kujifungua'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hudhurio_Date'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Para'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hali_ya_mama']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hali_ya_mtoto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Unyonyeshaji_withn_1_hr']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hali_ya_VVU']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kipimo_VVU']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mahali_Alipojifungulia'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kada_ya_mzalishaji'] . "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['BP']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['HB']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Matiti_hali']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uzazi_tumbo']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rangi']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hudhurio']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hudhurio_Date']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Msamba_hali']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Fistula']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Akili_Timamu']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Family_planing']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hudhurio_la_mtoto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['tareh_ya_hururio_la_mtoto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['ARV']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['ARV_Muda']. "</a></td>";

                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Ulishaji_wa_mtoto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Aina_Dawa']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Idadi_Dawa']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Idadi_Vitamin']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Chanjo_TT']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Joto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Chanjo']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uzito_wa_mtoto']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_HB']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['KMC']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kitovu']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Ngozi']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mdomo']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Macho']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Jaundice']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uambukizo_Mkali']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rufaa_To']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rufaa_from']. "</a></td>";
                echo "<td><a href='powercharts_postnatall_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rufaa_reason']. "</a></td>";

               $temp++;
            } echo "</tr></table>";
        } elseif ($select == 'familyplaning') {
            echo '<center><table>';
            echo '<tr id="thead">
           <td style="width:5%;"><b>Na.</b></td>
           <td><b>Tarehe</b></td>
            <td><b>Jina</b></td>
                <td><b>Jinsi</b></td>
                    <td><b>Umri</b></td>
                            <td><b>Aina ya Mteja</b></td>
                                <!--<td><b>Namba ya Utambulisho</b></td>-->
                                <td><b>Amezaa mara ngapi</b></td>
                                <td><b>Mimba zilizoharibika</b></td>
                                <td><b>Watoto hai</b></td>
                                <td><b>Aina na saiko ya vidonge</b></td>
                                <td><b>Kiasi cha Vidonge</b></td>
                                <td><b>Njia za uzazi wa mpango alizochagua katika hudhurio la kwanza</b></td>
                                <td><b>Idadi ya kondom Ke</b></td>
                                <td><b>Idadi ya kondom Me</b></td>
                                <td><b>Uchunguzi matiti</b></td>
                                <td><b>Buje</b></td>
                                <td><b>Kidonda</b></td>
                                <td><b>Chuchu kutoka damu</b></td>
                                <td><b>Jipu katika chuchu</b></td>
                                <td><b>Mengineyo</b></td>
                                <td><b>Uchunguzi shingo ya kizazi</b></td>
                                <td><b>Kutoka uchafu ukeni</b></td>
                                <td><b>Uvimbe kwenye shingo ya kizazi</b></td>
                                <td><b>Mchubuko kwenye shingo ya kizazi</b></td>
                                <td><b>Kutokwa damu ukeni kiurahisi</b></td>
                                <td><b>Uwezekano wa saratani</b></td>
                                <td><b>Mengineyo</b></td>
                                <td><b>Waliopewa huduma ya Cryotherapy</b></td>
                                <td><b>Mimba imeharibika/Baada ya kujifungua</b></td>
                                <td><b>Njia ya uzazi baada ya matibabu/aliyochagua ndani ya siku 42</b></td>
                                <td><b>Kuondoa</b></td>
                                <td><b>Jan</b></td>
                                <td><b>Feb</b></td>
                                <td><b>Machi</b></td>
                                <td><b>April</b></td>
                                <td><b>Mei</b></td>
                                <td><b>Jun</b></td>
                                <td><b>Jul</b></td>
                                <td><b>Ago</b></td>
                                <td><b>Sept</b></td>
                                <td><b>Oct</b></td>
                                <td><b>Nov</b></td>
                                <td><b>Des</b></td>
                                <td><b>Tayari ana maambukizi ya VVU PNU</b></td>
                                <td><b>Matokeo ya kipimo cha VVU cha Mama </b></td>
                                <td><b>Matokeo ya kipimo cha VVU cha Mwenza</b></td>
                                <td><b>Maoni / Maudhui Madogo Madogo</b></td>
                                <td><b>Rufaa</b></td>
                                </tr>';
            $select_Filtered_Patients = mysqli_query($conn,
                    "SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number,sp.Guarantor_Name,fp.Patient_ID,fp.Baada_Kujifungua,fp.Kuondoa,fp.FP_after_matibabu,fp.Visiting_Date,fp.Patient_type,fp.Utambulisho_No,fp.para,
                    fp.Abortions,fp.Watoto_hai,fp.Aina_vidonge,fp.Uzazi_njia,fp.Uchunguzi_matiti,fp.Buje,fp.Kidonda,fp.Kutoka_damu,fp.Jipu,fp.Mengineyo_matiti,fp.Uchunguzi_saratani,fp.Uchafu_ukeni,fp.Uvimbe_kizazi,fp.Mchubuko_kizazi,fp.Damu_ukeni,fp.VIA,fp.Mengineyo_Saratani,fp.Cryotherapy,
                    fp.Marudio_Jan,fp.Marudio_Feb,fp.Marudio_March,fp.Marudio_April,fp.Marudio_May,fp.Marudio_Jun,fp.Marudio_July,fp.Marudio_Aug,fp.Marudio_Sept,fp.Marudio_Oct,fp.Marudio_Nov,fp.Marudio_Des,fp.Ameambukizwa,fp.Mama_matokeo,fp.Mwenza_matokeo,fp.Maoni,fp.Rufaa,fp.Idadi_Kondom_Ke,
                    fp.Idadi_Kondom_Me,fp.Kiasi_vidonge
                    FROM tbl_patient_registration pr, tbl_sponsor sp,tbl_family_planing fp WHERE
                		pr.sponsor_id = sp.sponsor_id and fp.Patient_ID=pr.Registration_ID and Visiting_Date between '$fromDate' and '$toDate'  limit 500") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

                //AGE FUNCTION
                $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y ." y, ";
                $age .= $diff->m ." m, ";
//		$age .= $diff->d." D";
                echo "<tr><td width ='2%' id='thead'>" .$temp . ".<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Visiting_Date'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Patient_type'] . "</a></td>";
                //echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Utambulisho_No'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['para'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Abortions'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Watoto_hai'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Aina_vidonge'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kiasi_vidonge'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uzazi_njia'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Idadi_Kondom_Ke'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Idadi_Kondom_Me'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uchunguzi_matiti'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Buje'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kidonda'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kutoka_damu'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Jipu'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mengineyo_matiti'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uchunguzi_saratani'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uchafu_ukeni'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Uvimbe_kizazi'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mchubuko_kizazi'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Damu_ukeni'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VIA'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mengineyo_Saratani'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Cryotherapy'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Baada_Kujifungua'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['FP_after_matibabu'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Kuondoa'] . "</a></td>";

                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Jan'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Feb'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_March'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_April'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_May'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Jun'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_July'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Aug'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Sept'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Oct'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Nov'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Marudio_Des'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Ameambukizwa'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mama_matokeo'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mwenza_matokeo'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Maoni'] . "</a></td>";
                echo "<td><a href='powercharts_family_planing_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rufaa'] . "</a></td>";

                $temp++;
            }
            echo "</tr></table>";
        } elseif ($select == 'wajawazito'){

            echo '<center><table id="wajawazito">';
            echo '<thead><tr id="thead">
           <th style="width:5%;"><b>Na.</b></th>
           <th><b>Tarehe</b></th>
            <th><b>Jina kamili ya mteja</b></th>
                    <th><b>Umri</b></th>
                            <th><b>Jina la kijiji/kitongoji/Balozi,mtaa/barabara/Na. ya nyumba</b></th>
                                <th><b>Mume/Mwenza(Jina)</b></th>
                                <th><b>Jina la M/kiti serikali ya Mtaa/Kitongoji</b></th>
                                <th><b>Tarehe ya Chanjo ya TT<table><tr><td>\'N\' ana kadi \'H\' Hana kadi</td><td>Andika tarehe ya TT1</td><td>Andika tarehe ya TT2</td></tr></table></b></th>
                                <th><b>Umri wa mimba kwa wiki</b></th>
                                <th><b>Taarifa za Mimba zilizopita<table><tr><td>Mimba ya ngapi</td><td>Umezaa mara ngapi??</td><td>Watoto hai</td><td>Mimba zilizoharibika</td><td>FSB/MSB< kifo cha mtoto katika wiki moja \'N\' Ndiyo</td><td>Umri wa mtoto wa mwisho</td></tr></table></b></th>
                                <th><b>Vipimo/Taarifa Muhimu <table><tr><td>Kiwango cha damu(HB)mfano 11.0</td><td>Shinikizo la damu(BP)</td><td>Urefu (cm)</td><td>Sukari kwenye mkojo</td><td>Kujifungua kwa CS</td><td>Umri chini ya miaka 20</td><td>Umri zaidi ya miaka 35</td></tr></table></b></th>
                                <td><b>Kipimo cha kaswende  <table><tr><td>Matokeo<table><tr><td>KE</td><td>ME</td></tr></table></td><td>Ametibiwa<table><tr><td>KE</td><td>ME</td></tr></table></td></tr></table>  </b></td>
                                <td><b>Vipimo vya magonjwa ya ngono <table><tr><td>Matokeo<table><tr><td>ME</td><td>KE</td></tr></table></td> <td>Ametibiwa<table><tr><td>ME</td><td>KE</td></tr></table></td></tr></table></b></td>
                                <td><b>Mahudhurio ya marudio <table><tr><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td></tr></table></b></td>
                                <td><b>Huduma ya PMTCT <table><tr>  <td>Tayari ana maambukizi ya VVU<table><tr><td>KE</td><td>ME</td></tr></table></td>  <td>Amepata unasihi(Tarehe)<table><tr><td>KE</td><td>ME</td></tr></table></td>  <td>Amepima VVU<table><tr><td>KE</td><td>ME</td></tr></table></td>    <td>Tarehe ya kipimo<table><tr><td>KE</td><td>ME</td></tr></table></td>  <td>Matokeo ya kipimo cha 1 cha VVU<table><tr><td>KE</td><td>ME</td></tr></table></td>  <td>Amepata unasihi baada ya kupima<table><tr><td>KE</td><td>ME</td></tr></table></td>  <td>Matokeo ya kipimo cha pili cha VVU</td>  <td>Amepata ushauri juu ya ulishaji mtoto</td></tr></table> </b></td>
                                <td><b>Malaria <table><tr><td>Matokeo ya MRDT au BS</td><td>Amepata hati Punguzo</td><td>Tarehe ya IPT-1</td><td>Tarehe ya IPT-2</td><td>Tarehe ya IPT-3</td><td>Tarehe ya IPT-4</td></tr></table></b></td>
                                <td><b>Idadi ya Vidonge Iron/Folic Acid<table><tr><td>1</td><td>2</td><td>3</td><td>4</td></tr></table></b></td>
                                <td><b>Amepata Albendazole/Mebendazole</b></td>
                                <td><b>Rufaa <table><tr><td>Tarehe</td><td>Kituo alikopelekwa</td><td>Sababu ya rufaa</td><td>Kituo alikotokea</td></tr></table></b></td>
                                <td><b>Maoni</b></td>

                                </tr></thead>';

            // die("SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number,tw.*
            // from tbl_patient_registration pr,tbl_wajawazito tw
            // where tw.Patient_ID=pr.Registration_ID and hudhurio_tarehe between '$fromDate' and '$toDate'  limit 500");

            $select_Filtered_Patients = mysqli_query($conn,
                "SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number,tw.*
                from tbl_patient_registration pr,tbl_wajawazito tw
                where tw.Patient_ID=pr.Registration_ID and hudhurio_tarehe between '$fromDate' and '$toDate'  limit 500") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

                //AGE FUNCTION
                $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y ." y, ";
                $age .= $diff->m ." m, ";
//		$age .= $diff->d." D";
                echo "<tr><td width ='2%' id='thead'>" .$temp . ".<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['hudhurio_tarehe'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
//                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtaa_jina'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mwenza_jina'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mwenyekiti_jina'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['anakadi']."</td> <td>".$row['tt1tarehe']."</td><td>".$row['tt2tarehe']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mimba_umri'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['mimba_no']."</td><td>".$row['amezaa_mara']."</td><td>".$row['watoto_hai']."</td><td>".$row['abortions']."</td><td>".$row['fsb']."</td><td>".$row['mwisho_age']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['damu_kiwango']."</td><td>".$row['Bp']."</td><td>".$row['urefu']."</td><td>".$row['mkojo_sukari']."</td><td>".$row['kufunga_CS']."</td><td>".$row['under_20']."</td><td>".$row['under_35']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td><table><tr><td>".$row['kaswende_matokeo_ke']."</td><td>".$row['kaswende_matokeo_me']."</td></tr></table></td>    <td><table><tr><td>".$row['kaswende_ametibiwa_ke']."</td><td>".$row['kaswende_ametibiwa_me']."</td></tr></table></td> </tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td><table><tr><td>".$row['ng_matokeo_me']."</td><td>".$row['ng_matokeo_ke']."</td></tr></table></td>    <td><table><tr><td>".$row['ng_ametibiwa_me']."</td><td>".$row['ng_ametibiwa_ke']."</td></tr></table></td> </tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['marudio_2']."</td><td>".$row['marudio_3']."</td><td>".$row['marudio_4']."</td><td>".$row['marudio_5']."</td><td>".$row['marudio_6']."</td><td>".$row['marudio_7']."</td><td>".$row['marudio_8']."</td><td>".$row['marudio_9']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr> <td><table><tr><td>".$row['ana_VVU_ke']."</td> <td>".$row['ana_VVU_me']."</td></tr></table></td>    <td><table><tr><td>".$row['unasihi_ke']."</td> <td>".$row['unasihi_me']."</td></tr></table></td>  <td><table><tr><td>".$row['amepima_VVU_ke']."</td> <td>".$row['amepima_VVU_me']."</td></tr></table></td>   <td><table><tr><td>".$row['kipimo_tarehe_ke']."</td> <td>".$row['kipimo_tarehe_me']."</td></tr></table></td>   <td><table><tr><td>".$row['kipimo_1_VVU_matokeo_ke']."</td> <td>".$row['kipimo_1_VVU_matokeo_me']."</td></tr></table></td>    <td><table><tr><td>".$row['unasihi_kupima_ke']."</td> <td>".$row['unasihi_kupima_me']."</td></tr></table></td>  <td>".$row['matokeo_VVU_2']."</td>  <td>".$row['amepata_ushauri']."</td>   </tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['mrdt']."</td><td>".$row['hatipunguzo']."</td><td>".$row['IPT1']."</td><td>".$row['IPT2']."</td><td>".$row['IPT3']."</td><td>".$row['IPT4']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['vidonge_aina_1']."(".$row['vidonge_idadi_1'].")</td> <td>".$row['vidonge_aina_2']."(".$row['vidonge_idadi_2'].")</td> <td>".$row['vidonge_aina_3']."(".$row['vidonge_idadi_3'].")</td> <td>".$row['vidonge_aina_4']."(".$row['vidonge_idadi_4'].")</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mabendazol'] . "</a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><table><tr><td>".$row['rufaa_tarehe']."</td><td>".$row['alikopelekwa']."</td><td>".$row['rufaa_sababu']."</td><td>".$row['alikotokea']."</td></tr></table></a></td>";
                echo "<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['maoni'] . "</a></td>";
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
               $temp++;
            }

            echo "</tr></table>";



        } elseif ($select == 'watoto'){

            echo '<center><table>';
            echo '<tr id="thead">
           <td style="width:5%;"><b>Na.</b></td>
           <td><b>Tarehe</b></td>
            <td><b>Namba ya Utambulisho</b></td>
                <td><b>Namba ya Usajili wa vizazi</b></td>
                    <td><b>Jina la Mtoto</b></td>
                            <td><b>Tarehe ya kuzazliwa</b></td>
                                <td><b>Mahali anapoishi</b></td>
                                <td><b>Jinsi(ME/KE)</b></td>
                                <td><b>Jina la Mama</b></td>
                                <td><b>Ana kinga ya pepopunda</b></td>
                                <td><b>Hali ya VVU</b></td>
                                <td><b>HEID No</b></td>
                                <td><b>Tarehe ya Chanjo BCG</b></td>
                                <td><b>Tarehe ya Chanjo OPVO</b></td>
                                <td><b>Tarehe ya Chanjo Penta 1</b></td>
                                <td><b>Tarehe ya Chanjo Penta 2</b></td>
                                <td><b>Tarehe ya Chanjo Penta 3</b></td>
                                <td><b>
                                Tarehe ya Chanjo ya polio
                                <table><tr><td style="width:200px;text-align:center">1</td><td style="width:200px;text-align:center">2</td><td style="width:200px;text-align:center">3</td></tr></table>
                                </b></td>
                                <td><b>
                                Tarehe ya Chanjo pneumococcal(PCV13)
                                <table><tr><td style="width:200px;text-align:center">1</td><td style="width:200px;text-align:center">2</td><td style="width:200px;text-align:center">3</td></tr></table>
                                </b></td>
                                <td><b>
                                Tarehe ya Chanjo ya Rota
                                <table><tr><td style="width:300px;text-align:center">1</td><td style="width:300px;text-align:center">2</td></tr></table>
                                </b></td>
                                <td><b>
                                Tarehe ya Chanjo ya Surua/Rubella
                                <table><tr><td style="width:300px;text-align:center">1</td><td style="width:300px;text-align:center">2</td></tr></table>
                                </b></td>
                                <td><b>
                                Vitamin A
                                <table><tr><td>Miezi 6</td><td>chini ya mwaka</td><td>mwaka 1-5</td></tr></table>
                                </b></td>
                                <td><b>
                                Ukuaji wa mtoto(1=>80% au >-2SD,2=80% au -2SD--3SD;3=<60% au <-3SD)
                                <table><tr><td>Miezi 9</td><td>Miezi 18</td><td>Miezi 36</td><td>Miezi 48</td></tr></table>
                                </b></td>
                                <td><b>
                                Mebendazole/Albendazole kila miezi 6
                                <table><tr><td>Miezi 12</td><td>Miezi 18</td><td>Miezi 24</td><td>Miezi 30</td></tr></table>
                                </b></td>
                                <td><b>Hati punguzo chandarua</b></td>
                                <td><b>
                                Ulishaji wa mtoto
                                <table><tr><td>Maziwa ya mama pekee(EBF)</td><td>Maziwa mbadala</td></tr></table>
                                </b></td>
                                <td><b>
                                Rufaa
                                <table><tr><td>Kituo alikotoka mtoto</td><td>Kituo alikopelekwa</td><td>Sababu ya rufaa</td></tr></table>
                                </b></td>
                                <td><b>Maelezo mengineyo/Maoni</b></td>
                                </tr>';
            $select_Filtered_Patients = mysqli_query($conn,
                "select * from tbl_watoto tw where Tarehe between '$fromDate' and '$toDate'  limit 500") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

                //AGE FUNCTION
//                $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
//                $date1 = new DateTime($Today);
//                $date2 = new DateTime($row['Date_Of_Birth']);
//                $diff = $date1->diff($date2);
//                $age = $diff->y ." y, ";
//                $age .= $diff->m ." m, ";
//		$age .= $diff->d." D";
                echo "<tr><td width ='2%' id='thead'>" .$temp . ".<td><a href='powercharts_Wajawazito_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Tarehe'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Identity_No'])) . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Birth_reg_No'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mtoto_Jina']. "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Birth_date'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Address'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Jinsi'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mama_Jina'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Ana_TT2'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VVU_Hali'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['HEID_No'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['BCG'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['OPVO'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PENTA_1'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PENTA_2'] . "</a></td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PENTA_3'] . "</a></td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Polio_1'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Polio_2'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Polio_3'] . "</a></td></tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PCV_1'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PCV_2'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PCV_3'] . "</a></td></tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rota_1'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rota_2'] . "</a></td> </tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Surua_1'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Surua_2'] . "</a></td> </tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VM_6'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['V_U_mwaka'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['V_mwaka_1_5'] . "</a></td></tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VM_6'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['V_U_mwaka'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['V_mwaka_1_5'] . "</a></td></tr></table> </td>";
//

//                 UKUAJI

                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['AM_12'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['AM_18'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['AM_24'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['AM_30'] . "</a></td></tr></table> </td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Hati_punguzo'] . "</a></td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Mama_maziwa'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['maziwa_mbadala'] . "</a></td> </tr></table> </td>";
                echo "<td><table><tr><td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kituo_alikotoka'] . "</a></td>  <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['alikopelekwa'] . "</a></td> <td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Rufaa_sababu'] . "</a></td></tr></table> </td>";
                echo "<td><a href='powercharts_Watoto_real_edit.php?pn=" . $row['watoto_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['maoni'] . "</a></td>";



                $temp++;
            }
            echo "</tr></table>";



        } elseif ($select == 'wazazi'){
           echo '<center><table id="wazazi" class="display">';
           echo '<thead><tr id="thead">
           <th style="width:5%;"><b>Na.</b></th>
           <th><b>Namba ya Jalada</b></th>
            <th><b>Jina la mama</b></th>
                    <th><b>Umri</b></th>
                            <th><b>Namba ya Kadi ya Uzazi(RCH-4)</b></th>
                                <th><b>Mimba ya ngapi(Gravida)</b></th>
                                <th><b>Amezaa mara ngapi(Para)</b></th>
                                <th><b>Watoto Hai(Alive)</b></th>
                                <th><b>Jinsi</b></th>
                                <th><b>Tarehe na muda wa kulazwa</b></th>
                                <th><b>Tarehe na muda wa kujifungua</b></th>
                                <th><b>Taarifa kuhusu uchungu</b></th>
                                <th><b>Alipojifungulia</b></th>
                                <th><b>Njia ya kujifungua</b></th>
                                <th><b>Jinsi ya Mtoto(KE/ME)</b></th>
                                <th><b>Mtoto amesaidiwa kupumua</b></th>
                                <th><b>APGAR</b></th>
                                <th><b>Amenyonyeshwa ndani ya saa moja ya kuzaliwa</b></th>
                                <th><b>Uzito wa mtoto kwa gm/Kg</b></th>
                                <th><b>Tathmini ya mtoto baada ya masaa 24</b></th>
                                <th><b>FSB/MSB</b></th>
                                <th><b>PA</b></th>
                                <th><b>PPH</b></th>
                                <th><b>Mama amepewa sindano ya antibiotic</b></th>
                                <th><b>Oxytocin/ergometrin/misoprostol</b></th>
                                <th><b>inj.Magnesium sulfate</b></th>
                                <th><b>Amefanyiwa MVA/D&C</b></th>
                                <th><b>Kuongezewa damu</b></th>
                                <th><b>Mama amekeketwa</b></th>
                                <th><b>Kipimo cha VVU</b></th>
                                <th><b>Kipimo cha VVU wakati wa uchungu na baada ya kujifungua 	</b></th>
                                <th><b>Dawa za ARV kwa Mtoto</b></th>
                                <th><b>Ulishaji wa mtoto</b></th>
                                <th><b>Hali ya mama</b></th>
                                <th><b>Hali(Mama)</b></th>
                                <th><b>Tarehe ya Kuruhusiwa/Kufariki(Mama)</b></th>
                                <th><b>Sababu za kifo(Mama)</b></th>
                                <th><b>Hali ya mtoto</b></th>
                                <th><b>Hali(Mtoto)</b></th>
                                <th><b>Tarehe ya Kuruhusiwa/Kufariki(Mtoto)</b></th>
                                <th><b>Sababu za kifo(Mtoto)</b></th>
                                <th><b>Alikopokelewa</b></th>
                                <th><b>Sababu</b></th>
                                <th><b>Jina la Mzalishaji</b></th>
                                <th><b>Kada</b></th>
                                </tr><thead>';

            // die("SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number,sp.Guarantor_Name,tw.*
            //  FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_wazazi tw
            //  WHERE pr.sponsor_id = sp.sponsor_id and tw.Patient_No=pr.Registration_ID and admission_date
            //  BETWEEN '$fromDate' and '$toDate' limit 500");

            $select_Filtered_Patients = mysqli_query($conn,
                "SELECT pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number,sp.Guarantor_Name,tw.*
                 FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_wazazi tw
                 WHERE pr.sponsor_id = sp.sponsor_id and tw.Patient_No=pr.Registration_ID and admission_date
                 BETWEEN '$fromDate' and '$toDate' limit 500") or die(mysqli_error($conn));
               //tbl_wazazi
            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

                //AGE FUNCTION
                $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y ." y, ";
                $age .= $diff->m ." m, ";
//		$age .= $diff->d." D";
                echo "<tr><td width ='2%' id='thead'>" .$temp . ".<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['jalada_no'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                //echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                //echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['jalada_no'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['rch_no'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['gravida'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['para'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['watoto_hai'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_jinsi'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['admission_date'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kujifungua_trh'] . "</a></td>";
                //echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kujifungua_trh'] . "</a></td>";
                //echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtotoUzito'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['uchungu'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['jifungulia'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kujifungua_njia'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_jinsi'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kupumua'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['apgar'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['nyonyeshwa'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtotoUzito'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['tathmin'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['MSB'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['AP'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['PPH'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['antibiotic'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['miso'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['sulfate'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['MVA'] . "</a></td>";

                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['ongeza_damu'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['FGM'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VVU_Kipimo'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['VVU_uchungu'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['ARV_mtoto'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_ulishaji'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mama_hali'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mama_hali_details'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mama_discharge'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kifo_mama_sababu'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_hali'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_hali_details'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mtoto_discharge'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kifo_mtoto_sababu'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['alikopelekwa'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['sababu_rufaa'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['mzalishaji'] . "</a></td>";
                echo "<td><a href='powercharts_Wazazi_real_edit.php?pn=" . $row['Registration_ID'] . "&sn=".$row['wazazi_id']."&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['kada'] . "</a></td>";

                $temp++;
            }
            echo "</tr></table>";










        }
    }
}
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<!--<script src="media/js/jquery.js" type="text/javascript"></script>-->
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>

<style>
    .rotate{
     -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
       -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
  -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
             filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
         -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */

    padding-right:50px;
    width: 100%;
}
</style>

<script type="text/javascript">
//    ,#wajawazito
    $(document).ready(function(){
      $('#wazazi').DataTable({
          "bJQueryUI":true
      });
    });
</script>
