<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}


	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}


	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}


	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}


	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    //generate date filter
    if($Start_Date != '' && $End_Date != ''){
    	$Date_Filter = "s.Date_Time between '$Start_Date' and '$End_Date' and";
    }else{
    	$Date_Filter = "";
    }
?>
<legend align='right'><b>OPTICAL ~ PATIENTS FROM DOCTORS</b></legend>
<table width="100%" class="table table-striped table-hover">

<thead style="background-color:#bdb5ac;">
<!-- bdb5ac -->
<!-- e6eded -->
<!-- a3c0cc -->
	<tr>
		<th style="text-align:left;"><b>SN</b></th>
		<th style="text-align:left;"><b>PATIENT NAME</b></th>
		<th style="text-align:left;"><b>PATIENT NUMBER</b></th>
		<th style="text-align:left;"><b>SPONSOR</b></th>
		<th style="text-align:left;"><b>AGE</b></th>
		<th style="text-align:left;"><b>GENDER</b></th>
		<th style="text-align:left;"><b>MEMBER NUMBER</b></th>
	</tr>
</thead>
    <?php
    	if($Sponsor_ID == 0){
	    	if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
	        	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                pr.Patient_Name like '%$Patient_Name%' and
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));
	        }else if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != '' && $_GET['Patient_Number'] != null){
				$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                pr.Registration_ID like '%$Patient_Number%' and
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));
	        }else{
				$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));        	
	        }
	    }else{
	    	if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
	        	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                pr.Patient_Name like '%$Patient_Name%' and
	                                sp.Sponsor_ID = '$Sponsor_ID' and
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));
	        }else if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != '' && $_GET['Patient_Number'] != null){
				$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                pr.Registration_ID like '%$Patient_Number%' and
	                                sp.Sponsor_ID = '$Sponsor_ID' and
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));
	        }else{
				$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
	                                tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
	                                pr.Sponsor_ID = sp.Sponsor_ID and
	                                s.Registration_ID = pr.Registration_ID and
	                                $Date_Filter
	                                sp.Sponsor_ID = '$Sponsor_ID' and
	                                s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));        	
	        }
	    }
        $no = mysqli_num_rows($select);
        if($no > 0){
            $temp = 0;
            while ($data = mysqli_fetch_array($select)) {
                //calculate age
                $Date_Of_Birth = $data['Date_Of_Birth'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";

                $Patient_Type = $data['Patient_Type'];
                if(strtolower($Patient_Type) == 'inpatient'){

    ?>
                <tr>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Patient_Name']; ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Registration_ID']; ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Guarantor_Name']; ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['Gender'])); ?></a></td>
                    <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Member_Number']; ?></a></td>
                </tr>
    <?php
    			}else{
    ?>
                <tr>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Patient_Name']; ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Registration_ID']; ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Guarantor_Name']; ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['Gender'])); ?></a></td>
                    <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Member_Number']; ?></a></td>
                </tr>
    <?php
    			}
            }
        }
    ?>
</table>