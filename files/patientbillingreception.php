<?php
include("./includes/header.php");
include("./includes/connection.php");
require_once  './includes/ehms.function.inc.php';

$temp = 1;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['supervisor'])) {
                header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
                //header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

///////////////////////check for system configuration//////////////////

$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error());

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
///////////////////////////////////////////////////////////////////////////////////////
?>
<style>
    table,tr,td{
        /*border-collapse:collapse !important;*/
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='searchvisitorsoutpatientlist.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>


<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->

<!--Getting employee name -->
<?php
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
};
//$sql_select_make_payment_configuration="SELECT 	configname FROM tbl_config WHERE configvalue='show' AND configname='showMakePaymentButton'";
//$sql_select_make_payment_configuration_result=mysqli_query($conn,$sql_select_make_payment_configuration) or die(mysqli_error());
//if(mysqli_num_rows($sql_select_make_payment_configuration_result)>0){
//    //show button
//}else{tyle='display:none'";   
//}

?>

<?php
//    select patient information

    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,sp.Exemption,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID, Free_Consultation_Sponsor
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error());
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Exemption = $row['Exemption'];
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
            $Free_Consultation_Sponsor = $row['Free_Consultation_Sponsor'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
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
        $Exemption='no';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Free_Consultation_Sponsor = 'no';
    }
?>

<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID){
    document.getElementById("Search_Value").value = '';
            document.getElementById("Price").value = '';
            document.getElementById("Item_Name").value = '';
            document.getElementById("Quantity").value = '';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
            
            if (window.XMLHttpRequest) {
    myObject = new XMLHttpRequest();
    } else if (window.ActiveXObject){
    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
    }
    //alert(data);

    myObject.onreadystatechange = function (){
    data = myObject.responseText;
            if (myObject.readyState == 4) {
    //document.getElementById('Approval').readonly = 'readonly';
    document.getElementById('Items_Fieldset').innerHTML = data;
    }
    }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name+'&Sponsor_ID='+Sponsor_ID, true);
            myObject.send();
    }
</script>

<script type='text/javascript'>
    function getItemListType(){
    var Item_Category_Name = document.getElementById("Item_Category").value;
            if (window.XMLHttpRequest) {
    myObject = new XMLHttpRequest();
    } else if (window.ActiveXObject){
    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
    }

    alert(Item_Category_Name);
            //document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;

            myObject.onreadystatechange = function (){
            data = myObject.responseText;
                    if (myObject.readyState == 4) {
            document.getElementById('Approval').disabled = 'disabled';
                    document.getElementById('Approval_Comment').innerHTML = data;
            }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Approval_Bill.php?Item_Category_Name=' + Item_Category_Name, true);
            myObject.send();
    }
</script>
<!-- end of filtering-->




<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
            $("#Consultant").select2().select2("val", '');
            $("#Consultant").css('width', '100%');
            var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
            if (window.XMLHttpRequest) {
    mm = new XMLHttpRequest();
    }
    else if (window.ActiveXObject){
    mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
    }

    if (document.getElementById('direction').value == 'Direct To Doctor Via Nurse Station' || document.getElementById('direction').value == 'Direct To Doctor') {
    document.getElementById('Doctors_List').style.visibility = "";
            mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?Type_Of_Check_In=' + Type_Of_Check_In + '&direction=doctor', true);
            mm.send();
            $(".select_clinic").show();
    }
    else{
        $(".select_clinic").css("display","none");
    mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
            mm.open('GET', 'Get_Guarantor_Name.php?direction=clinic', true);
            mm.send();
            document.getElementById('Doctors_List').style.visibility = "hidden";
    }
    }
    function AJAXP3(){
    var data3 = mm.responseText;
            document.getElementById('Consultant').innerHTML = data3;
    }
</script>
<!-- end of selection-->



<!-- get employee id-->
<?php
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}
?>


<?php
if (isset($_POST['submittedPatientBillingForm'])) {
    //---get supervisor id 
    if (isset($_SESSION['supervisor'])) {
        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
            } else {
                $Supervisor_ID = '';
            }
        } else {
            $Supervisor_ID = '';
        }
    } else {
        $Supervisor_ID = '';
    }
    //end of fetching supervisor id
}
?>
<!-- end of process -->

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    label{
        font-weight: normal;
    }
</style>

<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <!--<br/>-->
    <fieldset>  
        <legend align=right><b>REVENUE CENTER - RECEPTION</b></legend>
        <center> 
            <table width='100%'>
                <tr>
                    <td style='text-align: right; width: 10%;'><b style="color:red">Billing Type</b></td>

                    <td style="width: 15%;" id="Billing_Type_Area">
                        <?php
                            $select_billing_type = mysqli_query($conn,"select Billing_Type from tbl_reception_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error());
                            $num_of_rows = mysqli_num_rows($select_billing_type);
                            if ($num_of_rows > 0) {
                                echo '<select style="width: 100%;" name="Billing_Type" id="Billing_Type" required="required" onchange="Refresh_Transaction_Mode(); Refresh_Remember_Mode();">';
                                while ($row = mysqli_fetch_array($select_billing_type)) {
                                    $Selected_Billing_Type = $row['Billing_Type'];
                                    $B_Type = $row['Billing_Type'];
                                }
                                if ($Selected_Billing_Type == 'Outpatient Cash') {
                                    echo "<option selected='selected'>Outpatient Cash</option>";
                                } else if ($Selected_Billing_Type == 'Outpatient Credit') {
                                    echo "<option selected='selected'>Outpatient Credit</option> ";
                                }
                                ?>

                                </select>			

                        <?php
                            } else {
                                if (strtolower($Guarantor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID))=='cash') {
                                    $B_Type = 'Outpatient Cash';
                        ?>

                                    <select style='width: 100%;'  name='Billing_Type' id='Billing_Type' required='required' onchange='clearFocus(this); Get_Item_Price2(); Refresh_Transaction_Mode(); Refresh_Remember_Mode();' onclick='clearFocus(this)'>
                                        <option selected='selected'>Outpatient Cash</option>
                                        <?php 
                                            if($Exemption=="yes"){
                                                ?>
                                         <?php 
                                    $Given_Username=$_SESSION['userinfo']['Given_Username'];
                                    $sql_check_for_bakup_privilage="SELECT change_bill_type_transaction_type_for_excempted FROM tbl_privileges WHERE change_bill_type_transaction_type_for_excempted='yes' AND Given_Username='$Given_Username'";
                                    $sql_check_for_bakup_privilage_result=mysqli_query($conn, $sql_check_for_bakup_privilage) or die(mysqli_error());
                                     if(mysqli_num_rows($sql_check_for_bakup_privilage_result)>0){
                                    ?>
                                         <option>Outpatient Credit</option>
                                                    <?php
                                     }
                                            }
                                        ?>
                                    </select>

                        <?php   } else { $B_Type = 'Outpatient Credit'; ?>

                                    <select style='width: 100%;'  name='Billing_Type' id='Billing_Type' required='required' onchange='clearFocus(this); Get_Item_Price2(); Refresh_Transaction_Mode(); Refresh_Remember_Mode(); Get_Free_Consult_Items();' onclick='clearFocus(this)'>
                                        <option selected='selected'>Outpatient Credit</option>
                                        <option>Outpatient Cash</option>
                                    </select>
                        <?php   }
                            }
                        ?>
                    </td>
                    <td style='text-align: right; width: 8%'>Patient Name</td><?php $Patient_Name=htmlspecialchars($Patient_Name,ENT_QUOTES)?>
                    <td width='15%'><input type='text' name='Patient_Name' readonly='readonly' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                    <td style='text-align: right; width: 7%;'>Registration#</td>
                    <td width="18%"><input type='text' name='Registration_Number' id='Registration_Number' readonly='readonly' value='<?php echo $Registration_ID; ?>'></td>
                    <td style='text-align: right;'>Receipt#</td>
                    <td><input type='text' name='Receipt_Number' id='Receipt_Number' readonly='readonly'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Type Of Check In</td>
                    <td>  
                        <select  style='width: 100%;'  name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='type_Of_Check_In(this.value)' onclick='clearFocus(this); examType()'>

                            <?php /* if(!isset($_GET['NR'])){ ?>
                              <option selected='selected'><?php echo $Check_In_Type; ?></option>
                              <?php } else{ ?>
                              <option selected='selected'><?php $Check_In_Type; ?></option>
                              <?php } */ ?>

                            <!-- <option>Cecap</option>
                            <option>Dental</option>
                            <option>Dialysis</option> -->
                            <option>Doctor Room</option>
                            <!-- <option>Dressing</option>
                            <option>Ear</option>
                            <option>HIV</option> -->
                            <!--<option>Laboratory</option>-->
                            <!-- <option>Matenity</option>
                            <option>Optical</option>  -->
                            <!--<option>Pharmacy</option>-->
                            <!-- <option>Physiotherapy</option> -->
                            <!--<option>Procedure</option>-->
                            <!--<option>Radiology</option>-->
                            <!-- <option>RCH</option>
                            <option>TB</option> -->
                            <!--<option>Theater</option>-->
                        </select>
                    </td>

                    <td style='text-align: right;'>Sponsor Name</td>
                    <td><input type='text' name='Guarantor_Name' readonly='readonly' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                    <td style='text-align: right; width: 11%'>Gender</td>
                    <td width='12%'><input type='text' name='Gender' readonly='readonly' id='Gender' value='<?php echo $Gender; ?>'></td>
                    <td style='text-align: right;'>Receipt Date & Time</td>
                    <td width='12%'><input type='text' name='Receipt_Date_Time' readonly='readonly' id='Receipt_Date_Time' value='<?php echo ''; ?>'></td>
                </tr>
                <tr>

                    <td style='text-align: right;'>Patient Direction</td>
                    <td>
                    <?php
                        //check if patient direction not default
                        if(isset($_SESSION['systeminfo']['Default_Patient_Direction'])){
                            $Default_Patient_Direction = $_SESSION['systeminfo']['Default_Patient_Direction'];
                        }else{
                            $Default_Patient_Direction = 'none';
                        }
                    ?>
                        <select style='width: 100%;'  id='direction' name='direction' onclick='clearFocus(this)' onchange='clearFocus(this); getDoctor(); Get_Free_Consult_Items();' required='required'>
                            <option selected='selected'></option>
                            <option <?php if(strtolower($Default_Patient_Direction) == 'direct to doctor'){ echo "selected='selected'"; } ?>>Direct To Doctor</option>
                            <option>Direct To Doctor Via Nurse Station</option>
                            <option <?php if(strtolower($Default_Patient_Direction) == 'direct to clinic'){ echo "selected='selected'"; } ?>>Direct To Clinic</option>
                            <option>Direct To Clinic Via Nurse Station</option>
                        </select>
                    </td>


                    <td style='text-align: right;'>Patient Age</td>
                    <td><input type='text' name='Patient_Age' id='Patient_Age'  readonly='readonly' value='<?php echo $age; ?>'></td>
                    <td style='text-align: right;'>Phone Number</td>
                    <td><input type='text' name='Phone_Number' id='Phone_Number' readonly='readonly' value='<?php echo $Phone_Number; ?>'></td>
                    <td style='text-align: right;'>Folio Number</td>
                    <td><input type='text' name='Folio_Number' id='Folio_Number' readonly='readonly'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Consultant</td>
                    <td>
                        <select  style='width: 100%;'  name='Consultant' id='Consultant' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this); Get_Free_Consult_Items()' onclick='clearFocus(this)' required='required'>
                                <option selected='selected'></option>
                            <?php
                                if(strtolower($Default_Patient_Direction) == 'none' || strtolower($Default_Patient_Direction) == 'direct to clinic'){
                            $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            }
                            if(strtolower($Default_Patient_Direction) == 'none' || strtolower($Default_Patient_Direction) == 'direct to doctor'){
                            $Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'";
                            $result = mysqli_query($conn,$Select_Doctors);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option><?php echo ucwords(strtolower($row['Employee_Name'])); ?></option>
                                <?php
                            }
                        }
                            ?>
                        </select>
                        <input type="button" name="Doctors_Queue" id="Doctors_Queue" value="Dq" onclick="open_Dialog()"/>
                        <input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" onclick="Get_Doctor()" style="Visibility: hidden;">
                    </td>
                    <td style='text-align: right;'>Claim Form Number</td>
                    <?php
//check claim form number status
                    $select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor
									where sponsor_id = '$Sponsor_ID'") or die(mysqli_error());
                    $num_rows = mysqli_num_rows($select_claim_status);
                    if ($num_rows > 0) {
                        while ($row = mysqli_fetch_array($select_claim_status)) {
                            $Claim_Number_Status = $row['Claim_Number_Status'];
                        }
                    } else {
                        $Claim_Number_Status = '';
                    }

                    if (strtolower($Claim_Number_Status) == 'mandatory') {
                        //check if there is any record in cache then capture claim form number
                        $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_reception_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error());
                        $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
                        if ($num_of_rows > 0) {
                            while ($row = mysqli_fetch_array($select_Claim_Number_Status)) {
                                $Selected_Claim_Form_Number = $row['Claim_Form_Number'];
                            }
                            echo "<td id='Claim_From_Number_Area'><input type='text' name='Claim_Form_Number' autocomplete='off' id='Claim_Form_Number' readonly='readonly' value='" . $Selected_Claim_Form_Number . "'></td>";
                        } else {
                            echo "<td id='Claim_From_Number_Area'><input type='text' name='Claim_Form_Number' autocomplete='off' id='Claim_Form_Number' required='required' onclick='clearFocus(this)' onchange='clearFocus(this)'></td>";
                        }
                    } elseif (strtolower($Claim_Number_Status) == 'not mandatory') {
                        //check if there is any record in cache then capture claim form number
                        $select_Claim_Number_Status = mysqli_query($conn,"select Claim_Form_Number from tbl_reception_items_list_cache where
										Employee_ID = '$Employee_ID' and
										    Registration_ID = '$Registration_ID' order by Reception_List_Item_ID desc limit 1") or die(mysqli_error());
                        $num_of_rows = mysqli_num_rows($select_Claim_Number_Status);
                        if ($num_of_rows > 0) {
                            while ($row = mysqli_fetch_array($select_Claim_Number_Status)) {
                                $Selected_Claim_Form_Number = $row['Claim_Form_Number'];
                            }
                            echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' autocomplete='off' value='" . $Selected_Claim_Form_Number . "'></td>";
                        } else {
                            echo "<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' autocomplete='off'></td>";
                        }
                    }
                    ?>
                <td style='text-align: right;'>Transaction Mode</td>
                <td>
                    <table width="100%">
                        <tr>
                            <td id="Transaction_Area">
                                <select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
                            <?php
                                $select_Transaction_type = mysqli_query($conn,"select Fast_Track from tbl_reception_items_list_cache alc
                                                                        where alc.Employee_ID = '$Employee_ID' and
                                                                        Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error());
                                $no_of_items = mysqli_num_rows($select_Transaction_type);
                                if($no_of_items > 0){
                                    while($data = mysqli_fetch_array($select_Transaction_type)){
                                        $Fast_Track = $data['Fast_Track'];
                                    }
                                    if($Fast_Track == '1'){
                                     echo "<option selected='selected'>Fast Track Transaction</option>";
                                    }else{
                                     echo "<option selected='selected'>Normal Transaction</option>";                            
                                    }
                                }else{
                            ?>
                                    <option selected="selected">Normal Transaction</option>
                                    <option <?php if(isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction' && $B_Type == 'Outpatient Cash'){ echo "selected='selected'"; } ?>>Fast Track Transaction</option>
                            <?php
                                }
                            ?>
                            </select>
                            </td>
                            <td>
                                <input type="checkbox" id="Remember_Mode" name="Remember_Mode" onclick="Remember_Mode_Function()" <?php if(isset($_SESSION['Transaction_Mode']) && $B_Type == 'Outpatient Cash'){ echo "checked='checked'"; } ?>>
                                <label for="Remember_Mode">Remember</label>
                            </td>
                        </tr>
                    </table>
                </td>

                    <td style='text-align: right;'>Supervised By</td>

                    <?php
                    if (isset($_SESSION['supervisor'])) {
                        if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
                            if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
                                $Supervisor = $_SESSION['supervisor']['Employee_Name'];
                            } else {
                                $Supervisor = "Unknown Supervisor";
                            }
                        } else {
                            $Supervisor = "Unknown Supervisor";
                        }
                    } else {
                        $Supervisor = "Unknown Supervisor";
                    }
                    ?>


                    <td><input type='text' name='Member_Number' id='Member_Number' readonly='readonly' value='<?php echo $Supervisor; ?>'></td>	

                </tr> 
                <tr>
                    <td style="text-align:right" class="select_clinic">
                        Select Clinic
                    </td>
                    <td class="select_clinic">
                        <select  style='width: 100%;'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);update_clinic_id()' onclick='clearFocus(this)' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td style="text-align:right">
                        Select Clinic Location
                    </td>
                    <td>
                        <select  style='width: 100%;height:30%'  name='clinic_location_id' id='clinic_location_id' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select sub.Sub_Department_ID,sub.Sub_Department_Name from tbl_sub_department sub,tbl_department de WHERE sub.Department_ID= de.Department_ID AND Department_Location='Clinic'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Sub_Department_ID']; ?>"><?php echo $row['Sub_Department_Name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="text-align:right">
                        <b style="color:red">Select Department</b>
                    </td>
                    <td>
                        <select  style='width: 100%;'  name='finance_department_id' id='finance_department_id' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);update_clinic_id()' onclick='clearFocus(this)' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $select_department = "select * from tbl_finance_department where enabled_disabled = 'enabled'";
                            $result = mysqli_query($conn,$select_department);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['finance_department_id']; ?>"><?php echo $row['finance_department_name']."--".$row['finance_department_code']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
<td>
                        <input type="checkbox" name="send_to_health" id="send_to_health" style="margin: 5px;" value="Direct" <?php if ($send_to_health == "Direct") { ?> checked <?php } ?>> Send to Health
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>

    <div id="Get_Selected_Item_Warning">
        <center>Process fail. Selected item missing price</center>
    </div>

    <div id="Change_Billing_Type_Alert">
        You are about to create fast track transaction. Billing type will change to <b>Outpatient Cash</b><br/><br/>
        <table width="100%">
            <tr>
                <td style="text-align: right;">
                    <input type="button" value="CONTINUE" onclick="Change_Billing_Type()" class="art-button-green">
                    <input type="button" value="DISCARD" onclick="Close_Change_Billing_Type_Alert()" class="art-button-green">
                </td>
            </tr>
        </table>
    </div>

    <div id="List_OF_Doctors">
        <center>
            <table width="100%">
                <tr>
                    <td>
                        <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
                            <table width="100%">
                                <?php
                                $counter = 0;
                                $get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error());
                                $doctors_num = mysqli_num_rows($get_doctors);
                                if ($doctors_num > 0) {
                                    while ($data = mysqli_fetch_array($get_doctors)) {
                                        ?>
                                        <tr>
                                            <td style='text-align: right;'>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
                                            </td>
                                            <td>
                                                <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </center>
    </div>

<script type="text/javascript">
    function Refresh_Remember_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Billing_Type == 'Outpatient Credit'){
            document.getElementById("Remember_Mode").checked = false;
            Change_Transaction_Mode("Normal Transaction");
        }else{
            if (window.XMLHttpRequest) {
                myObjectRem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRem.overrideMimeType('text/xml');
            }
            myObjectRem.onreadystatechange = function () {
                dataRem = myObjectRem.responseText;
                if (myObjectRem.readyState == 4) {
                    var feedback = dataRem;
                    if(feedback == 'yes'){
                        document.getElementById("Remember_Mode").checked = true;
                        Refresh_Transaction_Mode();
                    }else{
                        document.getElementById("Remember_Mode").checked = false;
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectRem.open('GET', 'Direct_Departmental_Refresh_Remember_Mode.php?Session=Outpatient', true);
            myObjectRem.send();
        }
    }
</script>

<script type="text/javascript">
    function Refresh_Transaction_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        if(Billing_Type == 'Outpatient Cash'){
            if (window.XMLHttpRequest) {
                myObjectRefreshMode = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRefreshMode = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRefreshMode.overrideMimeType('text/xml');
            }
            myObjectRefreshMode.onreadystatechange = function () {
                dataRefresh = myObjectRefreshMode.responseText;
                if (myObjectRefreshMode.readyState == 4) {
                    document.getElementById("Transaction_Area").innerHTML = dataRefresh;
                }
            }; //specify name of function that will handle server response........

            myObjectRefreshMode.open('GET', 'Reception_Refresh_Transaction_Mode.php', true);
            myObjectRefreshMode.send();
        }
    }
</script>

<script type="text/javascript">
$(document).ready(function() {

                                $('#send_to_health').change(function() {
                                    if (this.checked == true) {
                                        send_to_health();
                                    }
                                });
                            });

                            function send_to_health() {

                                var registration_id = <?= $Registration_ID; ?>

                                var Employee_ID = <?= $Employee_ID; ?>

                                var Sponsor_ID = <?= $Sponsor_ID; ?>

                                if ($("#send_to_health").is(":checked")) {

                                    var send_to_health = $("#send_to_health").val();

                                } else {
                                    var send_to_health = '';
                                }

                                $.ajax({
                                    type: "POST",
                                    url: "add_to_health.php",
                                    data: {
                                        registration_id: registration_id,
                                        Employee_ID: Employee_ID,
                                        Sponsor_ID: Sponsor_ID,
                                        send_to_health: send_to_health
                                    },
                                    success: function(data) {
                                        sent_to_health_data = JSON.parse(data);

                                        alert(sent_to_health_data[0]);
                                    }
                                });

                            }
    function Validate_Transaction_Mode(){
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Transaction_Mode = document.getElementById("Transaction_Mode").value;
        if(Transaction_Mode == 'Fast Track Transaction' && Billing_Type == 'Outpatient Credit'){
            document.getElementById("Transaction_Mode").value = 'Normal Transaction';
            $("#Change_Billing_Type_Alert").dialog("open");
        }
        Remember_Mode_Function();
    }
</script>

<script type="text/javascript">
    function Close_Change_Billing_Type_Alert(){
        Change_Transaction_Mode("Normal Transaction");
        $("#Change_Billing_Type_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Change_Transaction_Mode(Transaction_Mode){
        if(Transaction_Mode == 'Normal Transaction'){
            document.getElementById("Transaction_Area").innerHTML = '<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()"><option>Fast Track Transaction</option><option selected="selected">Normal Transaction</option></select>';
        }else{
            document.getElementById("Transaction_Area").innerHTML = '<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()"><option selected="selected">Fast Track Transaction</option><option>Normal Transaction</option></select>';
        }
    }
</script>
<script type="text/javascript">
    function Change_Billing_Type(){
        document.getElementById("Billing_Type_Area").innerHTML = '<select style="width: 100%;" name="Billing_Type" id="Billing_Type" required="required" onchange="Get_Item_Price2(); Refresh_Transaction_Mode(); Refresh_Remember_Mode(); Get_Free_Consult_Items();"><option selected="selected">Outpatient Cash</option><option>Outpatient Credit</option></select>';
        document.getElementById("Transaction_Mode").value = 'Fast Track Transaction';
        $("#Change_Billing_Type_Alert").dialog("close");
    }
</script>

<script type="text/javascript">
    function Remember_Mode_Function(){
        var Controler = 'not checked';
        if(document.getElementById("Remember_Mode").checked){
            Controler = "checked";
        }else{
            Controler = "not checked";
        }
        var Transaction_Mode = document.getElementById("Transaction_Mode").value;

        if (window.XMLHttpRequest) {
            myObjectRemember = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemember = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemember.overrideMimeType('text/xml');
        }
        myObjectRemember.onreadystatechange = function () {
            dataRemember = myObjectRemember.responseText;
            if (myObjectRemember.readyState == 4) {
                //Continue.................................
            }
        }; //specify name of function that will handle server response........

        myObjectRemember.open('GET', 'Reception_Remember_Transaction_Mode.php?&Transaction_Mode='+Transaction_Mode+'&Controler='+Controler, true);
        myObjectRemember.send();
    }
</script>

<script type="text/javascript">
    function update_transaction_mode(Registration_ID){
        if (window.XMLHttpRequest) {
            myObjectUpd = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpd.overrideMimeType('text/xml');
        }

        myObjectUpd.onreadystatechange = function () {
            dataUpd = myObjectUpd.responseText;
            if (myObjectUpd.readyState == 4) {
                document.getElementById('Transaction_Area').innerHTML = dataUpd;
            }
        }; //specify name of function that will handle server response........

        myObjectUpd.open('GET', 'Update_Transaction_Mode_Reception.php?Registration_ID=' + Registration_ID, true);
        myObjectUpd.send();
    }
</script>





    <script type="text/javascript">
                function Get_Doctor(){
                var Direction = document.getElementById("direction").value;
                        if (Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')){
                $("#List_OF_Doctors").dialog("open");
                }
                }
    </script>

    <script type="text/javascript">
        function Search_Doctors(){
        var Doctror_Name = document.getElementById("Doc_Name").value;
                if (window.XMLHttpRequest){
        myObject_Search_Doctor = new XMLHttpRequest();
        } else if (window.ActiveXObject){
        myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject_Search_Doctor.overrideMimeType('text/xml');
        }

        myObject_Search_Doctor.onreadystatechange = function (){
        data = myObject_Search_Doctor.responseText;
                if (myObject_Search_Doctor.readyState == 4) {
        document.getElementById('Doctors_Area').innerHTML = data;
        }
        }; //specify name of function that will handle server response........
                myObject_Search_Doctor.open('GET', 'Search_Doctors.php?Doctror_Name=' + Doctror_Name, true);
                myObject_Search_Doctor.send();
        }
    </script>

    <script type="text/javascript">
        function Get_Selected_Doctor(Doctror_Name)	{
        document.getElementById("Consultant").value = Doctror_Name;
                document.getElementById("Doc_Name").value = '';
                Search_Doctors();
                $("#List_OF_Doctors").dialog("close");
        }
    </script>

    <fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td width=25%>
                        <script type='text/javascript'>
                            function getItemsList(Item_Category_ID){
                            document.getElementById("Search_Value").value = '';
                                    document.getElementById("Price").value = '';
                                    document.getElementById("Item_Name").value = '';
                                    document.getElementById("Quantity").value = '';
                                    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                    var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                    if (window.XMLHttpRequest) {
                            myObject = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                            }
                            //alert(data);

                            myObject.onreadystatechange = function (){
                            data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                            //document.getElementById('Approval').readonly = 'readonly';
                            document.getElementById('Items_Fieldset').innerHTML = data;
                            }
                            }; //specify name of function that will handle server response........
                                    myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name+"&Sponsor_ID="+Sponsor_ID+"&from_reception=yes", true);
                                    myObject.send();
                            }

                            function getItemsListFiltered(Item_Name, Sponsor_ID){
                            document.getElementById("Price").value = '';
                                    document.getElementById("Item_Name").value = '';
                                    document.getElementById("Quantity").value = '';
                                    var Item_Category_ID = document.getElementById("Item_Category_ID").value;
                                    if (Item_Category_ID == '' || Item_Category_ID == null) {
                            Item_Category_ID = 'All';
                            }

                            if (window.XMLHttpRequest) {
                            myObject = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject.overrideMimeType('text/xml');
                            }
                            //alert(data);

                            myObject.onreadystatechange = function (){
                            data = myObject.responseText;
                                    if (myObject.readyState == 4) {
                            //document.getElementById('Approval').readonly = 'readonly';
                            document.getElementById('Items_Fieldset').innerHTML = data;
                            }
                            }; //specify name of function that will handle server response........
                                    myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID+"&from_reception=yes", true);
                                    myObject.send();
                            }

                            function Get_Item_Name(Item_Name, Item_ID){
                            document.getElementById("Item_Name").value = Item_Name;
                                    document.getElementById("Item_ID").value = Item_ID;
                                    document.getElementById("Quantity").value = '';
                                    document.getElementById("Quantity").focus();
                            }
    </script>
    <script type="text/javascript">
        function Get_Item_Price(Item_ID,Guarantor_Name, Sponsor_ID){
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Transaction_Mode = document.getElementById("Transaction_Mode").value;
            if (Billing_Type == ''){
                alert('Choose billing type');
                document.getElementById("Billing_Type").focus();
                document.getElementById("Billing_Type").style = 'border-color: red';
                exit();
            }
            if (window.XMLHttpRequest) {
                myObjectPrice = new XMLHttpRequest();
            } else if (window.ActiveXObject){
                myObjectPrice = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPrice.overrideMimeType('text/xml');
            }

            myObjectPrice.onreadystatechange = function (){
                dataPr = myObjectPrice.responseText;
                if (myObjectPrice.readyState == 4) {
                    document.getElementById('Price').value = dataPr;
                    document.getElementById("Quantity").value = 1;
                }
            }; //specify name of function that will handle server response........

            myObjectPrice.open('GET', 'Get_Items_Price.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID+'&Billing_Type='+Billing_Type+'&Transaction_Mode='+Transaction_Mode, true);
            myObjectPrice.send();
        }
    </script>
    <script type="text/javascript">
        //this will be called on billing type
        function Get_Item_Price2(){
            var Billing_Type = document.getElementById("Billing_Type").value;
            var Transaction_Mode = document.getElementById("Transaction_Mode").value;
            var Item_ID = document.getElementById("Item_ID").value;
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            if (Billing_Type != '' && Billing_Type != null && Item_ID != '' && Item_ID != null && Guarantor_Name != '' && Guarantor_Name != null) {
                if (window.XMLHttpRequest) {
                    myObject = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObject.overrideMimeType('text/xml');
                }
                myObject.onreadystatechange = function (){
                    dataPr2 = myObject.responseText;
                    if (myObject.readyState == 4) {
                        document.getElementById('Price').value = dataPr2;
                    }
                }; //specify name of function that will handle server response........

                myObject.open('GET', 'Get_Items_Price.php?Item_ID='+Item_ID+'&Guarantor_Name='+Guarantor_Name+'&Billing_Type='+Billing_Type+'&Transaction_Mode='+Transaction_Mode, true);
                myObject.send();
            }
        }
    </script>
    <script type="text/javascript">
                            function Make_Payment(){
                            var Item_Name = document.getElementById("Item_Name").value;
                                    var Discount = document.getElementById("Discount").value;
                                    var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                    var direction = document.getElementById("direction").value;
                                    var Consultant = document.getElementById("Consultant").value;
                                    var Item_ID = document.getElementById("Item_ID").value;
                                    var Quantity = document.getElementById("Quantity").value;
                                    var Registration_ID = <?php echo $Registration_ID; ?>;
                                    //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 

                                    //alert(Item_Name+", "+Discount+", "+Type_Of_Check_In+", "+direction+", "+Consultant+", "+Item_ID+", "+Quantity+", "+Registration_ID);
                                    if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null) {


                            if (window.XMLHttpRequest) {
                            myObject2 = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject2.overrideMimeType('text/xml');
                            }
                            //alert("eHMS");

                            myObject.onreadystatechange = function (){
                            data = myObject.responseText;
                                    if (myObject2.readyState == 4) {
                            document.getElementById('Price').value = data;
                                    //alert(data);
                            }
                            }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Perform_Reception_Transaction.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount, true);
                                    myObject2.send();
                            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null){
                            alertMessage();
                            }
                            }

                            function alertMessage(){
                            alert("Please Select Item First");
                                    document.getElementById("Quantity").value = '';
                            }

                            function update_Billing_Type() {
                            var Registration_ID = <?php echo $Registration_ID; ?>;
                                    if (window.XMLHttpRequest) {
                            myObject12 = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject12 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject12.overrideMimeType('text/xml');
                            }

                            //alert('Update_Billing_Type.php?Registration_ID='+Registration_ID);
                            myObject12.onreadystatechange = function (){
                            data12 = myObject12.responseText;
                                    if (myObject12.readyState == 4) {
                            document.getElementById('Billing_Type_Area').innerHTML = data12;
                            }
                            }; //specify name of function that will handle server response........

                                    myObject12.open('GET', 'Update_Billing_Type.php?Registration_ID=' + Registration_ID, true);
                                    myObject12.send();
                            }

                            function Update_Claim_Form_Number() {
                            var Registration_ID = <?php echo $Registration_ID; ?>;
                                    if (window.XMLHttpRequest) {
                            myObject16 = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject16 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject16.overrideMimeType('text/xml');
                            }

                            //alert('Update_Billing_Type.php?Registration_ID='+Registration_ID);
                            myObject16.onreadystatechange = function (){
                            data16 = myObject16.responseText;
                                    if (myObject16.readyState == 4) {
                            document.getElementById('Claim_Form_Number').innerHTML = data16;
                            }
                            }; //specify name of function that will handle server response........ 

                                    myObject16.open('GET', 'Update_Claim_Form_Number.php?Registration_ID=' + Registration_ID, true);
                                    myObject16.send();
                            }
                        </script>

                        <script type="text/javascript">
                            function Check_Item_Price(){
                                    var Consultant = document.getElementById("Consultant").value;
                                    var Clinic_ID = document.getElementById("Clinic_ID").value;
                                    var finance_department_id = document.getElementById("finance_department_id").value;
                                    var clinic_location_id = document.getElementById("clinic_location_id").value;
                                    var Item_Name = document.getElementById("Item_Name").value;
                                    var Discount = document.getElementById("Discount").value;
                                    var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                    var Item_ID = document.getElementById("Item_ID").value;
                                    var Quantity = document.getElementById("Quantity").value;
                                    var Registration_ID = <?php echo $Registration_ID; ?>;
                                    var Employee_ID = <?php echo $Employee_ID; ?>;
                                    var Billing_Type = document.getElementById("Billing_Type").value;
                                    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                    var Sponsor_ID = <?php echo $Sponsor_ID; ?>;
                                    var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                    var direction, Consultant;
                                    if (Type_Of_Check_In != 'Doctor Room'){
                            direction = 'others';
                                    Consultant = 'others';
                            } else{
                            direction = document.getElementById("direction").value;
                                    Consultant = document.getElementById("Consultant").value;
                            }
                            if((Clinic_ID==''|| Clinic_ID==null) && (direction=="Direct To Doctor Via Nurse Station"||direction=="Direct To Doctor")){
                                alert("Select clinic first")
                                exit;
                            }
                            
                            if(finance_department_id==''|| finance_department_id==null){
                                alert("Select Department first")
                                exit;
                            }
                            
                            if(clinic_location_id==''|| clinic_location_id==null){
                                alert("Select Clinic Location first")
                                exit;
                            }
                            
                            if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null && Employee_ID != '' && Employee_ID != null && Billing_Type != '' && Billing_Type != null <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?> && Claim_Form_Number != '' && Claim_Form_Number != null<?php } ?> && Consultant != null && Consultant != '') {
                            if (window.XMLHttpRequest){
                            myObjectCheckPrice = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObjectCheckPrice = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObjectCheckPrice.overrideMimeType('text/xml');
                            }
                            myObjectCheckPrice.onreadystatechange = function (){
                            data9090 = myObjectCheckPrice.responseText;
                                    if (myObjectCheckPrice.readyState == 4) {
                            var feedb = data9090.trim();
                                    if (feedb == 'yes'){
                            Get_Selected_Item()
                            } else{
                            $("#Get_Selected_Item_Warning").dialog("open");
                            }
                            }
                            }; //specify name of function that will handle server response........

                                    myObjectCheckPrice.open('GET', 'Reception_Check_Item_Price.php?Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Item_ID=' + Item_ID+'&Clinic_ID='+Clinic_ID+'&finance_department_id='+finance_department_id+'&clinic_location_id='+clinic_location_id, true);
                                    myObjectCheckPrice.send();
                            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?> && Claim_Form_Number != '' && Claim_Form_Number != null<?php } ?>){
                                alertMessage();
                            } else{
                            if (Discount == '' || Discount == null){
                            document.getElementById("Discount").value = '0';
                            }
                            if (Quantity == '' || Quantity == null){
                            document.getElementById("Quantity").focus();
                                    document.getElementById("Quantity").style = 'border-color: red';
                            }
<?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                if (Claim_Form_Number == '' || Claim_Form_Number == null){
                                document.getElementById("Claim_Form_Number").focus();
                                        document.getElementById("Claim_Form_Number").style = 'border-color: red';
                                }
<?php } ?>
                            if (Consultant == '' || Consultant == null){
                            document.getElementById("Consultant").focus();
                                    document.getElementById("Consultant").style = 'border-color: red';
                            }
                            if (direction == '' || direction == null){
                            document.getElementById("direction").focus();
                                    document.getElementById("direction").style = 'border-color: red';
                            }
                            if (Type_Of_Check_In == '' || Type_Of_Check_In == null){
                            document.getElementById("Type_Of_Check_In").focus();
                                    document.getElementById("Type_Of_Check_In").style = 'border-color: red';
                            }
                            if (Billing_Type == '' || Billing_Type == null){
                            document.getElementById("Billing_Type").focus();
                                    document.getElementById("Billing_Type").style = 'border-color: red';
                            }
                            }
                            }
                        </script>
                        <script type='text/javascript'>
                            function Get_Selected_Item(){
                            Consultant = document.getElementById("Consultant").value;
                            var Clinic_ID = document.getElementById("Clinic_ID").value;
                            var finance_department_id = document.getElementById("finance_department_id").value;
                            var clinic_location_id = document.getElementById("clinic_location_id").value;
                                    var Item_Name = document.getElementById("Item_Name").value;
                                    var Discount = document.getElementById("Discount").value;
                                    var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                    var Transaction_Mode = document.getElementById("Transaction_Mode").value;
                                    var direction, Consultant;
                                    if (Type_Of_Check_In != 'Doctor Room'){
                            direction = 'others';
                                    Consultant = 'others';
                            } else{
                            direction = document.getElementById("direction").value;
                                    Consultant = document.getElementById("Consultant").value;
                            }
                            var Item_ID = document.getElementById("Item_ID").value;
                                    var Quantity = document.getElementById("Quantity").value;
                                    var Registration_ID = <?php echo $Registration_ID; ?>;
                                    var Employee_ID = <?php echo $Employee_ID; ?>;
                                    var Billing_Type = document.getElementById("Billing_Type").value;
                                    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                    var Sponsor_ID = <?php echo $Sponsor_ID; ?>;
                                    var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                    if (Claim_Form_Number == '') {
                            Claim_Form_Number = null;
                            }

                            //alert('Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount); 

                            //alert('Add_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity+'&Consultant='+Consultant+'&Discount='+Discount);
                            if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null && Employee_ID != '' && Employee_ID != null && Billing_Type != '' && Billing_Type != null <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?> && Claim_Form_Number != '' && Claim_Form_Number != null<?php } ?> && Consultant != null && Consultant != '') {
                            if (window.XMLHttpRequest){
                            myObject2 = new XMLHttpRequest();
                            } else if (window.ActiveXObject){
                            myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                    myObject2.overrideMimeType('text/xml');
                            }
                            myObject2.onreadystatechange = function (){
                            data = myObject2.responseText;
                                    if (myObject2.readyState == 4) {
                            //alert("One Item Added");
                            document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                                    update_Billing_Type();
                                    Update_Claim_Form_Number();
                                    document.getElementById("Item_Name").value = '';
                                    document.getElementById("Item_ID").value = '';
                                    document.getElementById("Quantity").value = '';
                                    document.getElementById("Price").value = '';
                                    //update_fieldset(Registration_ID);
                                    update_total(Registration_ID);
                                    update_transaction_mode(Registration_ID);
                            }
                            }; //specify name of function that will handle server response........

                                    //myObject.open('GET','Perform_Reception_Transaction.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&direction='+direction+'&Quantity='+Quantity'&Consultant='+Consultant,true);
                                    myObject2.open('GET', 'Reception_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Type_Of_Check_In=' + Type_Of_Check_In + '&direction=' + direction + '&Quantity=' + Quantity + '&Consultant=' + Consultant + '&Discount=' + Discount + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number + '&Selected_Billing_Type=' + Billing_Type+'&Transaction_Mode='+Transaction_Mode+"&Clinic_ID="+Clinic_ID+"&finance_department_id="+finance_department_id+'&clinic_location_id='+clinic_location_id, true);
                                    myObject2.send();
                            } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Type_Of_Check_In != '' && Type_Of_Check_In != null && direction != '' && direction != null && Quantity != '' && Quantity != null <?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?> && Claim_Form_Number != '' && Claim_Form_Number != null<?php } ?>){
                            alertMessage();
                            } else{
                            if (Discount == '' || Discount == null){
                            document.getElementById("Discount").value = '0';
                            }
                            if (Quantity == '' || Quantity == null){
                            document.getElementById("Quantity").focus();
                                    document.getElementById("Quantity").style = 'border-color: red';
                            }
<?php if (strtolower($Claim_Number_Status) == 'mandatory') { ?>
                                if (Claim_Form_Number == '' || Claim_Form_Number == null){
                                document.getElementById("Claim_Form_Number").focus();
                                        document.getElementById("Claim_Form_Number").style = 'border-color: red';
                                }
<?php } ?>
                            if (Consultant == '' || Consultant == null){
                            document.getElementById("Consultant").focus();
                                    document.getElementById("Consultant").style = 'border-color: red';
                            }
                            if (direction == '' || direction == null){
                            document.getElementById("direction").focus();
                                    document.getElementById("direction").style = 'border-color: red';
                            }
                            if (Type_Of_Check_In == '' || Type_Of_Check_In == null){
                            document.getElementById("Type_Of_Check_In").focus();
                                    document.getElementById("Type_Of_Check_In").style = 'border-color: red';
                            }
                            if (Billing_Type == '' || Billing_Type == null){
                            document.getElementById("Billing_Type").focus();
                                    document.getElementById("Billing_Type").style = 'border-color: red';
                            }
                            }
                            }
                        </script>
                        <script>
                            function clearFocus(MyElement){
                            MyElement.style = 'border-color: white';
                            }
                        </script>

                        <table width = 100%>
                            <tr>
                                <td style='text-align: center;'>
                                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        <option selected='selected'></option>
                                        <?php
                                        $data = mysqli_query($conn,"select * from tbl_item_category WHERE can_be_used_on_registration='yes'");
                                        while ($row = mysqli_fetch_array($data)) {
                                            echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                                        }
                                        ?>   
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type='text' id='Search_Value' name='Search_Value' onkeyup="getItemsListFiltered(this.value, '<?php echo $Sponsor_ID ?>')" placeholder='Enter Item Name'></td>
                            </tr>			    
                            <tr>
                                <td>
                                    <fieldset style='overflow-y: scroll; height: 225px;' id='Items_Fieldset'>
                                        <table width=100%>
                                            <?php
                                            //$result = mysqli_query($conn,"SELECT * FROM tbl_items WHERE Status='Available'  order by Product_Name LIMIT 100") or die(mysqli_error());
                                             $sql_select_items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c,tbl_item_price ip
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and
                        ip.Item_ID = t.Item_ID and
                        t.Status = 'Available' and c.can_be_used_on_registration='yes' and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' limit 200";
                                             $result=mysqli_query($conn,$sql_select_items) or die(mysqli_error());
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>
							<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                                ?>

                                                <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>,'<?php echo $Guarantor_Name; ?>','<?php echo $Sponsor_ID; ?>')">

                                                <?php
                                                echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                                            }
                                            ?> 
                                        </table>
                                    </fieldset>		
                                </td>
                            </tr>
                        </table> 
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;' colspan=2>



                                    <!-- ITEM DESCRIPTION START HERE -->


                            <center>
                                <table width=100%>
                                    <tr>
                                        <td><b>Item Name</b></td>
                                        <td><b>Discount</b></td>
                                        <td><b>Price</b></td>
                                        <td><b>Balance</b></td>
                                        <td><b>Qty</b></td>
                                        <td><b>Amount</b></td>
                                        <td></td>
                                    </tr>
                                    <tr>  
                                        <td width=35%> 
                                            <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
                                            <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                                        </td> 
                                        <td>
                                            <input type='text' name='Discount' id='Discount' placeholder='Discount' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()' value=0>
                                        </td>
                                        <td>
                                            <input type='text' name='Price' id='Price' placeholder='Price' readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Balance' id='Balance' placeholder='Balance' value=1 readonly='readonly'>
                                        </td>
                                        <td>
                                            <input type='text' name='Quantity' id='Quantity' required='required' autocomplete='off' placeholder='Quantity' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                                        </td>
                                        <td>
                                            <input type='text' name='Amount' id='Amount' placeholder='Amount' readonly='readonly'>
                                        </td>
                                        <td style='text-align: center;'>
                                            <script type='text/javascript'>
                                                        function Make_Payment_Warning() {
                                                        alert("Please choose one of the options below");
                                                        }
                                                function Select_Patient_First() {
                                                alert("Please select patient first");
                                                }
                                            </script>
                                            <input type='button' name='submit' id='submit' value='ADD' class='art-button-green' onclick='Check_Item_Price()'>
                                        </td>
                                    </tr>
                                </table>   
                            </center>
                            <!-- ITEM DESCRIPTION ENDS HERE -->
                    </td>
                </tr>
                <tr>
                    <td colspan=2 id='Picked_Items_Fieldset'>
                        <!--<iframe src='Adhoc_Patient_Billing_Iframe.php?Registration_ID=<?php echo $Registration_ID; ?>&Employee_ID=<?php echo $Employee_ID; ?>' width='100%' height=200px></iframe>-->
                        <fieldset style='overflow-y: scroll; height: 200px;'>
                            <?php
                            $total = 0;
                            echo '<table width =100%>';
                            echo '<tr><td colspan=9><hr></td></tr>';
                            echo "<tr id='thead'><td style='width: 3%;'>Sn</td><td style='width: 10%;'>Check-in</td>";
                            echo '<td style="width: 20%;">Location</td>
							<td style="width: 28%;">Item description</td>
							    <td style="text-align:right; width: 8%;">Price</td>
								<td style="text-align:right; width: 8%;">Discount</td>
								    <td style="text-align:right; width: 8%;">Quantity</td>
									<td style="text-align:right; width: 8%;">Sub total</td><td width=4%>Remove</td></tr>';
                            echo '<tr><td colspan=9><hr></td></tr>';

                            $select_Transaction_Items = mysqli_query($conn,
                                    "select Reception_List_Item_ID, Check_In_Type, Patient_Direction, Product_Name, Price, Discount, Quantity,Registration_ID
						    from tbl_items t, tbl_reception_items_list_cache alc
							where alc.Item_ID = t.Item_ID and
							    alc.Employee_ID = '$Employee_ID' and
								    Registration_ID = '$Registration_ID'") or die(mysqli_error());
                            $no_of_items = mysqli_num_rows($select_Transaction_Items);
                            while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                echo "<tr><td>" . $temp . "</td><td>" . $row['Check_In_Type'] . "</td>";
                                echo "<td>" . $row['Patient_Direction'] . "</td>";
                                echo "<td>" . $row['Product_Name'] . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "</td>";
                                echo "<td style='text-align:right;'>" . number_format($row['Discount']) . "</td>";
                                echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity'])) . "</td>";
                                ?>
                                <td style='text-align: center;'> 
                                    <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Reception_List_Item_ID']; ?>,<?php echo $row['Registration_ID']; ?>);'>
                                </td>
                                <?php
                                $temp++;
                                $total = $total + (($row['Price'] - $row['Discount']) * $row['Quantity']);
                            }echo "</tr>";
                            echo "<tr><td colspan=8 style='text-align: right;'> Total : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . "</td></tr></table>";
                            ?>
                        </fieldset>
                        <table width='100%'>
                            <tr>
                            <script type='text/javascript'>
                                        function Send_To_Cashier(Registration_ID){
                                        document.location = 'Send_To_Cashier_Patient_Reception_Credit.php?Registration_ID=' + Registration_ID
                                        }

                                function Confirm_Send_To_Cashier(Registration_ID){
                                var r = confirm("Are you sure you want to send this bill to cashier?");
                                        if (r == true){
                                Send_To_Cashier(Registration_ID);
                                }
                                }
                            </script>
                            <?php
                            if ($no_of_items > 0) {
                                ?><input type="text"hidden="hidden" id="total_txt" value="<?php echo $total; ?>"/>
                                <td style='text-align: right; width: 20%;'><h4>Total : <?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code']; ?></h4></td>
                                <td style='text-align: right; width: 80%;'>
                                    <?php
                                        $slct = mysqli_query($conn,"select Prepaid_ID from tbl_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error());
                                        $nm = mysqli_num_rows($slct);
                                       if(strtolower($Selected_Billing_Type) == 'outpatient cash'){
                                           $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                            $sql_check_approve_bill_privilege_result=mysqli_query($conn,"SELECT Employee_ID FROM tbl_privileges WHERE Employee_ID='$Employee_ID' AND can_create_out_patient_bill='yes'") or die(mysqli_error());
                                            if(mysqli_num_rows($sql_check_approve_bill_privilege_result)>0){ 
                                            echo "<input type='button' class='art-button-green' value='Create Outpatient Bill' onclick='Create_Pre_Paid_Bill()'>";
                                            }else{
                                             echo "<input type='button' class='art-button-green' value='Create Outpatient Bill' onclick='alert(\"Access Denied\")'>";
                                               
                                            }
                                            }
                                    if (strtolower($_SESSION['systeminfo']['Centralized_Collection']) == 'yes') {
                                        if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
                                            if ($Selected_Billing_Type == 'Outpatient Credit') {
                                                ?>
                                                <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                                <?php
                                            } else {
                                                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                                                     if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                                    ?>
                                                    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                                                    <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                                                     <?php } } 
                                                     if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ 
                                                     ?>
                                                    <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                                     <?php } if (strtolower($_SESSION['systeminfo']['Display_Send_To_Cashier_Button']) == 'yes') { ?>
                                                    <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                                                <?php } ?>
                                                <?php
                                            }
                                        } else {
                                            if ($Selected_Billing_Type == 'Outpatient Credit') {
                                                ?>
                                                <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                                <?php
                                            } else {
                                                if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                                                    if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                                    ?>
                                                    <input type="button"  value="Go to mobile/Card Payment" class="art-button-green" onclick="create_epayment_mobile_card_bill()">&nbsp;&nbsp;
                                                    <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                                                <?php } } ?>
                                                <?php if (strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes') { 
                                                    if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){ 
                                                    ?>
                                                    <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                                    <?php } } ?>
                                                <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                        }
                                    } else {
                                        if ($Selected_Billing_Type == 'Outpatient Credit') {
                                            ?>
                                            <input type='button' value='Save Information' class='art-button-green' onclick='Patient_Billing_Reception_Generate_Receipt(<?php echo $Registration_ID; ?>)'>
                                            <?php
                                        } else {
                                            if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
                                                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
                                                ?>
                                                <input type='button' value='Create ePayment Bill' id='Pay_Via_Mobile' name='Pay_Via_Mobile' class='art-button-green' onclick='Pay_Via_Mobile_Function()'>
                                            <?php } }?>
                                            <?php if (strtolower($_SESSION['userinfo']['Revenue_Center_Works']) == 'yes') { 
                                                if(strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes'&&isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='makepayment'){
                                                ?>
                                                <input type='button' value='Make Payment' class='art-button-green' onclick='Confirm_Payment_Credit_Cases()'>
                                            <?php } } ?>
                                            <input type='button' name='Send_To_Cashier_Button' class='art-button-green' value='Send To Cashier' onclick='send_cashier_new_payment_method()'>
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td style='text-align: right; width: 60%;'><h4>Total : 0</h4></td>
                                <td style='text-align: right; width: 40%;'>
                                </td>
                                <?php
                            }
                            ?>
                </tr>
            </table>
            </td>
            </tr>
            </table>

            </td>
            </tr> 
            </table>
        </center>
    </fieldset>
    <div id="Pre_Paid_Dialog">
        Are you sure you want to create out patient bill?<br/><br/>
        <table width="100%">
            <tr>
                <td style="text-align: right;">
                    <input type="button" name="Create_Button" id="Create_Button" value="CREATE BILL" class="art-button-green" onclick="Create_Pre_Paid_Bill_Process()">
                    <input type="button" name="Create_Button" id="Create_Button" value="CANCEL" class="art-button-green" onclick="Pre_Paid_Close_Dialog()">
                </td>
            </tr>
        </table>
    </div>

    <script>
                function openItemDialog(Folio_Number, Insurance, Registration_ID, Payment_Type, Patient_Bill_ID){
                if (window.XMLHttpRequest){
                myObjectGetDetails = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectGetDetails.overrideMimeType('text/xml');
                }
                myObjectGetDetails.onreadystatechange = function (){
                data29 = myObjectGetDetails.responseText;
                        if (myObjectGetDetails.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = data29;
                        $("#Display_Folio_Details").dialog("open");
                }
                }; //specify name of function that will handle server response........

                        myObjectGetDetails.open('GET', 'billingprocess.php?Folio_Number=' + Folio_Number + '&Insurance=' + Insurance + '&Registration_ID=' + Registration_ID + '&Payment_Type=' + Payment_Type + '&Patient_Bill_ID=' + Patient_Bill_ID, true);
                        myObjectGetDetails.send();
                }
    </script>

    <script type="text/javascript">
        function Pre_Paid_Close_Dialog(){
            $("#Pre_Paid_Dialog").dialog("close");
        }
    </script>

    <script type="text/javascript">
        function Create_Pre_Paid_Bill(){
            $("#Pre_Paid_Dialog").dialog("open");
        }
    </script>

    <script type="text/javascript">
        function Create_Pre_Paid_Bill_Process(){
            var Registration_ID = '<?php echo $Registration_ID; ?>';
            document.location = 'Reception_Make_Payment.php?Registration_ID='+Registration_ID+'&Pre_Paid_Transaction=yes';
        }
    </script>
<div id="myDiaglog" style="display:none;">
    
    
</div>
<script type="text/javascript">
    
           
        function get_terminal_id(terminalid){
        if(terminalid.value!=''){
            $('#terminal_id').val(terminalid.value);
        } else {
            $('#terminal_id').val('');
        }
        
    }
    function get_terminals(trans_type){
        var registration_id = '<?php echo $Registration_ID; ?>';
       
        
         $('#terminal_id').val('');
        var uri = '../epay/get_terminals.php';
        //alert(trans_type.value);
        if(trans_type.value=="Manual"){
            var result=confirm("Are you sure you want to make manual payment?");
            if(result){
                Registration_ID = <?php echo $Registration_ID; ?>;
                document.location = 'Reception_Make_Payment.php?Registration_ID=' + Registration_ID+'&manual_offline=manual';
           }
        }else{
                $.ajax({
                type: 'GET',
                url: uri,
                data: {trans_type : trans_type.value},
                success: function(data){
                    $("#terminal_name").html(data);
                },
                error: function(){

                }
            });
        }
    }
    
    
      function offline_transaction(amount_required,reg_id){
               
        var uri = '../epay/revenuecenterpatientbillingreceptionOfflinePayment.php';
        
          
        //alert(trans_type.value);
        var comf = confirm("Are you sure you want to make MANUAL / OFFLINE Payments?");
        if(comf){
            
            $.ajax({
                type: 'GET',
                url: uri,
                data: {amount_required:amount_required,registration_id:reg_id},
                beforeSend: function (xhr) {
                    $('#offlineProgressStatus').show();
                },
                success: function(data){
                    //alert("dtat");
                    $("#myDiaglog").dialog({
                        title: 'Manual / Offline Transaction Form',
                        width: '35%',
                        height: 380,
                        modal: true,
                    }).html(data);
                },
                complete: function(){
                    $('#offlineProgressStatus').hide();
                },
                error: function(){
                     $('#offlineProgressStatus').hide();
                }
            });
        } 
    }
</script>
    <script type='text/javascript'>
        function Make_Payment_Credit_Cases(){
        Registration_ID = '<?php echo $Registration_ID; ?>';
          //document.location = 'Reception_Make_Payment.php?Registration_ID=' + Registration_ID;
            var amount_required=document.getElementById("total_txt").value;
          //  alert(amount_required)
            offline_transaction(amount_required,Registration_ID);
        }

        function Confirm_Payment_Credit_Cases(){
        //var r = confirm("You are about to make Transaction. Click OK to continue?");
          //      if (r == true) {
        Make_Payment_Credit_Cases();
        //
        }


        function Remove_Item(){
        alert("This Item Removed");
        }
    </script>

    <script>
        /*function Pay_Via_Mobile_Function() {
         var r = confirm("Are you sure you want to create ePayment Bill?\n Click OK to continue or Cancel to terminate process?");
         var Registration_ID = <?php echo $Registration_ID; ?>;
         if (r == true) {
         document.location = 'Transaction_Via_Mobile.php?Registration_ID='+Registration_ID+'&Location=Reception';
         }
         }*/
    </script>

    <script>
        function open_Dialog(){
        if (window.XMLHttpRequest){
        myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject){
        myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
        }
        myObjectGetDetails.onreadystatechange = function (){
        data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
        document.getElementById('Details_Area').innerHTML = data29;
                $("#Display_Number_Of_Patients").dialog("open");
        }
        }; //specify name of function that will handle server response........

                myObjectGetDetails.open('GET', 'patientqueue.php', true);
                myObjectGetDetails.send();
        }
    </script>

    <script>
        function Pay_Via_Mobile_Function(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Employee_ID = '<?php $Employee_ID; ?>';
                if (window.XMLHttpRequest){
        myObjectGetDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject){
        myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetDetails.overrideMimeType('text/xml');
        }

        myObjectGetDetails.onreadystatechange = function (){
        data29 = myObjectGetDetails.responseText;
                if (myObjectGetDetails.readyState == 4) {
        document.getElementById('ePayment_Area').innerHTML = data29;
                $("#ePayment_Window").dialog("open");
        }
        }; //specify name of function that will handle server response........

                myObjectGetDetails.open('GET', 'ePayment_Patient_Details.php?Employee_ID=' + Employee_ID + '&Registration_ID=' + Registration_ID, true);
                myObjectGetDetails.send();
        }
    </script>

<script type="text/javascript">
    function Get_Free_Consult_Items(){
        var direction = document.getElementById("direction").value;
        var Consultant = document.getElementById("Consultant").value;
        var Clinic_ID = document.getElementById("Clinic_ID").value;
        var Billing_Type = document.getElementById("Billing_Type").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Free_Consultation_Sponsor = '<?php echo strtolower($Free_Consultation_Sponsor); ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        
         
        if(Billing_Type == 'Outpatient Credit' && direction != null && direction != '' && Consultant != '' && Consultant != null && Free_Consultation_Sponsor == 'yes'){
            if(window.XMLHttpRequest) {
                myObjectFreeCons = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectFreeCons = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFreeCons.overrideMimeType('text/xml');
            }
            myObjectFreeCons.onreadystatechange = function (){
                dataFreeCon = myObjectFreeCons.responseText;
                if (myObjectFreeCons.readyState == 4) {
                    document.getElementById('Picked_Items_Fieldset').innerHTML = dataFreeCon;
                    update_Billing_Type();
                    Update_Claim_Form_Number();
                    update_total(Registration_ID);
                    update_transaction_mode(Registration_ID);
                }
            }; //specify name of function that will handle server response........

            myObjectFreeCons.open('GET','Get_Free_Consult_Items.php?Registration_ID='+Registration_ID+'&direction='+direction+'&Consultant='+Consultant+'&Sponsor_ID='+Sponsor_ID+'&Guarantor_Name='+Guarantor_Name,true);
            myObjectFreeCons.send()
        }
    }
</script>

    <script>
        function type_Of_Check_In(type_of_check_in){
        if (type_of_check_in !== 'Doctor Room'){
        //alert(type_of_check_in+' Not To Doctor Room');
        $("#direction,#Consultant").css("background", "#ccc");
                $("#direction,#Consultant").attr("disabled", "true");
                $("#direction,#Consultant").removeAttr('required');
                $("#direction,#Consultant").val("");
        } else{
        $("#direction,#Consultant").css("background", "white");
                $("#direction,#Consultant").attr("disabled", false);
                $("#direction,#Consultant").attr('required', 'required');
        }


        //MyElement.style = 'border-color: white';
        $(this).css("border-color", "white");
        }
    </script>
    <script type='text/javascript'>
        function Confirm_Remove_Item(Item_Name, Reception_List_Item_ID, Registration_ID){
        var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
                var Selected_Billing_Type = document.getElementById("Billing_Type").value;
                if (Confirm_Message == true) {
        if (window.XMLHttpRequest) {
        myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject){
        myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
        data = myObject.responseText;
                if (myObject.readyState == 4) {
        document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                update_total(Registration_ID);
                update_Billing_Type();
                update_transaction_mode(Registration_ID);
                Update_Claim_Form_Number();
        }
        }; //specify name of function that will handle server response........

                myObject.open('GET', 'Reception_Remove_Item_From_List.php?Reception_List_Item_ID=' + Reception_List_Item_ID + '&Registration_ID=' + Registration_ID + '&Selected_Billing_Type=' + Selected_Billing_Type, true);
                myObject.send();
        }
        }

        function update_total(Registration_ID){
        if (window.XMLHttpRequest) {
        myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject){
        myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
        }
        myObject.onreadystatechange = function (){
        data = myObject.responseText;
                if (myObject.readyState == 4) {
        document.getElementById('Total_Area').innerHTML = data;
        }
        }; //specify name of function that will handle server response........

                myObject.open('GET', 'Update_Total.php?Registration_ID=' + Registration_ID, true);
                myObject.send();
        }

        function Remove_Item(){
        alert("This Item Removed");
        }

        function update_clinic_id(){
             var Billing_Type = document.getElementById("Billing_Type").value;
             var Registration_ID = '<?php echo $Registration_ID; ?>';
             var Clinic_ID = document.getElementById("Clinic_ID").value;
             var finance_department_id = document.getElementById("finance_department_id").value;
             var clinic_location_id = document.getElementById("clinic_location_id").value;
             //alert(Registration_ID)
             if(Billing_Type=="Outpatient Credit"){
                 $.ajax({
                     type:"GET",
                     url:"update_clinic_id_on_credit_transaction.php",
                     data:{Billing_Type:Billing_Type,Registration_ID:Registration_ID,Clinic_ID:Clinic_ID,finance_department_id:finance_department_id,clinic_location_id:clinic_location_id},
                     success:function (data){
                         //alert(data)
                     }
                 });
             }
        }
        function Patient_Billing_Reception_Generate_Receipt(Registration_ID) {
            var direction = document.getElementById("direction").value;
            var Clinic_ID = document.getElementById("Clinic_ID").value;
            var clinic_location_id = document.getElementById("clinic_location_id").value;
            var finance_department_id = document.getElementById("finance_department_id").value; 
            if((Clinic_ID==''|| Clinic_ID==null) && (direction=="Direct To Doctor Via Nurse Station"||direction=="Direct To Doctor"||direction=='')){
                if(direction==''){
                    alert("Select Patient Direction")
                }else{
                   alert("Select clinic") 
                }
                exit;
            }
                
            if(clinic_location_id==""||clinic_location_id==null){
               alert("Select Clinic Location");
               exit;
            }
            if(finance_department_id==""||finance_department_id==null){
               alert("Select Finance  Department");
               exit;
            }
        var Confirm_Message = confirm("Are you sure you want to perform transaction?");
                if (Confirm_Message == true) {
        document.location = 'Confirmed_Patient_Billing_Reception_Payment.php?Registration_ID=' + Registration_ID;
                //document.location = 'Confirmed_Adhoc_Payment.php?Registration_ID='+Registration_ID;
        }
        }
    </script>

    <script type="text/javascript">
        function create_bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
                if (Payment_Mode != null && Payment_Mode != ''){

        } else{
        document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
        }
        }
    </script>

    <script>
        function Update_Phone_Number(){
        var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Phone_Number = document.getElementById("Patient_Phone_Number").value;
                alert(Phone_Number);
                /*if(window.XMLHttpRequest) {
                 myObject = new XMLHttpRequest();
                 }else if(window.ActiveXObject){ 
                 myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                 myObject.overrideMimeType('text/xml');
                 }
                 myObject.onreadystatechange = function (){
                 data = myObject.responseText;
                 if (myObject.readyState == 4) {
                 document.getElementById('Total_Area').innerHTML = data;
                 }
                 }; //specify name of function that will handle server response........
                 
                 myObject.open('GET','Update_Total.php?Registration_ID='+Registration_ID,true);
                 myObject.send();*/
        }
    </script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">

    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script> 
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

    <!-- pop up window -->
    <div id="Display_Number_Of_Patients" style="width:50%;" >
        <center id='Details_Area'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
    </div>


    <!-- ePayment pop up windows -->
    <div id="ePayment_Window" style="width:50%;" >
        <span id='ePayment_Area'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </span>
    </div>


    <script type="text/javascript">
                function Verify_ePayment_Bill(){
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                        var Employee_ID = '<?php echo $Employee_ID; ?>';
                        if (window.XMLHttpRequest){
                myObjectVerify = new XMLHttpRequest();
                } else if (window.ActiveXObject){
                myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectVerify.overrideMimeType('text/xml');
                }
                myObjectVerify.onreadystatechange = function (){
                data2912 = myObjectVerify.responseText;
                        if (myObjectVerify.readyState == 4) {
                var feedback = data2912;
                        if (feedback == 'yes'){
                Create_ePayment_Bill();
                } else{
                alert("You are not allowed to create transaction with zero price or zero quantity. Please remove those items to proceed");
                }
                }
                }; //specify name of function that will handle server response........

                        myObjectVerify.open('GET', 'Verify_ePayment_Bill.php?P_Type=Credit_Patient&Registration_ID=' + Registration_ID + '&Employee_ID=' + Employee_ID, true);
                        myObjectVerify.send();
                }
    </script>

    <script type="text/javascript">
        function Create_ePayment_Bill(){
        var Payment_Mode = document.getElementById("Payment_Mode").value;
                var Registration_ID = '<?php echo $Registration_ID; ?>';
                var Amount = document.getElementById("Amount_Required").value;
                if (Amount <= 0 || Amount == null || Amount == '' || Amount == '0'){
        alert("Process Fail! You can not prepare a bill with zero amount");
        } else{
        if (Payment_Mode != null && Payment_Mode != ''){
        if (Payment_Mode == 'Bank_Payment'){
        var Confirm_Message = confirm("Are you sure you want to create Bank Payment BILL?");
                if (Confirm_Message == true) {
        document.location = 'Prepare_Bank_Payment_Transaction.php?Section=Reception&Registration_ID=' + Registration_ID;
        }
        } else if (Payment_Mode == 'Mobile_Payemnt'){
        var Confirm_Message = confirm("Are you sure you want to create Mobile eBILL?");
                if (Confirm_Message == true) {
        document.location = "#";
        }
        }
        } else{
        document.getElementById("Payment_Mode").focus();
                document.getElementById("Payment_Mode").style = 'border: 3px solid red';
        }
        }
        }
    </script>

    <!--<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="script.responsive.js"></script>-->

    <script>
        $(document).ready(function(){
        $("#Display_Number_Of_Patients").dialog({ autoOpen: false, width:'30%', height:500, title:'DOCTORS QUEUE', modal: true});
                //      $('.ui-dialog-titlebar-close').click(function(){
                //	 	Get_Transaction_List();
                //      });

        });</script>

    <script>
        $(document).ready(function(){
        $("#ePayment_Window").dialog({ autoOpen: false, width:'55%', height:250, title:'Create ePayment Bill', modal: true});
        $("#Pre_Paid_Dialog").dialog({ autoOpen: false, width:'40%', height:140, title:'Create Bill', modal: true});
        });</script>

    <script>
        $(document).ready(function(){
            $("#List_OF_Doctors").dialog({ autoOpen: false, width:'30%', height:350, title:'DOCTORS LIST', modal: true});
            $("#Change_Billing_Type_Alert").dialog({autoOpen: false, width: '60%', height: 150, title: 'TRANSACTION WARNING!', modal: true});
        });</script>

    <script>
     $(document).ready(function(){
        $("#Get_Selected_Item_Warning").dialog({ autoOpen: false, width:'30%', height:150, title:'eHMS 2.0 ~ Information!', modal: true});
                $("select").select2();
        });
        function create_epayment_mobile_card_bill(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var Check_In_Type='Doctor Room';
            if(confirm("Are You sure you want to go to Mobile/Card Payment")){
                $.ajax({
                    type:'GET',
                    url:'ajax_create_epayment_mobile_card_bill_from_item_reception.php',
                    data:{Registration_ID:Registration_ID,Check_In_Type:Check_In_Type},
                    success:function(data){
                        if(data=="success"){
                            document.location = "./patient_sent_to_cashier_payment.php?itemfrom=reception&Registration_ID=<?php echo $Registration_ID ?>&Check_In_Type="+Check_In_Type+"&Payment_Cache_ID";

                        }else{
                            alert(data+"Process Fail...Please Try Again");
                        }
                    }
                });
            }
        }
        function send_cashier_new_payment_method(){
            var Registration_ID='<?php echo $Registration_ID; ?>';
            var Check_In_Type='Doctor Room';
            if(confirm("Are You sure you want to send to cashier?")){
                $.ajax({
                    type:'GET',
                    url:'ajax_create_epayment_mobile_card_bill_from_item_reception.php',
                    data:{Registration_ID:Registration_ID,Check_In_Type:Check_In_Type},
                    success:function(data){
                        if(data=="success"){
                          alert("Process Successfully");
                          document.location="searchvisitorsoutpatientlist.php";
                        }else{
                            alert(data+"Process Fail...Please Try Again");
                        }
                    }
                });
            }
        }
    </script>

    <!-- end of pop up window -->
    <?php
    include("./includes/footer.php");
    ?>
