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

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $End_Date = $row['today'];
        $Start_Date = date("Y-m-d", strtotime($End_Date)).' 00:00';
        $Today = date("Y-m-d", strtotime($End_Date));
    }

    if(isset($_GET['Billing_Type'])){
        $Billing_Type2 = $_GET['Billing_Type'];
    }else{
        $Billing_Type2 = 'OutpatientCash';
    }

    //generate filter value
    if(isset($_GET['Billing_Type'])){
        if(strtolower($_GET['Billing_Type']) == 'outpatientcash'){
            $B_Type = 'Outpatient Cash';
        }else if(strtolower($_GET['Billing_Type']) == 'inpatientcash'){
            $B_Type = 'Inpatient Cash';
        }else{
            $B_Type = 'Outpatient Cash';
        }
    }else{
        $B_Type = 'Outpatient Cash';
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
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
        if($Billing_Type == 'OutpatientCash'){
            $Page_Title = 'Outpatient Cash';
        }elseif($Billing_Type == 'OutpatientCredit'){
            $Page_Title = 'Outpatient Credit';
        }elseif($Billing_Type == 'InpatientCash'){
            $Page_Title = 'Inpatient Cash';
        }elseif($Billing_Type == 'InpatientCredit'){
            $Page_Title = 'Inpatient Credit';
        }elseif($Billing_Type == 'PatientFromOutside'){
            $Page_Title = 'Patient From Outside';
        }else{
            $Page_Title = '';
        }
    }else{
        $Billing_Type = '';
        $Page_Title = '';
    }
?>
   
   
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENT LIST') {
	    document.location = "laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option> Select List To View</option>
    <option>
	OUTPATIENT CASH
    </option>
    <option>
	OUTPATIENT CREDIT
    </option>
     <option>
	INPATIENT CASH
    </option> 
    <option>
	PATIENT LIST
    </option>
    <option>
	PATIENT FROM OUTSIDE
    </option>
</select>
 removed function
<input type='button' value='VIEW' onclick='gotolink()'>

</label> -->


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<a href="revenuecenterothersUnpaidlist.php" class="art-button-green">REMOVED TESTS</a>
    <a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 



<br/><br/>
<fieldset>
<center>
    <table width="100%">
        <tr>
            <td width="7%" style="text-align: right;"><b>Start Date</b></td>
            <td width="10%">
                <input type="text" name="Date" id="date_From" value="<?php echo $Start_Date; ?>" readonly="readonly" style="text-align: center;">
            </td>
            <td width="7%" style="text-align: right;"><b>End Date</b></td>
            <td width="10%">
                <input type="text" name="Date" id="date_To" value="<?php echo $End_Date; ?>" readonly="readonly" style="text-align: center;">
            </td>
            <td width="7%" style="text-align: right;"><b>Patients Type</b></td>
            <td width="7%">
                <select name="Patient_Type" id="Patient_Type" onchange="Patient_List_Search();" style="padding:4px;font-size: 14px">
                    <option selected="selected" value="Outpatient">Outpatient Cash</option>
                    <option value="Inpatient" <?php if(strtolower($B_Type) == 'inpatient cash'){ echo "selected='selected'"; } ?>>Inpatient Cash</option>
                </select>
            </td>
            <td width="20%">
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" onkeypress='Patient_List_Search()' oninput='Patient_List_Search()' placeholder='~~~ ~~~ Enter Patient Name ~~~ ~~~'>
            </td>
            <td width="20%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" onkeypress="Patient_List_Search_Via_Number()" oninput="Patient_List_Search_Via_Number()" placeholder="~~~ ~~~ Enter Patient Number ~~~ ~~~" style="text-align: center;" >
            </td>
            <td width="7%">
                <input type="button" name="Filter" id="Filter" class="art-button-green" value="FILTER" onclick="Patient_List_Search()">
            </td>
        </tr>        
    </table>
</center>
</fieldset>
<br/>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right">
        <b>
            <?php 
                if(strtolower($B_Type) == 'inpatient cash'){
                    echo strtoupper('Other Payments ~ inpatient cash'); 
                }else{
                    echo strtoupper('Other Payments ~ outpatient cash'); 
                }
            ?> 
        </b>
    </legend>
<?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='9'><hr></tr>";
        echo '<tr id="thead" style="width:5%;"><td><b>SN</b></td>
            <td width><b>STATUS</b></td>
            <td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>SUB DEPARTMENT</b></td>
            <td><b>MEMBER NUMBER</b></td>
        </tr>';
//        echo "<tr><td colspan='9'><hr></tr>";
//        $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID,
//            preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
//            preg.Member_Number, ilc.Transaction_Type, ilc.status,ilc.Sub_Department_ID from
//            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg where
//            pc.payment_cache_id = ilc.payment_cache_id and
//            preg.registration_id = pc.registration_id and
//            (ilc.status = 'active' OR ilc.status = 'approved') and
//             
//            ilc.Check_In_Type = 'Others' and
//            ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
//            (pc.billing_type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') and
//            ilc.Transaction_Type = 'Cash'
//            group by pc.payment_cache_id,ilc.sub_department_id order by pc.payment_cache_id desc limit 100";
        
//        $Transaction_Type="";
//        $status="";
//        $Sub_Department_ID=0;
     
        $sql_new=mysqli_query($conn,"SELECT preg.Member_Number,preg.Patient_Name,preg.Date_Of_Birth,preg.Gender,preg.Phone_Number,pc.Registration_ID,pc.Transaction_Status,pc.Payment_Cache_ID,pc.Sponsor_Name FROM tbl_payment_cache pc,tbl_patient_registration preg "
                . "WHERE preg.Registration_ID = pc.Registration_ID AND (pc.billing_type = 'Outpatient Cash' or pc.Billing_Type = 'Outpatient Credit') group by pc.payment_cache_id order by pc.payment_cache_id desc limit 100");
        
              while($row_one = mysqli_fetch_assoc($sql_new)){
                               $Member_Number =$row_one['Member_Number'];
                               $Patient_Name=$row_one['Patient_Name'];
                               $Date_Of_Birth =$row_one['Date_Of_Birth'];
                               $Gender =$row_one['Gender'];
                               $Phone_Number =$row_one['Phone_Number'];
                               $Registration_ID =$row_one['Registration_ID'];
                               $Transaction_Status =$row_one['Transaction_Status'];
                               $Payment_Cache_ID =$row_one['Payment_Cache_ID'];
                               $Sponsor_Name =$row_one['Sponsor_Name'];
      
                               
                 $sql_new2=mysqli_query($conn,"SELECT Transaction_Type,status,Sub_Department_ID FROM tbl_item_list_cache WHERE (status = 'active' OR status = 'approved') AND payment_cache_id='$Payment_Cache_ID' AND Check_In_Type = 'Others' group by sub_department_id");
//                   $value=mysqli_num_rows($sql_new2);
//                   ech $value;
               
                   while($row_two = mysqli_fetch_assoc($sql_new2)){
//                        echo"kjold";
                                         $Transaction_Type=$row_two['Transaction_Type'];
                                         $status=$row_two['status'];
                                        $Sub_Department_ID_new=$row_two['Sub_Department_ID'];
//                                       echo"kjold";
                                         
                    $sub_department_name =mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'"))['Sub_Department_Name'];
                     
                
        if(strtolower($status) == 'approved'){
            $change_b_color="style='background:green;color:#FFFFFF'";
            $change_color="color:#FFFFFF";
        }else{
            $change_color="";
            $change_b_color="";
        }
        echo "<tr $change_b_color><td id='thead' style='width:5%;$change_color' >".$temp."</td>";
        if(strtolower($status) == 'active'){
            echo "<td><b>Not Paid</b></td>";
        }else if(strtolower($status) == 'approved'){
            echo "<td><b>Approved</b></td>";
        }else{
            echo "<td> <b>Not Approved</b></td>";  
        } 
    
        //GENERATE PATIENT YEARS, MONTHS AND DAYS
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";       
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
    
        
        echo $Sub_Department_ID_new;
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".ucwords(strtolower($Patient_Name))."</a></td>";   
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Registration_ID."</a></td>"; 
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Sponsor_Name."</a></td>";        
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$age."</a></td>";        
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Gender."</a></td>";      
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Sub_Department_Name."</a></td>";     
        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$Registration_ID."&Transaction_Type=".$Transaction_Type."&Payment_Cache_ID=".$Payment_Cache_ID."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID_new."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Member_Number."</a></td>";
        echo "</tr>"; 
        $temp++;
   
                       
                   }
                              
                  
              }

        //echo $qr;
//
//        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
//        while($row = mysqli_fetch_array($select_Filtered_Patients)){
//        $Sub_Department_ID = $row['Sub_Department_ID'];
//        $sql_select_sub_department_name_result=mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
//        
//        if(mysqli_num_rows($sql_select_sub_department_name_result)){
//           $Sub_Department_Name=mysqli_fetch_assoc()['Sub_Department_Name']; 
//        }else{
//           $Sub_Department_Name=""; 
//        }
//        
//        if(strtolower($row['status']) == 'approved'){
//            $change_b_color="style='background:green;color:#FFFFFF'";
//            $change_color="color:#FFFFFF";
//        }else{
//            $change_color="";
//            $change_b_color="";
//        }
//        echo "<tr $change_b_color><td id='thead' style='width:5%;$change_color' >".$temp."</td>";
//        if(strtolower($row['status']) == 'active'){
//            echo "<td><b>Not Paid</b></td>";
//        }else if(strtolower($row['status']) == 'approved'){
//            echo "<td><b>Approved</b></td>";
//        }else{
//            echo "<td> <b>Not Approved</b></td>";  
//        } 
//    
//        //GENERATE PATIENT YEARS, MONTHS AND DAYS
//        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
//        $date1 = new DateTime($Today);
//        $date2 = new DateTime($row['Date_Of_Birth']);
//        $diff = $date1 -> diff($date2);
//        $age = $diff->y." Years, ";
//        $age .= $diff->m." Months, ";
//        $age .= $diff->d." Days";
//    
//    
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";   
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$row['Registration_ID']."</a></td>"; 
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$row['Sponsor_Name']."</a></td>";        
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$age."</a></td>";        
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$row['Gender']."</a></td>";      
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$Sub_Department_Name."</a></td>";     
//        echo "<td><a href='patientbillingothers.php?section=Others&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&LaboratoryWorks=LaboratoryWorksThisPage' target='_parent' style='text-decoration: none;$change_color'>".$row['Member_Number']."</a></td>";
//        echo "</tr>"; 
//        $temp++;
//    }
echo "</table>";
?>
</fieldset>


<!--<fieldset>  
            <legend align='right'><b><?php echo strtoupper('Laboratory Payments ~ '.$Page_Title); ?> </b></legend>
        <center>
            <table width=100% border=1>
              <tr>
                <td id='Search_Iframe'>
                    <iframe width='100%' height=350px src='Revenue_Center_Others_List_Iframe.php?Billing_Type=<?php echo $Page_Title; ?>'></iframe>
                </td>
              </tr>
            </table>
        </center>
</fieldset>-->
<br/>
<script>
    function Patient_List_Search(){
       
        var Patient_Name = document.getElementById("Search_Patient").value;
        document.getElementById("Patient_Number").value = '';

        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;

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
        
        myObjectSearchPatient.open('GET','Revenue_Center_Others_List_Iframe.php?Patient_Name='+Patient_Name+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Patient_List_Search_Via_Number(){
        var Patient_Number = document.getElementById("Patient_Number").value;
        document.getElementById("Search_Patient").value = '';
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;

        if(window.XMLHttpRequest){
            myObjectSearchP = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchP = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchP.overrideMimeType('text/xml');
        }
        myObjectSearchP.onreadystatechange = function (){
            data28 = myObjectSearchP.responseText;
            if (myObjectSearchP.readyState == 4) {
                console.log(data28);
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchP.open('GET','Revenue_Center_Others_List_Iframe.php?Patient_Number='+Patient_Number+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObjectSearchP.send();
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
   <script>
                $(document).ready(function(){
//                Patient_List_Search();
        });
    </script>
<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:  'now'
});
$('#date_From').datetimepicker({value:'',step:30});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->
<?php
    include("./includes/footer.php");
?>