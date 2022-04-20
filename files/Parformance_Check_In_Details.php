<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<?php
	session_start();
	include("./includes/connection.php");
	$Item_ID = 0;
	$temp = 0;
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	
	if($Sponsor_ID == 0 || $Sponsor_ID == ''){
		$Sponsor_Name = 'All';
	}else{
		$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($row = mysqli_fetch_array($select)) {
				$Sponsor_Name = $row['Guarantor_Name'];
			}
		}
	}

?>
	<table width="90%">
		<tr>
			<td>
				<input type="text" name="Item_Name" id="Item_Name" value="" readonly="readonly" style="text-align: center;" placeholder="Select Exclusion Name">
				<input type="hidden" name="Item_ID" id="Item_ID" value="">
			</td>
			<td width="25%">&nbsp;&nbsp;&nbsp;
				<input type="button" name="Filter" id="Filter" value="SELECT ITEM TO FILTER" class="art-button-green" onclick="Select_E_Item('<?php echo $Sponsor_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Employee_ID; ?>');">
			</td>
			<!-- <td width="7%">
				<a href="previewregistrationperformance.php?Sponsor_ID=<?php echo $Sponsor_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Employee_ID=<?php echo $Employee_ID; ?>" class="art-button-green" target="_blank">PREVIEW</a>
			</td> -->
		</tr>
	</table><br/>


	<?php echo "<center>Sponsor Name : ".$Sponsor_Name." - Start Date : ".$Date_From." - End Date : ".$Date_To."</center>"; ?>
	<?php echo "<center id='Exclusion_Title'>Selected Item : <i> (no item selected) </i></center><br/>"; ?>

<center id="Details_After_Excluded_Area">
	<table width="100%" style="background-color: white;">
		<tr><td colspan="7"><hr></td></tr>
		<tr>
			<td width="5%" style="text-align: right;"><b>SN</b>&nbsp;&nbsp;&nbsp;</td>
			<td><b>EMPLOYEE NAME</b></td>
			<td width="15%"style="text-align: right;"><b>PATIENTS PAID&nbsp;&nbsp;&nbsp;</b></td>
			<td width="15%"style="text-align: right;"><b>PATIENTS NOT PAID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			<td width="15%"style="text-align: right;"><b>TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
			<td colspan="2" style="text-align: center;"><b>ACTION</b></td>
		</tr>
		<tr><td colspan="7"><hr></td></tr>

<?php
	$Grand_Total_Paid = 0;
	$Grand_Total_Unpaid = 0;
	$Total_Paid = 0;
	if($Sponsor_ID == 0){
        if($Employee_ID == 0){
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' 
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }else{
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID'
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }
    }else{
        if($Employee_ID == 0){
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pr.Sponsor_ID = '$Sponsor_ID' 
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }else{
            $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_registration pr, tbl_employee emp where
                                            pr.Employee_ID = emp.Employee_ID and 
                                            pr.Registration_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' and
                                            pr.Sponsor_ID = '$Sponsor_ID'
                                            group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
        }
    }

    $numz = mysqli_num_rows($select_details);
    if($numz > 0){
    	while ($data = mysqli_fetch_array($select_details)) {
    		$Employee_Name = $data['Employee_Name'];
    		$Employee_ID = $data['Employee_ID'];
    		//get total paid based on dates & insurance selected
            if($Sponsor_ID == 0){
                $get_paid = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                        ci.Employee_ID = '$Employee_ID' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
            }else{
                $get_paid = mysqli_query($conn,"select ci.Check_In_ID from tbl_patient_payments pp, tbl_check_in ci where
                                        ci.Employee_ID = '$Employee_ID' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
            }
            $nums = mysqli_num_rows($get_paid);
            $Total_Paid = $nums;
            $Grand_Total_Paid += $nums;

            //GET TOTAL UNPAID PATIENTS VIA GRAND TOTAL MINUS TOTAL PAID
            if($Sponsor_ID == 0){
				$get_total = mysqli_query($conn,"select Check_In_ID from tbl_check_in ci where 
	            							ci.Employee_ID = '$Employee_ID' and 
	            							ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
            }else{
            	$get_total = mysqli_query($conn,"select Check_In_ID from tbl_check_in ci, tbl_patient_registration pr where 
	            							ci.Employee_ID = '$Employee_ID' and
	            							pr.Registration_ID = ci.Registration_ID and
	            							pr.Sponsor_ID = '$Sponsor_ID' and
	            							Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
            }
            $Grand_Nums = mysqli_num_rows($get_total);
            $Grand_Total_Unpaid += ($Grand_Nums - $Total_Paid);
            

?>
		<tr>
			<td width="5%" style="text-align: right;"><?php echo ++$temp; ?>.&nbsp;&nbsp;&nbsp;</td>
			<td><?php echo ucwords(strtolower($Employee_Name)); ?></td>
			<td width="15%" style="text-align: right;"><?php echo $Total_Paid; ?>&nbsp;&nbsp;&nbsp;</td>
			<td width="15%" style="text-align: right;"><?php echo ($Grand_Nums - $Total_Paid); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="8%" style="text-align: right;"><?php echo ($Total_Paid + ($Grand_Nums - $Total_Paid)); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="10%">
				<input type="button" value="PREVIEW PAID" class="art-button-green" onclick="AccessDeny()">
			</td>
			<td width="10%">
				<input type="button" value="PREVIEW UNPAID" class="art-button-green" onclick="AccessDeny()">
			</td>
		</tr>
<?php
    	}
    }
?>
<tr><td colspan="7"><hr></td></tr>
<tr>
	<td style="text-align: right;" colspan="2"><b>TOTAL</b></td>
	<td style="text-align: right;"><b><?php echo $Grand_Total_Paid; ?>&nbsp;&nbsp;&nbsp;</b></td>
	<td style="text-align: right;"><b><?php echo $Grand_Total_Unpaid; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	<td style="text-align: right;"><b><?php echo ($Grand_Total_Unpaid + $Grand_Total_Paid); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td></td>
	<!-- <td colspan="2" style="text-align: right;"><a href="#previewallpaiddetails.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Employee_ID=<?php echo $Employee_ID; ?>PreviewAll=PreviewAllThisPage" class="art-button-green">PREVIEW ALL</a></td> -->
</tr>
<tr><td colspan="7"><hr></td></tr>
</table>
</center>