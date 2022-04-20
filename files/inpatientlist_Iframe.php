<link rel="stylesheet" href="table.css" media="screen">
<?php
    @session_start();
    include("./includes/connection.php");
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
    //GET BRANCH ID
    $Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
       
    //Find the current date to filter check in list
        ?>
    <script type='text/javascript'>
        function patientsignoff(Patient_Payment_Item_List_ID) {
             if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','patientsignoff.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
    <?php
    echo '<center><table width =100% border=0>';
    echo "<tr id='thead'><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td>
				<td><b>DATE</b></td>
				</tr>";
    $qr = "SELECT * FROM tbl_patient_registration pr,tbl_sponsor s,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,
		  tbl_patient_payments pp
		  WHERE pr.Registration_ID = c.Registration_ID AND
		  pr.Sponsor_ID = s.Sponsor_ID AND
		  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  c.Employee_ID =".$_SESSION['userinfo']['Employee_ID']." AND
		  c.Process_Status = 'served' AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND
		  ppl.Process_Status = 'served' AND
		  pp.Branch_ID = ".$_SESSION['userinfo']['Branch_ID']." AND
		  pr.Patient_Name like '%$Patient_Name%'
                  AND c.Patient_Type='INPATIENT'
		  GROUP BY pp.Registration_ID
		  order by c.Consultation_Date_And_Time";
	
    $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
	$sn=1;	    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        echo "<td><a href='doctorspageinpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
	echo "<td>&nbsp;</td>";
        $sn++;
    }
	echo "</tr></table></center>";
?>