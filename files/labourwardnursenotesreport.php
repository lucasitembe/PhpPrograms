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
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr></table>";
		
	$htm .= '<table width="100%">
	            <tr><td><span style="font-size: x-small;">LABOUR WARD NURSE NOTES REPORT</span></td></tr>
	            <tr>
	            	<td style="text-align: left;" width="15%"><span style="font-size: x-small;"><b>PATIENT NAME : </b>'.ucwords(strtolower($Patient_Name)).'</span></td>
	            	<td style="text-align: left;" width="15%"><span style="font-size: x-small;"><b>PATIENT NUMBER : </b>'.$Registration_ID.'</span></td>
	            </tr>
	            <tr>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>GENDER : </b>'.$Gender.'</span></td>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>PATIENT AGE : </b>'.$age.'</span></td>
	            </tr>
	            <tr>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>DATE OF ADMISSION : </b>'.$Admission_Date_Time.'</span></td>
	            	<td style="text-align: left;"><span style="font-size: x-small;"><b>SPONSOR NAME : </b>'.strtoupper($Guarantor_Name).'</span></td>
	            </tr>
	        </table><br/>';

		

		$select = mysqli_query($conn,"select * from tbl_labour_ward_notes lwn, tbl_employee emp where 
								emp.Employee_ID = lwn.Employee_ID and
								Admision_ID = '$Admision_ID' and
								Registration_ID = '$Registration_ID' and
								Consultation_ID = '$Consultation_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td><span style="font-size: x-small;"><b>Date And Time : </b>'.@date("d F Y H:i:s",strtotime($data['Note_Date_Time'])).'
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nurse Name : </b>'.ucwords(strtolower($data['Employee_Name'])).'</td>
						</tr></table><br/>';
				$htm .= '<b><span style="font-size: x-small;">GENERAL EXAMINATION</span><b>';
				$htm .=	'<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="14%" style="text-align: right;"><span style="font-size: x-small;">Palpation</span></td>
							<td><span style="font-size: x-small;">'.$data['Palpation'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">Presentation</span></td>
							<td><span style="font-size: x-small;">'.$data['Presentation'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">Position</span></td>
							<td><span style="font-size: x-small;">'.$data['Position'].'</span></td>
						</tr>
						<tr>
							<td style="text-align: right;"><span style="font-size: x-small;">PV Examination</span></td>
							<td><span style="font-size: x-small;">'.$data['Pv_Examination'].'</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">OS Size</span></td>
							<td><span style="font-size: x-small;">'.$data['OS'].'</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">Membrane</span></td>
							<td><span style="font-size: x-small;">'.$data['Membrane'].'</span></td>
						</tr>
						<tr>
							<td style="text-align: right;"><span style="font-size: x-small;">Contraction</span></td>
							<td><span style="font-size: x-small;">'.$data['Contraction'].'</span></td>
							<td style="text-align: right;"><span style="font-size: x-small;">Liquir</span></td>
							<td><span style="font-size: x-small;">'.$data['Liquir'].'</span></td>';
				
				if($data['Liquir'] == 'Meconium Light' || $data['Liquir'] == 'Meconium Thick'){
					//Generate Colour
					if($data['Colour'] == 'Greenish_Plus'){
						$Colour = 'Greenish+';
					}else if($data['Colour'] == 'Greenish_Plus2'){
						$Colour = 'Greenish++';
					}else if($data['Colour'] == 'Yellowish_Plus'){
						$Colour = 'Yellowish+';
					}else if($data['Colour'] == 'Yellowish_Plus2'){
						$Colour = 'Yellowish++';
					}else{
						$Colour = '';
					}
					$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">Colour</span></td>
							<td><span style="font-size: x-small;">'.$Colour.'</span></td>';
				}else{
					$htm .= '';
				}
				$htm .= '</tr></table><br/>';

				$htm .= '<b><span style="font-size: x-small;">VITAL SIGNS</span><b>';
				$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="14%" style="text-align: right;"><span style="font-size: x-small;">Temperature</span></td>
							<td><span style="font-size: x-small;">'.$data['Temperature'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">Purse</span></td>
							<td><span style="font-size: x-small;">'.$data['Purse'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">Respiration</span></td>
							<td><span style="font-size: x-small;">'.$data['Respiration'].'</span></td>
						</tr>
						<tr>
							<td width="14%" style="text-align: right;"><span style="font-size: x-small;">BP</span></td>
							<td><span style="font-size: x-small;">'.$data['BP'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">FHR</span></td>
							<td><span style="font-size: x-small;">'.$data['FHR'].'</span></td>
							<td width="12%" style="text-align: right;"><span style="font-size: x-small;">BMI</span></td>
							<td><span style="font-size: x-small;">'.$data['BMI'].'</span></td>
						</tr>';
				$htm .= '</table><br/>';

				$htm .= '<b><span style="font-size: x-small;">EXP AND REMARKS</span><b>';
				$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
						<tr>
							<td width="14%" style="text-align: right;"><span style="font-size: x-small;">Exp</span></td>
							<td><span style="font-size: x-small;">'.$data['Exp'].'</span></td>
						</tr>
						<tr>
							<td width="14%" style="text-align: right;"><span style="font-size: x-small;">Remarks</span></td>
							<td><span style="font-size: x-small;">'.$data['Remarks'].'</span></td>
						</tr>';
				$htm .= '</table>';
			}
		}

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>