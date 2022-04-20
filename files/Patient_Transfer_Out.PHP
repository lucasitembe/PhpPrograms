<?php
	session_start();
	include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
    	if(isset($_SESSION['userinfo']['Admission_Works'])){
    	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Admission_Supervisor'])){
                    header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
                }
            }
    	}else{
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = '';
    }
    
    if(isset($_GET['Admision_ID'])){
    	$Admision_ID = $_GET['Admision_ID'];
    }else{
    	$Admision_ID = '';
    }

    //get details
    $select = mysql_query("select sp.Guarantor_Name, pr.Gender, hw.Hospital_Ward_Name, pr.Patient_Name, a.Hospital_Ward_ID, hw.Hospital_Ward_Name, a.Admission_Status, a.Admission_Date_Time, bd.Bed_Name
                            from tbl_hospital_ward hw, tbl_patient_registration pr, tbl_sponsor sp, tbl_admission a, tbl_beds bd where
                            a.Registration_ID = pr.Registration_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            bd.Bed_ID = a.Bed_ID and
                            hw.Hospital_Ward_ID = a.Hospital_Ward_ID and
                            Admission_Status <> 'Discharged' and
                            a.Admision_ID = '$Admision_ID' and
                            pr.Registration_ID = '$Registration_ID' order by a.Admision_ID desc limit 200") or die(mysql_error());
	$no = mysql_num_rows($select) ;
	if($no > 0){
		while ($row = mysql_fetch_array($select)) {
			$Patient_Name = $row['Patient_Name'];
			$Gender = $row['Gender'];
			$Hospital_Ward_ID = $row['Hospital_Ward_ID'];
			$Hospital_Ward_Name = $row['Hospital_Ward_Name'];
			$Guarantor_Name = $row['Guarantor_Name'];
			$Admission_Date_Time = $row['Admission_Date_Time'];
			$Bed_Name = $row['Bed_Name'];
		}
	}else{
		$Patient_Name = '';
		$Gender = '';
		$Hospital_Ward_ID = '';
		$Hospital_Ward_Name = '';
		$Guarantor_Name = '';
		$Admission_Date_Time = '';
		$Bed_Name  = '';
	}
?>
<fieldset>
	<table width="100%">
		<tr>
			<td width="12%" style="text-align: right;">Patient Name</td>
			<td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
			<td width="12%" style="text-align: right;">Sponsor Name</td>
			<td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
			<td width="12%" style="text-align: right;">Gender</td>
			<td><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
		</tr>
		<tr>
			<td style="text-align: right;">Current Ward</td>
			<td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Hospital_Ward_Name)); ?>"></td>
			<td style="text-align: right;">Current Bed</td>
			<td><input type="text" readonly="readonly" value="<?php echo $Bed_Name; ?>"></td>
			<td style="text-align: right;">Admission Date</td>
			<td><input type="text" readonly="readonly" value="<?php echo @date("d F Y H:i:s",strtotime($Admission_Date_Time)); ?>"></td>
		</tr>
	</table>
</fieldset>
<fieldset>
<?php
    //check for pending transfers
    $check = mysql_query("select Transfer_Detail_ID from tbl_patient_transfer_details where Admision_ID = '$Admision_ID' and Transfer_Status = 'pending'") or die(mysql_error());
    $num_check = mysql_num_rows($check);
	if($num_check < 1){
?>
	<table width="100%">
		<tr>
			<td style="text-align: right;" width="15%">Ward Name</td>
			<td width="15%">
				<select required='required' name='Hospital_Ward_ID' id='Hospital_Ward_ID' onchange="Get_Ward_Beds(this.value)" >
                    <option selected='selected'></option>
                    <?php
                    	$select = mysql_query("select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward where
                    							Hospital_Ward_Name <> '$Hospital_Ward_Name' and
                    							ward_nature = 'both' or ward_nature = '$Gender'") or die(mysql_error());
                    	$nm = mysql_num_rows($select);
                    	if($nm > 0){
	                    	while ($row = mysql_fetch_array($select)) {
		                        $Ward_Name = $row['Hospital_Ward_Name'];
		                        $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
		                        $Number_of_Beds = $row['Number_of_Beds'];

		                        $count_beds = "SELECT COUNT(*) as beds FROM tbl_admission WHERE Hospital_Ward_ID = $Hospital_Ward_ID AND Admission_Status = 'Admitted'";
		                        $taken_beds = mysql_query($count_beds) or die(mysql_error());
		                        while ($row = mysql_fetch_assoc($taken_beds)) {
		                            $beds_accupied = $row['beds'];
		                            $available_beds = $Number_of_Beds - $beds_accupied;
		                        }
		                        ?>
		                        <option value='<?php echo $Hospital_Ward_ID; ?>'>
		                            <?php
		                            	echo $Ward_Name;
		                            ?>
		                        </option>
		                        <?php
		                    }
		                }

					?>
                </select>
			</td>
			<td style="text-align: right;" width="15%" width="15%">Bed Number</td>
			<td id="Ward_Area" width="15%">
                <select name="Bed_ID" id="Bed_ID" onchange="checkPatientNumber(this.value)">
                    <option selected='selected'></option>
                </select>
			</td>
			<td width="10%" style="text-align: right;">
				<input type="button" value="PROCESS TRANSFER" class="art-button-green" onclick="Create_Patient_transfer_Dialog(<?php echo $Registration_ID; ?>,<?php echo $Admision_ID; ?>)">
			</td>
		</tr>
	</table>
	<?php }else{ ?>
		<center><span style='color: #037CB0;'><b><?php echo ucwords(strtolower($Patient_Name)); ?> has pending transfer request. <br/>Close dialog and Click "PATIENT TRANSFER IN" button above to complete transfer</b></span></center>
	<?php } ?>
</fieldset>