<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
?>

<style>
        table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        
        }
    tr:hover{
    background-color:#eeeeee;
    cursor:pointer;
    }
 </style> 
 

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    if(isset($_GET['Billing_Type'])){
        $Billing_Type2 = $_GET['Billing_Type'];
    }else{
        $Billing_Type2 = '';
    }

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
?>

<br/><br/>
<center>
    <table width=60% style="background-color: white;">
        <tr>
        	<td width="5%"><b>SPONSOR</b></td>
        	<td width="10%">
        		<select name="Sponsor_ID" id="Sponsor_ID" onchange="Patient_List_Search()">
        			<option selected="selected" value="0">All</option>
<?php
					$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($data = mysqli_fetch_array($select)) {
?>
							<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
<?php
						}
					}
?>
        		</select>
        	</td>
            <td>
                <input type='text' name='Patient_Name' id='Patient_Name' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder='~~~~~ Enter Patient Name ~~~~~'>
            </td>
            <td>
                <input type='text' name='Patient_Number' id='Patient_Number' style="text-align: center;" oninput='Patient_Search()' onkeyup='Patient_Search()' placeholder='~~~~~ Enter Patient Number ~~~~~'>
            </td>
        </tr>
        
    </table>
</center>
<br/>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right"><b>ALL PAYMENTS ~ OUTPATIENT CASH</b></legend>
    <?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='9'><hr></tr>";
        echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
                <td width><b>STATUS</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>MEMBER NUMBER</b></td>
            </tr>';
        echo "<tr><td colspan='9'><hr></tr>";
        
        $select = mysqli_query($conn,"select pc.Payment_Cache_ID, pr.Registration_ID, sp.Guarantor_Name, pr.Member_Number, pr.Gender, pr.Patient_Name, pr.Date_Of_Birth from
        						tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_sponsor sp where
        						pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
        						pc.Registration_ID = pr.Registration_ID and
        						ilc.Transaction_Type = 'Cash' and
        						sp.Sponsor_ID = pr.Sponsor_ID and
								ilc.ePayment_Status = 'pending' and
        						(ilc.Status = 'active' or ilc.Status = 'approved') and
        						pc.Billing_Type = 'Outpatient Cash' group by pr.Registration_ID order by ilc.Payment_Item_Cache_List_ID desc limit 200") or die(mysqli_error($conn));

        while($row = mysqli_fetch_array($select)){        
        
            //GENERATE PATIENT YEARS, MONTHS AND DAYS
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
            
            $select_items = mysqli_query($conn,"select itm.Product_Name, ilc.Quantity, ilc.Edited_Quantity,itm.Seen_On_Allpayments,ilc.Price, ilc.Discount, ilc.Payment_Item_Cache_List_ID, ilc.Check_In_Type, ilc.Payment_Item_Cache_List_ID, ilc.ePayment_Status
                                from tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_items itm where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                ilc.Item_ID = itm.Item_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '".$row['Payment_Cache_ID']."' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending' and itm.Seen_On_Allpayments='yes' order by ilc.Check_In_Type") or die(mysqli_error($conn));
            $num_rows= mysqli_num_rows($select_items);
            if($num_rows>0){
            echo "<tr><td id='thead'>".$temp.".</td><td><b>Not Paid</b></td><td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
            echo "<td><a href='patientbillingallitems.php?Payment_Cache_ID=".$row['Payment_Cache_ID']."&PatientBilling=PatientBillingThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
            echo "</tr>"; 
            $temp++;
            }
        }
        echo "</table>";
    ?>
</fieldset>



<script>
    function Patient_List_Search(){
    	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
    	var Patient_Name = document.getElementById("Patient_Name").value;
    	document.getElementById("Patient_Number").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Payments_All_Items_Search.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
        myObjectSearchPatient.send();   
    }
</script>

<script>
    function Patient_Search(){
    	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
    	var Patient_Number = document.getElementById("Patient_Number").value;
    	document.getElementById("Patient_Name").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Payments_All_Items_Search.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number,true);
        myObjectSearchPatient.send();
    }
</script>

<br/>
<?php
    include("./includes/footer.php");
?>