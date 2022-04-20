<style>
.dawa:hover{
    background: #dedede;
    cursor: pointer;
}
</style>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Admission_ID'])){
		$Admission_ID = $_GET['Admission_ID'];
	}else{
		$Admission_ID = 0;
	}

	if(isset($_GET['Consultation_ID'])){
		$Consultation_ID = $_GET['Consultation_ID'];
	}else{
		$Consultation_ID = '';
	}
	
	$Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("d F Y", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }

// echo "Admission ID:".$Admission_ID." Reg: ".$Registration_ID." Cons: ".$Consultation_ID;

	if (isset($_GET['Registration_ID'])) {
        $select = mysqli_query($conn,"SELECT Member_Number, Patient_Name, Registration_ID, Gender, Guarantor_Name, Date_Of_Birth
                                from tbl_patient_registration pr, tbl_sponsor sp where
                                pr.Registration_ID = '$Registration_ID' and
                                sp.Sponsor_ID = pr.Sponsor_ID") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Member_Number = $row['Member_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
            }
            //generate patient age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        } else {
            $Member_Number = '';
            $Patient_Name = '';
            $Gender = '';
            $Registration_ID = 0;
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }

    //get admission date
    $select = mysqli_query($conn,"SELECT Admission_Date_Time from tbl_admission where Admision_ID = '$Admission_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Admission_Date_Time = @date("d F Y H:i:s",strtotime($row['Admission_Date_Time']));
        }
    }else{
        $Admission_Date_Time = '0000/00/00 00:00:00';
    }
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="10%" style="text-align: right;">Patient Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Age</td>
            <td><input type="text" readonly="readonly" value="<?php echo $age; ?>"></td>
            <td width="10%" style="text-align: right;">Gender</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Gender); ?>"></td>
            <td width="10%" style="text-align: right;">Patient Number</td>
            <td><input type="text" readonly="readonly" value="<?php echo $Registration_ID; ?>"></td>
        </tr>
        <tr>
            <td style="text-align: right;">Date Of Admission</td>
            <td><input type="text" value="<?php echo $Admission_Date_Time; ?>" readonly="readonly"></td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" readonly="readonly" value="<?php echo strtoupper($Guarantor_Name); ?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Items_Fieldset'>
<?php
$select_day=mysqli_query($conn,"SELECT DATE(me.Time_Given) AS Time_Given FROM tbl_inpatient_medicines_given me, tbl_patient_registration pr WHERE me.Registration_ID=pr.Registration_ID AND me.Registration_ID='$Registration_ID' GROUP BY DATE(me.Time_Given) ORDER BY me.Time_Given ASC");
    while($row_days_one=mysqli_fetch_assoc($select_day)){
        $time_given1=$row_days_one['Time_Given'];
        
        
//   $time_given1=date("l jS \of F Y",strtotime($time_given1)); 
        
   $value = date("l jS \of F Y",strtotime($time_given1)); 
 echo "<h3>$value</h3>";       

echo "<table width='100%' id='nurse_medicine'>";
echo ""
 . "<tr nobr='true' style='font-size: 15px; padding:10px; background: #ccc;'>";
echo "<td widtd='5%'> <b>SN </b></td>";
echo "<td><b> Medicine Name</b> </td>";
echo "<td><b> Dose</b> </td>";
echo "<td><b> Route</b> </td>";
echo "<td><b> Amnt Given </b></td>";
echo "<td widtd='11%'><b> Time Given </b></td>";
echo "<td><b>Nurse Significant Event and Interventions </b></td>";
echo "<td widtd='5%'><b> Discontinued</b></td>";
echo "<td><b> Discontinue Reason </b></td>";
echo "<td><b> Given by </b></td></tr>
<tr><td colspan='10'><hr></td></tr>";


$select_services = "SELECT it.Product_Name, sg.given_time, sg.route_type, sg.Time_Given, sg.Amount_Given, sg.Nurse_Remarks, sg.Discontinue_Status, em.Employee_Name
			FROM 
				tbl_inpatient_medicines_given sg,
				tbl_items it,
				tbl_employee em
					WHERE  DATE(Time_Given)=DATE('$time_given1') and em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND sg.consultation_ID='$Consultation_ID' ORDER BY sg.Time_Given DESC";
            



$select_testing_record = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
$temp =1;
while ($service = mysqli_fetch_assoc($select_testing_record)) {
    $Product_Name = $service['Product_Name'];
    $given_time = $service['given_time'];
    $route_type = $service['route_type'];
    $Time_Given = $service['Time_Given'];
    $Amount_Given = $service['Amount_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $Employee_Name = $service['Employee_Name'];
    echo "<tr style='font-size: 14px;padding: 2px;' class='dawa'>";
    echo "<td id='thead'>" . $temp . "</td>";
    echo "<td>" . $Product_Name . "</td>";
     echo "<td>" . $given_time . "</td>";
    echo "<td>" . $route_type  . "</td>";
    echo "<td>" . $Amount_Given . "</td>";
    echo "<td>" . $Time_Given . "</td>";
    echo "<td>" . $Nurse_Remarks . "</td>";
    echo "<td>" . $Discontinue_Status . "</td>";
    echo "<td>" . $Discontinue_Reason . "</td>";
    echo "<td>" . $Employee_Name . "</td></tr>
    <tr><td colspan='10'><hr></td></tr>";
    
    $temp++;
    echo "</table></center><br/>";
}


    }
?>
</fieldset>