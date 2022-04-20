<?php
    @session_start();
    include("./includes/connection.php");
   // $filter=' AND DATE(pl.Transaction_Date_And_Time) = DATE(NOW()) ';
     $filter=' ';
   // $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())';

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
    $Patient_Old_Number = filter_input(INPUT_GET, 'Patient_Old_Number');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND pl.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Old_Number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Patient_Old_Number'";
}

//echo $filter;exit;
    
	 $n=1;
	
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
       
    //Find the current date to filter check in list
   
   echo '<center><table width ="100%" id="myPatients">';
    echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
            <th><b>OLD PATIENT NUMBER</b></th>
            <th><b>PATIENT NUMBER</b></th>
                <th><b>SPONSOR</b></th>
                    <th><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                            <th><b>PHONE NUMBER</b></th>
                                <th><b>MEMBER NUMBER</b></th>
                                <th><b>TRANS DATE</b></th>
				<th><b>ACTION</b></th>
				</tr>
                                </thead>";
    
     $hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
     
//     $select_Filtered_Patients = mysqli_query($conn,
//            "select  n.emergency,pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
// from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp
//		      where sp.sponsor_id = pr.sponsor_id and
//		      pp.Registration_ID = pr.Registration_ID and
//		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and
//                      pl.Patient_Direction = 'Direct To Doctor' and pl.Consultant_ID = ".$_SESSION['userinfo']['Employee_ID']." and
//		      pp.Branch_ID = '$Folio_Branch_ID' and  pl.Process_Status = 'not served' $filter GROUP BY pp.Registration_ID order by pl.Transaction_Date_And_Time") or die(mysqli_error($conn));
     
     $select_Filtered_Patients=  mysqli_query($conn,"
                SELECT n.emergency,pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                LEFT JOIN tbl_nurse n ON n.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                
                WHERE pl.Process_Status= 'not served' AND
                      pl.Patient_Direction = 'Direct To Doctor' AND 
                      pl.Consultant_ID = ".$_SESSION['userinfo']['Employee_ID']." AND
                      pp.Branch_ID = '$Folio_Branch_ID'  AND
                      pp.Transaction_status != 'cancelled'
                      $filter
                GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time LIMIT 10
            ") or die(mysqli_error($conn));
     
     
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
		 $style="";
         $startspan="";
	 $endspan="";
  /*       
		echo "
                SELECT n.emergency,pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                LEFT JOIN tbl_nurse n ON n.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                
                WHERE pl.Process_Status= 'not served' AND
                      pl.Patient_Direction = 'Direct To Doctor' AND 
                      pl.Consultant_ID = ".$_SESSION['userinfo']['Employee_ID']." AND
                      pp.Branch_ID = '$Folio_Branch_ID'  AND
                      pp.Transaction_status != 'cancelled'
                      $filter
                GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time LIMIT 10
            ";
			exit();

		 */
	    $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
        /* if($row['emergency'] =='Green'){
           $style="style='background-color:#ddfdd'";
            $startspan="<span style='color:green'>";
	    $endspan="</span>";
        }else if($row['emergency'] =='Emergency'){
			 $style="style='background-color:#ddfdd'";
             $startspan="<span style='color:red'>";
	         $endspan="</span>";
		}else if($row['emergency'] =='Yellow'){
			$style="style='background-color:#ddfdd'";
            $startspan="<span style='color:yellow'>";
	        $endspan="</span>";
		} else if($row['emergency'] =='Black') {
			$style="style='background-color:#ddfdd'";
            $startspan="<span style='color:black'>";
	        $endspan="</span>";
		}      */
	
        echo "<tr ><td >$startspan".$n."$endspan</td><td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".ucwords(strtolower($row['Patient_Name']))."$endspan</a></td>";
         echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Old_Registration_Number']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Registration_ID']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Guarantor_Name']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$age."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Gender']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Phone_Number']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Member_Number']."$endspan</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan".$row['Transaction_Date_And_Time']."$endspan</a></td>";
	
        ?>
    
    <td>
         <?php
                   if($hospitalConsultType=='One patient to one doctor'){
                ?>
	<input type='button' value='NO SHOW' class='art-button-green'
	       onclick='patientnoshow("<?php echo $row['Patient_Payment_Item_List_ID']; ?>","<?php echo $row['Patient_Name']?>")'>
          <?php
            }
            ?>
    </td>
	<?php
	$n++;
   }   echo "</tr>";
?></table></center>