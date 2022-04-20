<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
        
        $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
        $location='location=otherdepartment&';
    }

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}	

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    $age ='';
    }
?>
<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>AdHOC CONTINUING INPATIENT LIST</b></legend>
        <table width=100% border=1>
            <tr id='thead'>
    		    <td width=5%><b>SN</b></td>
    		    <td><b>PATIENT NAME</b></td>
    		    <td style='text-align: left; width: 10%;'><b>PATIENT NUMBER</b></td>
                <td style='text-align: left; width: 10%;'><b>MEMBER NUMBER</b></td>
                <td style='text-align: left; width: 10%;'><b>SPONSOR</b></td>
                <td style='text-align: left; width: 13%;'><b>AGE</b></td>
                <td style='text-align: left; width: 5%;'><b>GENDER</b></td>
                <td style='text-align: left; width: 10%;'><b>PHONE NUMBER</b></td>
    		</tr>
    	    <tr><td colspan="8"><hr></td></tr>
<?php
    //select patients based on sponsor required
	if(isset($_GET['Patient_Number']) && $Patient_Number != '' && $Patient_Number != null){
    		$select = mysqli_query($conn,"SELECT pr.Registration_ID, pr.Patient_Name, pr.Gender, cid.Check_In_ID, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.Admision_ID from
                            tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad, tbl_check_in_details cid where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'Admitted' and
                            cid.Admit_Status = 'admitted' and
                            cid.Admission_ID = ad.Admision_ID and
                            pr.Registration_ID = '$Patient_Number' and
                            ad.Discharge_Clearance_Status = 'not cleared' ORDER BY ad.Admision_ID DESC") or die(mysqli_error($conn));
	}else{
		if($Sponsor_ID == 0){
    		$select = mysqli_query($conn,"SELECT pr.Registration_ID, pr.Patient_Name, pr.Gender, cid.Check_In_ID, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.Admision_ID from
                            tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad, tbl_check_in_details cid where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'Admitted' and   
                            cid.Admit_Status = 'admitted' and
                            cid.Admission_ID = ad.Admision_ID and
                            pr.Patient_Name like '%$Patient_Name%' and
                            ad.Discharge_Clearance_Status = 'not cleared' ORDER BY ad.Admision_ID DESC") or die(mysqli_error($conn));
		}else{
    		$select = mysqli_query($conn,"SELECT pr.Registration_ID, pr.Patient_Name, pr.Gender, cid.Check_In_ID, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.Admision_ID from
                            tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad, tbl_check_in_details cid where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            sp.Sponsor_ID = '$Sponsor_ID' and
                            ad.Admission_Status = 'Admitted' and   
                            cid.Admit_Status = 'admitted' and
                            cid.Admission_ID = ad.Admision_ID and
                            pr.Patient_Name like '%$Patient_Name%' and
                            ad.Discharge_Clearance_Status = 'not cleared' ORDER BY ad.Admision_ID DESC LIMIT 50") or die(mysqli_error($conn));		
		}
	}	


    $num = mysqli_num_rows($select);

    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Date_Of_Birth = $data['Date_Of_Birth'];
            $Check_In_ID = $data['Check_In_ID'];
            //calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
?>
            <tr id='thead'>
                <td width=5%><?php echo ++$temp.'<b>.</b>'; ?></td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Patient_Name']; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Registration_ID']; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Member_Number']; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Guarantor_Name']; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $age; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Gender']; ?>
                    </a>
                </td>
                <td>
                    <a href='adhoc_inpatient_page.php?<?php echo $location ?>Registration_ID=<?php echo $data['Registration_ID']; ?>&Admision_ID=<?php echo $data['Admision_ID']; ?>&Check_In_ID=<?= $Check_In_ID ?>&AdhocInpatientPage=AdhocInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Phone_Number']; ?>
                    </a>
                </td>
            </tr>
<?php
        }
        echo '<tr><td colspan="8"><hr></td></tr>';
    }
?>
        </table>