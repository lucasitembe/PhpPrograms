<link rel="stylesheet" href="table.css" media="screen"> 
<link rel='stylesheet' href='fixHeader.css'>
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name = '';
    }
    
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    } //
    if(isset($_GET['Phone_Number'])){
	$Phone_Number = $_GET['Phone_Number'];
    }else{
	$Phone_Number = '';
    }

    if(isset($_GET['src'])){
	 $src = $_GET['src'];
    }else{
	 $src = '';
    }

    if(isset($_GET['Old_Patient_Number'])){
	 $Old_Patient_Number = $_GET['Old_Patient_Number'];
    }else{
	 $Old_Patient_Number = '';
    }
    
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }


    echo '<center><table width =100% class="fixTableHead"><thead>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
	    <td><b>COSTUMER NAME</b></td>
            <td><b>COSTUMER NUMBER</b></td>
		    <td><b>SPONSOR</b></td>
			<td><b>AGE</b></td>
			    <td><b>GENDER</b></td>
				<td><b>PHONE NUMBER</b></td>
				    <td><b>DATE SIGNED CONCERT FORM</b></td></tr></thead>';

	if($src != ''){
       $select_Filtered_Patients = mysqli_query($conn,
       
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID
            and pr.Old_Registration_Number = '$Old_Patient_Number'
            order by Registration_ID Desc") or die(mysqli_error($conn));
	}else{		    
    
    if($Patient_Name != '' && $Patient_Name != null && $Patient_Number != '' && $Patient_Number != null && $Phone_Number != '' && $Phone_Number != null){
	$select_Filtered_Patients = mysqli_query($conn,
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID
            and pr.Patient_Name like '%$Patient_Name%' and
            pr.Registration_ID = '$Patient_Number' and
            pr.Phone_Number like '%$Phone_Number%'        
            order by Registration_ID Desc limit 200") or die(mysqli_error($conn));

    }elseif($Patient_Name != '' && $Patient_Name != null && ($Patient_Number == '' || $Patient_Number == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
    
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID
            and pr.Patient_Name like '%$Patient_Name%'        
            order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
            
    }elseif($Patient_Number != '' && $Patient_Number != null && ($Patient_Name == '' || $Patient_Name == null) && ($Phone_Number == '' || $Phone_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
   
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID
            and (pr.Registration_ID = '$Patient_Number' or pr.Old_Registration_Number = '$Patient_Number') order by pr.Registration_ID asc limit 200") or die(mysqli_error($conn));
    }elseif($Phone_Number != '' && $Phone_Number !=null && ($Patient_Name == '' || $Patient_Name == null) && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
    
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID and
            pr.Phone_Number like '%$Phone_Number%'  order by pr.Registration_ID asc limit 200") or die(mysqli_error($conn));
    }elseif($Phone_Number != '' && $Phone_Number !=null && ($Patient_Name == '' || $Patient_Name == null) && ($Patient_Number == '' || $Patient_Number == null)){
	$select_Filtered_Patients = mysqli_query($conn,
    
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID and
            pr.Phone_Number like '%$Phone_Number%'  order by pr.Registration_ID asc limit 200") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
    
            "SELECT pr.Patient_Name,pr.Old_Registration_Number,  pr.Registration_ID, pr.Date_Of_Birth, pr.Gender,  pr.Phone_Number, tcd.date, sp.Guarantor_Name
            from tbl_patient_registration pr, 
            tbl_sponsor sp,
            tbl_consert_forms_details tcd
            where pr.sponsor_id = sp.sponsor_id 
            and pr.Registration_ID=tcd.Registration_ID and
            pr.Patient_Name like '%$Patient_Name%'  order by pr.Registration_ID asc limit 200") or die(mysqli_error($conn));
    }
    }

		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
        echo "<tr><td width ='2%' id='thead'>".$temp."<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&PatientBilling=PatientBillingThisForm' target='_blank' style='text-decoration: none;'>";echo ($row['Old_Registration_Number']==""?$row['Registration_ID']:$row['Old_Registration_Number']);   echo "</a></td>";
            echo "<td>".$row['Guarantor_Name']."</a></td>";
            echo "<td>".$age."</td>";
            echo "<td>".$row['Gender']."</td>";
            echo "<td>".$row['Phone_Number']."</td>";
            echo "<td><a href='preview_done_concertforms.php?Registration_ID=".$row['Registration_ID']."&date_done=".$row['date']."' target='_blank' style='text-decoration: none;'> ".$row['date']."</a></td>";
       }        
?></table></center>

