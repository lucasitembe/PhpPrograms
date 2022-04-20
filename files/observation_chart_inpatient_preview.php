<style>
.careplan:hover{
    background: #ccc;
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

<fieldset style='overflow-y: scroll; height: 300px; background-color: white; height: 300px;' id='Items_Fieldset'>
<center>
<?php
$Transaction_Items_Qry = "SELECT Registration_ID,consultation_ID,n.employee_ID,date,Blood_Pressure,Pulse_Blood,Temperature,oxygen_saturation,Resp_Bpressure,Fluid_Drug,Oral_Fluid,Drainage,Gas_Tric,Urine,Employee_Name
     FROM tbl_nursecommunication_observation n JOIN tbl_employee e ON e.Employee_ID=n.employee_ID WHERE  Registration_ID='$Registration_ID' ORDER BY date DESC";
	
$select_testing_record = mysqli_query($conn,$Transaction_Items_Qry) or die(mysqli_error($conn));  
if(mysqli_num_rows($select_testing_record)>0){ 
    $temp = 1;
    echo '<table width =100% border="0" id="nurse_care">     
    <thead>
           <tr style="background: #dedede; font-size: 13px;padding:0 20px;">
                <td style="width:5%;"><b>S/N</b></td>
                <td><b>DATE &amp; TIME</b></td>
                <td><b>TEMP(c)</b></td>
                <td><b>BP 1/2hr (mmhg)</b></td>
                <td><b>Pulse 1/2hr (bpm)</b></td>
                <td><b>Resp(bpm)</b></td>
                <td><b>Intravenous Fluid Drugs</b></td>
                <td><b>Oral Fluid (ccc)</b></td>
                <td><b>Drainage (ccc)</b></td>
                <td><b>Gas Tric</b></td>
                <td><b>Urine</b></td>
                <td><b>Oxygen Saturation</b></td>
                <td><b>Prepared By</b></td>
            </tr>
            <tr>
                <td colspan="13"><hr width="100%"/></td>
            <tr/>
    </thead>';
while($row = mysqli_fetch_array($select_testing_record)){
            echo  "<tr class='careplan'>
            <td id='thead' style='padding: 5px;'>".$temp."</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['Temperature'] . "</td>";
            echo "<td>" . $row['Blood_Pressure'] . "</td>";
            echo "<td>" . $row['Pulse_Blood'] . "</td>";
            echo "<td>" . $row['Resp_Bpressure'] . "</td>";
            echo "<td>" . $row['Fluid_Drug'] . "</td>";
            echo "<td>" . $row['Oral_Fluid'] . "</td>";
            echo "<td>" . $row['Drainage'] . "</td>";
            echo "<td>" . $row['Gas_Tric'] . "</td>";
            echo "<td>" . $row['Urine'] . "</td>";
            echo "<td>" . $row['oxygen_saturation'] . "</td>";
            echo "<td>" . $row['Employee_Name'] . "</td>";
            echo "</tr>
                    <tr>
                <td colspan='13'><hr width='100%'/></td>
            <tr/>";
    $temp++;
 }
}else{
    echo "<span style='text-align: center; font-size: 25px; color: red;'>NO OBSERVATION FILLED FOR THIS PATIENT</span>";
}
  echo  "</table></center>";



?>
    
</fieldset>