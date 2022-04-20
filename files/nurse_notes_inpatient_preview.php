<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Admission_ID'])){
		$Admission_ID = $_GET['Admission_ID'];
	}else{
		$Admission_ID = 0;
	}

	if(isset($_GET['Consultation_ID'])){
		$Consultation_ID = $_GET['Consultation_ID'];
	}else{
		$Consultation_ID = '';
	}
	
	$Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("d F Y", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }

// echo "Admission ID:".$Admission_ID." Reg: ".$Registration_ID." Cons: ".$Consultation_ID;

	if (isset($_GET['Registration_ID'])) {
        $select = mysqli_query($conn,"SELECT Member_Number, Patient_Name, Registration_ID, Gender, Guarantor_Name, Date_Of_Birth
                                from tbl_patient_registration pr, tbl_sponsor sp where
                                pr.Registration_ID = '$Registration_ID' and
                                sp.Sponsor_ID = pr.Sponsor_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Member_Number = $row['Member_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
            }
            //generate patient age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        } else {
            $Member_Number = '';
            $Patient_Name = '';
            $Gender = '';
            $Registration_ID = 0;
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }

    //get admission date
    $select = mysqli_query($conn,"SELECT Admission_Date_Time from tbl_admission where Admision_ID = '$Admission_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Admission_Date_Time = @date("d F Y H:i:s",strtotime($row['Admission_Date_Time']));
        }
    }else{
        $Admission_Date_Time = '0000/00/00 00:00:00';
    }

    //get final diagnosis
    $Disease_Name = '';

    //Outpatient diagnosis
    $select = mysqli_query($conn,"SELECT Disease_Name from tbl_disease_consultation dc, tbl_disease d where
                            dc.disease_ID = d.disease_ID and
                            dc.Consultation_ID = '$Consultation_ID' and
                            dc.diagnosis_type = 'diagnosis' group by d.disease_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Disease_Name .= $data['Disease_Name'].'; ';
        }
    }

    //Inpatient diagnosis
    $select = mysqli_query($conn,"SELECT Disease_Name from tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_disease d where
                            d.disease_ID = wrd.disease_ID and
                            wrd.diagnosis_type = 'diagnosis' and
                            wrd.Round_ID = wr.Round_ID and
                            wr.Consultation_ID = '$Consultation_ID' group by d.disease_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Disease_Name .= $data['Disease_Name'].'; ';
        }
    }
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="10%" style="text-align: right;">Patient Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Age</td>
            <td><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
            <td width="10%" style="text-align: right;">Gender</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Number</td>
            <td><input type="text" readonly="readonly" value="<?php echo $Registration_ID; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Diagnosis</td>
            <td colspan="3">
                <textarea style="width: 100%; height: 20px;" id="Diagnosis" name="Diagnosis" readonly="readonly"><?php echo $Disease_Name; ?></textarea>
            </td>
            <td style="text-align: right;">Date Of Admission</td>
            <td><input type="text" value="<?php echo $Admission_Date_Time; ?>" readonly="readonly"></td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Items_Fieldset'>
	<?php
		$select = mysqli_query($conn,"SELECT pp.Notes_ID, pp.Notes_Date_Time, pp.Notes, emp.Employee_Name
								from tbl_nurse_notes pp, tbl_employee emp where
								emp.Employee_ID = pp.Employee_ID and
								pp.Admision_ID = '$Admission_ID' and
								Registration_ID = '$Registration_ID' order by Notes_ID desc") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		$temp = 0;
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
	?>
				<table width="100%">
					<tr>
						<td width="4%" style="background-color: #eeeebb;"><b><?php echo ++$temp; ?><b>.</b></td>
						<td width="12%" style="text-align: right; background-color: #eeeebb;"><b>DATE AND TIME : </b></td>
						<td style="background-color: #eeeebb;"><b><?php echo @date("d F Y H:i:s",strtotime($data['Notes_Date_Time'])); ?></b></td>
						<td width="12%" style="text-align: right; background-color: #eeeebb;"><b>NURSE NAME : </b></td>
						<td style="background-color: #eeeebb;"><b><?php echo strtoupper($data['Employee_Name']); ?></b></td>
						<td style="text-align: center; background-color: #eeeebb;" width="10%">
							<a href="patientnotespreviewsubreport.php?Notes_ID=<?php echo $data['Notes_ID']; ?>&PatientNotesPreviewSubReport=PatientNotesPreviewSubReportThisPage" target="_blank" style="text-decoration: none;"><button>Preview Report</button></a>
						</td>
					</tr>
					<tr><td colspan="6"><hr></td></tr>
					<tr>
						<td colspan="6">
							<table width="100%">
								<tr>
									<td width="13%" style="text-align: right; vertical-align: top;"><b>Nursing Notes</b></td>
									<td><textarea style="width: 100%; height: 100px;" readonly="readonly"><?php echo $data['Notes']; ?></textarea></td>
								</tr>
							</table>
						</td>
					</tr>
				</table><br/>
	<?php
			}
		}
	?>
</fieldset>