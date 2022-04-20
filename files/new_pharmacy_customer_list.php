<?php 
    include './includes/header.php';
    include 'pharmacy-repo/interface.php';

    $Interface = new PharmacyInterface();
    $Patient_Details = $Interface->fetchOutpatientList_("","","");
    $count = 1;
?>
<a href="pharmacyworkspage_new.php" class="art-button-green">BACK</a>
<br><br>

<fieldset>
    <legend style="font-weight: 500;">OUTPATIENT PATIENT LIST</legend>
    <table width='100%'>
        <tr>
            <td width='33.3%'><input type="text" style="padding: 8.5px;text-align:center" onkeyup="searchPatient()" id="patient_name" placeholder="Enter Patient Name"></td>
            <td width='33.3%'><input type="text" style="padding: 8.5px;text-align:center" onkeyup="searchPatient()" id="patient_number" placeholder="Enter Patient Number"></td>
            <td width='33.3%'><input type="text" style="padding: 8.5px;text-align:center" onkeyup="searchPatient()" id="patient_patient_number" placeholder="Enter Patient Phone Number"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 500px;overflow-y:scroll">
    <table width='100%'>
        <tr style="background-color: #ddd;font-weight:500">
            <td width='5%' style="padding: 8px;"><center>S/N</center></td>
            <td width='15.8%' style="padding: 8px;">PATIENT NAME</td>
            <td width='15.8%' style="padding: 8px;">PATIENT NUMBER</td>
            <td width='15.8%' style="padding: 8px;">PATIENT SPONSOR</td>
            <td width='15.8%' style="padding: 8px;">AGE</td>
            <td width='15.8%' style="padding: 8px;">GENDER</td>
            <td width='15.8%' style="padding: 8px;">PHONE NUMBER</td>
        </tr>

        <tbody id='displayPatientList'>
            <?php foreach($Patient_Details as $Patient) :  ?>
                <?php $Age = $Interface->getCurrentPatientAge($Patient['Date_Of_Birth']); ?>
                <tr style="background-color: #fff;">
                    <td width='5%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><center><?= $count++ ?></center></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= ucwords($Patient['Patient_Name']) ?></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= $Patient['Registration_ID'] ?></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= $Patient['Guarantor_Name']?></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= $Age ?></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= $Patient['Gender'] ?></a></td>
                    <td width='15.8%' style="padding: 8px;"><a href='new_pharmacy_othersworks_page.php?Registration_ID=<?=$Patient['Registration_ID']?>'><?= $Patient['Phone_Number'] ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<script>
    function searchPatient(){
        var patient_name = $('#patient_name').val();
        var patient_number = $('#patient_number').val();
        var patient_patient_number = $('#patient_patient_number').val();

        $.get('pharmacy-repo/common.php',{patient_name:patient_name,patient_number:patient_number,patient_patient_number:patient_patient_number,load_outpatient_customer:'load_outpatient_customer'},(response) => {
            $('#displayPatientList').html(response);
        });
    }
</script>

<?php include './includes/footer.php'; ?>