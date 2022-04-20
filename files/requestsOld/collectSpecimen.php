<?php
session_start();
include("../includes/connection.php");
if (isset($_POST['patientId'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $patient_id = $_POST['patientId'];
    $payment_id = $_POST['paymentId'];
    $Patient_Payment_Item_List_ID = $_POST['paymentListItem'];
    $item_id = $_POST['id'];
    $item_list_cache_id = $_POST['val'];
}

if (isset($_POST['statusFrom'])) {
    if ($_POST['statusFrom'] == 'payment') {

        $sql = mysql_query("select 'payment' as Status_From,i.Product_Name as item_name,i.Item_ID as item_id,
                  'Direct From Payment' as Employee,pr.* \n"
                . "from tbl_patient_payment_item_list as il \n"
                . "join  tbl_patient_payments as pc on pc.Patient_Payment_ID = il.Patient_Payment_ID "
                . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID \n"
                . "join tbl_items as i on i.Item_ID =il.Item_ID \n"
                . "where il.Check_In_Type='Laboratory' and il.Patient_Payment_ID ='$payment_id'
         and i.item_id='$item_id' and pc.Receipt_Date='" . $_POST['rquiredDate'] . "' AND Payment_Item_Cache_List_ID='" . $item_list_cache_id . "'");
    } else if ($_POST['statusFrom'] == 'cache') {

        $sql = mysql_query("select i.Product_Name as item_name,i.Item_ID as item_id,e.Employee_Name as Employee,pr.* \n"
                . "from tbl_payment_cache as pc\n"
                . "join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID\n"
                . "Join tbl_employee as e on e.Employee_ID =pc.Employee_ID\n"
                . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID\n"
                . "join tbl_items as i on i.Item_ID =pl.Item_ID\n"
                . "where pl.Check_In_Type='Laboratory' and pr.Registration_ID ='$patient_id' and i.Item_ID ='" . $item_id . "' AND pl.Status !='Sample Collected' AND Payment_Item_Cache_List_ID='" . $item_list_cache_id . "'");
    }
}
//echo $patient_id;
//select for display the name of the patient
$sql1 = mysql_query("SELECT * FROM tbl_patient_registration where Registration_ID='$patient_id'");
$disp = mysql_fetch_array($sql1);
?>

<fieldset style="margin-top:5px;min-height:300px;">
    <center>
        <table border="1"  style="width:100%;" class="hiv_table" bgcolor="white">
            <tr>
                <td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;">
            <center>


                <?php echo $disp['Patient_Name'] . " ," . $disp['Gender'] . "," . $disp['Date_Of_Birth'] . " years of age,"; ?>
            </center>
            </td>
            </tr>
        </table>


        <?php
        $i = 1;
        while ($rows = mysql_fetch_array($sql)) {
            ?>
            <table border="1"  style="width:100%;" class="hiv_table" bgcolor="white">
                <tr>
                    <th colspan="4" style="width:70%;text-align:left;">TEST:<?php echo $rows['item_name']; ?> </th>
                </tr>
                <?php
                $sql1 = mysql_query("SELECT * FROM tbl_tests_specimen join tbl_laboratory_specimen on ref_specimen_ID=Specimen_ID WHERE tests_item_ID='" . $item_id . "' ");
                ?>

                <tr>
                    <td>
                        <table  border="0"  style="width:100%;margin-top:2px;border:1px solid #ccc;" class="hiv_table">
                            <tr bgcolor="#eee">
                                <td style="color:blue;border:1px solid #ccc;">Print Barcode</td>
                                <td  style="color:blue;border:1px solid #ccc;width:17%">Manual Specimen ID</td>
                                <td style="color:blue;border:1px solid #ccc;width:20%">Specimen ID</td>
                                <td style="color:blue;border:1px solid #ccc;width:20%">Specimen Name</td>
                                <td style="color:blue;border:1px solid #ccc;width:10%">Container</td>
                                <td style="color:blue;border:1px solid #ccc;">Accept</td>
                                <td style="width:10%;color:blue;border:1px solid #ccc;">
                                    Reject
                                </td>
                            </tr>

                            <?php
                            $num = mysql_num_rows($sql1);
                            
                           // die("SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $_POST['val'] . "' AND Specimen_ID='" . $rows1['ref_specimen_ID'] . "'");
                            
                            if ($num > 0) {
                                while ($rows1 = mysql_fetch_array($sql1)) {
                                    $barcode = mysql_query("SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $_POST['val'] . "' AND Specimen_ID='" . $rows1['ref_specimen_ID'] . "'");
                                    $checkCollectionStatus = mysql_fetch_assoc($barcode);
                                   // if ($checkCollectionStatus['collection_Status'] !== 'Rejected') {
                                        if (isset($_POST['statusFrom']))
                                            
                                            ?>

                                        <tr>
                                            <?php
                                            if ($_POST['patientId'] != '') {
                                                $patient_id = $_POST['patientId'];
                                            } else {
                                                $patient_id = '';
                                            }

                                            $Laboratory_Test_specimen_ID = $rows1['ref_specimen_ID'];
                                            $CheckAvailability = "SELECT * FROM tbl_specimen_results WHERE payment_item_ID='" . $_POST['val'] . "' AND Specimen_ID='" . $Laboratory_Test_specimen_ID . "' AND collection_Status='collected'";
//                                            echo $CheckAvailability;
                                            $result = mysql_query($CheckAvailability);
                                            $num = mysql_num_rows($result);
                                            $result_ID = mysql_fetch_assoc($result);
                                            $disalbed = 'style="opacity:0.3" disabled="disabled"';
                                            //echo $CheckAvailability;exit;
                                            ?>
                                            <td width="90px;">
                                                 <?php
                                                if ($num > 0) {
                                                    $disalbed = 'style=""';
                                                }

                                                 //echo $checkCollectionStatus['collection_Status'];
                                                ?>
                                                <input type='button'  <?php echo $disalbed; ?> name='Print_Barcode' id="Print_Barcode_<?php echo $rows1['ref_specimen_ID'] ?>" value='BARCODE' onclick='Print_Barcode_Payment()' class='Print_Barcode art-button'>                                        </td>
                                            <td style="border:1px solid #ccc;">
                                                <?php
                                                // $barcodeTxt='a';
                                                // $row7 = mysql_fetch_assoc($barcode);
                                                // $barcodeTxt=$row7['BarCode'];
                                                ?>
                                                <input style="text-align:left" type="text" value="<?php echo $checkCollectionStatus['BarCode'] //$barcodeTxt  ?>" class="manualBarcode<?php echo $rows1['ref_specimen_ID']; ?>" id="<?php echo $rows1['ref_specimen_ID']; ?>"> 
                                            </td>
                                            <td style="border:1px solid #ccc;" >
                                                <?php
                                                if (isset($status['Patient_Payment_Test_Specimen_ID'])) {
                                                    echo "PDPSN-" . $status['Patient_Payment_Test_Specimen_ID'];
                                                } else if (isset($status['Patient_Cache_Test_Specimen_ID'])) {
                                                    echo "PDSN-" . $status['Patient_Cache_Test_Specimen_ID'];
                                                }
                                                ?>
                                            </td>

                                            <td style="border:1px solid #ccc;">
                                                <?php echo $rows1['Specimen_Name']; ?>
                                            </td>
                                            <td style="border:1px solid #ccc;">
                                                <?php echo $rows1['Sample_Container']; ?>
                                            </td>
                                            <td style="border:1px solid #ccc;">

                                                <?php
                                                if ($num > 0) {
//                                                    if($result_ID['Rejection_Status']=='Rejected'){
//                                                    echo '<input type="checkbox" value="' . $_POST['val'] . '" id="' . $Laboratory_Test_specimen_ID . '" class="specimenCollectChecked specimenCollect_' . $Laboratory_Test_specimen_ID . '">';
//  
//                                                    }else{
                                                    echo '<input type="checkbox" value="' . $_POST['val'] . '" id="' . $Laboratory_Test_specimen_ID . '" class="specimenCollectChecked specimenCollect_' . $Laboratory_Test_specimen_ID . '" checked="true">';

//                                                    }
                                                } else {
                                                    echo '<input type="checkbox" value="' . $_POST['val'] . '" id="' . $Laboratory_Test_specimen_ID . '" class="specimenCollect specimenCollect_' . $Laboratory_Test_specimen_ID . '">';
                                                }
                                                ?>
                                                <input type="text" id="patient_id" value="<?php echo $patient_id; ?>" style="display:none">
                                                <input type="text" id="payment_id" value="<?php echo $payment_id; ?>" style="display:none">
                                                <input type="text" id="Patient_Payment_Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID; ?>" style="display:none">
                                                <input type="text" id="Patient_Payment_Test_ID" value="<?php echo $Patient_Payment_Test_ID; ?>" style="display:none">
                                            </td>
                                            <td style="width:50%;">
                                                <?php
                                                if ($checkCollectionStatus['Rejection_Status'] == 'Rejected') {
                                                    ?>
                                                <input style="float:left;" type="checkbox" id="<?php echo $Laboratory_Test_specimen_ID; ?>" value="<?php echo $_POST['val']; ?>" class="rejectSpecimen specimenCollect_<?php echo $Laboratory_Test_specimen_ID; ?>" checked="true">
                                                <?php
                                                 } else {
                                                     ?>
                                                      <input style="float:left;" type="checkbox" id="<?php echo $Laboratory_Test_specimen_ID; ?>" value="<?php echo $_POST['val']; ?>" class="rejectSpecimen specimenCollect_<?php echo $Laboratory_Test_specimen_ID; ?>">
                                                <?php 
                                                
                                                 }
                                                ?>
                                               
                                                <select class="rejectionreason" style="display:none" id="reject_<?php echo $Laboratory_Test_specimen_ID; ?>" >
                                                    <option value="">
                                                        Choose reason for rejection</option>
                                                    <option value="Unlabelled specimen">Unlabelled specimen</option>
                                                    <option value="Wrong container">Wrong container</option>
                                                    <option value="Insufficient in volume">Insufficient in volume</option>

                                                    <option value="Insufficient patient information">Insufficient patient information</option>

                                                    <option value="Incomplete labelled specimen">Incomplete labelled specimen

                                                    </option>

                                                    <option value="Haemolysed specimen">
                                                        Haemolysed specimen
                                                    </option>

                                                    <option value="Wrong specimen">
                                                        Wrong specimen
                                                    </option>

                                                    <option value="Prolonged time before specimen">
                                                        Prolonged time before specimen
                                                    </option>

                                                    <option value="Specimen identification Discrepacy">
                                                        Specimen identification Discrepacy
                                                    </option>

                                                    <option value="Others">
                                                        Others
                                                    </option>
                                                </select>
                                                <button style="float:left;display:none" type="button" id="button_reject_<?php echo $Laboratory_Test_specimen_ID; ?>" onclick="rejectSpecemen('reject_<?php echo $Laboratory_Test_specimen_ID; ?>', '<?php echo $Laboratory_Test_specimen_ID; ?>', '<?php echo $result_ID['result_ID'];
                                                ?>', '<?php echo $patient_id; ?>', '<?php echo $item_id; ?>', '<?php echo $item_list_cache_id; ?>')">Save</button>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                   // }
                                }
                            } else {
                                echo 'No specimen assigned';
                            }
                            ?>
                            <tr>
                                <td colspan="7" style="padding:10px;">
                            <center>
                                <span style="color:red;font-size:20px;">
                                    <?php
                                    if (isset($_POST['statusFrom']))
                                        if ($_POST['statusFrom'] == 'payment') {
                                            echo"KEY:PDPSN Stand For Patient from Direct to Payment Specimen Number";
                                        } elseif ($_POST['statusFrom'] == 'cache') {
                                            echo"KEY:PDSN Stand For Patient from Doctor  Specimen Number";
                                        }
                                    ?>

                                </span>
                            </center>
                    </td>
                </tr>

            </table>
            </td>
            </tr>
            <table>
                <tr>
                    <?php
                    echo "<button type='button' onclick='reloadPage()' class='art-button-green'>Done</button>";
                    ?>
                </tr>
            </table>
            <?php
            $i++;
            echo "</table>";
        }
        ?>                               
        <script>
            function Print_Barcode_Payment() {
                // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                var winClose = popupwindow('barcode_specimen/BCGcode39.php?Registration_ID=<?php echo $patient_id; ?>&Item_ID=<?php
        echo $item_id;
        ?>&payment_Cache_Id=<?php echo $_POST['val']; ?>&LaboratoryTestSpecimenBacodedThisPage=ThisPage','Print Barcode', 330, 230);
                //winClose.close();
                //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

            }

            function popupwindow(url, title, w, h) {
                var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                var wTop = window.screenTop ? window.screenTop : window.screenY;

                var left = wLeft + (window.innerWidth / 2) - (w / 2);
                var top = wTop + (window.innerHeight / 2) - (h / 2);
                var mypopupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                return mypopupWindow;
            }

        </script>
        <script type="text/javascript">
            $(".specimenCollect").click(function () {
            
                var current = $(this);
                var payVal = $(this).val();
                var id = $(this).attr('id');
                var barcode_id = 'Print_Barcode_' + $(this).attr('id');
                var classSpec = $(current).attr('class');
                var manualBarcode = $('.manualBarcode' + id).val();
                var nospace = manualBarcode.trim();
                var dataSpec = classSpec.split(' ');
                //alert(barcode_id);exit();

                if ($(this).is(":checked")) {

//                 if(nospace.length>0){
//                     
//                 }else{
//                     alert('Fill barcode first to submit this specimen');
//                     $(current).prop( "checked", false );
//                     exit();
//                 }
                    var patient_id = $('#patient_id').val();
                    var payment_id = $('#payment_id').val();
                    var Patient_Payment_Item_List_ID = $('#Patient_Payment_Item_List_ID').val();
                    var Patient_Payment_Test_ID = $('#Patient_Payment_Test_ID').val();
                    $.ajax({
                        type: "POST",
                        url: "insert_value.php",
                        data: 'action=submit&id=' + id + '&patient_id=' + patient_id + '&payment_id=' + payment_id + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Patient_Payment_Test_ID=' + Patient_Payment_Test_ID + '&payVal=' + payVal + '&manualBarcode=' + manualBarcode,
                        success: function () {
                            alert('Great! Specimen is successfully submitted');
                            $('#' + barcode_id).attr('style', '');
                            $('#' + barcode_id).attr('disabled', false);
                        }
                    });

                    $(current).attr('class','specimenCollectChecked' + dataSpec[1].trim());
                    
                } else {
                    //alert('Checked');
                    var checked = $(this);
                    var payVal = $(this).val();
                    var classSpec = $(checked).attr('class');
                    var id = $(this).attr('id');
                    var dataSpec = classSpec.split(' ');

                    if (!$(this).is(":checked")) {
                        if (confirm('Are you sure you want to cancel specimen submission?')) {
                            $.ajax({
                                type: "POST",
                                url: "insert_value.php",
                                data: 'Undocheck=delete&id=' + id + '&payVal=' + payVal,
                                success: function (html) {
                                    // alert(html);
                                    $('.manualBarcode' + id).val('');
                                    $('#' + barcode_id).attr('style', 'opacity:0.3');
                                    $('#' + barcode_id).attr('disabled', 'disabled');
                                }
                            });

                            $(checked).attr('class', 'specimenCollect ' + dataSpec[1].trim());
                        }
                        
                        $(checked).attr("checked", false);
                    }
                }
            });

        </script>
        <script>
            $('.specimenCollectChecked').click(function () {
                var checked = $(this);
                var payVal = $(this).val();
                var classSpec = $(checked).attr('class');
                var id = $(this).attr('id');
                var dataSpec = classSpec.split(' ');

                if (!$(this).is(":checked")) {
                    if (confirm('Are you sure you want to cancel specimen submission?')) {
                        $.ajax({
                            type: "POST",
                            url: "insert_value.php",
                            data: 'Undocheck=delete&id=' + id + '&payVal=' + payVal,
                            success: function (html) {
                                // alert(html);
                                $('.manualBarcode' + id).val('');
                            }
                        });

                        $(checked).attr('class', 'specimenCollect ' + dataSpec[1].trim());
                    }
                    $(checked).attr("checked", false);
                }


            });
        </script>
        <script>
            $('.rejectSpecimen').click(function () {
                var spec_id = $(this).attr("id");
             
                //alert("Clicked "+spec_id);button_reject_
                if ($(this).is(":checked")) {
                    $("#reject_" + spec_id).show();
                    $("#button_reject_" + spec_id).show();
                } else {
                    $("#reject_" + spec_id).val('');
                    $("#button_reject_" + spec_id).hide();
                    $("#reject_" + spec_id).hide();
                }

            });


        </script>
        <script>
            function rejectSpecemen(textAreaLoc, specimentID, result_ID, registration_id, item_id, ilc) {
               // alert(textAreaLoc + ' ' + specimentID + ' ' + result_ID + ' ' + registration_id + ' ' + item_id + ' ' + ilc);
                var reason = $("#" + textAreaLoc).val();
              //  alert(reason);
               if(reason==''){
                   alert('Please select reason for rejection.');
                   exit;
               }
                var check = confirm("You are about to reject this speciment! Continue?");

                if (check) {
                    //alert("Reasons:"+reason+" ID="+specimentID+" payment_id="+0+" result_ID="+result_ID); 

                    $.ajax({
                        type: "POST",
                        url: "insert_value.php",
                        data: 'rejectSpecimen=true&id=' + specimentID + '&payVal=' + ilc + '&reason=' + reason + "&result_ID=" + result_ID,
                        success: function (html) {
                            if (parseInt(html) === 1) {
                                alert('Specimen removed successifully');
                                specimens(registration_id, item_id, ilc);
//                                $("#reject_" + specimentID).val('');
//                                $("#button_reject_" + specimentID).hide();//
//                                $("#reject_" + specimentID).hide();
//                                $(".specimenCollect_" + specimentID).hide();
                            } else {
                                alert(html+" Alert an error has occured! Please try again letter");
                            }
                        }
                    });
                }
            }
        </script>
        <script>
            function specimens(registration_id, item_id, ilc) {
                var id = item_id;
                var val = ilc;
                var patientId = registration_id;
                var statusFrom = 'cache';
                $.ajax({
                    type: 'POST',
                    url: 'requests/collectSpecimen.php',
                    data: 'action=collect&id=' + id + '&paymentStatus=&trasanction=&patientId=' + patientId + '&paymentId=&paymentListItem=&rquiredDate=&statusFrom=' + statusFrom + '&val=' + val,
                    success: function (html) {
                        //alert(html);
                        $('#vewmeDiv').html(html);
                    }
                });
            }
        </script>
        <script>
            $('.ui-dialog-titlebar-close').click(function () {
                reloadPage();
                //var location = $('#refreshspecimenCollection').val();
              
//                if (location != null || location != '') {
                  
               // }

            });

        </script>
        <script>
          function reloadPage(){
               window.location= window.location.href;
          }
        </script>