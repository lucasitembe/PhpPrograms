<?php
include("./includes/header.php");
include("./includes/connection.php");
//get sub department id
$Sub_Department_ID = '';
$paymentstatus='';
$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

if (isset($_SESSION['Laboratory'])) {
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}
if ($_GET['Status_From'] != '') {
    $Status_From = $_GET['Status_From'];
} else {
    $Status_From = '';
}

if ($_GET['patient_id'] != '') {
    $Registration_ID = $_GET['patient_id'];
} else {
    $Registration_ID = '';
}
if ($_GET['payment_id'] != '') {
    $Patient_Payment_ID = $_GET['payment_id'];
} else {
    $Patient_Payment_ID = '';
}
if ($_GET['Sponsor'] != '') {
    $Sponsor = $_GET['Sponsor'];
} else {
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
}
if ($_GET['subcategory_ID'] != '') {
    $subcategory_ID = $_GET['subcategory_ID'];
} else {
    $subcategory_ID = filter_input(INPUT_GET, 'subcategory_ID');
}
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
//$Sponsor = filter_input(INPUT_GET, 'Sponsor');
//$subcategory_ID = filter_input(INPUT_GET, 'subcategory_ID');

$backFilter = '';
$filter = "AND pc.Payment_Cache_ID='$Patient_Payment_ID'";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "AND pc.Payment_Cache_ID='$Patient_Payment_ID'";
    $backFilter = "?filter=  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if ($subcategory_ID != 'All' && !empty($subcategory_ID)) {
    $filter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
    $backFilter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
}

if ($Sponsor != 'All' && !empty($Sponsor)) {
    $backFilter .=" AND sp.Sponsor_ID=$Sponsor";
}


//die($filter);

$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
// if (isset($_SESSION['userinfo'])) {
//     if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
//         if ($_SESSION['userinfo']['Laboratory_Works'] != 'yes') {
//             header("Location: ./index.php?InvalidPrivilege=yes");
//         }
//     } else {
//         header("Location: ./index.php?InvalidPrivilege=yes");
//     }
// } else {
//     @session_destroy();
//     header("Location: ../index.php?InvalidPrivilege=yes");
// }

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo " <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File()' class='art-button-green' />
      ";
    }
}

$from_to = "";
if(isset($_GET['from_to']) && $_GET['from_to'] == "admission") {
    $from_to = "admission";
}
echo "<a href='searchpatientlaboratorylist.php?from_to=".$from_to."&Date_From=".$Date_From."&Date_To=".$Date_To."' class='art-button-green'>BACK</a>";

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $payment_id = $_GET['payment_id'];
} else {
    $patient_id = 0;
    $payment_id = 0;
}

$query = "select pl.Price,Guarantor_Name,pc.Sponsor_ID,'cache' as Status_From,pc.Payment_Cache_ID,pl.Priority,pc.Billing_Type,i.Product_Name as item_name,i.Item_ID as item_id,e.Employee_Name as Employee,pr.*,pl.Payment_Item_Cache_List_ID as Patient_Payment_Item_List_ID,pl.Status as Status,pl.Transaction_Type as transaction,payment_type,Require_Document_To_Sign_At_receiption "
        . "from tbl_payment_cache as pc \n"
        . "join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID \n"
        . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID \n"
        . "Join tbl_employee as e on e.Employee_ID =pc.Employee_ID \n"
        . "join tbl_items as i on i.Item_ID =pl.Item_ID \n"
        . "JOIN tbl_sponsor sp ON sp.Sponsor_ID=pc.Sponsor_ID "
        . "where pl.Check_In_Type='Laboratory' and (pl.Status='active' or pl.Status='paid') $filter";

$sql = mysqli_query($conn,$query) or die(mysqli_error($conn));
?>

<br /><br />
<table border="1" style="width:100%; color:#fff;" class="hiv_table" bgcolor="#0079AE">
    <tr>
        <?php
        $Today_Date = mysqli_query($conn,"select now() as today");
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $new_Date = date("Y-m-d", strtotime($original_Date));
            $Today = $new_Date;
            $age = '';
        }


        $dob = '';
        $age = '';
        $sql1 = mysqli_query($conn,"SELECT Patient_Name,Gender,Date_Of_Birth,Guarantor_Name FROM tbl_patient_registration pr JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID where Registration_ID='$patient_id'");
        $disp = mysqli_fetch_array($sql1);
        $Date_Of_Birth = $disp['Date_Of_Birth'];

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);

        $diff = $date1->diff($date2);
        $age = $diff->y . " Years ";
        $age.=$diff->m . ' Months ';
        $age.= $diff->d . ' Days ';
        
        ?>
        <td colspan="4" style="width:100%;padding-bottom:10px;padding-top:5px;">
            <center><b
                    style='background:#0079AE;padding: 6px 9px 12px 9px;margin-top: 20%'><?php echo strtoupper($disp['Patient_Name'] . " | " . $disp['Gender'] . " | " . $disp['Guarantor_Name'] . " | " . $age); ?></b>
            </center>
        </td>
    </tr>
</table>


<fieldset style="margin-top:5px;overflow-y:scroll;height:410px;">

    <legend align="right" style="background-color:#006400;color:white;padding:5px;">
        <b>
            PATIENT SPECIMEN COLLECTION
        </b>

    </legend>





    <div id="viewTestSpecimen" style="display: none;">
        <div id="vewmeDiv" style="height:300px"></div>
    </div>
    <center>

        <table border="1" style="width:90%" class="hiv_table" bgcolor="white">

            <tr>
                <th style="width:5px">S/N</th>
                <th style="width:30%;text-align:left;">DESCRIPTION</th>
                <th style="width:10%;text-align:left;">PRICE</th>
                <th style="width:5%">PRIORITY</th>
                <th style="width:15%">PAYMENT STATUS</th>
                <th style="width:10%">Status</th>
                <th style="width:15%">ACTIONS</th>
                <th style="width:10%">INPATIENT BILL</th>
            </tr>

            <?php
            $i = 1;
            $Employee = '';
            $item_id = '';
            $total_price=0;
            while ($rows = mysqli_fetch_array($sql)) {
                $payment_id = $_GET['payment_id'];
                $Sponsor_ID = $rows['Sponsor_ID'];
                $Guarantor_Name = $rows['Guarantor_Name'];
                $Price = $rows['Price'];
                $total_price =$total_price+$Price;
                    $check_number = mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID,ppt.Specimen_Taken as Taken,ppt.Specimen_Number as To_Be_Taken 
                    FROM tbl_patient_cache_test as ppt \n"
                            . " where ppt.Payment_Item_Cache_List_ID='" . $rows['Patient_Payment_Item_List_ID'] . "'");
                    if (mysqli_num_rows($check_number) > 0) {
                        $Status_For_Test = mysqli_fetch_array($check_number);
                        $To_Be_Taken = (int) $Status_For_Test['To_Be_Taken'];
                        $Taken = (int) $Status_For_Test['Taken'];
                    } else {
                        $To_Be_Taken = -1;
                        $Taken = 0;
                    }
              
                ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td style="width:40%;"><input name="" type="" readonly='readonly' style="width:60%"
                        value="<?php echo $rows['item_name']; ?>"></td>
                <td style="text-align: right"><?= number_format($Price) ?></td>
                <td>

                    <?php
                        if (strtolower($rows['Priority']) == 'normal') {
                            echo "<b style='color:blue'>" . $rows['Priority'] . "</b>";
                        } elseif (strtolower($rows['Priority']) == 'urgent') {
                            echo "<b style='color:red'>" . $rows['Priority'] . "</b>";
                        } elseif (strtolower($rows['Priority']) == 'regular') {
                            echo "<b style='color:black'>" . $rows['Priority'] . "</b>";
                        } else {
                            echo "<b style=''>Undefined</b>";
                        }
                        ?>
                </td>

                <td>
                    <input name="" class="paystatus" id="<?php echo $rows['Patient_Payment_Item_List_ID']; ?>" type=""
                        readonly='readonly' style="width:98%" value="<?php
                    $billing_Type = strtolower($rows['Billing_Type']);
                    $status = strtolower($rows['Status']);
                    $transaction_Type = strtolower($rows['transaction']);
                    $payment_type = strtolower($rows['payment_type']);

                    if (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "cash")) {
                        echo 'Not paid';
                         $paymentstatus='Not paid';
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "credit")) {
                        echo 'Not Billed';
                        $paymentstatus='Not Billed';
                    }elseif (($billing_Type == 'outpatient credit' && $status == 'active') && $rows['Require_Document_To_Sign_At_receiption']=='Mandatory') {
                        echo 'Not Approved';
                        $paymentstatus='Not Approved';
                    }  elseif (($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
                        echo 'Not paid';
                        $paymentstatus='Not paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

                        if ($pre_paid == '1') {
                            if(strtolower($payment_type)=='post'){
                                echo 'Not Billed';
                                 $paymentstatus='Not Billed';
                            } else {
                                echo 'Not paid';
                                $paymentstatus='Not paid';
                            }
                            
                        } else {
                            if ($payment_type == 'pre'  && $status == 'active') {
                                   echo 'Not paid';
                                    $paymentstatus='Not paid';
                            } else {
                                echo 'Not Billed';
                                 $paymentstatus='Not Billed';
                            }
                        }
                        
                    } elseif ($billing_Type == 'outpatient cash' && $status == 'paid' && $transaction_Type == "cash"){
                        echo 'Paid';
                        $paymentstatus='Paid';
                    }elseif(($billing_Type == 'outpatient credit' || $billing_Type == 'Outpatient Credit') && $status == 'paid'){
                        echo 'Approved';
                        $paymentstatus='Approved';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        echo 'Paid';
                         $paymentstatus='Paid';
                    } else {
                        if ($payment_type == 'pre') {
                            echo 'Not paid';
                             $paymentstatus='Not paid';
                        } else {
                            echo 'Not Billed';
                            $paymentstatus='Not Billed';
                        }
                    }
                        ?>">
                </td>
                <td style="border-left:1px solid #ccc;text-align:center">

                    <?php
                        //Get specimen collection status
                        $specimenTaken = "SELECT ref_specimen_ID FROM tbl_tests_specimen WHERE tests_item_ID='" . $rows['item_id'] . "'";
                        $getQuery = mysqli_query($conn,$specimenTaken);
                        $getNumber = mysqli_num_rows($getQuery);

                        $specimenResults = "SELECT Specimen_ID FROM tbl_specimen_results WHERE payment_item_ID='" . $rows['Patient_Payment_Item_List_ID'] . "' AND collection_Status='collected'";
                        $myResult = mysqli_query($conn,$specimenResults);
                        $myNumbers = mysqli_num_rows($myResult);

                        //echo $myNumbers.' '.$getNumber;
                        $checked="";
                        $unchecked="";
                        if ($myNumbers > 0 && $getNumber == $myNumbers) {
                            echo '<div class="status">Done</div>';
                            $checked="checked='checked'";
                        } elseif ($myNumbers > 0 && $myNumbers < $getNumber) {
                            echo '<div class="status">On progress</div>';
                        } elseif ($myNumbers == 0) {
                            echo '<div class="status"> Not Collected   </div>';
                        }
                       if($paymentstatus == "Not Billed" || $paymentstatus == "Paid" || $paymentstatus == "Approved"){
                            $class_disable="";
                        }else{
                            $class_disable="disabled='disabled'";
                        }
                       if($paymentstatus == "Not paid"){
                            $class_disable_bill="";
                        }else{
                            $class_disable_bill="disabled='disabled'";
                        }
                        
                       $Patient_Payment_Item_List_ID=$rows['Patient_Payment_Item_List_ID'];
                       $item=$rows['item_id']; 
                    echo "<input type='checkbox' class='specimenCollection Patient_Payment_Item_List_ID_n_status' $checked $class_disable
                        value='$Patient_Payment_Item_List_ID..kiunganishi$paymentstatus' name='item_id'
                        data-id='$item'
                        id='Patient_Payment_Item_List_ID'> ";
                    ?>

                </td>

                <?php
                    $item_id = $rows['item_id'];
                    $query = mysqli_query($conn,"SELECT 'cache' as Status_From,pl.Priority,pl.Status as Status,pl.Item_ID as item_id,pl.Transaction_Type as Transaction_Type,pr.*,pl.Payment_Item_Cache_List_ID as Patient_Payment_Item_List_ID \n"
                            . "from tbl_payment_cache as pc \n"
                            . "join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID \n"
                            . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID \n"
                            . "Join tbl_employee as e on e.Employee_ID =pc.Employee_ID \n"
                            . "join tbl_items as i on i.Item_ID =pl.Item_ID \n"
                            . "where pl.Check_In_Type='Laboratory' and pc.Payment_Cache_ID ='".$rows['Payment_Cache_ID']."' and pl.Item_ID='$item_id'
                        ");


                    $Payment_Status = '';
                    $Transaction_Type = '';

                    $item_row = mysqli_fetch_assoc($query);
                    $Payment_Status = $item_row['Status'];
                    $Transaction_Type = $item_row['Transaction_Type'];
                    ?>

                <td style="border-left:1px solid #ccc;text-align:center">
                    <button style="width:150px;height:30px"
                        class="viewSpecimen paid<?php echo $rows['Patient_Payment_Item_List_ID']; ?>"
                        value="<?php echo $rows['Patient_Payment_Item_List_ID']; ?>"
                        id="<?php echo $rows['item_id']; ?>"><b>Collect</b> Specimen</button>
                    <input type="text" id="paymentStatus" style="display:none" value='<?php echo $Payment_Status; ?>' />
                    <input type="text" id="trasanction" style="display:none" value='<?php echo $Transaction_Type; ?>' />
                    <input type="text" id="patientId" style="display:none" value='<?php echo $patient_id; ?>' />
                    <input type="text" id="paymentId" style="display:none" value='<?php echo $payment_id; ?>' />
                    <input type="text" id="paymentListItem" style="display:none"
                        value='<?php echo $rows['Patient_Payment_Item_List_ID']; ?>' />
                    <input type="text" id="rquiredDate" style="display:none"
                        value='<?php echo $_GET['Required_Date']; ?>' />
                    <input type="text" id="statusFrom" style="display:none"
                        value='<?php echo $rows['Status_From']; ?>' />
                    <input type="text" class="CollectDone" style="display:none"
                        value='<?php echo $rows['Patient_Payment_Item_List_ID']; ?>' />

                    </button>
                    <a
                        href='laboratory_setup_test.php?Item_ID=<?php echo $rows['item_id'] ?>&Status_From=<?php echo $rows['Status_From'] ?>&Patient_Payment_Item_List_ID=<?php echo $rows['Patient_Payment_Item_List_ID']; ?>&patient_id=<?php echo $patient_id ?>&payment_id=<?php echo $payment_id ?>&Required_Date=<?php echo $_GET['Required_Date'] ?>&LaboratorySetupTestThisPage=ThisPage'>
                        <button style="width:150px;height:30px">Define Specimen</button>
                    </a>
                </td>
                <?php if($billing_Type=="inpatient cash"||$billing_Type=="inpatient credit"){
                    $Patient_Payment_Item_List_ID=$rows['Patient_Payment_Item_List_ID'];    
                    ?>
                <td>
                    Send to Bill <input type="checkbox" <?= $class_disable_bill ?>
                        value="<?= $Patient_Payment_Item_List_ID ?>" class="send_to_bill_this_item" />
                </td>
                <?php  } ?>
            </tr>

            <?php
                $i++;
                $Employee = $rows['Employee'];
                $item_id = $rows['item_id'];
            }
            ?>
            <?php if($paymentstatus == "Not Billed" || $paymentstatus == "Paid" || $paymentstatus == "Approved"){
                            $class_disable="";
                            
                        }else{
                            $class_disable="disabled='disabled'";
                            
                        }
                        if($paymentstatus == "Not paid"){
                            $class_disable_bill="";
                        }else{
                            $class_disable_bill="disabled='disabled'";
                        }
         ?>
            <tr>
                <td colspan="2"></td>
                <td colspan="" style="text-align:right"><b><?= number_format($total_price) ?></b></td>
                <td></td>
                <td colspan="2">
                    <label>Select All <input type="checkbox" id="select_all_checkbox" <?= $class_disable ?>> </label>
                    <!--          <button class="art-button-green" id="itemIDAdd" onclick="Change_Status()"  style="margin-left:13px !important; color:white !important; font-weight:bold;" >Collect All</button>-->
                    <button class="art-button-green" id="itemIDAdd1" onclick="Change_Status1()"
                        style="margin-left:13px !important; color:white !important; font-weight:bold;">Uncollect</button>
                </td>
                <?php if($billing_Type=="inpatient cash"||$billing_Type=="inpatient credit"){?>
                <td>
                    <label>Select All <input type="checkbox" id="bill_all" <?= $class_disable_bill ?> /></label>
                </td>
                <?php } ?>
            </tr>
        </table>
        <div align="right" style="color:black;padding:5px; margin-right: 100px;">


        </div>
        <input type="checkbox" <?= $class_disable ?> id="unselect_all_checkbox" class="hidden">
        <br>
        <style>
        .art-button {
            height: 27px !important;
            color: #FFFFFF !important;
        }
        </style>

</fieldset>
<fieldset>
    <table>
        <tr>
            <td colspan="1" style="width:20%;padding:5px;font-size:14px">
                <span>
                    <b>Consulted By: </b>
                </span>
                <span>
                    <?php echo $Employee; ?>
                </span>
            </td>
            <td>
                <?php
            echo "<center><a href='searchpatientlaboratorylist.php' class='art-button CollectDonebtn'>SUBMIT TO RESULTS TEAM</a>";
            echo "<a href='' id='workSheet' class='art-button-green'>CREATE WORK SHEET</a>";
            echo "<a href='previewSampledTests.php?".$_SERVER['QUERY_STRING']."' id='previewSampleTest' class='art-button-green' target='_blank'>PREVIEW / PRINT</a>";
            echo "<button class='art-button-green' onclick='viewPatientPhoto(".$patient_id.")'>VIEW PATIENT PHOTO</button>";
                // echo "<button class='art-button-green' onclick='alert(\"Access Denied\")'>CREATE INPATIENT BILL</button>";
            echo "<button class='art-button-green' onclick='creating_inpatient_bill()'>CREATE INPATIENT BILL</button>";

            echo "</center>";
            ?></td>
        </tr>
    </table>
</fieldset>

<!--End here bana-->
<div id="showWorksheet" style="display: none">
    <center>

        <table class="hiv_table" style="width:100%">
            <tr>

                <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline'
                        id="Date_From" placeholder="Start Date" />
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline'
                        id="Date_To" placeholder="End Date" />&nbsp;

                    <select id="subcategory_ID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php 
                         $query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));
                          echo '<option value="All">~~~~~Select Department~~~~~</option>';
                         while ($row = mysqli_fetch_array($query_sub_cat)) {
                          echo '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
                         }
                        ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" id="Filter">
                    <input type="button" value="Print work sheet" class="art-button-green" id="print_worksheet">
                </td>
            </tr>
            <tr>
                <td id='Search_Iframe'>
                    <div id="iframeDiv" style="height: 400px;overflow-y: auto;overflow-x: hidden">

                    </div>
                </td>
            </tr>
        </table>
    </center>

</div>

</center>

<?php

function updateStatus($messege) {
    $sql = mysqli_query($conn,"UPDATE tbl_item_list_cache as pl "
            . "join tbl_payment_cache as pc on pc.Payment_Cache_ID =pl.Payment_Cache_ID\n"
            . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID\n"
            . "SET pl.Process_Status = '$messege'"
            . "where pl.Check_In_Type='Laboratory' and pr.Registration_ID ='$patient_id'");

    $sql2 = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list as pl \n"
            . "join tbl_patient_payments as pc on pc.Patient_Payment_ID =pl.Patient_Payment_ID \n"
            . "join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID\n"
            . "SET pl.Process_Status = '$messege' where pl.Check_In_Type='Laboratory' and pr.Registration_ID ='$patient_id' ");
}
?>




<script type="text/javascript">
$("#select_all_checkbox").click(function(e) {
    $(".Patient_Payment_Item_List_ID_n_status").not(this).prop('checked', this.checked);
    Change_Status();

});
$("#bill_all").click(function(e) {
    $(".send_to_bill_this_item").not(this).prop('checked', this.checked);
});

function creating_inpatient_bill() {
    var selected_item = [];
    var Registration_ID = '<?php echo $_GET['patient_id'] ?>';
    var Sponsor_ID = '<?php echo $Sponsor_ID ?>';
    var Guarantor_Name = '<?php echo $Guarantor_Name ?>';


    var validate = 0;
    $(".send_to_bill_this_item:checked").each(function() {
        selected_item.push($(this).val());
        validate++;
    });
    if (validate <= 0) {
        alert("Please Select Test You Want To Sent To Bill First");
    } else {
        if (confirm("Are you sure you want to bill the selected items?")) {
            $.ajax({
                type: 'POST',
                url: 'ajax_creating_inpatient_bill.php',
                data: {
                    selected_item: selected_item,
                    Registration_ID: Registration_ID,
                    Sponsor_ID: Sponsor_ID,
                    Guarantor_Name: Guarantor_Name,
                    Check_In_Type: "Laboratory"
                },
                success: function(data) {
                    console.log(selected_item);
                    console.log(data);
                    //                    if(data=='collected_successfullly'){
                    alert("Bill Created Successfully");
                    location.reload();
                    //                    }else{
                    //                        alert("Fail to create inpatient bill");
                    //                    }
                }
            });
        }
    }
}

function Change_Status() {
    var selected_item = [];
    var validate = 0;
    $(".Patient_Payment_Item_List_ID_n_status:checked").each(function() {
        selected_item.push($(this).val());
        validate++;
    });
    if (validate <= 0) {
        alert("Please Select Test You Want to Collect First");
    } else {
        var Registration_ID = '<?php echo $_GET['patient_id'] ?>';
        $.ajax({
            type: 'POST',
            url: 'Select_all_sample.php',
            data: {
                selected_item: selected_item,
                Registration_ID: Registration_ID
            },
            success: function(data) {
                console.log(data);
                if (data == 'collected_successfullly') {
                    alert("Collected Successfully");
                    location.reload();
                } else if (data == 201 || data == '201fail') {
                    alert(
                        "Please Define Sample Specimen for Test that Paid/Approved but failed to Collect \n Click 'DEFINE SPECIMEN' Button to Define"
                    );
                } else {
                    alert("Test is not approved or paid");
                }
                //                $("#attached_category_body").html(data);
                //                refresh_content()
            }
        });
    }
    //refresh_content() 
}

function Change_Status1() {
    var selected_item = [];
    var validate = 0;
    $(".Patient_Payment_Item_List_ID_n_status:checked").each(function() {
        selected_item.push($(this).val());
        validate++;
    });
    var Registration_ID = '<?php echo $_GET['patient_id'] ?>';
    if (validate <= 0) {
        alert("Please Select Test You Want to Uncollect First");
    } else {
        $.ajax({
            type: 'POST',
            url: 'Uncheck_sample_item.php',
            data: {
                selected_item: selected_item
            },
            success: function(data) {
                console.log(data);
                if (data == 'collected_successfullly') {
                    alert(" Not Collected");
                    location.reload();
                } else if (data == 201) {
                    alert(
                        "Please Define Sample Specimen for Test that Paid/Approved but failed to Collect \n Click 'DEFINE SPECIMEN' Button to Define"
                    );
                } else {
                    alert("Test is not approved or paid");
                }
                //                $("#attached_category_body").html(data);
                //                refresh_content()
            }
        });
    }
    //refresh_content() 
}
$(document).ready(function() {
    $('.paystatus').each(function() {
        var id = $(this).attr('id');
        var status = $(this).val();

        if (status == 'Not paid') {
            $('.paid' + id).attr('disabled', 'disabled');
            $('.paid' + id).text('Payment is not done');
        } else if (status == 'Not Approved') {
            $('.paid' + id).attr('disabled', 'disabled');
            $('.paid' + id).text('Billing not approved');
        }
    });

});


$('.specimenCollection').click(function() {
    // $(this).attr("disabled", "disabled");
    var id = $(this).data('id');
    var payVal = $(this).val().split("..kiunganishi")[0];
    var paymentStatus = $(this).val().split("..kiunganishi")[1]
    var Undocheck
    if (!$(this).is(":checked")) {
        Undocheck = "Undocheck"
    }
    if (paymentStatus == "Not Billed" || paymentStatus == "Paid" || paymentStatus == "Approved") {
        var data = {
            id: id,
            action: "submit",
            payVal: payVal,
            Undocheck: Undocheck
        }
        $.ajax({
            type: 'POST',
            url: "insert_value.php",
            data: data,
            success: function(response) {
                if (response == "success") {
                    alert("Specimen Collected")
                } else {
                    alert("Failed to collect specimen...please retry")
                }
                location.reload();
            }
        });
    } else {
        alert("Make sure item has been paid")
        return;
    }
});

$('.viewSpecimen').click(function() {
    var id = $(this).attr('id');
    var val = $(this).val();
    var paymentStatus = $("#paymentStatus").val();
    var trasanction = $("#trasanction").val();
    var patientId = $("#patientId").val();
    var paymentId = $("#paymentId").val();
    var paymentListItem = $("#paymentListItem").val();
    var rquiredDate = $("#rquiredDate").val();
    var statusFrom = $("#statusFrom").val();
    $.ajax({
        type: 'POST',
        url: 'requests/collectSpecimen.php',
        data: 'action=collect&id=' + id + '&paymentStatus=' + paymentStatus + '&trasanction=' +
            trasanction + '&patientId=' + patientId + '&paymentId=' + paymentId + '&paymentListItem=' +
            paymentListItem + '&rquiredDate=' + rquiredDate + '&statusFrom=' + statusFrom + '&val=' +
            val,
        success: function(html) {
            //alert(html);
            $('#vewmeDiv').html(html);
        }
    });
    $('#viewTestSpecimen').dialog({
        modal: 'true',
        resizable: false,
        title: 'Collect Specimen',
        draggable: true,
        width: '90%',
        height: 400
    });

    $('#viewTestSpecimen').dialog('option', 'position', 'center');
});

$('.CollectDonebtn').click(function(e) {
    e.preventDefault();
    var payID;
    var i = 1;
    //var msg='Specimen collection is not complete,are you sure you want to continue?';
    $('.status').each(function() {
        var html3 = $(this).text();
        if (html3 == 'On progress') {
            if (confirm('Specimen collection is not complete,are you sure you want to continue?')) {
                return true;
            } else {
                exit();
            }

        }
    });
    $('.CollectDone').each(function() {
        if ($(this).val() !== '') {
            if (i == 1) {
                payID = $(this).val();
            } else {
                payID += "," + $(this).val();
            }
        }
        i++;


    });
    var Registration_ID = '<?php echo $_GET['patient_id'] ?>';
    $.ajax({
        type: 'POST',
        url: 'requests/SubmitResults.php',
        data: 'payID=' + payID + '&Registration_ID=' + Registration_ID,
        success: function(html) {
            var data = html.split('*&&^^%%$');
            //$('#previewSampleTest').attr('href','previewSampledTests.php?ids='+data[2]);

            if (data[0] == 'Note_Completed') {
                alert(data[1]);
                window.location = window.location.href;
            } else {
                alert(data[1]);
                //window.location.href = "searchpatientlaboratorylist.php";
                window.location = window.location.href;
            }
        }
    });
});

$('#workSheet').on('click', function(e) {
    e.preventDefault();
    $('#showWorksheet').dialog({
        modal: 'true',
        resizable: true,
        title: 'Work sheet per test',
        draggable: true,
        width: '90%',
        height: 500
    });
});

$('#Filter').on('click', function() {
    var fromDate = $('#Date_From').val();
    var toDate = $('#Date_To').val();
    var subcategory_ID = $('#subcategory_ID').val();
    $.ajax({
        type: 'POST',
        url: 'requests/workSheetReport.php',
        data: 'action=selectData&fromDate=' + fromDate + '&toDate=' + toDate + '&subcategory_ID=' +
            subcategory_ID,
        cache: false,
        success: function(html) {
            $('#iframeDiv').html(html);
        }
    });
});

$('#print_worksheet').on('click', function() {
    var fromDate = $('#Date_From').val();
    var toDate = $('#Date_To').val();
    var subcategory_ID = $('#subcategory_ID').val();
    window.open('Print_Worksheet.php?action=action&fromDate=' + fromDate + '&toDate=' + toDate +
        '&subcategory_ID=' + subcategory_ID);
});
</script>
<script>
$('#Date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
});
$('#Date_From').datetimepicker({
    value: '',
    step: 30
});
$('#Date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
});
$('#Date_To').datetimepicker({
    value: '',
    step: 30
});
</script>
<script>
function Show_Patient_File() {
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
    var winClose = popupwindow(
        'Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $_GET['patient_id']; ?>&PatientFile=PatientFileThisForm',
        'Patient File', 1300, 700);
    //winClose.close();
    //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

}

function popupwindow(url, title, w, h) {
    var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h +
        '; center:yes;dialogTop:' + top + '; dialogLeft:' + left
    ); //'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    return mypopupWindow;
}
</script>
<!--<link rel="stylesheet" href="css/jquery-ui.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<?php
include("./includes/footer.php");
?>