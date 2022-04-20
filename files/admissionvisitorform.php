<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
?>
<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,
                                    pr. Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                                        Member_Number,Member_Card_Expire_Date, sp.Claim_Number_Status,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Region = $row['Region'];
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
            $Claim_Number_Status = strtolower($row['Claim_Number_Status']);
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }
        /* $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->m." Months";
          }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */

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
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Tribe = '';
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
        $Claim_Number_Status = '';
    }
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
    $Claim_Number_Status = '';
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        if (isset($_GET['location']) && $_GET['location'] == 'registered') {
            echo " <a href='registeredtoadmit.php' class='art-button-green'>
                        BACK
                    </a>";
        } else {
            echo " <a href='admissionnewpatient.php?AdmissionNewPatient=AdmissionNewPatientThisPage' class='art-button-green'>
                        BACK
                    </a>";
        }
    }
}
?>

<br/><br/>
<fieldset>
    <legend align="right"><b>PATIENT INITIAL ADMISSION</b></legend><br/>
    <table width="100%">
        <tr>
            <td style='text-align: right' width="12%">Patient Name</td>
            <td><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo $Patient_Name; ?>'></td>
            <td style='text-align: right'>Visit Date</td>
            <td><input type='text' name='Visit_Date' readonly='readonly' id='dateee' value='<?php echo $Today; ?>'></td> 
        </tr> 
        <tr>
            <td style='text-align: right'>Gender</td>
            <td><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'></td>
            <td style='text-align: right'>Sponsor Name</td>
            <td><input type='text' name='Sponsor_Name' id='Sponsor_Name' disabled='disabled' value='<?php echo $Guarantor_Name; ?>'></td>
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
        <?php
        if (strtolower($Guarantor_Name) != 'cash') {
            ?>
            <tr>
                <td style='text-align: right'>Claim Form Number</td>
                <td><input type='text' name='claim_form_number'  id='claim_form_number'  required></td>

            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td style='text-align: right'>Claim Form Number</td>
                <td><input type='text' name='claim_form_number' readonly id='claim_form_number'></td>

            </tr>
            <?php
        }
        ?>
        <tr>
            <td width="12%" style="text-align:right;">Patient Type</td>
            <td colspan="3">
                <select  name="ToBeAdmitted" id="ToBeAdmitted" >
                    <!--<option selected="selected" value="no">OUTPATIENT</option>-->
                    <option value="yes">INPATIENT</option>
                </select>
            </td>
        </tr>
        <tr style="" width="100%">
            <td style="text-align:right;width:12% ">
                Continuation Sheet
            </td>
            <td colspan="3">
                <textarea name="ToBeAdmittedReason" id="ToBeAdmittedReason" style="resize: none;"></textarea>
            </td>
        </tr>
        <tr>
            <td width="12%" style="text-align:right;">Remarks</td>
            <td colspan="3">
                <textarea name="remark" id="remark" style="resize: none;"></textarea>
            </td>
        </tr>
<!--        <tr>
            <td width="12%" style="text-align:right;">Claim Form Number</td>
            <td colspan="3">
                <input type="text" name="claim_form_number" id="claim_form_number" value="" style="" />
            </td>
        </tr>-->
        <tr>
            <td colspan=4 style="text-align: right;">
                <input type="hidden" id="patient_id" value=""/>
                <input type="button" name="button" class="art-button-green" value="INITIAL ADMIT PATIENT" onclick="check_if_admited_or_in_admit_list()">
            </td>
        </tr>

        </tbody>
    </table>
</fieldset>

<script type="text/javascript">
//    function Admit_Patient(Patient_Name) {
//        alert("Are you sure you want to admit " + Patient_Name);
//    }
</script>

<script>
    function check_if_admited_or_in_admit_list(){
       var Registration_ID = '<?php echo $Registration_ID; ?>';
       $.ajax({
           type:'GET',
           url:'check_if_admited_or_in_admit_list.php',
           data:{Registration_ID:Registration_ID},
           success:function (data){
               console.log(data)
               if(data=="admit_list"){
                   alert("The Patient Is on PATIENTS TO ADMIT LIST \n Please select patient from the list and then admit to your Ward");
               }else if(data=="free_to_admit"){
                   Admit_Patient();
               }else{
                   alert("Patient Arleady Admitted to ~~"+data.toUpperCase());
               }
           },
           error:function(x,y,z){
              console.log(z); 
           }
       });
    }
    function Admit_Patient() {
        var myobj, data;
            
        
        
        var ToBeAdmitted = document.getElementById("ToBeAdmitted").value;
        var ToBeAdmittedReason = document.getElementById("ToBeAdmittedReason").value;
        var remark = document.getElementById("remark").value;
        var claim_form_number = document.getElementById("claim_form_number").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Claim_Number_Status = '<?php echo $Claim_Number_Status; ?>';
<?php
if (strtolower($Claim_Number_Status) == 'mandatory') {
    ?>
            if (claim_form_number == '') {
                alert('Please enter claim form number');
                $('#claim_form_number').focus().css("border", "1px solid red");
                exit;
            }
    <?php
}
?>


        if (confirm("Are you sure you want to add a patient to admission list?")) {

//            var claim_form_number = document.getElementById("claim_form_number").value;

            if (window.XMLHttpRequest) {
                myobj = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myobj = new ActiveXObject('Micrsoft.XMLHTTP');
                myobj.overrideMimeType('text/xml');
            }

            myobj.onreadystatechange = function () {
                data = myobj.responseText;
                if (myobj.readyState == 4) {
                    // alert(data);
                    if (data == '1') {
                        alert("Patient ready to be admitted!");
                        //window.location = 'admissionworkspage.php?section=Admission&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage';
                        window.location = "admit.php?Registration_ID="+Registration_ID;
                    } else {
                        alert(data);
                        //  alert('An error has occured try again or contact system administrator');
                    }
                    // document.getElementById('Patients_Fieldset_List').innerHTML = data6;
                }
            }; //specify name of function that will handle server response........

            myobj.open('GET', 'emergencyAdmission.php?ToBeAdmitted=' + ToBeAdmitted + '&ToBeAdmittedReason=' + ToBeAdmittedReason + '&remark=' + remark + '&Registration_ID=' + Registration_ID + '&claim_form_number=' + claim_form_number + '&is_new_patient=1', true);
            myobj.send();
        }
    }
</script>

<?php
include("./includes/footer.php");
?>


