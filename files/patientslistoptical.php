<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
     

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $End_Date = $row['today'];
        $Start_Date = date("Y-m-d", strtotime($End_Date)).' 00:00';
        $Today = date("Y-m-d", strtotime($End_Date));
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
<a href="opticaladdnewpatient.php?OpticalAddNewPatient=OpticalAddNewPatientThisPage" class="art-button-green">ADD NEW PATIENT</a>
<a href='./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage' class='art-button-green'>BACK</a>
<br/><br/>
<center>
    <table width="60%">
        <tr>
            <td width="20%">
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" onkeypress='Patient_List_Search()' oninput='Patient_List_Search()' placeholder='~~~ ~~~ Enter Patient Name ~~~ ~~~'>
            </td>
            <td width="20%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" onkeypress="Patient_List_Search_Via_Number()" oninput="Patient_List_Search_Via_Number()" placeholder="~~~ ~~~ Enter Patient Number ~~~ ~~~" style="text-align: center;" >
            </td>
        </tr>        
    </table>
</center>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right"><b>PATIENTS LIST</b></legend>
<?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='9'><hr></tr>";
        echo '<tr id="thead" style="width:5%;">
                <td><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>PATIENT NUMBER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>MEMBER NUMBER</b></td>
            </tr>';
        echo "<tr><td colspan='9'><hr></tr>";
        $select_Filtered_Patients = mysqli_query($conn,"select Patient_Name, Registration_ID, Guarantor_Name, Gender, Member_Number, Date_Of_Birth
                                                    from tbl_patient_registration pr, tbl_sponsor s where
                                                    pr.Sponsor_ID = s.Sponsor_ID order by Registration_ID desc limit 100") or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
        echo "<tr><td id='thead' style='width:5%;' >".$temp."</td>";
    
        //GENERATE PATIENT YEARS, MONTHS AND DAYS
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
    
    
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";   
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>"; 
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";        
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";        
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";    
        echo "<td><a href='opticaltransaction.php?Section=Outside&Registration_ID=".$row['Registration_ID']."&OpticalTransaction=OpticalTransactionThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        echo "</tr>"; 
        $temp++;
    }
    echo "</table>";
?>
</fieldset>
<br/>
<script>
    function Patient_List_Search(){
        var Patient_Name = document.getElementById("Search_Patient").value;
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
        
        myObjectSearchPatient.open('GET','Patient_List_Search_Optical.php?Patient_Name='+Patient_Name,true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Patient_List_Search_Via_Number(){
        var Patient_Number = document.getElementById("Patient_Number").value;
        document.getElementById("Search_Patient").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchP = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchP = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchP.overrideMimeType('text/xml');
        }
        myObjectSearchP.onreadystatechange = function (){
            data28 = myObjectSearchP.responseText;
            if (myObjectSearchP.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
            myObjectSearchP.open('GET','Patient_List_Search_Optical.php?Patient_Number='+Patient_Number,true);
        myObjectSearchP.send();
    }
</script>
<?php
    include("./includes/footer.php");
?>