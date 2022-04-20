<?php
include("./includes/header.php");
include("./includes/connection.php");
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['Patient_Payment_Item_List_ID'])){
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}
?>
    <!--START HERE-->
<?php
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}


$Registration_ID='';
?>
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
        ?>
        <!--Script to display patient optional photo-->
        <!--PATIENT PHOTO SCRIPT START-->

        <script>
            function displayPatientPhoto(){
                document.getElementById('photo').onclick = function(){
                    if(document.getElementById('photo').checked){
                        //use css style to display the photo
                        document.getElementById("PatientPhoto").style.display = "block";
                    }else{
                        document.getElementById("PatientPhoto").style.display = "none";
                    }
                };
                //hide on initial page load
                document.getElementById("PatientPhoto").style.display = "none";
            }

            window.onload = function(){
                displayPatientPhoto();
            };
        </script>


        <!--PATIENT PHOTO SCRIPT END-->
        <script type="text/javascript">
            function gotolink(){
                var url = "<?php
		if($Registration_ID!=''){
		    echo "Registration_ID=$Registration_ID&";
		}
		if(isset($_GET['Patient_Payment_ID'])){
		    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		    }
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		    }
		?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
                var patientlist = document.getElementById('patientlist').value;

                if(patientlist=='MY PATIENT LIST'){
                    document.location = "doctorcurrentpatientlist.php?"+url;
                }else if (patientlist=='CLINIC PATIENT LIST') {
                    document.location = "clinicpatientlist.php?"+url;
                }else if (patientlist=='CONSULTED PATIENT LIST') {
                    document.location = "doctorconsultedpatientlist.php?"+url;
                }else{
                    alert("Choose Type Of Patients To View");
                }
            }
        </script>

        <label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
            <select id='patientlist' name='patientlist'>
<!--                <option></option>-->
                <option>
                    MY PATIENT LIST
                </option>
                <option>
                    CLINIC PATIENT LIST
                </option>
                <option>
                    CONSULTED PATIENT LIST
                </option>
            </select>
            <input type='button' value='VIEW' onclick='gotolink()'>
        </label>
        <a href='<?php if($Registration_ID!=''){
            ?>doctorpatientfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
            if(isset($_GET['Patient_Payment_ID'])){
                echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
            }
            if(isset($_GET['Patient_Payment_Item_List_ID'])){
                echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
            }
            ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage<?php
        }
        ?>' <?php if($Registration_ID==''){ ?> onclick="alert('Choose Patient First !');" <?php } ?> class='art-button-green'>PATIENT FILE</a>
        <a href='patientsignoff.php?<?php
        if($Registration_ID!=''){
            echo "Registration_ID=$Registration_ID&";
        }
        if(isset($_GET['Patient_Payment_ID'])){
            echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
        }
        if(isset($_GET['Patient_Payment_Item_List_ID'])){
            echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            SIGN OFF
        </a>
        
    <?php  } } ?>
	
<?php
if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
        ?>
        <a href='./doctorsworkspage.php?DoctorsWorksPage=DoctorsWorksThisPage' class='art-button-green'>
            BACK
        </a>
    <?php  } } ?>
    <br/><br/>
    <!-- get employee id-->
<?php
if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
?>

    <!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
if(isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])){
    //select the current Patient_Payment_ID to use as a foreign key

    $qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = ".$_GET['Patient_Payment_ID']."
					    and pp.registration_id = '$Registration_ID'";
    $sql_Select_Current_Patient = mysqli_query($conn,$qr);
    $row = mysqli_fetch_array($sql_Select_Current_Patient);
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = $row['Folio_Number'];
    $Claim_Form_Number = $row['Claim_Form_Number'];
    $Billing_Type = $row['Billing_Type'];
    //$Patient_Direction = $row['Patient_Direction'];
    //$Consultant = $row['Consultant'];
}else{
    $Patient_Payment_ID = '';
    $Payment_Date_And_Time = '';
    //$Check_In_Type = $row['Check_In_Type'];
    $Folio_Number = '';
    $Claim_Form_Number = '';
    $Billing_Type = '';
    //$Patient_Direction = '';
    //$Consultant ='';
}
?>
    <!--Getting employee name -->
<?php
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $Employee_Name = 'Unknown Employee';
}
?>

    <script type='text/javascript'>
        function access_Denied(){
            alert("Access Denied");
            document.location = "./index.php";
        }
    </script>
    <script>
        function Search_Patient(){
            var Patient_Name = document.getElementById("Patient_Name").value;
            document.getElementById('Search_Patient').src = "radiologyresults_Iframe.php?Patient_Name="+Patient_Name+" ";
        }
    </script>
    
   
		
        <center>
            <table width="40%" align="center">
                <tr>
                    <td style="text-align: center"><input type="text" name="Patient_Name" id="Patient_Name" style="text-align: center" placeholder='~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' onkeyup="Search_Patient()"  /></td>
                </tr>
            </table>
        </center>

    <fieldset>
		<legend align="center"><b>RADIOLOGY RESULTS</b></legend>
        <center>
            <table width="100%">
                <tr>
                    <td>
                        <iframe id="Search_Patient" src="radiologyresults_Iframe.php?LaboratoryResultsThisPage=ThisPage" width="100%" height="400px"></iframe>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>
	
	<!--Lab Results-->
	<div style="text-align:center;">
        <a href='laboratoryresult.php?<?php
        if($Registration_ID!=''){
            echo "Registration_ID=$Registration_ID&";
        }
        if(isset($_GET['Patient_Payment_ID'])){
            echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
        }
        if(isset($_GET['Patient_Payment_Item_List_ID'])){
            echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            LABORATORY RESULTS
        </a>
        <!--Radiology Results-->
        <a href='radiologyresult.php?<?php
        if($Registration_ID!=''){
            echo "Registration_ID=$Registration_ID&";
        }
        if(isset($_GET['Patient_Payment_ID'])){
            echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
        }
        if(isset($_GET['Patient_Payment_Item_List_ID'])){
            echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
        }
        ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            RADIOLOGY RESULTS
        </a>
		</div>
<?php
include("./includes/footer.php");
?>