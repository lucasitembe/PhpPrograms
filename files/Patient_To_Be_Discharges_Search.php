<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}
	
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
    $temp = 0;
?>
<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS TO BE DISCHARGED</b></legend>
        <table width=100% border=1>
            <tr id='thead'>
    		    <td width=5%><b>SN</b></td>
    		    <td><b>PATIENT NAME</b></td>
    		    <td style='text-align: left; width: 7%;'><b>PATIENT #</b></td>
                <td style='text-align: left width: 7%;;'><b>MEMBER #</b></td>
                <td style='text-align: left width: 10%;;'><b>SPONSOR</b></td>
                <td style='text-align: left; width: 13%;'><b>PATIENT AGE</b></td>
                <td style='text-align: left; width: 5%;'><b>GENDER</b></td>
                <td style='text-align: left; width: 10%;'><b>EMPLOYEE DISCHARGE</b></td>
                <td style='text-align: left; width: 13%;'>&nbsp;&nbsp;<b>DISCHARGE DATE</b></td>
                <?php if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
                	<td width="7%"><b>ACTION</b></td>
                <?php } ?>
    		</tr>
    	    <tr><td colspan="10"><hr></td></tr>
<?php

	//create sql statement
	if($Sponsor_ID == '0'){
		$select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.pending_setter, ad.pending_set_time, ad.Admision_ID 
                            from tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'pending' and
                            ad.Credit_Bill_Status = 'pending' and
                            ad.Cash_Bill_Status = 'pending' and
                            ad.Discharge_Clearance_Status = 'not cleared' order by Admision_ID desc limit 200") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.pending_setter, ad.pending_set_time, ad.Admision_ID 
                            from tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'pending' and
                            ad.Credit_Bill_Status = 'pending' and
                            ad.Cash_Bill_Status = 'pending' and
                            pr.Sponsor_ID = '$Sponsor_ID' and
                            ad.Discharge_Clearance_Status = 'not cleared' order by Admision_ID desc limit 200") or die(mysqli_error($conn));
	}
$num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Date_Of_Birth = $data['Date_Of_Birth'];
            //calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";

            if($data['pending_setter'] != null && $data['pending_setter'] != ''){
                $Emp_ID = $data['pending_setter'];
                //get employee set
                $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Emp_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($slct);
                if($no > 0){
                    while ($dt = mysqli_fetch_array($slct)) {
                        $Employee_Name = $dt['Employee_Name'];
                    }
                }else{
                    $Employee_Name = '';
                }
            }else{
                $Employee_Name = '';
            }
?>
            <tr id='thead'>
                <td width=5%><?php echo ++$temp.'<b>.</b>'; ?></td>
                <td><?php echo $data['Patient_Name']; ?></td>
                <td><?php echo $data['Registration_ID']; ?></td>
                <td><?php echo $data['Member_Number']; ?></td>
                <td><?php echo $data['Guarantor_Name']; ?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo $data['Gender']; ?></td>
                <td><?php echo ucwords(strtolower($Employee_Name)); ?></td>
                <td>&nbsp;&nbsp;<?php echo $data['pending_set_time']; ?></td>
                <?php //if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
                <td>
                    <input type="button" name="Action" id="Action" value="UNDO DISCHARGED" class="art-button-green" onclick="Undo_Discharge_Warning(<?php echo $data['Admision_ID']; ?>);">
                </td>
                <?php // } ?>
            </tr>
<?php
        }
    }
?>
        </table>