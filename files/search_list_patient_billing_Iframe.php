<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    require_once './includes/ehms.function.inc.php';
    $temp = 0;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];
    }else{
        $Patient_Name = '';
    }
    
    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }
    if(isset($_GET['Phone_Number'])){
	$Phone_Number = $_GET['Phone_Number'];
    }else{
	$Phone_Number = '';
    }
    
    $filter='';
    
    if(!empty($Patient_Name)){
        $filter="and  pr.Patient_Name like '%$Patient_Name%'";
    }else  if(!empty($Patient_Number)){
        $filter="and  pr.Registration_ID = '$Patient_Number'";
    }else  if(!empty($Phone_Number)){
        $filter="and  pr.Phone_Number = '$Phone_Number'";
    }
    
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    if(isset($_SESSION['userinfo']['Branch_ID'])){
	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }   
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
     $Yest = new DateTime('yesterday');
        $Yesterday = $Yest->format('Y-m-d') . substr($original_Date, 10);
    
    
    //check system settings
    $get_reception_setting = mysqli_query($conn,"select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($get_reception_setting);
    if($no > 0){
	while($data = mysqli_fetch_array($get_reception_setting)){
	    $Reception_Picking_Items = $data['Reception_Picking_Items'];
	}
    }else{
	$Reception_Picking_Items = 'no';
    }
 
  echo '<center><table width =100%>';
    echo ' <tr id="">    
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="10%"><b>PATIENT NO</b></td>
                    <td width="15%"><b>AGE</b></td>
                    <td width="8%"><b>GENDER</b></td>
                    <td width="13%"><b>SPONSOR</b></td>
                    <td width="13%"><b>PHONE NUMBER</b></td>
                    <td width="14%"><b>MEMBER NUMBER</b></td>
                </tr>';

//    echo "select pr.Patient_Name, pr.Registration_ID, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_ID from 
//                            tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
//                            pr.registration_id = ci.registration_id and
//                            sp.sponsor_id = pr.sponsor_id and
//                            Check_In_Date between '$Yesterday' and '$Today' $filter LIMIT 20";
    
//    $select_Filtered_Patients = mysqli_query($conn,
//            "select * from tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
//		pr.registration_id = ci.registration_id and
//		    sp.sponsor_id = pr.sponsor_id and
//			ci.check_in_status = 'pending' and Check_In_Date = '$Today' and
//			    ci.Branch_ID = '$Folio_Branch_ID' ") or die(mysqli_error($conn));
    
    $select_Filtered_Patients = mysqli_query($conn,"select pr.sponsor_id,pr.Patient_Name, pr.Registration_ID, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, ci.Check_In_ID from 
                            tbl_patient_registration pr , tbl_check_in ci, tbl_sponsor sp where
                            pr.registration_id = ci.registration_id and
                            sp.sponsor_id = pr.sponsor_id and
                            Check_In_Date between '$Yesterday' and '$Today' $filter ORDER BY Check_In_Date DESC LIMIT 20") or die(mysqli_error($conn));
    
    $num = mysqli_num_rows($select_Filtered_Patients);
            while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
               $Registration_ID = $row['Registration_ID'];

                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";

                $select_item = mysqli_query($conn,"select Patient_Payment_Cache_ID,Billing_Type from tbl_patient_payments_cache where Registration_ID = '$Registration_ID' ") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_item);
                if ($num > 0) {
                    echo "<tr><td id=''>" . ++$temp . "</td><td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
                    echo "<td><a href='patientbillingprepared.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td></tr>";
                } else if(strtolower($row['Guarantor_Name'])=="cash" || strtolower(getPaymentMethod($row['sponsor_id'])) == 'cash') {
                    echo "<tr><td id=''>" . ++$temp . "</td><td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
                    echo "<td><a href='revenuecenterpatientbillingreception.php?Registration_ID=" . $row['Registration_ID'] . "&NR=true&Check_In_ID=" . $row['Check_In_ID'] . "&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td></tr>";
                }
            }
        
    
            ?></table></center>

