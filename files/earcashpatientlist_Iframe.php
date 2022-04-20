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
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    ?>
    <script type='text/javascript'>
        function patientnoshow(Patient_Payment_Item_List_ID) {
             if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','patientitemnoshow.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
    
    <script>
	function goToAction(Payment_Item_Cache_List_ID,Payment_Cache_ID,Transaction_Type,Sub_Department_ID,Registration_ID){
	    var choice = document.getElementById('choice_'+Payment_Item_Cache_List_ID).value;
	    if (choice=='Make Payment') {
		window.open('earitems.php?Earitems=EaritemsThisPage&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Transaction_Type='+Transaction_Type+'&Sub_Department_ID='+Sub_Department_ID+'&Registration_ID='+Registration_ID,'_parent');
	    }
	}
    </script>
    <?php
    echo '<center><table width =100%>';
    echo "<tr id='thead'><td><b>PATIENT NAME</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>MEMBER NUMBER</b></td>
                                <td><b>DATE</b></td>
				<td><b>STATUS</b></td>
				<td><center><b>ACTION</b></center></td>
				</tr>";
    $select_Filtered_Patients = mysqli_query($conn,
            "select * from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp,tbl_items i
		      where sp.sponsor_id = pr.sponsor_id and
		      pp.Registration_ID = pr.Registration_ID and
		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and
		      pl.Process_Status = 'not served' and
		      pl.Check_In_Type = 'Procedure' and
		      i.Consultation_Type='Ear' and
		      pr.Patient_Name LIKE '%$Patient_Name%' and
		      pp.Billing_Type = 'Outpatient Cash' and
		      pp.Branch_ID = '$Folio_Branch_ID' GROUP BY pp.Patient_Payment_ID order by pl.Transaction_Date_And_Time") or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
	echo "<td><a href='earclinicalnotes.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$row['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$row['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>PAID</a></td>";
	?>
    <td>
	<center>
	    <input type='button' value='NO SHOW' class='art-button-green'
	       onclick='patientnoshow("<?php echo $row['Patient_Payment_Item_List_ID']; ?>")'>
	</center>
    </td>
	<?php
    }   echo "</tr>";
    
    $qr="SELECT * FROM tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_sponsor sp,tbl_items i
		    WHERE pc.Payment_Cache_ID = il.Payment_Cache_ID
		    AND pr.Registration_ID = pc.Registration_ID
		    AND sp.sponsor_id = pr.sponsor_id
		    AND i.Item_ID=il.Item_ID
		    AND i.Consultation_Type='Ear'
		    AND pr.Patient_Name LIKE '%$Patient_Name%'
		    AND (il.Status = 'active' OR il.Status='approved')
                    AND il.Check_In_Type ='Procedure'
		    AND il.Transaction_Type = 'Cash' GROUP BY pc.Registration_ID order by il.Transaction_Date_And_Time";
		    
    $select_cache_patient = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_cache_patient)){
	echo "<tr><td>".$row['Patient_Name']."</td>";
        echo "<td>".$row['Guarantor_Name']."</td>";
        echo "<td>".$row['Date_Of_Birth']."</td>";
        echo "<td>".$row['Gender']."</td>";
        echo "<td>".$row['Phone_Number']."</td>";
        echo "<td>".$row['Member_Number']."</td>";
        echo "<td>".$row['Transaction_Date_And_Time']."</td>";
	echo "<td>NOT PAID</td>";
	
	    ?>
	    <td>
		<center>
		    <label class='art-button-green'>
			<select style='text-align: center' id='choice_<?php echo $row['Payment_Item_Cache_List_ID']; ?>'>
			    <option>Choose Action</option>
			    <option>No Show</option>
			    <option>Make Payment</option>
			</select>
			<input type='button' value='send' onclick="goToAction('<?php echo $row['Payment_Item_Cache_List_ID']; ?>','<?php echo $row['Payment_Cache_ID']; ?>','<?php echo $row['Transaction_Type']; ?>','<?php echo $row['Sub_Department_ID']; ?>','<?php echo $row['Registration_ID']; ?>')" class='art-button-green'>
		    </label>
		</center>
	    </td>
	    <?php
	
    }
?></table></center>