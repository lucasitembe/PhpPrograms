<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
// session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
    

?>
<?php

    if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
    if(isset($_GET['from_consulted'])){
    $from_consulted=$_GET['from_consulted'];
}
    if(isset($_GET['Patient_Payment_ID'])){
  $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
}
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
     $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
    
}
?>
  <a href='index.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
<br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>Physiotherapy Treatment</b></legend>
        <center><table width = 60%>
<!--				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='Cancer_Registration.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                        <button style='width: 100%; height: 100%'>
                            REGISTRATION
                        </button>
                    </a>
  
                </td>
				</tr>-->
<!--                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                      
                        <a href='list_of_cancer_type.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                               TYPES OF CANCER
                            </button>
                        </a>
                    </td>
                </tr>-->
<!--                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <a href='cancer_setup.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                              CANCER SETUP
                            </button>
                        </a>
                    </td>
                </tr>-->
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              <!--radiation_setup.php-->
                        <a href='physiotherapy_patient_list.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                              Mobility Assessment
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              <!--radiotherapy_patient_list-->
              <!--radiotherapy_parameter_patientlist.php-->
              <!--radiation_parameter_calculation.php-->
                        <a href='physiotherapy_patient_list_knee.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                              Knee Arthoscopy Assessment
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <!--treatment_delivery.php-->
                        <!--treatment_devery_patientlist.php-->
                        <!--treatment_delivery.php-->
                        <a href='respiratory_assessment_patient_list.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                             Respiratory Assessment
                            </button>
                        </a>
                    </td>
             
                <tr>
                   <td style='text-align: center; height: 40px; width: 33%;'>
              
                        <!--treatment_delivery.php-->
                        <!--treatment_devery_patientlist.php-->
                        <!--treatment_delivery.php-->
                        <a href='elderly_care_assessment_patient_list.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?php echo $from_consulted; ?>'>
                            <button style='width: 100%; height: 100%'>
                             Elderly Care Assessment
                            </button>
                        </a>
                    </td>
                </tr>
		  </table>
        </center>
</fieldset><br/>


<?php
    include("./includes/footer.php");
?>
