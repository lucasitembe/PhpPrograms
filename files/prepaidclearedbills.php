<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
            if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    echo "<a href='./patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a>";
?>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <center>
    <table width="70%">
        <tr>
            <td width="30%">
                <input type="text" name="Patient_Name" id="Patient_Name" placeholder="~~~ ~~ Patient Name ~~ ~~~" autocomplete="off" style="text-align: center;" onkeypress="Search_Patient('Patient_Name')" oninput="Search_Patient('Patient_Name')">
            </td>
            <td width="30%">
                <input type="text" name="Patient_Number" id="Patient_Number" placeholder="~~~ ~~ Patient Number ~~ ~~~" autocomplete="off" style="text-align: center;" onkeypress="Search_Patient('Patient_Number')" oninput="Search_Patient('Patient_Number')">
            </td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient()">
                    <option value="0">All</option>
            <?php
                $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor") or die(mysqli_error($conn));
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
        </tr>
    </table>
    </center>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px;" id="Patient_Area">
    <legend align="left"><b>PRE / POST PAID CLEARED BILLS</b></legend>
    <table width = "100%">
<?php
    $Title = '<tr><td colspan="7"><hr></td></tr>
                <tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="14%"><b>PATIENT NUMBER</b></td>
                    <td width="14%"><b>SPONSOR NAME</b></td>
                    <td width="15%"><b>PATIENT AGE</b></td>
                    <td width="9%"><b>GENDER</b></td>
                    <td width="12%"><b>MEMBER NUMBER</b></td>
                </tr>
                <tr><td colspan="7"><hr></td></tr>';

    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Registration_ID, pr.Phone_Number, pr.Member_Number, sp.Guarantor_Name, pd.Prepaid_ID
                            from tbl_patient_registration pr, tbl_sponsor sp, tbl_prepaid_details pd where
                            pd.Registration_ID = pr.Registration_ID and pd.Status = 'cleared' and
                            pr.Sponsor_ID = sp.Sponsor_ID order by Registration_ID Desc limit 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $link = '<a href="clearedbill.php?Registration_ID='.$data['Registration_ID'].'&Prepaid_ID='.$data['Prepaid_ID'].'&PostPaidRevenueCenter=PostPaidRevenueCenterThisForm" style="text-decoration:none;">';
            if($temp%20 == 0){ echo $Title; }

            //Calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($data['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
?>
            <tr id="sss">
                <td><?php echo $link.(++$temp); ?></a></td>
                <td><?php echo $link.ucwords(strtolower($data['Patient_Name'])); ?></td>
                <td><?php echo $link.$data['Registration_ID']; ?></td>
                <td><?php echo $link.$data['Guarantor_Name']; ?></td>
                <td><?php echo $link.$age; ?></td>
                <td><?php echo $link.$data['Gender']; ?></td>
                <td><?php echo $link.$data['Member_Number']; ?></td>
            </tr>
<?php
        }
    }else{
        echo $Title;
    }
?>
    </table>
</fieldset>

<script type="text/javascript">
    function Search_Patient(parameter){
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Patient_Name = document.getElementById("Patient_Name").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Section = '<?php echo $Section; ?>';

        if(parameter == 'Patient_Name'){
            document.getElementById("Patient_Number").value = '';
        }else if(parameter == 'Patient_Number'){
            document.getElementById("Patient_Name").value = '';
            document.getElementById("Sponsor_ID").value = 0;
            Sponsor_ID = 0;
        }

        if (window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function () {
            dataCheck = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataCheck;
            }
        }; //specify name of function that will handle server response........

        if(parameter == 'Patient_Name'){
            myObjectSearch.open('GET', 'Pre_Paid_Cleared_List_Search_Patients.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name+'&Section='+Section, true);            
        }else if(parameter == 'Patient_Number'){
            myObjectSearch.open('GET', 'Pre_Paid_Cleared_List_Search_Patients.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number+'&Section='+Section, true);
        }
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function Filter_Patient(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Section = '<?php echo $Section; ?>';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Patient_Name = document.getElementById("Patient_Name").value;

        if (window.XMLHttpRequest) {
            myObjectPr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPr = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPr.overrideMimeType('text/xml');
        }

        myObjectPr.onreadystatechange = function () {
            dataPr = myObjectPr.responseText;
            if (myObjectPr.readyState == 4) {
                document.getElementById("Patient_Area").innerHTML = dataPr;
            }
        }; //specify name of function that will handle server response........

        myObjectPr.open('GET', 'Pre_Paid_Cleared_List_Search_Patients.php?Sponsor_ID='+Sponsor_ID+'&Section='+Section+'&Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name, true);            
        myObjectPr.send();
    }
</script>
<?php
    include("./includes/footer.php");
?>