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
	$temp = 0;
    $Employee_ID = 0;

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


    $htm = "<table width ='100%' height = '30px'>
            <tr><td>
            <img src='./branchBanner/branchBanner.png'>
            </td></tr>
        </table>";

    $htm .= "<br/><span style='font-size: x-small;'><b>LIST OF UNPAID PATIENTS <br/>
                Employee Checked-in ~ All Employees<br/>
                Item selected to review payments ~ <i>".ucwords(strtolower($Product_Name))."</i><br/>
                Start Date ~ ".$Date_From."<br/>
                End Date ~ ".$Date_To."<br/><br/>
            ";

	$htm .= '<table width="100%" style="background-color: white;">
		<tr><td colspan="5"><hr></td></tr>
		<tr>
			<td width="5%" style="text-align: right;"><span style="font-size: x-small;"><b>SN</b></span></td>
			<td><span style="font-size: x-small; "><b>EMPLOYEE NAME</b></span></td>
			<td width="15%" style="text-align: right;"><span style="font-size: x-small; "><b>PAID</b></span></td>
            <td width="15%" style="text-align: right;"><span style="font-size: x-small; "><b>NOT PAID</b></span></td>
			<td width="15%" style="text-align: right;"><span style="font-size: x-small; "><b>TOTAL</b></span></td>
		</tr>
		<tr><td colspan="5"><hr></td></tr>';

	$Grand_Total_Paid = 0;
	$Grand_Total_Unpaid = 0;
	$Total_Paid = 0;
	$Total_Unpaid = 0;

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
    $Control = 0;
    if($numz > 0){
    	while ($data = mysqli_fetch_array($select_details)) {
    		$Employee_Name = $data['Employee_Name'];
    		$Employee_ID = $data['Employee_ID'];
    		//get total paid based on dates & insurance selected
            if($Sponsor_ID == 0){
                $get_paid = mysqli_query($conn,"select ci.Check_In_ID, pp.Patient_Payment_ID from tbl_patient_payments pp, tbl_check_in ci where
                                        ci.Employee_ID = '$Employee_ID' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
            }else{
                $get_paid = mysqli_query($conn,"select ci.Check_In_ID, pp.Patient_Payment_ID from tbl_patient_payments pp, tbl_check_in ci where
                                        ci.Employee_ID = '$Employee_ID' and
                                        pp.Check_In_ID = ci.Check_In_ID and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by Ci.Check_In_ID") or die(mysqli_error($conn));
            }

            $nums = mysqli_num_rows($get_paid);
            if($nums > 0){
            	while ($data = mysqli_fetch_array($get_paid)) {
            		$Patient_Payment_ID = $data['Patient_Payment_ID'];

            		//check if available 
            		$slt = mysqli_query($conn,"select Item_ID from tbl_patient_payment_item_list where Item_ID = '$Item_ID' and Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
            		$slt_num = mysqli_num_rows($slt);
            		if($slt_num > 1){
            			$Control += 1;
            			$Total_Paid += 1;
            			$Grand_Total_Paid += 1;
            		}else if($slt_num == 1){
            			$Total_Paid += 1;
            			$Grand_Total_Paid += 1;
            		}else{
            			/*$Total_Unpaid += 1;
            			$Grand_Total_Unpaid += 1;*/
            		}
            	}
            }

            //GET TOTAL UNPAID PATIENTS VIA (GRAND TOTAL MINUS TOTAL PAID)
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
            $Grand_Nums = mysqli_num_rows($get_total); //total unpaid
            $Grand_Total_Unpaid += ($Grand_Nums - $Total_Paid); // grand total unpaid


	$htm .= '<tr>
    			<td width="5%" style="text-align: right;"><span style="font-size: x-small;">'.++$temp.'.</span></td>
    			<td><span style="font-size: x-small;">'.ucwords(strtolower($Employee_Name)).'</span></td>
    			<td width="15%" style="text-align: right;"><span style="font-size: x-small;">'.$Total_Paid.'</span></td>
                <td width="15%" style="text-align: right;"><span style="font-size: x-small;">'.($Grand_Nums - $Total_Paid).'</span></td>
    			<td width="15%" style="text-align: right;"><span style="font-size: x-small;">'.($Total_Paid + ($Grand_Nums - $Total_Paid)).'</span></td>
    		</tr>';

			$Total_Paid = 0;
			$Total_Unpaid = 0;

    	}
    }

    $htm .= '<tr><td colspan="5"><hr></td></tr>
                <tr>
                	<td style="text-align: right;" colspan="2"><span style="font-size: x-small;"><b>TOTAL</b></span></td>
                	<td style="text-align: right;"><span style="font-size: x-small;"><b>'.$Grand_Total_Paid.'</b></span></td>
                    <td style="text-align: right;"><span style="font-size: x-small;"><b>'.$Grand_Total_Unpaid.'</b></span></td>
                	<td style="text-align: right;"><span style="font-size: x-small;"><b>'.($Grand_Total_Unpaid + $Grand_Total_Paid).'</b></span></td>
                </tr>
                <tr><td colspan="5"><hr></td></tr>
                </table>';

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>