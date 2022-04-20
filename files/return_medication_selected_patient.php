<?php 
    include './includes/header.php';
    include 'pharmacy-repo/interface.php';
    $Interface = new PharmacyInterface();

    $patientDetails =$Interface->getPatientDetailsPharmacy($_GET['Payment_Cache_No']);

    $age = floor((strtotime(date('Y-m-d')) - strtotime($patientDetails[0]['Date_Of_Birth'])) / 31556926) . " Years";
    $date1 = new DateTime($Today);
    $date2 = new DateTime($patientDetails[0]['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
?>
<a href="list_of_inpatient_patient.php" class="art-button-green">BACK</a>

<fieldset style="height: 610px;">
    <legend>RETURN ITEM TO PHARMACY</legend>

    <table width='100%' style="background-color: #fff;">
        <tr>
            <td style="padding: 6px;" width='16%'><b>PATIENT NAME</b></td>
            <td style="padding: 6px;" width='16%'><?=$patientDetails[0]['Patient_Name']?></td>
            <td style="padding: 6px;" width='16%'><b>PATIENT No</b></td>
            <td style="padding: 6px;" width='16%'><?=$patientDetails[0]['Registration_ID']?></td>
            <td style="padding: 6px;" width='16%'><b>GENDER</b></td>
            <td style="padding: 6px;" width='16%'>MALE</td>
            
        </tr>

        <tr>
            <td style="padding: 6px;"><b>AGE</b></td>
            <td style="padding: 6px;"><?=$age?></td>
            <td style="padding: 6px;"><b>SPONSOR</b></td>
            <td style="padding: 6px;"><?=$patientDetails[0]['Guarantor_Name']?></td>
            <td style="padding: 6px;"><b>Phone Number</b></td>
            <td style="padding: 6px;"><?=$patientDetails[0]['Phone_Number']?></td>
        </tr>
    </table>

    <br>

    <div style="height: 420px;overflow-y: scroll;overflow-x: hidden">
        <table width='100%'>
            <thead>
                <tr style="background-color: #ddd;">
                    <td style="padding: 6px;" width='3%'><center>S/No.</center></td>
                    <td style="padding: 6px;" >ITEM NAME</td>
                    <td style="padding: 6px;" width='11.5%'>DOSAGE</td>
                    <td style="padding: 6px;text-align:center" width='11%'>DISPENSED QUANTITY</td>
                    <td style="padding: 6px;text-align:right" width='11%'>PRICE</td>
                    <td style="padding: 6px;text-align:right" width='11%'>SUBTOTAL</td>
                    <td style="padding: 6px;" width='11%'>DISPENSED DATE</td>
                    <td style="padding: 6px;text-align:center" width='11%'>RETURN QUANTITY</td>
                </tr>
            </thead>

            <tbody id='view'></tbody>
        </table>
    </div>
    <br>
    <table width='100%' id="action-table">
        <tr><td><a href="#" class="art-button-green pull-right" onclick="returnMedication()">PROCESS RETURN MEDICATION</a></td></tr>
    </table>
</fieldset>

<script>
    $(document).ready(() => {
        loadItems();
    })

    function loadItems(){
        $.get('pharmacy-repo/common.php',{
            load_items_to_return:'load_items_to_return',
            Payment_Cache_ID : '<?=$_GET['Payment_Cache_No']?>'
        },(response) => {
            $('#view').html(response);
        });
    }

    function validateReturnedQty(dispensed_qty,Payment_Item_Cache_List_ID){
        var return_qty = $('#Return_Qty'+Payment_Item_Cache_List_ID).val();
        if(return_qty > dispensed_qty){
            $('#Return_Qty'+Payment_Item_Cache_List_ID).css('border','2px solid red');
            $('#action-table').css('display','none');
        }else{
            $('#Return_Qty'+Payment_Item_Cache_List_ID).css('border','2px solid #eee');
            $('#action-table').css('display','inline-table');
        }
    }

    function returnMedication(){
        var return_medication = [];
        var Registration_ID = '<?=$_GET['Reg_No']?>';
        var Payment_Cache_ID = '<?=$_GET['Payment_Cache_No']?>';
        var Sub_Department_ID = '<?=$_SESSION['Pharmacy_ID']?>';

        $(".returned_quantity_cls_hd").each(function(){
            var Payment_Item_Cache_List_ID=$(this).val();
            var Item_ID = $('#Item'+Payment_Item_Cache_List_ID).val();
            var Patient_Payment_ID = $('#Patient_Payment_ID'+Payment_Item_Cache_List_ID).val();
            var Previous_qty = $('#Previous_qty'+Payment_Item_Cache_List_ID).val();
            var Return_Qty = $('#Return_Qty'+Payment_Item_Cache_List_ID).val();
            if(Return_Qty != ""){
                return_medication.push(Payment_Item_Cache_List_ID+'Join'+Item_ID+'Join'+Patient_Payment_ID+'Join'+Previous_qty+'Join'+Return_Qty);
            }
        });

        if(confirm('Are you sure you want return Written Quantity ?')){
            if(return_medication.length > 0){
                $.post('pharmacy-repo/common.php',{
                    Payment_Cache_ID:Payment_Cache_ID,
                    return_medication:return_medication,
                    Employee_ID:'<?=$_SESSION['userinfo']['Employee_ID']?>',
                    Sub_Department_ID:'<?=$_SESSION['Pharmacy_ID']?>',
                    Registration_ID:Registration_ID,
                    return_medication_pharmacy:'return_medication_pharmacy'
                },(response) => {
                    loadItems();
                });
            }else{
                alert('No Item with quantity to return');
            }
        }
    }
</script>

<?php include '/includes/footer.php'; ?>