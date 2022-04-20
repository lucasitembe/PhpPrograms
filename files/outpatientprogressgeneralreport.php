<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$E_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$E_Name = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = '';
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

	if (isset($_GET['Registration_ID'])) {
        $select = mysqli_query($conn,"select Member_Number, Patient_Name, Registration_ID, Gender, Guarantor_Name, Date_Of_Birth
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
    $select = mysqli_query($conn,"select Admission_Date_Time from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
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
    $select = mysqli_query($conn,"select Disease_Name from tbl_disease_consultation dc, tbl_disease d where
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
    $select = mysqli_query($conn,"select Disease_Name from tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_disease d where
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

	$htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	    <tr><td>&nbsp;</td></tr></table>";
		
	$htm .= '<table width="100%">
	            <tr><td><span style="font-size: x-small;">STANDARDIZED NURSING CARE PLAN REPORT</span></td></tr>
	            <tr>
	            	<td style="text-align: left;" width="15%"><span style="font-size: x-small;"><b>PATIENT NAME : </b>'.ucwords(strtolower($Patient_Name)).'</span></td>
	            	<td style="text-align: left;" width="15%"><span style="font-size: x-small;"><b>PATIENT NUMBER : </b>'.$Registration_ID.'</span></td>
	            </tr>
	            <tr>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>GENDER : </b>'.$Gender.'</span></td>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>PATIENT AGE : </b>'.$age.'</span></td>
	            </tr>
	            <tr>
	            	<!--<td style="text-align: left;"><span style="font-size: x-small;"><b>DATE OF ADMISSION : </b>'.$Admission_Date_Time.'</span></td>-->
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>SPONSOR NAME : </b>'.strtoupper($Guarantor_Name).'</span></td>
	            </tr>
	            <tr>
	            	<td style="text-align: left;" colspan="2"><span style="font-size: x-small;"><b>DIAGNOSIS : </b>'.$Disease_Name.'</span></td>
	            </tr>
	        </table><br/>';

		

		$select = mysqli_query($conn,"select pp.Checked_Date_Time, pp.Assessment, pp.Nursing_Diagnosis, pp.Goal, pp.Nursing_Interverntion, pp.Evaluation, emp.Employee_Name
								from tbl_patient_progress pp, tbl_employee emp where
								emp.Employee_ID = pp.Employee_ID and
								pp.Admision_ID IS NULL and
								Registration_ID = '$Registration_ID' order by Progress_ID desc") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		$temp = 0;
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td  width="50%" colspan="5"><span style="font-size: x-small;">'.++$temp.'<b>. Date And Time : </b>'.@date("d F Y H:i:s",strtotime($data['Checked_Date_Time'])).'
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nurse Name : </b>'.ucwords(strtolower($data['Employee_Name'])).'</td>
						</tr>';
				$htm .=	'<tr>
								<td width="30%" style="text-align: right;"><span style="font-size: x-small;">Assessment (Data Statement)</span></td>
								<td colspan="4"><span style="font-size: x-small;">'.$data['Assessment'].'</span></td>
							</tr>
							<tr>
								<td style="text-align: right;"><span style="font-size: x-small;">Nursing Diagnosis</td>
								<td colspan="4"><span style="font-size: x-small;">'.$data['Nursing_Diagnosis'].'</span></td>
							</tr>
							<tr>
								<td style="text-align: right;"><span style="font-size: x-small;">Goal / Expected Outcome</span></td>
								<td colspan="4"><span style="font-size: x-small;">'.$data['Goal'].'</span></td>
							</tr>
							<tr>
								<td style="text-align: right;"><span style="font-size: x-small;">Nursing Intervention Statements</span></td>
								<td colspan="4"><span style="font-size: x-small;">'.$data['Nursing_Interverntion'].'</span></td>
							</tr>
							<tr>
								<td style="text-align: right;"><span style="font-size: x-small;">Evaluation (Patient Response)</span></td>
								<td colspan="4"><span style="font-size: x-small;">'.$data['Evaluation'].'</span></td>
							</tr>
				</table><br/><br/>';
			}
		}

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>