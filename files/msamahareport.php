<?php
	session_start();
	include("./includes/connection.php");
	$Title = '<tr><td colspan="15"><hr></td></tr>
			    <tr>
			        <td width="3%"><b>SN</b></td>
			        <td><b>Date</b></td>
			        <td><b>Jina la mgojwa</b></td>
			        <td><b>namba ya mgonjwa</b></td>        
			        <td><b>Umri</b></td>
			        <td><b>Jinsia</b></td> 
			        <td><b>Nambari ya simu</b></td>
			        <td><b>Aina ya Msamaha</b></td>
			        <td><b>Jina la mtu anayependekeza Msamaha</b></td>
			        <td><b>Jina la Balozi</b></td>
			        <td><b>Region</b></td>
			        <td><b>District</b></td>
			        <td><b>Ward</b></td>
			        <td><b>Kazi ya mke/mlezi</b></td>
			        <td><b>Prepared By</b></td>
			   	</tr>
			   	<tr><td colspan="15"><hr></td></tr>';

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

	if(isset($_GET['msamaha_aina'])){
		$msamaha_aina = $_GET['msamaha_aina'];
	}else{
		$msamaha_aina = '';
	}

	if(isset($_GET['jinsi'])){
		$jinsi = $_GET['jinsi'];
	}else{
		$jinsi = '';
	}

	if(isset($_GET['employee_ID'])){
		$employee_ID = $_GET['employee_ID'];
	}else{
		$employee_ID = '';
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = str_replace(" ", "", $_GET['Patient_Number']);
	}else{
		$Patient_Number = '';
	}

	if(isset($_GET['Patient_Phone'])){
		$Patient_Phone = $_GET['Patient_Phone'];
	}else{
		$Patient_Phone = '';
	}

	//create filter
	$filter = " ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and sp.Exemption = 'yes' and ";

	if($msamaha_aina != '' && $msamaha_aina != null && $msamaha_aina != '0'){
		//$filter .= " ci.msamaha_Items = '$msamaha_aina' and ";
                
                if($msamaha_aina != '' && $msamaha_aina != null && $msamaha_aina != '0'){
                $slct = mysqli_query($conn,"select msamaha_aina from tbl_msamaha_items where msamaha_Items = '$msamaha_aina'") or die(mysqli_error($conn));
                    $nmz = mysqli_num_rows($slct);
                    if($nmz > 0){
                            $res = mysqli_fetch_assoc($slct);
                            $msamaha_aina = $res['msamaha_aina'];
                            $filter .= " ms.aina_ya_msamaha = '$msamaha_aina' and ";
                    }

            }
                
                
		//Get Msamaha name
		$slct = mysqli_query($conn,"select msamaha_aina from tbl_msamaha_items where msamaha_Items = '$msamaha_aina'") or die(mysqli_error($conn));
		$nmz = mysqli_num_rows($slct);
		if($nmz > 0){
			$res = mysqli_fetch_assoc($slct);
			$Msamaha_Title = $res['msamaha_aina'];
		}else{
			$Msamaha_Title = '';
		}
	}else{
		$Msamaha_Title = 'All';
	}

	/*if($msamaha_aina != '' && $msamaha_aina != null){
		$filter2 = " ms.aina_ya_msamaha = '$msamaha_aina' and ";
	}else{
		$filter2 = '';
	}*/

	if($jinsi != '' && $jinsi != null){
		$filter .= " pr.Gender = '$jinsi' and ";
	}

	if($employee_ID != '' && $employee_ID != null){
		$filter .= "ci.Employee_ID = '$employee_ID' and ";
	}

	if($Patient_Name != null && $Patient_Name != ''){
		$filter .= " pr.Patient_Name like '%$Patient_Name%' and ";
	}

	if($Patient_Number != null && $Patient_Number != ''){
		$filter .= " pr.Registration_ID = '$Patient_Number' and ";
	}

	if($Patient_Phone != null && $Patient_Phone != ''){
		$filter .= " pr.Phone_Number = '$Patient_Phone' and ";
	}

	$Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age = '';
	}

    $htm = '<table width ="100%" height="30px">
			<tr><td><img src="./branchBanner/branchBanner.png" width=100%></td></tr>
			<tr><td style="text-align: left;"><b><span style="font-size: x-small;">WAGONJWA WA MSAMAHA FROM '.date('j F,Y H:i:s', strtotime($Date_From)).' TO '.date('j F,Y H:i:s', strtotime($Date_To)).'</span></b></td></tr>
			<tr>
				<td style="text-align: left;">
					<b><span style="font-size: x-small;">
					MSAMAHA TYPE: '.$Msamaha_Title.',&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMPLOYEE: 
					'.$employee_name.',&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GENDER: '.$gender_name.'</span></b>
					</td></tr>
        </table><br/>';
    $htm .= '<table width =100% border="1" style="border-collapse: collapse;">';
    $Title = '<thead><tr>
		        <td width="3%"><b><span style="font-size: x-small;">SN</span></b></td>
		        <td><b><span style="font-size: x-small;">Date</span></b></td>
		        <td><b><span style="font-size: x-small;">Jina la mgojwa</span></b></td>
		        <td><b><span style="font-size: x-small;">namba ya mgonjwa</b></td</span>>        
		        <td><b><span style="font-size: x-small;">Umri</span></b></td>
		        <td><b><span style="font-size: x-small;">Jinsia</span></b></td> 
		        <td><b><span style="font-size: x-small;">Nambari ya simu</span></b></td>
		        <td><b><span style="font-size: x-small;">Aina ya Msamaha</span></b></td>
		        <td><b><span style="font-size: x-small;">Aliyependekeza Msamaha</span></b></td>
		        <td><b><span style="font-size: x-small;">Jina la Balozi</span></b></td>
		        <td><b><span style="font-size: x-small;">Region</span></b></td>
		        <td><b><span style="font-size: x-small;">District</span></b></td>
		        <td><b><span style="font-size: x-small;">Ward</span></b></td>
		        <td><b><span style="font-size: x-small;">Kazi ya mke/mlezi</span></b></td>
		        <td><b><span style="font-size: x-small;">Prepared By</span></b></td>
		   	</tr></thead>';
	//get list of patients
	$select = mysqli_query($conn,"select ci.msamaha_Items, ci.Anayependekeza_Msamaha,
							Check_In_Date_And_Time, Patient_Name,
 							Date_Of_Birth, Gender, Occupation,
 							pr.Phone_Number, pr.Region, pr.District, 
 							pr.Ward, emp.Employee_Name, emp.Employee_ID, pr.Registration_ID from

 							tbl_msamaha ms,tbl_patient_registration pr, tbl_employee emp, tbl_sponsor sp, tbl_check_in ci where
 							$filter
 							pr.Sponsor_ID = sp.Sponsor_ID and
 							ci.Registration_ID = pr.Registration_ID and
                                                        ms.Registration_ID = pr.Registration_ID and
 							ci.Employee_ID = emp.Employee_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	$temp = 0;
	if($num > 0){
		$htm .= $Title;
		while ($row = mysqli_fetch_array($select)) {
			$msamaha_Items = $row['msamaha_Items'];
			$Anayependekeza_Msamaha = $row['Anayependekeza_Msamaha'];

	        $date1 = new DateTime($Today);
	        $date2 = new DateTime($row['Date_Of_Birth']);
	        $diff = $date1->diff($date2);
	        $age = $diff->y . " Years";
	        //$age .= $diff->m . " Months, ";
	        //$age .= $diff->d . " Days";

	        //get patient other details
	        $Registration_ID = $row['Registration_ID'];
	        $query = mysqli_query($conn,"select msamaha_ID, aina_ya_msamaha, anayependekeza,
		 							jina_la_balozi, kiwango_cha_elimu, kazi_mke, 
		 							idadi_mahudhurio, emp.Employee_Name from
									tbl_msamaha ms, tbl_employee emp where $filter2
									emp.Employee_ID = ms.anayependekeza and
		 							Registration_ID = '$Registration_ID'");
	        $no = mysqli_num_rows($query);
	        if($no > 0){
			    $result=  mysqli_fetch_assoc($query);
			    $anayependekeza  = $result['anayependekeza'];
				$aina_ya_msamaha = $result['aina_ya_msamaha'];
				$anayependekeza = $result['anayependekeza'];
				$jina_la_balozi = $result['jina_la_balozi'];
				$kiwango_cha_elimu = $result['kiwango_cha_elimu'];
				$kazi_mke = $result['kazi_mke'];
				$idadi_mahudhurio = $result['idadi_mahudhurio'];
				$Employee_Name = $result['Employee_Name'];
			}else{
				$aina_ya_msamaha = '';
				$anayependekeza = '';
				$jina_la_balozi = '';
				$kiwango_cha_elimu = '';
				$kazi_mke = '';
				$idadi_mahudhurio = '';
				$Employee_Name = '';
			}

			//get anayependekeza and msamaha type
			if($msamaha_Items != null && $Anayependekeza_Msamaha != null){
				$mpendekezi = mysqli_query($conn,"select (select Employee_Name from tbl_employee where Employee_ID = '$Anayependekeza_Msamaha') as Employee_Name,
											msamaha_aina from tbl_msamaha_items where msamaha_Items = '$msamaha_Items'") or die(mysqli_error($conn));
				$nm = mysqli_num_rows($mpendekezi);
				if($nm > 0){
					$result = mysqli_fetch_assoc($mpendekezi);
					$aliyependekeza = $result['Employee_Name'];
					$aina_ya_msamaha = $result['msamaha_aina'];
				}else{
					$aliyependekeza = '';
					$aina_ya_msamaha = '';
				}
			}else{
				$mpendekezi = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$anayependekeza'") or die(mysqli_error($conn));
				$nm = mysqli_num_rows($mpendekezi);
				if($nm > 0){
					$result = mysqli_fetch_assoc($mpendekezi);
					$aliyependekeza = $result['Employee_Name'];
				}else{
					$aliyependekeza = '';
				}
			}

	        $htm .= '<tr>
				        <td>'.++$temp.'</td>
				        <td>'.$row['Check_In_Date_And_Time'].'</td>
				        <td>'.ucwords(strtolower($row['Patient_Name'])).'</td>
				        <td>'.$row['Registration_ID'].'</td>
				        <td>'.$age.'</td>
				        <td>'.$row['Gender'].'</td>
				        <td>'.$row['Phone_Number'].'</td>
				        <td>'.$aina_ya_msamaha.'</td>
				        <td>'.$aliyependekeza.'</td>
				        <td>'.$jina_la_balozi.'</td>
				        <td>'.$row['Region'].'</td>
				        <td>'.$row['District'].'</td>
				        <td>'.$row['Ward'].'</td>
				        <td>'.$kazi_mke.'</td>
				        <td>'.$row['Employee_Name'].'</td>
					</tr>';
		}
		$htm .= "</table>";
	}else{
		$htm .= "<br/><br/><br/><br/><br/><center><h3><b>NO PATIENTS FOUND</b></h3></center>";
	}
	$htm = mb_convert_encoding($htm, 'UTF-8', 'UTF-8');
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>