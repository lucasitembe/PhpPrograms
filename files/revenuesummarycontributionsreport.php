<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
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
	
	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = 'All';
	}
	
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = '0';
	}

	//get employee
    if($Employee_ID == '0'){
        $Emp_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                $Emp_Name = $row['Employee_Name'];
            }
        }
    }
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor = $_GET['Sponsor_ID'];
	}else{
		$Sponsor = '0';
	}

	//get Sponsor
    if($Sponsor == '0'){
        $Sponsor_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Sponsor_Name = $row['Guarantor_Name'];
            }
        }else{
            $Sponsor_Name = 'ALL';
        }
    }

	$filter = " pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and pp.Transaction_status <> 'cancelled' and ";

	if($Employee_ID != null && $Employee_ID != '' && $Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and ";
	}

	if($Sponsor != 0 && $Sponsor != null && $Sponsor != ''){
		$filter .= " pp.Sponsor_ID = '$Sponsor' and ";
	}

	if($Billing_Type == 'Outpatient'){
		$Bill_Type = 'Outpatient';
	}else if($Billing_Type == 'Inpatient'){
		$Bill_Type = 'Inpatient';
	}else{
		$Bill_Type = 'All';
	}


	$htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	    <tr><td>&nbsp;</td></tr></table>";
		
	$htm .= '<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">REVENUE SUMMARY PATIENT CONTRIBUTIONS</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($Start_Date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($End_Date)).'</b></span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">PATIENT TYPE : '.strtoupper($Bill_Type).',&nbsp;&nbsp;&nbsp;&nbsp;SPONSOR : '.strtoupper($Sponsor_Name).'</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">EMPLOYEE NAME : '.strtoupper($Emp_Name).'</span></td></tr>
	        </table></center>';

	$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">';
	$Title = "<thead><tr>
			    <td width=5%><b><span style='font-size: x-small;'>SN<span></b></td>
			    <td><b><span style='font-size: x-small;'>PARTICULAR NAME<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>CASH<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>CREDIT<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>TOTAL<span></b></td>
			</tr></thead>";
	$htm .= $Title;
	$temp = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Msamaha = 0;

	//Tunatumia hii kwa muda mpaka fast track itakapokamilika maeneo yote
	$Total_Cash_Fast_Track = 0;
	$Total_Credit_Fast_Track = 0;
	
	//get Particular type
	$get_particulars = mysqli_query($conn,"select Particular_Type from tbl_items group by Particular_Type order by Particular_Type asc") or die(mysqli_error($conn));
	$num_particulars = mysqli_num_rows($get_particulars);
	if($num_particulars > 0){
		while ($data = mysqli_fetch_array($get_particulars)) {
			$Particular_Type = $data['Particular_Type'];
			$Total_Cash = 0;
			$Total_Credit = 0;

			if($Billing_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										(pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}else if($Billing_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										(pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}else{
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Fast_Track from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
										i.Item_ID = ppl.Item_ID and
										i.Particular_Type = '$Particular_Type' and
										$filter
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID")	or die(mysqli_error($conn));
			}

			while ($row = mysqli_fetch_array($select)) {
				$Amount = (($row['Price'] - $row['Discount']) * $row['Quantity']);
				if($row['Fast_Track'] == '1' || strtolower($Particular_Type) == 'fast track'){
					if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
				        $Total_Cash_Fast_Track += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }else if((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
				        $Total_Credit_Fast_Track += $Amount;
				        $Grand_Total_Credit += $Amount;
				    }
				}else{
					if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
				        $Total_Cash += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }else if((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')){
				        $Total_Credit += $Amount;
				        $Grand_Total_Credit += $Amount;
				    }
				}
			}
			if(strtolower($Particular_Type) != 'fast track'){
				$htm .= '<tr>
					    <td><span style="font-size: x-small;">'.++$temp.'</span></td>
					    <td><span style="font-size: x-small;">'.strtoupper($data['Particular_Type']).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Credit).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash + $Total_Credit).'</span></td>
					</tr>';
			}
		}
	}

	$htm .= '<tr>
			    <td><span style="font-size: x-small;">'.++$temp.'</span></td>
			    <td><span style="font-size: x-small;">FAST TRACK</span></td>
			    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash_Fast_Track).'</span></td>
			    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Credit_Fast_Track).'</span></td>
			    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash_Fast_Track + $Total_Credit_Fast_Track).'</span></td>
			</tr>';
	$htm .= '<tr>
			    <td colspan="2"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Cash).'</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Cash + $Grand_Total_Credit).'</span></b></td>
			</tr></table>';
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>