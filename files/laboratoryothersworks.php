<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
        header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
        @session_start();
        if(!isset($_SESSION['supervisor'])){ 
            header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
        }
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
    }else{
    @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
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

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option> Select List To View</option>
    <option>
        OUTPATIENT CASH
    </option>
    <option>
        INPATIENT CASH
    </option>
    <option>
        PATIENT LIST
    </option>
</select>
</label> 




<?php
    if(isset($_SESSION['userinfo'])){
?>
    <a href='laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } ?>


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    $age ='';
    }
?>
<!-- end of the function -->


 

 



<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
    $Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
               $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
       // if($age == 0){
        
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
        
        /*}
        if($age == 0){
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1 -> diff($date2);
        $age = $diff->d." Days";
        }*/
      
        
        
        
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';            
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
        }
?>



 
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->
<?php
    if(isset($_GET['Patient_Payment_ID']) && isset($_GET['Registration_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    //select folio number and other details to display
    $sql_Select_Current_Patient = mysqli_query($conn,"select * from tbl_patient_payments pp
                                                    where Registration_ID = '$Registration_ID' and
                                                        Patient_Payment_ID = '$Patient_Payment_ID'
                                                            order by pp.Patient_Payment_ID desc limit 1");
        $no = mysqli_num_rows($sql_Select_Current_Patient);
    if($no > 0){
        while($row = mysqli_fetch_array($sql_Select_Current_Patient)){ 
        $Folio_Number = $row['Folio_Number'];
                $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
        $Billing_Type = $row['Billing_Type'];
        }
         
    }else{
        $Folio_Number = '';
            $Payment_Date_And_Time = '';
        $Billing_Type = '';
    }
    }
    else{
    $Folio_Number = '';
        $Payment_Date_And_Time = '';
    $Billing_Type = '';
    }
?>
 
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b>REVENUE CENTER</b></legend>
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right; width: 10%;'>Billing Type</td>
                
                                <td style='width: 15%;'>
                                    <select name='Billing_Type' id='Billing_Type' required='required'>
                                        <option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                </td>
                <td style='text-align: right; width: 15%'>Patient Name</td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                
                                <td style='text-align: right; width: 15%;'>Receipt Number</td>
                                <td width: 15%><input type='text' name='Receipt_Number' id='Receipt_Number' readonly='readonly' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr>
                            <tr>
                 
                
                <td style='text-align: right;'>Occupation</td>
                                <td>
                    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
                </td>
                <td style='text-align: right; width: 11%'>Gender</td>
                                <td width='12%'><input type='text' name='Gender' readonly='readonly' id='Gender' value='<?php echo $Gender; ?>'></td>
                                <td style='text-align: right;' width=14%>Receipt Date & Time</td>
                                <td width='12%'><input type='text' name='Receipt_Date_Time' readonly='readonly' id='Receipt_Date_Time' value='<?php echo $Payment_Date_And_Time; ?>'></td>
                            </tr>
                            <tr> 
                                <td style='text-align: right;'>Patient Age</td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td style='text-align: right; width: 15%;'>Registration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                                <td style='text-align: right;'>Folio Number</td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td style='text-align: right;'>Sponsor Name</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td style='text-align: right;'>Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                                
                <td style='text-align: right;'>Supervised By</td>
                
                <?php
                    if(isset($_SESSION['supervisor'])) {
                    if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
                        if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
                        $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                        }else{
                        $Supervisor = "Unknown Supervisor";
                        }
                    }else{
                        $Supervisor = "Unknown Supervisor";
                    }
                    }else{
                        $Supervisor = "Unknown Supervisor";
                    }
                ?>
                
                
                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td> 
                
                            </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>
<fieldset>
    <table width=100%>
        <tr>
            <td style='text-align: right;'>
        <?php
            if(strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit'){
        ?>
            <a href='individualsummaryreport.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualSummaryReport=IndividualSummaryReportThisForm' class='art-button-green' target='_Blank'>Preview Debit Note</a>
        <?php  
            }else{
        ?>
            <a href='individualsummaryreport.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&IndividualSummaryReport=IndividualSummaryReportThisForm' class='art-button-green' target='_Blank'>Preview Receipt</a>
        <?php
            }
        ?>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset>   
        <center> 
            <table width=100%>         
                <tr>
                    <td colspan=2>
                        <?php
                            //get Check_In_ID from url
                            if(isset($_GET['Check_In_ID'])){
                                $Check_In_ID = $_GET['Check_In_ID'];
                            }else{
                                $Check_In_ID = 0;
                            }
                        
                            echo "<iframe src='Patient_Billing_Review_Iframe.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=".$Patient_Payment_ID."' width='100%' height=240px></iframe>";
                        ?>                                      
                        
                    </td>
                </tr>
            </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>