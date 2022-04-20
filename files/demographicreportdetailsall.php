<?php
	session_start();
	$temp = 0;
	$Grand_Total = 0;
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get sponsor name
	$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Guarantor_Name = $row['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	if(isset($_GET['Date_From'])){
		$Date_From =$_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['District_ID'])){
		$District_ID = $_GET['District_ID'];
	}else{
		$District_ID = '';
	}

	if(isset($_GET['Age_From'])){
		$Age_From = $_GET['Age_From'];
	}else{
		$Age_From = 0;
	}

	if(isset($_GET['Age_To'])){
		$Age_To = $_GET['Age_To'];
	}else{
		$Age_To = 0;
	}

	//get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
	}
	$Employee_ID = $_GET['Employee_ID'];
	if(isset($_GET['agetype'])){
		$agetype = $_GET['agetype'];
	}else{
		$agetype = '';
	}
	if(isset($_GET['visit_type'])){
		$visit_type = $_GET['visit_type'];
	}else{
		$visit_type = '';
	}
	if(isset($_GET['Type_Of_Check_In'])){
		$Type_Of_Check_In = $_GET['Type_Of_Check_In'];
	}else{
		$Type_Of_Check_In = '';
	}
	$Region_ID = $_GET['Region_ID'];
	$filter ='';
	$filterdaterange='';
	if($Region_ID != 'All' && $Region_ID !=''){
		$filter = " AND pr.Region='$Region_ID'";
	}
	if($District_ID != 'All' && $District_ID != 0){
		$filter .= "  AND pr.District='$District_ID'";
	}
	if($visit_type != 'All' ){
		$filter .= " AND visit_type='$visit_type'";
	}
	if($Type_Of_Check_In != 'All'){
		$filter .= " AND Type_Of_Check_In='$Type_Of_Check_In'";
	}
	if($Employee_ID != ''){
		$filter .=" AND ci.Employee_ID='$Employee_ID'";
	}
	if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
		$Clinic_ID = '';
	}
	if($Clinic_ID != 'All'){
		$filter .=" AND Clinic_ID='$Clinic_ID' ";
	}
	$filterAge='';
	if($Age_From!='' && $Age_To !=''){
		$filterAge =" AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$Age_From."' AND '".$Age_To."' AND ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' ";
	}
	

	//get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
    }

	
	//THIS IS FOR KCMC
	// $get_patients = mysqli_query($conn,"SELECT pp.Check_In_ID, Date_Of_Birth,Check_In_Date_And_Time, Gender, pr.Registration_ID,  pr.Phone_Number, pr.Patient_Name, Employee_Name from tbl_check_in ci, tbl_patient_registration pr, tbl_patient_payment_item_list ptl,	tbl_patient_payments pp where ptl.Patient_payment_ID=pp.Patient_payment_ID AND ci.Check_In_ID = pp.Check_In_ID AND ptl.Check_In_Type = 'Doctor Room' AND	ci.Registration_ID = pr.Registration_ID and 	pr.Sponsor_ID = '$Sponsor_ID'  $filter $filterAge") or die(mysqli_error($conn));
						
	//HAJI DODOMA HOSP ANATAKA WALIOKUWA CHECKED IN TU DOESNT MATTER KM AMEONWA NA DOCTOR===>
	$get_patients = mysqli_query($conn,"SELECT pp.Check_In_ID, Date_Of_Birth,Check_In_Date_And_Time, Gender, pr.Registration_ID,  pr.Phone_Number, pr.Patient_Name, Employee_Name from tbl_check_in ci, tbl_patient_registration pr, tbl_patient_payment_item_list ptl,	tbl_patient_payments pp, tbl_employee e where e.Employee_ID=ci.Employee_ID AND ptl.Patient_payment_ID=pp.Patient_payment_ID  AND ci.Check_In_ID = pp.Check_In_ID AND ptl.Check_In_Type = 'Doctor Room' AND	ci.Registration_ID = pr.Registration_ID and 	pr.Sponsor_ID = '$Sponsor_ID'  $filter $filterAge") or die(mysqli_error($conn));



	$htm = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
	    </table>";
	
	

	$num = mysqli_num_rows($get_patients);

	$htm .= '<span style="font-size: x-small;"><b>Sponsor ~ </b>'.$Guarantor_Name.'<br/>
	<b>Start Date ~ </b>'.$Date_From.'<br/>
	<b>End Date ~ </b>'.$Date_To.'</span><br/><br/>
	<table width="100%" style="background-color: white;">
		<tr>
			<td width="5%"><span style="font-size: x-small;"><b>SN</b></span></td>
			<td><span style="font-size: x-small;"><b>PATIENT NAME</b></span></td>
			<td width="12%"><span style="font-size: x-small;"><b>PATIENT#</b></span></td>
			<td><span style="font-size: x-small;"><b>GENDER</b></span></td>
			<td><span style="font-size: x-small;"><b>PHONE NUMBER</b></span></td>
						<td><span style="font-size: x-small;"><b>SPONSOR</b></span></td>
						<td><b>CHECK IN TIME</b></td>';
        
        
                       if(strtolower($_SESSION['userinfo']['can_view_demographic_revenue']) == 'yes'){
                         $htm .='<td width="18%"><span style="font-size: x-small;"><b>CHECK IN EMPLOYEE</b></span></td>
			  <td width="10%" style="text-align: right;"><span style="font-size: x-small;"><b>AMOUNT</b></span></td>';
                         
                        } 

		 $htm.='</tr>
		';

	if($num > 0){
		while ($data = mysqli_fetch_array($get_patients)) {
			$Registration_ID = $data['Registration_ID'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Check_In_ID = $data['Check_In_ID'];
			//generate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y;
			
			if($age >= $Age_From && $age <= $Age_To){
				//get total spent
				$P_Amount = mysqli_query($conn,"select SUM((Price - Discount) * Quantity) as Amount from
											tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Check_In_ID = '$Check_In_ID' and Transaction_status<>'cancelled'") or die(mysqli_error($conn));
				$num_P = mysqli_num_rows($P_Amount);
				if($P_Amount > 0){
					while ($p_Data = mysqli_fetch_array($P_Amount)) {
						$Amount = $p_Data['Amount'];
						$Grand_Total += $p_Data['Amount'];

					}
				}else{
					$Amount = 0;
				}
				$htm .='<tr>
					<td width="5%"><span style="font-size: x-small;">'.++$temp.'<b>.</b></span></td>
					<td><span style="font-size: x-small;">'.ucwords(strtolower($data['Patient_Name'])).'</b></span></td>
					<td><span style="font-size: x-small;">'.$data['Registration_ID'].'</b></span></td>
					<td><span style="font-size: x-small;">'.$data['Gender'].'</b></span></td>
                                            <td><span style="font-size: x-small;">'.$data['Phone_Number'].'</b></span></td>
					<td><span style="font-size: x-small;">'.$Guarantor_Name.'</b></span></td>
					<td>'.$data['Check_In_Date_And_Time'].'</td>';
                                
                                        if(strtolower($_SESSION['userinfo']['can_view_demographic_revenue']) == 'yes'){
                                          $htm .='<td><span style="font-size: x-small;">'.ucwords(strtolower($data['Employee_Name'])).'</b></span></td>
					  <td style="text-align: right;"><span style="font-size: x-small; text-align: right;">'.number_format($Amount).'</b></span></td>'; 
                                        }
                       
				$html.='</tr>';
			}
		}

		
                if(strtolower($_SESSION['userinfo']['can_view_demographic_revenue']) == 'yes'){
                 $htm .='<tr><td colspan="9"><hr></td></tr>';
                 $htm .="<tr>
                        <td colspan='8' style='text-align: left'><span style='font-size: x-small;'><b>GRAND TOTAL</span></td>
                        <td style='text-align: right;'><span style='font-size: x-small;'>".number_format($Grand_Total)."</span></td>
                        </tr>";
		$htm .='<tr><td colspan="9"><hr></td></tr>';   
                }
	}
?>

<?php
	$htm .= '</table>';
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>