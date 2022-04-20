<?php
	session_start();
	include("./includes/connection.php");
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


	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}
	//get employee check in
	$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Employee_Name = $row['Employee_Name'];
		}
	}else{
		$Employee_Name = '';
	}


	//get item name
	$select = mysqli_query($conn,"select Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Product_Name = $data['Product_Name'];
		}
	}else{
		$Product_Name = 'none';
	}


	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	$htm = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
	    </table>";
	$htm .= "<br/><span style='font-size: x-small;'><b>LIST OF UNPAID PATIENTS <br/>
				Employee Checked-in ~ ".ucwords(strtolower($Employee_Name))."<br/>
				Item selected to review payments ~ <i>".ucwords(strtolower($Product_Name))."</i><br/>
				Start Date ~ ".$Date_From."<br/>
				End Date ~ ".$Date_To."<br/><br/>
			";
	$htm .= '<table width="100%">
		<tr>
			<td width="5%"><span style="font-size: x-small;"><b>SN</span></b></td>
			<td><span style="font-size: x-small;"><b>PATIENT NAME</span></b></td>
			<td width="25%"><span style="font-size: x-small;"><b>AGE</span></b></td>
			<td width="15%"><span style="font-size: x-small;"><b>SPONSOR</span></b></td>
			<td width="20%" style="text-align: right;"><span style="font-size: x-small;"><b>CHECK IN DATE</span></b></td>
		</tr>
		<tr><td colspan="5"><hr></td></tr>';

    //get all check in 
    if($Sponsor_ID == 0){
        $get_checkin = mysqli_query($conn,"select ci.Check_In_ID, ci.Registration_ID, ci.Check_In_Date_And_Time from tbl_check_in ci where 
        							ci.Employee_ID = '$Employee_ID' and
        							ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
    }else{
    	$get_checkin = mysqli_query($conn,"select Check_In_ID, ci.Registration_ID, ci.Check_In_Date_And_Time from tbl_check_in ci, tbl_patient_registration pr where 
                                    ci.Employee_ID = '$Employee_ID' and
                                    pr.Registration_ID = ci.Registration_ID and
                                    pr.Sponsor_ID = '$Sponsor_ID' and
                                    Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
    }


    $nums = mysqli_num_rows($get_checkin);
	if($nums > 0){
		while ($data = mysqli_fetch_array($get_checkin)) {
    		$Check_In_ID = $data['Check_In_ID'];
    		$Registration_ID = $data['Registration_ID'];

    		//check if paid
    		$verify = mysqli_query($conn,"select Check_In_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
    								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
    								pp.Check_In_ID = '$Check_In_ID' and
    								ppl.Item_ID = '$Item_ID'") or die(mysql_erro());

    		$verify_num = mysqli_num_rows($verify);
    		if($verify_num < 1){
    			//get patient details
    			$select2 = mysqli_query($conn,"select Date_Of_Birth, Patient_Name, Guarantor_Name from 
    									tbl_patient_registration pr, tbl_sponsor sp where
    									sp.Sponsor_ID = pr.Sponsor_ID and
    									pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

    			$select_num = mysqli_num_rows($select2);
    			if($select_num > 0){
	    			$temp++;
	    			while($row = mysqli_fetch_array($select2)){
	    				//calculate age
	    				$date1 = new DateTime($Today);
						$date2 = new DateTime($row['Date_Of_Birth']);
						$diff = $date1 -> diff($date2);
						$age = $diff->y." Years, ";
						$age .= $diff->m." Months, ";
						$age .= $diff->d." Days";

						$htm .= '<tr>
								<td width="5%"><span style="font-size: x-small;">'.$temp.'.</span></td>
								<td><span style="font-size: x-small;">'.ucwords(strtolower($row['Patient_Name'])).'</span></td>
								<td><span style="font-size: x-small;">'.$age.'</span></td>
								<td><span style="font-size: x-small;">'.$row['Guarantor_Name'].'</span></td>
								<td style="text-align: right;"><span style="font-size: x-small;">'.$data['Check_In_Date_And_Time'].'</span></td>
							</tr>';
	    			}
	    		}
    		}
    	}
	}

	$htm .= "</table>";

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>