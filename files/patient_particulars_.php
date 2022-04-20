<?php 
    include './includes/header.php'; 
    include 'common/common.interface.php';    

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Result = new CommonInterface();
    $Patient_Result = $Result->getSinglePatientDetailsForParticularConsultation($_GET['Registration_ID'],$_GET['Patient_Payment_Item_List_ID']);
    $Patient_Age = $Result->getCurrentPatientAge($Patient_Result[0]['Date_Of_Birth']);
    $formatted_price = (int) $Patient_Result[0]['Price'];
    $calculate_amount = (int) $Patient_Result[0]['Price'] * (int) $Patient_Result[0]['Quantity'];
?>

<a href="physiotherapy_opd_patient_list.php?clinic=<?=$_GET['Clinic']?>" class="art-button-green">BACK</a>
<br><br>

<fieldset>
    <table width='100%;background-color:#fff'>
        <legend style="font-weight: 500;">PATIENT DETAILS</legend>
        <tr style="background-color: #fff;">
            <td width='12.5%' style="padding: 8px;font-weight:500;">Patient Name</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Patient_Name']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Sponsor Name</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Sponsor_Name']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Gender</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Gender']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Registered Date</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Registration_Date']?></td>
        </tr>

        <tr style="background-color: #fff;">
            <td width='12.5%' style="padding: 8px;font-weight:500">Member Number</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Member_Number']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Occupation</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Occupation']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">D.O.B</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Date_Of_Birth']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Folio Number</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Folio_Number']?></td>
        </tr>

        <tr style="background-color: #fff;">
            <td width='12.5%' style="padding: 8px;font-weight:500">Bill Type</td>
            <td width='12.5%' style="padding: 8px;"><?=$_GET['Patient_Type']." ".ucfirst($Patient_Result[0]['payment_method'])?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Phone Number</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Phone_Number']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Registration Number</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Registration_ID']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Patient Age</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Age?></td>
        </tr>

        <tr style="background-color: #fff;">
            <td width='12.5%' style="padding: 8px;font-weight:500">Region</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Region']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Ward</td>
            <td width='12.5%' style="padding: 8px;"><?=($Patient_Result[0]['Ward'] == "") ? "Not Set" : $Patient_Result[0]['Ward'] ;?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Consulting/Doctor</td>
            <td width='12.5%' style="padding: 8px;"><?=$_SESSION['userinfo']['Employee_Name']?></td>
            <td width='12.5%' style="padding: 8px;font-weight:500">Receipt No</td>
            <td width='12.5%' style="padding: 8px;"><?=$Patient_Result[0]['Patient_Payment_ID']?></td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <table width='100%'>
        <tr style="background-color: #eee;">
            <td style="padding: 8px;font-weight:500;text-align:center">S/N</td>
            <td style="padding: 8px;font-weight:500">Item Code</td>
            <td style="padding: 8px;font-weight:500">Item Description</td>
            <td style="padding: 8px;font-weight:500;text-align:end">Price</td>
            <td style="padding: 8px;font-weight:500;text-align:end">Discount</td>
            <td style="padding: 8px;font-weight:500;text-align:center">Quantity</td>
            <td style="padding: 8px;font-weight:500;text-align:end">Amount</td>
        </tr>

        <tr style="background-color: #fff;">
            <td style="padding: 8px;text-align:center" width='5%'>1</td>
            <td style="padding: 8px" width='7.5%'><?=$Patient_Result[0]['Product_Code']?></td>
            <td style="padding: 8px" ><?=$Patient_Result[0]['Product_Name']?></td>
            <td style="padding: 8px;text-align:end" width='12.5%'><?=number_format($formatted_price,2)?></td>
            <td style="padding: 8px;text-align:end" width='12.5%'><?=$Patient_Result[0]['Discount']?></td>
            <td style="padding: 8px;text-align:center" width='12.5%'><?=$Patient_Result[0]['Quantity']?></td>
            <td style="padding: 8px;text-align:end" width='12.5%'><?=number_format($calculate_amount,2)?></td>
        </tr>
    </table>
</fieldset>

<fieldset style="text-align: center;padding:10px">
    <a href="template_redirection.php?clinic=<?=$_GET['Clinic']?>&Patient_Payment_Item_List_ID=<?=$_GET['Patient_Payment_Item_List_ID']?>&Registration_ID=<?=$_GET['Registration_ID']?>&Patient_Type=Outpatient" class="art-button-green">CLINICAL NOTES</a>
    <a href="psychiatric_assessment_.php?clinic=<?=$_GET['Clinic']?>&Patient_Payment_Item_List_ID=<?=$_GET['Patient_Payment_Item_List_ID']?>&Registration_ID=<?=$_GET['Registration_ID']?>&Patient_Type=Outpatient" class="art-button-green">PSYCHIATRY ASSESSMENT CLINICAL NOTES</a>
</fieldset>

<?php include './includes/footer.php'; ?>