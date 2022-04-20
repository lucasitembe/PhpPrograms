<?php
    @session_start();
    include("./includes/connection.php");
  
    $filter = " AND DATE(pc.Payment_Date_And_Time)=DATE(NOW())";

if (isset($_GET['Sponsor'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
}

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND pc.Payment_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pc.Sponsor_ID=$Sponsor";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_Number'";
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
		window.open('dialysisitems.php?Dialysisitems=DialysisitemsThisPage&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID+'&Payment_Cache_ID='+Payment_Cache_ID+'&Transaction_Type='+Transaction_Type+'&Sub_Department_ID='+Sub_Department_ID+'&Registration_ID='+Registration_ID,'_parent');
	    }
	}
    </script>
    <?php
    echo '<center><table width ="100%" style="background-color:white;" id="patients-list">';
     echo "<thead>
         <tr >
        <td><b>SN</b></td>
        <td><b>PATIENT NAME</b></td>
        <td><b>REG NO</b></td>
                <td><b>SPONSOR</b></td>
                    <td><b>DATE OF BIRTH</b></td>
                    <td><b>AGE</b></td>
                        <td><b>GENDER</b></td>
                            <td><b>PHONE NUMBER</b></td>
                                <td><b>DATE</b></td>
                                <td><b>ACTION</b></td>
				</tr></thead>";


    $sn=1;
    $mysql_select_patient=mysqli_query($conn,"SELECT pc.Sponsor_Name,pc.Receipt_Date as Required_Date,pc.Payment_Cache_ID,pc.consultation_id,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,"  . "pr.Phone_Number,pr.Registration_ID FROM tbl_patient_registration AS pr,tbl_payment_cache as pc WHERE pr.Registration_ID =pc.Registration_ID $filter ") or die(mysqli_error($conn));
    
            while($row_value = mysqli_fetch_assoc($mysql_select_patient)){
                                    $Payment_Cache_ID=$row_value["Payment_Cache_ID"];
                                    $Registration_ID=$row_value["Registration_ID"];



    $mysql_patietnt=mysqli_query($conn,"SELECT il.Payment_Item_Cache_List_ID,il.Process_Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time   FROM tbl_item_list_cache as il,tbl_sub_department as sd,tbl_department as d,tbl_items as i WHERE sd.Sub_Department_ID =il.Sub_Department_ID AND d.Department_ID=sd.Department_ID AND il.Item_ID = i.Item_ID AND (il.Check_In_Type ='Dialysis' OR d.Department_Location='Dialysis') AND (il.Status='active' OR il.Status='paid') AND il.Dialysis_Status = 0 AND removing_status='No' AND    il.Payment_Cache_ID='$Payment_Cache_ID' ORDER BY Transaction_Date_And_Time ASC LIMIT 20 ") or die(mysqli_error($conn));    
              
          
     
    while($row = mysqli_fetch_assoc($mysql_patietnt)){
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row_value['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
        
        echo "<tr><td>".$sn++."</td> <td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Patient_Name']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Registration_ID']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Sponsor_Name']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Date_Of_Birth']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Gender']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row_value['Phone_Number']."</a></td>";
       // echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row['registration_number']."&Patient_Payment_ID=".$row['payment_id']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row_value['Registration_ID']."&Patient_Payment_ID=".$row_value['Payment_Cache_ID']."&consultation_id=".$row_value['consultation_id']."&Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
	//echo "<td><a href='dialysisclinicalnotes_unconsulted.php?Registration_ID=".$row['registration_number']."&Patient_Payment_ID=".$row['payment_id']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>Remove</a></td>";
	echo "<td><input type='button' class='art-button removeItem' onclick='here(this.id)' id='".$row['Payment_Item_Cache_List_ID']."' value='Remove'></td>";
        ?>
<!--    <td>
	<center>
	    <input type='button' value='NO SHOW' class='art-button-green'
	       onclick='patientnoshow("<?php //echo $row['Patient_Payment_Item_List_ID']; ?>")'>
	</center>
    </td>-->
	<?php
    }
                
            }
            

  

    echo "</tr>";
?></table></center>

<script>
function here(id){ 
 if(confirm('Are you sure you want to remove this patient from list?')){
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
       location.reload();
    }
  };
  xhttp.open("GET", "requests/removeItemfromDialysisList.php?remove&Payment_Item_Cache_List_ID="+id, true);
  xhttp.send();  
     
 }

}
</script>
