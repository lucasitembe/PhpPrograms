<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    if($_GET['Hospital_Ward_ID']!=''){
	$Hospital_Ward_ID=$_GET['Hospital_Ward_ID'];
    }else{
	$Hospital_Ward_ID='';
    }
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    
   	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
	//end
    ?>
    <script type='text/javascript'>
        function patientnoshow(Patient_Payment_Item_List_ID,Patient_Name) {
	    
	    var Confirm_Noshow=confirm("Are You Sure You Want To No Show "+Patient_Name+"?");
	    if (Confirm_Noshow) {
		if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','patientnoshow.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
	    mm.send();
	    return true;
	    }else{
		return false;
	    }
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
    <?php
    echo '<center><table width =100%>';
    echo "<tr id='thead'>
    <td style='width:5%'><b>SN</b></td>
     <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NO</b></td>
				  <td><b>GENDER</b></td>
                <td><b>AGE</b></td>
                  <td><b>SPONSOR</b></td>
                            <td><b>PHONE NUMBER</b></td>
                            <td><b>WARD</b></td>
                                <td><b>ADMISSION DATE</b></td>
				</tr>";
    if($Patient_Name !=''){
	$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * FROM tbl_patient_registration pr,tbl_admission ad,tbl_sponsor sp,tbl_hospital_ward hw
		    WHERE pr.Registration_ID=ad.Registration_ID
		    AND pr.Sponsor_ID=sp.Sponsor_ID
		    AND ad.Hospital_Ward_ID=hw.Hospital_Ward_ID
		    AND pr.Patient_Name LIKE '%$Patient_Name%'") or die(mysqli_error($conn));	    
    }elseif($Hospital_Ward_ID!=''){
	$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * FROM tbl_patient_registration pr,tbl_admission ad,tbl_sponsor sp,tbl_hospital_ward hw
		    WHERE pr.Registration_ID=ad.Registration_ID
		    and pr.Sponsor_ID=sp.Sponsor_ID
		    AND ad.Hospital_Ward_ID=hw.Hospital_Ward_ID
		    AND ad.Hospital_Ward_ID LIKE '%$Hospital_Ward_ID%'") or die(mysqli_error($conn));
    }elseif($Hospital_Ward_ID!='' && $Patient_Name!=''){
	$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * FROM tbl_patient_registration pr,tbl_admission ad,tbl_sponsor sp,tbl_hospital_ward hw
		    WHERE pr.Registration_ID=ad.Registration_ID
		    and pr.Sponsor_ID=sp.Sponsor_ID
		    AND ad.Hospital_Ward_ID=hw.Hospital_Ward_ID
		    AND ad.Hospital_Ward_ID LIKE '%$Hospital_Ward_ID%'
		    AND pr.Patient_Name LIKE '%$Patient_Name%'") or die(mysqli_error($conn));
    }else{
	$select_Filtered_Patients = mysqli_query($conn,
            "SELECT * FROM tbl_patient_registration pr,tbl_admission ad,tbl_sponsor sp,tbl_hospital_ward hw
		    WHERE pr.Registration_ID=ad.Registration_ID
		    AND pr.Sponsor_ID=sp.Sponsor_ID
		    AND ad.Hospital_Ward_ID=hw.Hospital_Ward_ID") or die(mysqli_error($conn));	
    }
    $sn=1;
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
		
	echo "<tr><td id='thead'>".$sn."</td>";
      echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		 echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		 echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		
        echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
       
        echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Hospital_Ward_Name']."</a></td>";
        
        echo "<td><a href='doctorspageinpatientwork.php?Folio_Number=".$row['Folio_Number']."&Registration_ID=".$row['Registration_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Admission_Date_Time']."</a></td>";
		$sn++;
	?>
	<?php
    }   echo "</tr>";
?></table></center>