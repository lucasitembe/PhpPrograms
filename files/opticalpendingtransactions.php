<?php
    $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
       include("./includes/header_general.php"); 
       header("Location:directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm");
    }
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Eye_Works'])){
	if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Start_Date = $Today.' 00:00';
    }
    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
    if(strtolower($Section) == 'home'){
        echo "<a href='./opticalworkspage.php?OpticalWorks=OpticalWorksThisPage' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='./opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage' class='art-button-green'>BACK</a>";
    }
?>
<br/><br/>
<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <table width="100%">
        <tr>
            <td width="7%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" onchange="Update_Patient_List()">
                    <option selected="selected" value="0">All</option>
                <?php
                    //select sponsors
                    $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
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
            <td width="9%" style="text-align: right;">Patient Type</td>
            <td>
                <select name="Patient_Type" id="Patient_Type" onchange="Update_Patient_List()">
                    <option selected="selected">Cash Patients</option>
                    <option>Credit Patients</option>
                </select>
            </td>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder=' ~~~~~ Enter Patient Name ~~~~~' autocomplete='off'>
            </td>
            <td>
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search_Via_Number()' onkeyup='Patient_List_Search_Via_Number()' placeholder=' ~~~~~ Enter Patient Number ~~~~~' autocomplete='off'>
            </td>
            <td width="8%" style="text-align: center;">
            <input type="button" name="Filter" id="Filter" class="art-button-green" value="FILTER" onclick="Filter_Patients()">
            </td>
        </tr>
    </table>
</fieldset>


<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Items_Fieldset'>
    <legend align="right"><b>Optical ~ Pending Cash Transactions</b></legend>
    <table width="100%">
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="12%"><b>SPONSOR NAME</b></td>
            <td width="7%"><b>GENDER</b></td>
            <td width="14%"><b>AGE</b></td>
            <td width="12%"><b>TRANSACTION DATE</b></td>
            <td width="10%"><b>MEMBER NUMBER</b></td>
        </tr>
        <tr><td colspan="9"><hr></td></tr>

    <?php
        //select transactions
        $select = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ic.Sponsor_Name, ic.Sponsor_ID, pr.Member_Number, pr.Phone_Number, 
                                pr.Patient_Name, sp.Guarantor_Name, pr.Gender, pr.Date_Of_Birth, emp.Employee_Name, ic.Employee_ID, ic.consultation_ID, Transaction_Date_Time
                                from tbl_optical_items_list_cache ic, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                                ic.Registration_ID = pr.Registration_ID and
                                sp.Sponsor_ID = ic.Sponsor_ID and
                                emp.Employee_ID = ic.Employee_ID group by ic.Registration_ID, ic.Employee_ID, ic.consultation_ID order by Item_Cache_ID desc limit 100") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            $temp = 0;
            while ($data = mysqli_fetch_array($select)) {
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
        }
    ?>
    </table>
</fieldset>

<script>
    function Patient_List_Search(){
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
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
                document.getElementById('Items_Fieldset').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Pending_Transactions_Optical_List_Iframe.php?Patient_Name='+Patient_Name+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Patient_List_Search_Via_Number(){
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        document.getElementById("Search_Patient").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchP = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchP = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchP.overrideMimeType('text/xml');
        }
        myObjectSearchP.onreadystatechange = function (){
            data29 = myObjectSearchP.responseText;
            if (myObjectSearchP.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data29;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchP.open('GET','Pending_Transactions_Optical_List_Iframe.php?Patient_Number='+Patient_Number+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectSearchP.send();
    }
</script>

<script type="text/javascript">
    function Filter_Patient(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Patient").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchS = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchS = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchS.overrideMimeType('text/xml');
        }
        myObjectSearchS.onreadystatechange = function (){
            data30 = myObjectSearchS.responseText;
            if (myObjectSearchS.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data30;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchS.open('GET','Pending_Transactions_Optical_List_Iframe.php?Sponsor_ID='+Sponsor_ID,true);
        myObjectSearchS.send();
    }
</script>

<script type="text/javascript">
    function Update_Patient_List(){
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Patient").value = '';

        if(Patient_Type == 'Cash Patients'){
            Filter_Patient();
        }else{
            if(window.XMLHttpRequest){
                myObjectUpdate = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdate.overrideMimeType('text/xml');
            }
            myObjectUpdate.onreadystatechange = function (){
                data31 = myObjectUpdate.responseText;
                if (myObjectUpdate.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = data31;
                }
            }; //specify name of function that will handle server response........
            
            myObjectUpdate.open('GET','Optical_Update_Pending_Patient_List.php?Sponsor_ID='+Sponsor_ID,true);
            myObjectUpdate.send();
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>