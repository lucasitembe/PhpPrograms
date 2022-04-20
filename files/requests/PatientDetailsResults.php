<?php
session_start();
include("../includes/connection.php");
$consultation_ID = '';
$Registration_ID = '';
$Patient_Payment_ID = '';
$Patient_Payment_Item_List_ID = '';
$consultedDate = '';
$consultType = '';
$doctorsworkpage = '';
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
}if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}if (isset($_GET['consultedDate'])) {
    $consultedDate = $_GET['consultedDate'];
}if (isset($_GET['consultType'])) {
    $consultType = $_GET['consultType'];
}if (isset($_GET['doctorsworkpage'])) {
    $doctorsworkpage = $_GET['doctorsworkpage'];
}

//echo $consultation_ID.' '.$Registration_ID.' '.$Patient_Payment_ID.' '.$Patient_Payment_Item_List_ID.' '.$consultedDate.' '.$consultType.' '.$doctorsworkpage;
//echo $consultedDate;exit;
if ($consultType == 'Radiology') { //check for radiology result
    //echo 'Radiology';
    //SELECTING PATIENTS LIST
    $qr = "SELECT * FROM
			tbl_radiology_patient_tests rpt INNER JOIN tbl_items i
			ON rpt.Item_ID = i.Item_ID 
                        JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID
                        JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
			WHERE rpt.Registration_ID = '$Registration_ID' AND
			pc.consultation_id ='$consultation_ID' AND
			rpt.Status = 'done'
			
			";

    //DIE($qr2);		

    echo '<table width="100%">';
    echo '<tr style="text-transform:uppercase; font-weight:bold;" id="thead">';
    echo '<td style="width:3%;">SN</td>';
    echo '<td>Test Name</td>';
    echo '<td>Doctor Comment</td>';
    echo '<td>Radiology Remarks</td>';
    echo '<td>Radiologist</td>';
    echo '<td>Sonographer</td>';
    echo '<td>Date</td>';
    echo '<td>Results</td><br/>';
    echo '</tr>';

    $select_patients_qry = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    $sn = 1;

    while ($patient = mysqli_fetch_assoc($select_patients_qry)) {

        $patient_numeber = $patient['Registration_ID'];
        $test_name = $patient['Product_Name'];
        $remarks = '<textarea  readonly="readonly">' . $patient['Remarks'] . '</textarea>';
        if (empty($patient['Remarks'])) {
            $remarks = '<textarea  readonly="readonly">NONE</textarea>';
        }
        $Registration_ID = $patient['Registration_ID'];
        $served_date = $patient['Date_Time'];
        $Radiologist = $patient['Radiologist_ID'];
        $Sonographer = $patient['Sonographer_ID'];
        $Patient_Payment_Item_List_ID = $patient['Patient_Payment_Item_List_ID'];
        $Item_ID = $patient['Item_ID'];

        $Patient_Payment_Item_List_ID = $patient['Patient_Payment_Item_List_ID'];

        $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

        $ppID = mysqli_fetch_assoc($rs);
        $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
        if (mysqli_num_rows($rs) == 0) {
            $Patient_Payment_ID = 0;
        }
        //die($href);
        $listtype = '';
        $PatientType = '';
        $Doctor = '';
        $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . "&RI=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=" . $listtype;

        /* $add_parameters = '<a href="'.$href.'"><button onclick="radiologyviewimage(\''.$href.'\')">Add</button></a>'; */
        $imaging = '<button style="width:74%;" class="art-button-green" onclick="radiologyviewimage(\'' . $href . '\',\'' . $test_name . '\')">Imaging</button>';
        $commentsDescription = '<button style="width:74%;" class="art-button-green" onclick="commentsAndDescription(\'' . $href . '\',\'' . $test_name . '\')">Report</button>';
        $results_url = "radiologyviewimage_Doctor.php?II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&RI=" . $Registration_ID . "&Doctor=" . $Doctor;

        $view_results = $imaging . $commentsDescription;

        //Getting Radiologist Name
        $select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
        $select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
        if (mysqli_num_rows($select_radi_qry) > 0) {
            while ($radist = mysqli_fetch_assoc($select_radi_qry)) {
                $Radiologist_Name = $radist['Employee_Name'];
            }
        } else {
            $Radiologist_Name = 'N/A';
        }

        //Getting Sonographer Name
        $select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
        $select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
        if (mysqli_num_rows($select_sono_qry) > 0) {
            while ($sonog = mysqli_fetch_assoc($select_sono_qry)) {
                $Sonographer_Name = $sonog['Employee_Name'];
            }
        } else {
            $Sonographer_Name = 'N/A';
        }

        //Getting Doctors Comments
        $select_docomments = "SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Patient_Payment_Item_List_ID'";
        $select_docomments_qry = mysqli_query($conn,$select_docomments) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_docomments_qry) > 0) {
            while ($docom = mysqli_fetch_assoc($select_docomments_qry)) {
                $thecomm = $docom['Doctor_Comment'];
                if ($thecomm == '') {
                    $newcom = 'NONE';
                } else {
                    $newcom = $thecomm;
                }
                $Doctor_Comment = "<textarea style='color:#000;' >" . $newcom . " </textarea/>";
            }
        } else {
            // $Doctor_Comment = "<input type='text' style='color:#000;' disabled='disabled' value='NONE' />";
            $Doctor_Comment = "<textarea style='color:#000;' >NONE</textarea/>";
        }

        $style = 'style="text-decoration:none;"';

        echo '<tr>';
        echo '<td>' . $sn . '</td>';
        echo '<td>' . $test_name . '</td>';
        echo '<td>' . $Doctor_Comment . '</td>';
        echo '<td>' . $remarks . '</td>';
        echo '<td>' . $Radiologist_Name . '</td>';
        echo '<td>' . $Sonographer_Name . '</td>';
        echo '<td>' . $served_date . '</td>';
        echo '<td>' . $view_results . '</td>';
        echo '</tr>';
        ;
        $sn++;
    }


    echo '</table>';
} else if ($consultType == 'Laboratory') { //check for laboratory result
    $Query = "SELECT * FROM tbl_item_list_cache 
        INNER JOIN tbl_test_results AS trs ON Payment_Item_Cache_List_ID=payment_item_ID 
		INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID
         JOIN tbl_consultation tc ON  tc.consultation_ID=tbl_payment_cache.consultation_id		
		WHERE tc.Registration_ID='" . $Registration_ID . "' AND tbl_payment_cache.consultation_id ='$consultation_ID' AND tbl_item_list_cache.Check_In_Type='Laboratory'";

    //die($Query);
    $QueryResults = mysqli_query($conn,$Query) or die(mysqli_error($conn));
    echo "<table class='' style='width:100%'>";
    echo "<tr>
					<th width='1%'>S/N</th>
					<th >Test Name</th>
					<th width='25%'>Doctor's Notes</th>
					<th width='20%'>Lab Remarks</th>
					<th width='2%'>Attachment</th>
					<th width='5%'>Status</th>
					<th width='1%'>Results</th>
			</tr>";
    $i = 1;

    if (mysqli_num_rows($QueryResults) > 0) {
        while ($row = mysqli_fetch_assoc($QueryResults)) {
            $st = "";

            $RS = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $rowSt = mysqli_fetch_assoc($RS);
            $Submitted = $rowSt['Submitted'];
            $Validated = $rowSt['Validated'];
            if ($Validated == 'Yes') {
                $st = '<span style="color:blue;text-align:center;font-size: 14px;font-weight: bold;">Done</span>';
            } else if (in_array($Validated, array('No', '')) && in_array($Submitted, array('No', ''))) {
                $st = "No Result";
            } else {
                $st = '<span style="text-align:center;color: red;font-size: 14px;font-weight: bold;">Provisional</span>';
            }

            $totalParm = mysqli_num_rows($RS);
            $result = "";

            $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $positive = mysqli_num_rows($postvQry);

            $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $negative = mysqli_num_rows($negveQry);

            $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $abnormal = mysqli_num_rows($abnormalQry);

            $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $normal = mysqli_num_rows($normalQry);

            $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $high = mysqli_num_rows($highQry);

            $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $low = mysqli_num_rows($lowQry);

            if ($positive == $totalParm) {
                $result = "Positive";
            } elseif ($negative == $totalParm) {
                $result = "Negative";
            } elseif ($abnormal == $totalParm) {
                $result = "Abnormal";
            } elseif ($normal == $totalParm) {
                $result = "Normal";
            } elseif ($high == $totalParm) {
                $result = "High";
            } elseif ($low == $totalParm) {
                $result = "Low";
            }
            
            if($st=='No Result'){
                 $result = $st;
                 $st = "";
            }


            //retrieve attachment info
            $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
            $attach = mysqli_fetch_assoc($query);
            $image = '';
            if ($attach['Attachment_Url'] != '') {
                $image = "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancyboxold' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='40' height='20' /></a>";
            }


            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td><input type='text' id='' readonly='true' value='" . $row['Product_Name'] . "'></td>";
            echo "<td><input type='text' id='doctorNotes' value='" . $row['Doctor_Comment'] . "'></td>";
            echo "<td><textarea rows='1' cols='5' style='height:18px'>" . $attach['Description'] . "</textarea></td>";
            echo "<td style='text-align:center'>" . $image . "</td>";
            echo "<td>" . $st . "</td>";

            if (!empty($result)) {
                echo "<td style='background-color: white; text-align: center; color: rgb(101, 82, 18); font-weight: bold; font-size: 12px;'>" . $result . "</td>";
            } else {
                echo "<td><input type='button' class='generalresltsdoctor' name='" . $row['Product_Name'] . "' ppil='" . $row['Payment_Item_Cache_List_ID'] . "' patientID='" . $Registration_ID . "' id='" . $row['Item_ID'] . "' value='Results'></td>";
            }
            echo "</tr>";
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;font-size:20px;color:red">You do not have result for this patient</td></tr>';
    }
    echo "</table>";
    //echo 'Laboratory';
} else if ($consultType == 'Surgery') { //check for laboratory result
    echo "<fieldset>";
    //get Post_operative_IDs
    $select_surgery = mysqli_query($conn,"select pon.*, i.Product_Name, emp.Employee_Name from tbl_post_operative_notes pon, tbl_items i, tbl_item_list_cache ilc, tbl_employee emp where
										pon.consultation_ID = '$consultation_ID' and
										emp.Employee_ID = pon.Employee_ID and
										ilc.Payment_Item_Cache_List_ID = pon.Payment_Item_Cache_List_ID and
                                                                                ilc.Status != 'notsaved' and
										i.Item_ID = ilc.Item_ID") or die(mysqli_error($conn));
    $num_surgery = mysqli_num_rows($select_surgery);
    if ($num_surgery > 0) {
        $temp = 0;
        while ($dt = mysqli_fetch_array($select_surgery)) {
            $Post_operative_ID = $dt['Post_operative_ID'];
            ?>
            <br/><br/>
            <fieldset style="background-color: white;">
                <br/>
                <table width="100%">
                    <tr>
                        <td colspan="3" style="text-align: left;"><b><?php echo ++$temp; ?>. SURGERY NAME&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dt['Product_Name']); ?></b></td>
                        <td style="text-align: left;" colspan="5"><b>CONSULTANT&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?php echo strtoupper($dt['Employee_Name']); ?></b></td>
                    </tr>
                    <tr><td colspan="8" style="text-align: left;"><hr></td></tr>
                    <tr>
                        <td style="text-align: right;" width="9%">Surgery Date</td><td width="20%"><input type="text" value="<?php echo $dt['Surgery_Date']; ?>" readonly="readonly"></td>
                        <td width="10%">Type Of Anesthetic</td><td width="10%"><input type="text" value="<?php echo $dt['Type_Of_Anesthetic']; ?>" readonly="readonly"></td>
                        <td style="text-align: right;">Incision</td><td width="22%"><input type="text" value="<?php echo $dt['Incision']; ?>" readonly="readonly"></td>
                        <td style="text-align: right;">Position</td><td><input type="text" value="<?php echo $dt['Position']; ?>" readonly="readonly"></td>
                    </tr>
                </table>
                <br/><br/>
                <table width="100%">
                    <tr><td colspan="2" style="text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COMMENTS</b></td></tr>
                    <tr><td colspan="2" style="text-align: left;"><hr></td></tr>
                    <?php if ($dt['Procedure_Description'] != null && $dt['Procedure_Description'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Procedure Description And Closure</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Procedure_Description']; ?></textarea></td>
                        </tr>
                    <?php } ?>

                    <?php if ($dt['Identification_Of_Prosthesis'] != null && $dt['Identification_Of_Prosthesis'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Identification Of Prosthesis</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Identification_Of_Prosthesis']; ?></textarea></td>
                        </tr>
                    <?php } ?>


                    <?php if ($dt['Estimated_Blood_loss'] != null && $dt['Estimated_Blood_loss'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Estimated Blood Loss</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Estimated_Blood_loss']; ?></textarea></td>
                        </tr>
                    <?php } ?>


                    <?php if ($dt['Complications'] != null && $dt['Complications'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Problems / Complications</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Complications']; ?></textarea></td>
                        </tr>
                    <?php } ?>


                    <?php if ($dt['Drains'] != null && $dt['Drains'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Drains</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Drains']; ?></textarea></td>
                        </tr>
                    <?php } ?>


                    <?php if ($dt['Specimen_sent'] != null && $dt['Specimen_sent'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Specimen Sent</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Specimen_sent']; ?></textarea></td>
                        </tr>
                    <?php } ?>


                    <?php if ($dt['Postoperative_Orders'] != null && $dt['Postoperative_Orders'] != '') { ?>
                        <tr>
                            <td width="17%" style="text-align: right;">Postoperative Orders</td>
                            <td><textarea style="width: 100%; height: 30px;" readonly="readonly"><?php echo $dt['Postoperative_Orders']; ?></textarea></td>
                        </tr>
                    <?php } ?>
                </table><br/><br/>

                <?php
                $Number = 0;
                $select_post = mysqli_query($conn,"select  d.disease_code, d.disease_name
											from tbl_post_operative_diagnosis pod, tbl_disease d where
											d.disease_ID = pod.disease_ID and
											pod.Post_operative_ID = '$Post_operative_ID' and
											pod.Diagnosis_Type = 'Preoperative Diagnosis'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_post);
                if ($no > 0) {
                    ?>
                    <table width="100%">
                        <tr><td colspan="3" style="text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PREOPERATIVE DIAGNOSIS (INDICATION)</b></td></tr>
                        <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                        <tr>
                            <td width='17%' style='text-align: right;'><b>DISEASE CODE</b></td><td width="2%"></td>
                            <td style="text-align: left;"><b>DISEASE NAME</b></td>
                        </tr>
                        <?php
                        while ($data = mysqli_fetch_array($select_post)) {
                            echo "<tr>
										<td style='text-align: right;'>" . strtoupper($data['disease_code']) . "</td><td></td>
										<td style='text-align: left;'>" . ucwords(strtolower($data['disease_name'])) . "</td>
									</tr>";
                        }
                        echo "</table><br/><br/>";
                    }
                    ?>

                    <?php
                    $Number = 0;
                    $select_post = mysqli_query($conn,"select  d.disease_code, d.disease_name
											from tbl_post_operative_diagnosis pod, tbl_disease d where
											d.disease_ID = pod.disease_ID and
											pod.Post_operative_ID = '$Post_operative_ID' and
											pod.Diagnosis_Type = 'Postoperative Diagnosis'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_post);
                    if ($no > 0) {
                        ?>
                        <table width="100%">
                            <tr><td colspan="3" style="text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POSTOPERATIVE DIAGNOSIS (FINDINGS)</b></td></tr>
                            <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                            <tr>
                                <td width='17%' style='text-align: right;'><b>DISEASE CODE</b></td><td width="2%"></td>
                                <td style="text-align: left;"><b>DISEASE NAME</b></td>
                            </tr>
                            <?php
                            while ($data = mysqli_fetch_array($select_post)) {
                                echo "<tr>
										<td style='text-align: right;'>" . strtoupper($data['disease_code']) . "</td><td></td>
										<td style='text-align: left;'>" . ucwords(strtolower($data['disease_name'])) . "</td>
									</tr>";
                            }
                            echo "</table><br/>";
                        }
                        ?>

                        <?php
                        //get participants
                        $Surgeons = '';
                        $Assisitant_Surgeons = '';
                        $Nurses = '';
                        $Anaesthetics = '';

                        $select = mysqli_query($conn,"select pop.Employee_Type, emp.Employee_Name from 
											tbl_post_operative_participant pop, tbl_employee emp where
											emp.Employee_ID = pop.Employee_ID and
											pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {

                            while ($data = mysqli_fetch_array($select)) {
                                if ($data['Employee_Type'] == 'Surgeon') {
                                    $Surgeons .= ucwords(strtolower($data['Employee_Name'])) . '<br/>';
                                } else if ($data['Employee_Type'] == 'Assistant Surgeon') {
                                    $Assisitant_Surgeons .= ucwords(strtolower($data['Employee_Name'])) . '<br/>';
                                } else if ($data['Employee_Type'] == 'Nurse') {
                                    $Nurses .= ucwords(strtolower($data['Employee_Name'])) . '<br/>';
                                } else if ($data['Employee_Type'] == 'Anaesthetics') {
                                    $Anaesthetics .= ucwords(strtolower($data['Employee_Name'])) . '<br/>';
                                }
                            }
                            ?>
                            <table width="100%">
                                <tr><td colspan="3" style="text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PARTICIPANTS</b></td></tr>
                                <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                                <tr>
                                    <td width='17%' style='text-align: right;'><b>TITLE NAME</b></td><td width="2%"></td>
                                    <td style="text-align: left;"><b>PARTICIPANT NAME</b></td>
                                </tr>
                                <?php
                                if ($Surgeons != '') {
                                    echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
									<tr>
										<td style='text-align: right;'><b>Surgeons</b></td><td></td>
										<td style='text-align: left;'>" . $Surgeons . "</td>
									</tr>";
                                }
                                if ($Assisitant_Surgeons != '') {
                                    echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
									<tr>
										<td style='text-align: right;'><b>Assistant Surgeons</b></td><td></td>
										<td style='text-align: left;'>" . $Assisitant_Surgeons . "</td>
									</tr>";
                                }
                                if ($Nurses != '') {
                                    echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
									<tr>
										<td style='text-align: right;'><b>Nurses</b></td><td></td>
										<td style='text-align: left;'>" . $Nurses . "</td>
									</tr>";
                                }
                                if ($Anaesthetics != '') {
                                    echo "<tr><td colspan='3' style='text-align: left;'><hr></td></tr>
									<tr>
										<td style='text-align: right;'><b>Anaesthetists</b></td><td></td>
										<td style='text-align: left;'>" . $Anaesthetics . "</td>
									</tr>";
                                }
                                echo '</table><br/>';
                            }
                            ?>

                            <?php
                            //external participants
                            $select = mysqli_query($conn,"select * from tbl_post_operative_external_participant where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if ($num > 0) {
                                while ($data = mysqli_fetch_array($select)) {
                                    $External_Surgeons = $data['External_Surgeons'];
                                    $External_Assistant_Surgeon = $data['External_Assistant_Surgeon'];
                                    $External_Scrub_Nurse = $data['External_Scrub_Nurse'];
                                    $External_Anaesthetic = $data['External_Anaesthetic'];
                                }
                                if (($External_Surgeons != null && $External_Surgeons != '') || ($External_Assistant_Surgeon != null && $External_Assistant_Surgeon != '') || ($External_Scrub_Nurse != null && $External_Scrub_Nurse != '') || ($External_Anaesthetic != null && $External_Anaesthetic != '')) {
                                    ?>

                                    <table width="100%">
                                        <tr><td colspan="3" style="text-align: left;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXTERNAL PARTICIPANTS</b></td></tr>
                                        <tr><td colspan="3" style="text-align: left;"><hr></td></tr>
                                        <tr>
                                            <td width='17%' style='text-align: right;'><b>TITLE NAME</b></td><td width="2%"></td>
                                            <td style="text-align: left;"><b>PARTICIPANTS</b></td>
                                        </tr>
                                        <?php
                                        if ($External_Surgeons != '' && $External_Surgeons != null) {
                                            echo "<tr>
											<td style='text-align: right;'><b>External Surgeons</b></td><td></td>
											<td style='text-align: left;'>" . $External_Surgeons . "</td>
										</tr>";
                                        }
                                        if ($External_Assistant_Surgeon != '' && $External_Assistant_Surgeon != null) {
                                            echo "<tr>
											<td style='text-align: right;'><b>External Assistant Surgeons</b></td><td></td>
											<td style='text-align: left;'>" . $External_Assistant_Surgeon . "</td>
										</tr>";
                                        }
                                        if ($External_Scrub_Nurse != '' && $External_Scrub_Nurse != null) {
                                            echo "<tr>
											<td style='text-align: right;'><b>External Nurses</b></td><td></td>
											<td style='text-align: left;'>" . $External_Scrub_Nurse . "</td>
										</tr>";
                                        }
                                        if ($External_Anaesthetic != '' && $External_Anaesthetic != null) {
                                            echo "<tr>
											<td style='text-align: right;'><b>External Anaesthetists</b></td><td></td>
											<td style='text-align: left;'>" . $External_Anaesthetic . "</td>
										</tr>";
                                        }
                                        echo '</table>';
                                    }
                                }
                                ?>
                                </fieldset><br/><br/><br/><br/><br/>

                                <?php
                                /* //get post operative details
                                  $get_details = mysqli_query($conn,"") or die(mysqli_error($conn));
                                  $nm_details = mysqli_num_rows($get_details);
                                  if($nm_details > 0){
                                  while ($dtails = mysqli_fetch_array($get_details)) {

                                  }
                                  } */
                            }
                        }
                        echo "</fieldset>";
                    } else if ($consultType == 'Procedure') { //check for laboratory result
                        $qry = "SELECT tit.Product_Name,ilc.Doctor_Comment,ilc.remarks,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.Consultant_ID) AS sentby,(SELECT Employee_Name FROM tbl_employee em WHERE em.Employee_ID=ilc.ServedBy) AS servedby,ilc.Transaction_Date_And_Time AS sentOn,ServedDateTime  AS servedOn
                  FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID
		JOIN tbl_items tit ON tit.Item_ID=ilc.Item_ID 
		JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
		WHERE pc.consultation_id ='$consultation_ID' AND
	  ilc.Status = 'served' AND ilc.Check_In_Type='Procedure' 
         		 
		";

                        $rs = mysqli_query($conn,$qry)or die(mysqli_error($conn));
                        echo '<table width="100%">';
                        echo '<tr style="font-weight:bold;" id="thead">';
                        echo '<td style="width:3%;">SN</td>';
                        echo '<td>Procedure Name</td>';
                        echo '<td>Doctor Comment</td>';
                        echo '<td>Proc Remarks</td>';
                        echo '<td>Ordered By</td>';
                        echo '<td>Ordered Date</td>';
                        echo '<td>Served By</td>';
                        echo '<td>Served Date</td>';
                        echo '</tr>';
                        $sn = 1;

                        if (mysqli_num_rows($rs) > 0) {
                            while ($row = mysqli_fetch_assoc($rs)) {
                                $test_name = $row['Product_Name'];
                                $Doctor_Comment = $row['Doctor_Comment'];
                                $remarks = $row['remarks'];
                                $sentby = $row['sentby'];
                                $sentOn = $row['sentOn'];
                                $servedby = $row['servedby'];
                                $servedOn = $row['servedOn'];

                                echo '<tr style="text-align:left">';
                                echo '<td>' . $sn . '</td>';
                                echo '<td>' . $test_name . '</td>';
                                echo '<td>' . $Doctor_Comment . '</td>';
                                echo '<td>' . $remarks . '</td>';
                                echo '<td>' . $sentby . '</td>';
                                echo '<td>' . $sentOn . '</td>';
                                echo '<td>' . $servedby . '</td>';
                                echo '<td>' . $servedOn . '</td>';

                                echo '</tr>';
                                ;
                                $sn++;
                            }
                        } else {
                            echo '<tr><td colspan="10" style="text-align:center;font-size:20px;color:red">This Patient has no procedure done on this date</td></tr>';
                        }
                        //echo 'Procedure';
                    } else if ($consultType == 'Pharmacy') { //check for Pharmacy result
                        $subqr = "SELECT * FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID = pc.Payment_Cache_ID JOIN tbl_items i ON i.item_ID = ilc.item_ID
	   JOIN tbl_employee em ON em.Employee_ID=ilc.Consultant_ID
	   JOIN tbl_consultation tc ON tc.consultation_ID=pc.consultation_id
	   WHERE Check_In_Type='Pharmacy' AND pc.Registration_ID='$Registration_ID' AND pc.consultation_id ='$consultation_ID' AND ilc.Status='dispensed'";

                        //echo $subqr;exit;
                        $result = mysqli_query($conn,$subqr) or die(mysqli_error($conn));

                        echo '<table width="100%">';
                        echo '<tr style="font-weight:bold;" id="thead">';
                        echo '<td style="width:3%;">SN</td>';
                        echo '<td style="text-align:left">Medication Name</td>';
                        echo '<td style="text-align:left">Qty</td>';
                        echo '<td style="text-align:left">Dosage</td>';
                        echo '<td style="text-align:left">Dispensor</td>';
                        //echo '<td style="text-align:left">Doctor Remarks</td>';			
                        echo '<td style="text-align:left">Doctor Ordered</td>';
                        echo '<td style="text-align:left">Date</td>';
                        echo '</tr>';
                        $sn = 1;

                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {
                                $qr = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $row['Dispensor'] . "'")or die(mysqli_error($conn));
                                $Disponsor = mysqli_fetch_assoc($qr)['Employee_Name'];
                                $Pharmacy = $row['Product_Name'];
                                $orderedQty = $row['Quantity'];
                                $disepnsedQty = $row['Edited_Quantity'];
                                $Employee_Name = $row['Employee_Name'];
                                // $remarks=$row['remarks'];
                                $Doctor_Comment = $row['Doctor_Comment'];
                                $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                                $qty = 0;

                                if ($disepnsedQty > 0) {
                                    $qty = $disepnsedQty;
                                } else {
                                    $qty = $orderedQty;
                                }

                                echo '<tr>';
                                echo '<td style="width:3%;">' . $sn . '</td>';
                                echo '<td style="text-align:left">' . $Pharmacy . '</td>';
                                echo '<td style="text-align:left">' . $qty . '</td>';
                                echo '<td style="text-align:left">' . $Doctor_Comment . '</td>';
                                echo '<td style="text-align:left">' . $Disponsor . '</td>';
                                //echo '<td style="text-align:left">'.$remarks.'</td>';
                                echo '<td style="text-align:left">' . $Employee_Name . '</td>';
                                echo '<td style="text-align:left">' . $Transaction_Date_And_Time . '</td>';
                                echo '</tr>';

                                $sn++;
                            }
                        } else {
                            echo '<tr><td colspan="10" style="text-align:center;font-size:20px;color:red">This Patient has no medication given on this date</td></tr>';
                        }
                    }
                    ?>

                    <script>
                        $('.generalresltsdoctor').click(function () {
                            var name = $(this).attr('name');
                            var patientID = $(this).attr('patientID');
                            var ppil = $(this).attr('ppil');
                            //alert('alert');
                            $('#showGeneral').html();
                            var id = $(this).attr('id');
                            $.ajax({
                                type: 'POST',
                                url: 'requests/LabResultsDoctorView.php',
                                data: 'generalResult=getGeneral&id=' + id + '&patientID=' + patientID + '&ppil=' + ppil,
                                cache: false,
                                success: function (html) {
                                    // alert(html);
                                    $('#showGeneral').html(html);
                                }
                            });
                            $('#labGeneral').dialog({
                                modal: true,
                                width: '90%',
                                minHeight: 450,
                                resizable: true,
                                draggable: true,
                                title: name
                            }).dialog("widget")
                                    .next(".ui-widget-overlay")
                                    .css("background-color", "rgb(255,255,255)");

                            //$("#labGeneral").dialog('option', 'title', testName);

                            $('#labResults').fadeOut(100);
                        });
                    </script>