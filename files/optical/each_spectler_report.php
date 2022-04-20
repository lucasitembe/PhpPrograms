<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$E_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$E_Name = '';
	}
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	//get Guarantor_Name
	if($Sponsor_ID == 0){
		$Guarantor_Name = 'All';
	}else{
		$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Guarantor_Name = $data['Guarantor_Name'];
			}
		}else{
			$Guarantor_Name = 'All';
		}
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
    if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
    }
    if(isset($_GET['Product_Name'])){
		$Product_Name = $_GET['Product_Name'];
	}else{
		$Product_Name = '';
    }
 
    if(isset($_SESSION['Optical_info'])){
        $Sub_Department_ID = $_SESSION['Optical_info'];
    }else{
        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
		$Today = $new_Date;
		
    }
    $htm = "<table width ='100%' height = '30px'>
			<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td></td></tr></table>";
		
	$htm .= '<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">PATIENT SPECTACLES  REPORT OF '.strtoupper( $Product_Name).'</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($Start_Date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($End_Date)).'</b></span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">SPONSOR : '.strtoupper($Guarantor_Name).'</span></td></tr>
	        </table></center>';


	$htm .=	'<table width="100%" border=1 style="border-collapse: collapse;" class="table table-striped">
			    <thead><tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;width:250px;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;width:110px;"><b>PATIENT NO</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>DISPENSED DATE</b></th>
                <th style="text-align:left;"><b>DISPENSED BY</b></th>
                </tr></thead>';
  

                            
        $select = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name,preg.Patient_Name,pp.Registration_ID,preg.Gender, preg.Phone_Number,emp.Employee_Name,ilc.Dispense_Date_Time
        from tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration preg,tbl_item_list_cache ilc,tbl_employee emp  where
        i.Item_ID = ppl.Item_ID and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and 
        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and emp.Employee_ID=ilc.Dispensor and
        i.Consultation_Type = 'Optical' and pp.Registration_ID=preg.Registration_ID and
        pp.Transaction_status <> 'cancelled' and ilc.Patient_Payment_ID=pp.Patient_Payment_ID and ilc.Status='dispensed' and i.Item_ID='$Item_ID' and
        pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' group by ilc.Dispense_Date_Time") or die(mysqli_error($conn));
    
    
    
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {

			//FINDING LIST OF PATIENT GIVEN ITEM

			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					//calculate patient age
					$date1 = new DateTime($Today);
					$date2 = new DateTime($data['Date_Of_Birth']);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";
                    $product_name.=$data['Product_Name'].",";
                    echo "<tbody id='search_result'>";
                        echo "<tr>";
                        echo "<td style='text-align:left'>".++$temp."</td>";
                        echo "<td>".ucwords(strtolower($data['Patient_Name']))."</td>";
                        echo "<td>".$data['Registration_ID']."</td>";
                        // echo "<td>".$data['Product_Name']."</td>";
                        echo "<td>".$data['Gender']."</td>";
                        echo "<td>".$data['Phone_Number']."</td>";
                        echo "<td>".$data['Dispense_Date_Time']."</td>";
                        echo "<td>".$data['Employee_Name']."</td>";
                        echo "</tr>";
				}
			}

			$htm .=	'<tr>
				        <td style="text-align:left">'.++$temp.'</td>
				        <td>'.ucwords(strtolower($data['Patient_Name'])).'</td>
				        <td>'.$data['Registration_ID'].'</td>
                        <td>'.$data['Gender'].'</td>
				        <td>'.$data['Phone_Number'].'</td>
				        <td>'.$data['Dispense_Date_Time'].'</td>
				        <td>'.$data['Employee_Name'].'</td>
				    </tr>';

		}
	}
	$htm .= '</table>';

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('s','A4-L', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered by GPITG');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>