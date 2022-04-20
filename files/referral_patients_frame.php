<?php
    include("./includes/connection.php");
   
    $filter = "  AND rp.status = 'active' ";


    if(isset($_GET['Patient_Name'])){
    	$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name='';
    }

    if(isset($_GET['Patient_Number'])){
    	$Patient_Number = $_GET['Patient_Number'];
    }  else {
        $Patient_Number ='';
    }
    
    if(isset($_GET['Sponsor'])){
    	$Sponsor = $_GET['Sponsor'];
    }else{
    	$Sponsor = 'All';
    }
     
    if(isset($_GET['status'])){
    	$status = $_GET['status'];
        $filter =" AND  rp.status = '$status'";
    }

    //new added to search referral patients by date range
    if(isset($_GET['Date_From'])){
        $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
    }else{
        $Date_From='';
    }
    if(isset($_GET['Date_To'])){
        $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
    }else{
        $Date_To='';
    }
    if(isset($_GET['Referral_ID'])){
        $Referral_ID=mysqli_real_escape_string($conn,$_GET['Referral_ID']);
    }else{
        $Referral_ID='';
    }
    
    if(!empty($Date_From) && !empty($Date_To)){
        $filter.=" AND rp.trans_date BETWEEN '".$Date_From."' AND '".$Date_To."'";
    } 
  
    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
    if (!empty($Referral_ID) && $Referral_ID != 'All') {
        $filter .="  AND rp.referral_to=$Referral_ID";
    }

    if (!empty($Patient_Name)) {
        $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
    }
   if (!empty($Patient_Number)) {
        $filter ="  AND pr.Registration_ID = '$Patient_Number'";
    }
    
   
    
   	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	//end
    
	$patients_tobeadmitted = "
		SELECT rp.status,referr_id,Date_Of_Birth,rp.Registration_ID,rp.Patient_Payment_Item_List_ID,Patient_Name,Guarantor_Name,Gender,pr.Phone_Number,Employee_Name,ref_hosp_name FROM 
			tbl_referral_patient rp,
                        tbl_referral_hosp rh,
			tbl_patient_registration pr,
			tbl_employee em,
			tbl_sponsor sp
			WHERE 
				pr.Registration_ID = rp.Registration_ID AND
				rp.employee_id = em.Employee_ID AND
				pr.Sponsor_ID = sp.Sponsor_ID AND
                                rp.referral_to = rh.hosp_ID
				$filter order by rp.trans_date DESC LIMIT 100
	";
	$patients_tobeadmitted_qry = mysqli_query($conn,$patients_tobeadmitted) or die(mysqli_error($conn));
	$sn = 1;
	echo "<table width='100%' id='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>DOCTOR</b></th>
                <th><b>REFFERAL TO</b></th>
                <th><b>ACTION</b></th>
             </tr>
         </thead>";
		
	while($toreferral = mysqli_fetch_assoc($patients_tobeadmitted_qry)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($toreferral['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($toreferral['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
		$patient_name = $toreferral['Patient_Name'];
		$sponsor = $toreferral['Guarantor_Name'];
		//$dob = $toreferral['Date_Of_Birth'];
		$gender = $toreferral['Gender'];
		$phone = $toreferral['Phone_Number'];
		$doctor = $toreferral['Employee_Name'];
		$Registration_ID = $toreferral['Registration_ID'];
                $Patient_Payment_Item_List_ID = $toreferral['Patient_Payment_Item_List_ID'];
		$ref_hosp_name = $toreferral['ref_hosp_name'];
                $referr_id = $toreferral['referr_id'];
		$ref_link = "<a class='art-button-green' href='previewreferralreport.php?referr_id=".$referr_id."' target='_blank'>Preview</a>";
		$lab_link = '<a class="art-button-green" href="privewlabresult.php?ppilid='.$Patient_Payment_Item_List_ID.'&regid='.$Registration_ID.'"  target="_blank">Lab Result</a>';
		$rad_link = '<a class="art-button-green" href="privewradresult.php?ppilid='.$Patient_Payment_Item_List_ID.'&regid='.$Registration_ID.'"  target="_blank">Rab Result</a>';
                $process_link = '<button type="button" class="art-button-green" onclick="processpatient('.$referr_id.')"  target="_blank">Process</button>';
                $status= $toreferral['status'];
                $link_style = "style='text-decoration:none;'";
		
		echo "<tr>";
			echo "<td>".$sn."</td>";
			echo "<td>".ucwords(strtolower($patient_name))."</td>";
			echo "<td>".$Registration_ID."</td>";
			echo "<td>".$gender."</td>";
			echo "<td>".$age."</td>";
			echo "<td>".$sponsor."</td>";
			echo "<td>".$phone."</td>";
			echo "<td>".$doctor."</td>";
			echo "<td>".$ref_hosp_name."</td>";
                        
                        echo "<td>";
                        
                        if($status=='active'){
                            echo $process_link;
                        }elseif($status=='served'){
                            echo $ref_link.''.$lab_link.''.$rad_link; 
                        }elseif($status=='removed'){
                            echo ''; 
                        }
                        
                        echo "</td>";
                        
		echo "</tr>";
		$sn++;
	}
	echo "</table>";
	
?>
