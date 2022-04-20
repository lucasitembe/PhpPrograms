<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    } else {
        $Registration_ID = '';
    }
    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
?>
<a href='prepaidpatientslist.php?Section=Visitor&PrePaidPatientsList=PrePaidPatientsListThisPage' class='art-button-green'>PRE / POST PAID PATIENTS</a>
<a href='registerpatient.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>ADD NEW PATIENT</a>
<a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>BACK</a>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>

<?php
    $select_Patient = mysqli_query($conn,"select Old_Registration_Number,Title,Patient_Name, Date_Of_Birth, Gender, pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                                    Member_Number,Member_Card_Expire_Date, pr.Phone_Number,Email_Address,Occupation, Employee_Vote_Number,Emergence_Contact_Name,
                                    Emergence_Contact_Number,Company,Registration_ID Employee_ID,Registration_Date_And_Time,Guarantor_Name, Registration_ID,sp.Sponsor_ID
                                    from tbl_patient_registration pr, tbl_sponsor sp where pr.Sponsor_ID = sp.Sponsor_ID and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) { //
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Region = $row['Region'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Tribe = $row['Tribe'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        }
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Tribe = '';
        $Sponsor_ID = '';
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
        $age = '';
    }
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }

</style> 

<br>       
<fieldset style="margin-top:16px">  
    <legend align="left"><b>PRE / POST PAID NEW VISITOR PAGE</b></legend>
    <center>
        <table width=100%>
            <tr> 
                <td>
                    <table width=100%>
                        <tr>
                            <td style='text-align: right'>Patient Name</td>
                            <td><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo $Patient_Name; ?>'></td>
                            <td style='text-align: right'>Visit Date</td>
                            <td><input type='text' name='Visit_Date' readonly='readonly' id='dateee' value='<?php echo $Today; ?>'></td> 
                        </tr> 
                        <tr>
                            <td style='text-align: right'>Gender</td>
                            <td><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'></td>
                            <td style='text-align: right'>Occupation</td>
                            <td><input type='text' name='Occupation' id='Occupation' disabled='disabled' value='<?php echo $Occupation; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Age</td>
                            <td><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo $age; ?>'></td>
                            <td style='text-align: right'>Telephone</td>
                            <td><input type='text' name='Phone_Number' disabled='disabled' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                        </tr>
                        <tr>
                            <td style='text-align: right'>Patient Number</td>
                            <td><input type='text' name='Patient_Number' disabled='disabled' id='Patient_Number' value='<?php echo $Registration_ID; ?>'></td>
                            <td style='text-align: right'>Country</td>
                            <td><input type='text' name='Country' disabled='disabled' id='Country' value='<?php echo $Country; ?>'></td>

                        </tr>
                        <tr>
                            <td style='text-align: right'>Patient Old Number</td>
                            <td><input type='text' name='Old_Registration_Number' disabled='disabled' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'></td>
                            <td style='text-align: right'>Region</td>
                            <td><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td> 
                        </tr>
                        <tr>
                            <td style='text-align: right'>Sponsor</td>
                            <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                            <td style='text-align: right'>District</td>
                            <td><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td> 
                        </tr>
                        <tr>
                            <td style='text-align: right'>Membership Number</td>
                            <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Member_Number; ?>'>
                            <td style='text-align: right'>Ward</td>
                            <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Ward; ?>'></td> 
                        </tr>
                        <tr>
                            <td style='text-align: right'>Emergency Contact Number</td>
                            <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Emergence_Contact_Number; ?>'></td>
                            <td style='text-align: right'>Tribe</td>
                            <td><input type='text' name='Tribe' id='Tribe' disabled='disabled' value='<?php echo $Tribe; ?>'></td>
                        </tr>
                        <tr>
                            <td style='text-align: right'>Emergency Contact Name</td>
                            <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Emergence_Contact_Name; ?>'></td>
                            <td style='text-align: right'>Type Of Check In</td>
                            <td>
                                <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                                    <option>Afresh</option>
                                </select>
                            </td>                                
                        </tr>
                    </table>
                </td>
                <td>
                    <table width=100%>
                        <tr><td><img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=90% height=90%></td></tr>
                        <tr>
                            <td style='text-align: center;'>
                        <?php
                            $check = mysqli_query($conn,"select Registration_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
                            $no = mysqli_num_rows($check);
                            if($no > 0){
                                echo '<input type="button" value="CHECK IN" class="art-button-green" onclick="Check_In_Patient_Warning()">';
                            }else{
                                echo '<input type="button" value="CHECK IN" class="art-button-green" onclick="Check_In_Patient()">';
                            }
                        ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<div id="Check_In_Warning">
    Process fail!! Selected patient currently has pending pre paid bill<br/><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="CONTINUE WITH CURRENT BILL" onclick="Continue(<?php echo $Registration_ID; ?>)">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Close_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Process_Fail">
    Process fail! Please try again
</div>

<script type="text/javascript">
    function Continue(Registration_ID){
        var Section = '<?php echo $Section; ?>';
        var Section2 = '<?php echo strtolower($Section); ?>';
        if(Section2 == 'reception' || Section2 == 'visitor'){
            document.location = "patientbillingreception.php?Registration_ID="+Registration_ID+"&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
        }else{
            document.location = "postpaidrevenuecenter.php?Registration_ID="+Registration_ID+"&Section="+Section+"&PostPaidRevenueCenter=PostPaidRevenueCenterThisForm";
        }
    }
</script>

<script type="text/javascript">
    function Check_In_Patient_Warning(){
        $("#Check_In_Warning").dialog("open");
    }
</script>

<script type="text/javascript">
    function Check_In_Patient(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Section = '<?php echo strtolower($Section); ?>';

        if (window.XMLHttpRequest) {
            myObjectCheck = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectCheck = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectCheck.overrideMimeType('text/xml');
        }

        myObjectCheck.onreadystatechange = function () {
            dataCheck = myObjectCheck.responseText;
            if (myObjectCheck.readyState == 4) {
                var feedback = dataCheck;
                if(feedback == 'yes'){
                    if(Section == 'revenue'){
                        document.location = "revenuecenterpatientbillingreception.php?Registration_ID="+Registration_ID+"&NR=true&Check_In_ID=&PatientBilling=PatientBillingThisForm";
                    }else{
                        document.location = "patientbillingreception.php?Registration_ID="+Registration_ID+"&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
                    }
                }else{
                    $("#Process_Fail").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........
        myObjectCheck.open('GET', 'Pre_Paid_Check_In_Patient.php?Registration_ID='+Registration_ID, true);
        myObjectCheck.send();
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    $(document).ready(function () {
        $("#Check_In_Warning").dialog({autoOpen: false, width: '45%', height: 150, title: 'eHMS 2.0 ~ INFORMATION!', modal: true});
        $("#Process_Fail").dialog({autoOpen: false, width: '35%', height: 150, title: 'eHMS 2.0 ~ INFORMATION!', modal: true});
    });
</script>

<?php
    include("./includes/footer.php");
?>