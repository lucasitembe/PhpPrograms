<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $Controler = 'Normal';

    echo '<legend style="background-color:#006400;color:white" align="right"><b>'.strtoupper('Optical Payments').'</b></legend>';

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
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    
    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	   $age ='';
    }

    //generate filter value
    if(isset($_GET['Patient_Name']) && $_GET['Patient_Name'] != null && $_GET['Patient_Name'] != ''){
        $filter = "pr.patient_name like '%$Patient_Name%' and";
    }else if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
        $filter = "pr.Registration_ID = '$Patient_Number' and";
    }else{
        $filter = "";
    }

    echo '<center><table width =100% border=0>';
    echo "<tr><td colspan='9'><hr></tr>";
    echo '<tr>
                <td width="5%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="10%"><b>PATIENT NUMBER</b></td>
                <td width="12%"><b>SPONSOR NAME</b></td>
                <td width="10%"><b>GENDER</b></td>
                <td width="14%"><b>AGE</b></td>
                <td width="10%"><b>PHONE NUMBER</b></td>
                <td width="10%"><b>MEMBER NUMBER</b></td>
                <td width="10%"><b>PREPARED BY</b></td>
            </tr>';
	echo "<tr><td colspan='9'><hr></tr>";
        if($Sponsor_ID == 0){
            $qr="select pr.Registration_ID, pr.Date_Of_Birth, ic.Sponsor_Name, ic.Sponsor_ID, pr.Member_Number, pr.Phone_Number, ic.Sub_Department_ID,
                            pr.Patient_Name, sp.Guarantor_Name, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, ic.Employee_ID, ic.Consultant_ID
                            from tbl_optical_items_list_cache ic, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                            ic.Registration_ID = pr.Registration_ID and
                            sp.Sponsor_ID = ic.Sponsor_ID and
                            $filter
                            emp.Employee_ID = ic.Employee_ID group by ic.Registration_ID, ic.Employee_ID, ic.consultation_ID order by Item_Cache_ID desc limit 100";
        }else{
            $qr="select pr.Registration_ID, pr.Date_Of_Birth, ic.Sponsor_Name, ic.Sponsor_ID, pr.Member_Number, pr.Phone_Number, ic.Sub_Department_ID,
                            pr.Patient_Name, sp.Guarantor_Name, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, ic.Employee_ID, ic.Consultant_ID
                            from tbl_optical_items_list_cache ic, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                            ic.Registration_ID = pr.Registration_ID and
                            sp.Sponsor_ID = ic.Sponsor_ID and
                            sp.Sponsor_ID = '$Sponsor_ID' and
                            $filter
                            emp.Employee_ID = ic.Employee_ID group by ic.Registration_ID, ic.Employee_ID, ic.consultation_ID order by Item_Cache_ID desc limit 100";
        }
					
    //echo $qr;
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    	echo "<tr><td id='thead' style='width:5%;' >".$temp."</td>";
	
    	//GENERATE PATIENT YEARS, MONTHS AND DAYS
    	$age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years"; 		
    	$date1 = new DateTime($Today);
    	$date2 = new DateTime($row['Date_Of_Birth']);
    	$diff = $date1 -> diff($date2);
    	$age = $diff->y." Years, ";
    	$age .= $diff->m." Months, ";
    	$age .= $diff->d." Days";
?>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Patient_Name']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Registration_ID']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Guarantor_Name']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Gender']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $age; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Phone_Number']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Member_Number']; ?></a></td>
                <td><a href='opticalpayments.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Consultant_ID=<?php echo $row['Consultant_ID']; ?>&OpticalPayments=OpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $row['Employee_Name']; ?></a></td>
            </tr>
<?php
    	$temp++;
    }
?>
</table>
</center>