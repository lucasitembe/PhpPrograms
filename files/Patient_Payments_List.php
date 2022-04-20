<legend align="left"><b>PAYMENTS PREVIEW LIST</b></legend>
<?php
	session_start();
	include("./includes/connection.php");

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

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

	$Filter = " ci.Visit_Date between '$Start_Date' and '$End_Date' and ";

	if(isset($_GET['Patient_Name'])){
		$Filter .= " pr.Patient_Name like '%$Patient_Name%' and ";
	}

	if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$Filter .= " pr.Registration_ID = '$Patient_Number' and "; 
	}

	if($Sponsor_ID != 0){
		$Filter .= " sp.Sponsor_ID = '$Sponsor_ID' and ";
	}
?>
<table width = "100%">
<?php
	$temp = 0;
    $Title = '<tr><td colspan="10"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="14%"><b>PATIENT NUMBER</b></td>
                    <td width="14%"><b>SPONSOR NAME</b></td>
                    <td width="15%"><b>PATIENT AGE</b></td>
                    <td width="9%"><b>GENDER</b></td>
                    <td width="15%"><b>VISIT DATE</b></td>
                    <td width="10%"><b>OUTPATIENT<br/> TOTAL</b></td>
                    <td width="10%"><b>INPATIENT <br/>TOTAL</b></td>
                    <td width="10%"><b>TOTAL</b></td>
                </tr>
                <tr><td colspan="10"><hr></td></tr>';

    $select = mysqli_query($conn,"SELECT pp.Patient_Payment_ID,pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, sp.Sponsor_ID, ci.Check_In_ID, ci.Check_In_Date_And_Time
                            from tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci, tbl_patient_payments pp where
                            $Filter
                            pp.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = ci.Registration_ID and
                            ci.Check_In_ID = pp.Check_In_ID and
                            (((Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Patient From Outside') and Pre_Paid = '0') or ( pp.Billing_Type = 'Inpatient Cash' and payment_type = 'pre')) and
                            pp.Transaction_status <> 'cancelled'  and pp.auth_code <> ''
                            group by ci.Check_In_ID, pp.Registration_ID, pp.Sponsor_ID
                            order by ci.Check_In_ID Desc limit 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
       $All_Grand_Total=0;
       $All_Grand_Total_inpatient=0;
       $All_Grand_Total_outpatient=0;
        while ($data = mysqli_fetch_array($select)) {
            $link = '<label onclick="Preview_Details('.$data['Registration_ID'].','.$data['Check_In_ID'].','.$data['Sponsor_ID'].')" style="color: #037CB0;">';
            if($temp%10 == 0){ echo $Title; }
                $Patient_Payment_ID=$data['Patient_Payment_ID'];
                
                //calculate total amount///////////////////////////////////////////////////////////
                	$Grand_Total = 0;
                	$Grand_Total_inpatient = 0;
                	$Grand_Total_outpatient = 0;
	//Get Receipts
        $Registration_ID=$data['Registration_ID'];
        $Check_In_ID=$data['Check_In_ID'];
        $Sponsor_ID=$data['Sponsor_ID'];
	$select_res = mysqli_query($conn,"SELECT Patient_Payment_ID, Payment_Date_And_Time, Billing_Type from
								tbl_patient_payments where
								Registration_ID = '$Registration_ID' and
								Check_In_ID = '$Check_In_ID' and
								Sponsor_ID = '$Sponsor_ID' and
								Transaction_status <> 'cancelled' and auth_code <> '' and
								(((Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Patient From Outside') and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and payment_type = 'pre'))") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select_res);
	if($nm > 0){
		while ($row = mysqli_fetch_array($select_res)) {
			$count = 0;
			$Total = 0;
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
			$Billing_Type = $row['Billing_Type'];

						$slct = mysqli_query($conn,"SELECT i.Product_Name, ppl.Price, ppl.Discount, ppl.Quantity from tbl_patient_payment_item_list ppl, tbl_items i where
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
											ppl.Item_ID = i.Item_ID") or die(mysqli_error($conn));
						$nmz = mysqli_num_rows($slct);
						if($nmz > 0){
							while ($dt = mysqli_fetch_array($slct)) {
								$Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                if($Billing_Type=="Outpatient Cash"){
                                                                    $Grand_Total_outpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }else{
                                                                    $Grand_Total_inpatient += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                                                }
								$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
						}

		}
	}
                //////////////////////////////////////////////////////////////////////////////////
            //Calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
?>
            <tr id="sss">
                <td><?php echo $link.(++$temp); ?></label></td>
                <td><?php echo $link.ucwords(strtolower($data['Patient_Name'])); ?></label></td>
                <td><?php echo $link.$data['Registration_ID']; ?></label></td>
                <td><?php echo $link.$data['Guarantor_Name']; ?></label></td>
                <td><?php echo $link.$age; ?></label></td>
                <td><?php echo $link.$data['Gender']; ?></label></td>
                <td><?php echo $link.$data['Check_In_Date_And_Time']; ?></label></td>
                <td><?php echo $link.number_format($Grand_Total_outpatient); ?></label></td>
                <td><?php echo $link.number_format($Grand_Total_inpatient); ?></label></td>
                <td><?php echo $link.number_format($Grand_Total); ?></label></td>
            </tr>
<?php
        $All_Grand_Total+=$Grand_Total;
        $All_Grand_Total_inpatient+=$Grand_Total_inpatient;
        $All_Grand_Total_outpatient+=$Grand_Total_outpatient;
        }
    }else{
    	echo $Title;
    }
    ?>      <tr>
                <td colspan="10">
                    <hr/>
                </td>
            </tr>
            <tr>
                <td colspan="7"><b>GRAND TOTAL :</b></td>
                <td><b><?= number_format($All_Grand_Total_outpatient) ?></b></td>
                <td><b><?= number_format($All_Grand_Total_inpatient) ?></b></td>
                <td><b><?= number_format($All_Grand_Total) ?></b></td>
            </tr>
</table>