<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 0;
    $Controler = 'Normal';

    echo '<legend align="right"><b>Optical ~ Pending Cash Transactions</b></legend>';

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
    echo "<tr><td colspan='8'><hr></tr>";
    echo '<tr>
                <td width="5%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="10%"><b>PATIENT NUMBER</b></td>
                <td width="12%"><b>SPONSOR NAME</b></td>
                <td width="7%"><b>GENDER</b></td>
                <td width="14%"><b>AGE</b></td>
                <td width="12%"><b>TRANSACTION DATE</b></td>
                <td width="10%"><b>MEMBER NUMBER</b></td>
            </tr>';
	echo "<tr><td colspan='8'><hr></tr>";
        if($Sponsor_ID == 0){
            $qr="select pr.Registration_ID, pr.Date_Of_Birth, ic.Sponsor_Name, ic.Sponsor_ID, pr.Member_Number, pr.Phone_Number, ic.Sub_Department_ID,
                            pr.Patient_Name, sp.Guarantor_Name, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, ic.Employee_ID, ic.Consultant_ID, ic.Transaction_Date_Time, ic.consultation_ID
                            from tbl_optical_items_list_cache ic, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                            ic.Registration_ID = pr.Registration_ID and
                            sp.Sponsor_ID = ic.Sponsor_ID and
                            $filter
                            emp.Employee_ID = ic.Employee_ID group by ic.Registration_ID, ic.Employee_ID, ic.consultation_ID order by Item_Cache_ID desc limit 100";
        }else{
            $qr="select pr.Registration_ID, pr.Date_Of_Birth, ic.Sponsor_Name, ic.Sponsor_ID, pr.Member_Number, pr.Phone_Number, ic.Sub_Department_ID,
                            pr.Patient_Name, sp.Guarantor_Name, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, ic.Employee_ID, ic.Consultant_ID, ic.Transaction_Date_Time, ic.consultation_ID
                            from tbl_optical_items_list_cache ic, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                            ic.Registration_ID = pr.Registration_ID and
                            sp.Sponsor_ID = ic.Sponsor_ID and
                            sp.Sponsor_ID = '$Sponsor_ID' and
                            $filter
                            emp.Employee_ID = ic.Employee_ID group by ic.Registration_ID, ic.Employee_ID, ic.consultation_ID order by Item_Cache_ID desc limit 100";
        }

					
    //echo $qr;
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($data = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $data['Sub_Department_ID'];
    	
    	//GENERATE PATIENT YEARS, MONTHS AND DAYS
    	$date1 = new DateTime($Today);
    	$date2 = new DateTime($data['Date_Of_Birth']);
    	$diff = $date1 -> diff($date2);
    	$age = $diff->y." Years, ";
    	$age .= $diff->m." Months, ";
    	$age .= $diff->d." Days";
?>
    <tr>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Patient_Name']; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Registration_ID']; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Guarantor_Name']; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Gender']; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $age; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Transaction_Date_Time']; ?></a></td>
        <td><a href='editopticalpayments.php?Session=Cash&Registration_ID=<?php echo $data['Registration_ID']; ?>&consultation_ID=<?php echo $data['consultation_ID']; ?>&EditOpticalPayments=EditOpticalPaymentsThisPage' style='text-decoration: none;'><?php echo $data['Member_Number']; ?></a></td>
    </tr>
<?php
    }
?>
</table>
</center>