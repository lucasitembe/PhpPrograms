<legend align="left"><b>PATIENT TRANSFER OUT</b></legend>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

	if(isset($_GET['Ward_id'])){
		$Ward_id = $_GET['Ward_id'];
	}else{
		$Ward_id = '';
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	$filter = '';

	if($Sponsor_ID > 0){
		$filter .= " sp.Sponsor_ID = '$Sponsor_ID' and ";
	}

	if($Patient_Name != null && $Patient_Name != ''){
		$filter .= " pr.Patient_Name like '%$Patient_Name%' and ";
	}

	if($Patient_Number != null && $Patient_Number != ''){
		$filter .= " pr.Registration_ID = '$Patient_Number' and ";
	}

	if($Ward_id  > 0){
		$filter .= " hw.Hospital_Ward_ID = '$Ward_id' and ";
	}
?>
<table width="100%">
<?php
    $temp = 0;
    $Title = '<tr><td colspan="12"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="15%"><b>PATIENT NAME</b></td>
            <td width="7%"><b>PATIENT #</b></td>
            <td width="14%"><b>SPONSOR NAME</b></td>
            <td width="14%"><b>PATIENT AGE</b></td>
            <td width="6%"><b>GENDER</b></td>
            <td width="9%"><b>WARD NAME</b></td>
            <td width="13%"><b>ADMITTED DATE</b></td>
            <td width="8%"><b>ACTION</a></td>
        </tr>
        <tr><td colspan="12"><hr></td></tr>';

    $select = mysqli_query($conn,"select sp.Guarantor_Name, pr.Gender, pr.Registration_ID, pr.Date_Of_Birth, hw.Hospital_Ward_Name, pr.Patient_Name, hw.Hospital_Ward_Name, a.Admission_Status, a.Admission_Date_Time, a.Admision_ID
                            from tbl_hospital_ward hw, tbl_patient_registration pr, tbl_sponsor sp, tbl_admission a where
                            a.Registration_ID = pr.Registration_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            $filter
                            hw.Hospital_Ward_ID = a.Hospital_Ward_ID and Admission_Status != 'Discharged' AND a.ward_room_id != 0 AND a.Bed_Name !='' order by a.Admision_ID desc limit 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {

            $date1 = new DateTime();
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
            if($temp%30 == 0){ echo $Title; }
?>
            <tr id="sss">
                <td><?php echo ++$temp; ?></td>
                <td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
                <td><?php echo $data['Registration_ID']; ?></td>
                <td><?php echo $data['Guarantor_Name']; ?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo $data['Gender']; ?></td>
                <td><?php echo $data['Hospital_Ward_Name']; ?></td>
                <td><?php echo @date("d F Y H:i:s",strtotime($data['Admission_Date_Time'])); ?></td>
                <td width="8%" style="text-align: center;"><input type="button" class="art-button-green" value="TRANSFER PATIENT" onclick="Transfer_Patient(<?php echo $data['Registration_ID']; ?>,<?php echo $data['Admision_ID']; ?>)"></td>
            </tr>
<?php
        }
    }else{
        echo $Title;
    }
?>
    </table>
