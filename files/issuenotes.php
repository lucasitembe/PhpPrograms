<script src='js/functions.js'></script>
<!--<script src="jquery.js"></script>-->
<?php
include("./includes/header.php");
include("./includes/connection.php");

include_once("./functions/department.php");
include_once("./functions/items.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['HAS_ERROR'])) {
    unset($_SESSION['HAS_ERROR']);
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

//get requisition id
if (isset($_SESSION['Issue_Note']['Requisition_ID'])) {
    $Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];
} else {
    $Requisition_ID = 0;
}
$Store_Issue = $_SESSION['Storage_Info']['Sub_Department_ID'];
if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
    // $counts_query = mysqli_query($conn, "SELECT COUNT(Requisition_ID)as count FROM tbl_requisition WHERE requisition_status in ('submitted','edited','saved','Not Approved') and Store_Issue='$Store_Issue'");
    // $row = mysqli_fetch_assoc($counts_query);
    if (isset($_GET['from_phamacy_works'])) {
        $from_phamacy_works = "&from_phamacy_works=yes";
    } else {
        $from_phamacy_works = "";
    }
    if ($row['count'] > 0) {
        echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage$from_phamacy_works' class='art-button-green'>NEW ISSUE NOTE </a>";
    } else {
        echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage$from_phamacy_works' class='art-button-green'>NEW ISSUE NOTE </a>";
    }
}

if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' && $_SESSION['userinfo']['Session_Master_Priveleges'] == 'yes') {
    echo "<a href='unapprovedissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>APPROVE ISSUES</a>";
}

if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
    echo "<a href='previousissuenoteslist.php?lform=sentData&page=issue_list' class='art-button-green'>PROCESSED ISSUE NOTES</a>";
}

if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
    if (isset($_GET['from_phamacy_works'])) {
        echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
    }
}
?>


<?php
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn, "select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Sub_Department_Name = $row['Sub_Department_Name'];
    } else {
        $Sub_Department_Name = '';
    }
}

if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    //get all other details
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $sql_select = mysqli_query($conn, "SELECT rq.Requisition_ID, rq.Requisition_Description, rq.Sent_Date_Time, sd.Sub_Department_Name, rq.Store_Need as Department_Req_ID, emp.Employee_Name
									from tbl_employee emp, tbl_sub_department sd, tbl_requisition rq where
									emp.Employee_ID = rq.Employee_ID and
									rq.Store_Issue = sd.Sub_Department_ID and
									rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            $Requisition_Description = $row['Requisition_Description'];
            $Sent_Date_Time = $row['Sent_Date_Time'];
            $Deparment_Requesting = $row['Sub_Department_Name'];
            $Employee_Prepared = $row['Employee_Name'];
            $Department_Req_ID = $row['Department_Req_ID'];
        }
    } else {
        $Requisition_Description = '';
        $Sent_Date_Time = '';
        $Deparment_Requesting = '';
        $Employee_Prepared = '';
        $Department_Req_ID = '';
    }
}

$Deparment_Requesting = '';
$Department_Issue = '';
$Department_Issue_ID = '';

if (isset($_SESSION['Issue_Note']['Requisition_ID'])) {
    $Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];

    $Requesting_Department = Get_Requesting_Department_From_Requisition($Requisition_ID);
    if (!empty($Requesting_Department)) {
        $Deparment_Requesting = $Requesting_Department['Sub_Department_Name'];
        $Deparment_Requesting_ID = $Requesting_Department['Sub_Department_ID'];
    }

    $Issuing_Department = Get_Issuing_Department_From_Requisition($Requisition_ID);
    if (!empty($Issuing_Department)) {
        $Department_Issue = $Issuing_Department['Sub_Department_Name'];
        $Department_Issue_ID = $Issuing_Department['Sub_Department_ID'];
    }
}
?>

<br /><br />
<form action='#' method='post' id="issu_Form">
    <fieldset>
        <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b> Issue note</b></legend>
        <table width=100%>
            <tr>
                <?php
                $Process_Status = 'not processed';
                if (isset($_SESSION['Issue_Note']['Requisition_ID'])) {
                    $Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];
                    $Process_Status = 'not processed';
                    //check if this requisition already processed
                    $check_status = mysqli_query($conn, "select Requisition_Status from tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($check_status);
                    if ($num > 0) {
                        while ($row = mysqli_fetch_array($check_status)) {
                            $Requisition_Status = $row['Requisition_Status'];
                            if ($Requisition_Status == 'Served' || $Requisition_Status == 'Not Approved') {
                                //get details from tbl_issues
                                $get_details = mysqli_query($conn, "select isu.Issue_ID, isu.Issue_Description,isu.IV_Number, isu.Receiving_Officer, isu.Issue_Date_And_Time from tbl_issues isu
											where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($get_details);
                                if ($no > 0) {
                                    $Process_Status = 'processed';
                                    while ($data2 = mysqli_fetch_array($get_details)) {
                                        $Issue_ID = $data2['Issue_ID'];
                                        $Issue_Description = $data2['Issue_Description'];
                                        $IV_Number = $data2['IV_Number'];
                                        $Receiving_Officer = $data2['Receiving_Officer'];
                                        $Issue_Date = $data2['Issue_Date_And_Time'];
                                    }
                                    if ($Requisition_Status == 'Served') {
                                        $Requisition_Status = 'served';
                                    }
                                } else {
                                    $Issue_ID = '';
                                    $Issue_Description = '';
                                    $IV_Number = '';
                                    $Receiving_Officer = '';
                                    $Issue_Date = '';
                                }
                            } else {
                                $Issue_ID = '';
                                $Issue_Description = '';
                                $IV_Number = '';
                                $Receiving_Officer = '';
                                $Issue_Date = '';
                            }
                        }
                    } else {
                        $Issue_ID = '';
                        $Issue_Description = '';
                        $IV_Number = '';
                        $Receiving_Officer = '';
                        $Issue_Date = '';
                    }
                } else {
                    $Issue_ID = '';
                    $Issue_Description = '';
                    $IV_Number = '';
                    $Receiving_Officer = '';
                    $Issue_Date = '';
                }
                ?>
                <td style='text-align: right;' width=12%>Requisition Number</td>
                <?php if (isset($_SESSION['Issue_Note']['Requisition_ID'])) { ?>
                    <td width=13%><input type='text' readonly='readonly' value='<?php echo $Requisition_ID; ?>' name='Requisition_Number' id='Requisition_Number' size=10></td>
                <?php } else { ?>
                    <td width=13%><input type='text' readonly='readonly' value='' name='Requisition_Number' id='Requisition_Number' size=10></td>
                <?php } ?>
                <td style='text-align: right;' width=10%>Requisition Date</td>
                <?php if (isset($_SESSION['Issue_Note']['Requisition_ID'])) { ?>
                    <td width=15%><input type='text' readonly='readonly' value='<?php echo $Sent_Date_Time; ?>' name='Requisition_Date' id='Requisition_Date'></td>
                <?php } else { ?>
                    <td width=15%><input type='text' readonly='readonly' value='' name='Requisition_Date' id='Requisition_Date'></td>
                <?php } ?>
                <td style='text-align: right;' width=12%>Issue Number</td>
                <?php if ($Process_Status == 'processed') { ?>
                    <td width=13%><input type='text' readonly='readonly' value='<?php echo $Issue_ID; ?>' name='IV_Number' id='IV_Number' size=10></td>
                <?php } else { ?>
                    <td width=13%><input type='text' readonly='readonly' value='New' name='IV_Number' id='IV_Number' size=10></td>
                <?php } ?>
                <td style='text-align: right;' width=10%>Issue Date</td>
                <?php if ($Process_Status == 'processed') { ?>
                    <td width=15%><input type='text' readonly='readonly' value='<?php echo $Issue_Date; ?>' name='Issue_Date' id='Issue_Date'></td>
                <?php } else { ?>
                    <td width=15%><input type='text' readonly='readonly' value='' name='Issue_Date' id='Issue_Date'></td>
                <?php } ?>
            </tr>
            <tr>
                <td style='text-align: right;' width=12%>Department Requesting</td>
                <?php if (isset($_SESSION['Issue_Note']['Requisition_ID'])) { ?>
                    <td width=13%><input type='text' readonly='readonly' value='<?php echo $Deparment_Requesting; ?>' name='Requisition_Number' id='Requisition_Number' size=10></td>
                <?php } else { ?>
                    <td width=13%><input type='text' readonly='readonly' value='' name='Requisition_Number' id='Requisition_Number' size=10></td>
                <?php } ?>
                <td style='text-align: right;' width=10%>Prepared By</td>
                <?php if (isset($_SESSION['Issue_Note']['Requisition_ID'])) { ?>
                    <td width=15%><input type='text' readonly='readonly' value='<?php echo $Employee_Prepared; ?>' name='Requisition_Date' id='Requisition_Date'></td>
                <?php } else { ?>
                    <td width=15%><input type='text' readonly='readonly' value='' name='Requisition_Date' id='Requisition_Date'></td>
                <?php } ?>

                <td style='text-align: right;' width=12%>Department Issuing</td>
                <td width=13%><input type='text' readonly='readonly' value='<?php echo $Department_Issue; ?>' name='IV_Number' id='IV_Number' size=10></td>
                <td style='text-align: right;' width=10%>Issued By</td>
                <td width=15%><input type='text' readonly='readonly' name='Issue_By' id='Issue_By' value='<?php echo $Employee_Name; ?>'></td>
            </tr>
            <tr>
                <td style='text-align: right;'>Requisition Description</td>
                <?php if (isset($_SESSION['Issue_Note']['Requisition_ID'])) { ?>
                    <td colspan=3><input type='text' readonly='readonly' value='<?php echo $Requisition_Description; ?>' name='Requisition_Description' id='Requisition_Description'></td>
                <?php } else { ?>
                    <td colspan=3><input type='text' readonly='readonly' value='' name='Requisition_Description' id='Requisition_Description'></td>
                <?php } ?>

                <td style='text-align: right;'>Issue Description</td>
                <?php if ($Process_Status == 'processed') { ?>
                    <td colspan=3><input type='text' name='Issue_Description' value='<?php echo $Issue_Description; ?>' id='Issue_Description'></td>
                <?php } else { ?>
                    <td colspan=3><input type='text' name='Issue_Description' value='' id='Issue_Description'></td>
                <?php } ?>
            </tr>
            <tr>
                <td style='text-align: right;'>Receiving Officer</td>
                <?php if ($Process_Status == 'processed') { ?>
                    <td colspan=3><input type='text' name='Receiving_Officer' value='<?php echo $Receiving_Officer; ?>' id='Receiving_Officer'></td>
                <?php } else { ?>
                    <td colspan=3><input type='text' name='Receiving_Officer' value='' id='Receiving_Officer'></td>
                <?php } ?>

                <td style='text-align: right;'>IV Number</td>
                <?php if ($Process_Status == 'processed') { ?>
                    <td colspan=3><input type='text' name='IV_Number' value='<?php echo $IV_Number; ?>' id='IV_Number'></td>
                <?php } else { ?>
                    <td colspan=3><input type='text' name='IV_Number' value='' id='IV_Number'></td>
                <?php } ?>
            </tr>
        </table>
    </fieldset>

    <fieldset>
        <table width=100%>
            <tr>
                <?php if ($Process_Status == 'processed') { ?>
                    <?php if ($Requisition_Status == 'Not Approved') { ?>
                        <td width="10%" style="text-align: right;">Username</td>
                        <td width="12%">
                            <input type="text" name="Supervisor_Username" id="Supervisor_Username" autocomplete="off" style="text-align: center;" placeholder=" Username">
                        </td>
                        <td width="10%" style="text-align: right;">Password</td>
                        <td width="12%">
                            <input type="password" name="Supervisor_Password" id="Supervisor_Password" autocomplete="off" style="text-align: center;" placeholder=" Password">
                        </td>
                        <td>
                            <input type="text" name="Supervisor_Comment" id="Supervisor_Comment" placeholder="Comment" autocomplete="off">
                        </td>
                        <td width="10%" style="text-align: center;">
                            <input type="button" name="Approval_Button" id="Approval_Button" value="APPROVE ISSUE NOTE" class="art-button-green" onclick="check_if_valid_user_to_approve_this_document()">
                        </td>
                    <?php } ?>
                    <td width="10%" style='text-align: right;'>
                        <input type="button" name="Preview" id="Preview" value="PREVIEW ISSUE" class="art-button-green" onclick="Preview_Grn_Issue_Note(<?php echo $Issue_ID; ?>)">
                        <!-- <a href='#previewissuereport.php?PreviewIssueReport=PreviewIssueReportThisPage' class='art-button-green'>PREVIEW ISSUE</a> -->
                    </td>
                <?php } else { ?>
                    <td style='text-align: right;'><input type='button' name='Submit' id='Submit' value='SUBMIT' class='art-button-green' onclick="validate_issues()"></td>
                <?php } ?>
            </tr>
        </table>
    </fieldset>

    <script>
        document.getElementById("Submit").addEventListener("click", function(event) {
            Receiving_Officer = document.getElementById("Receiving_Officer").value;

            /*if (Receiving_Officer == '') { document.getElementById("Receiving_Officer").style = 'border: 3px solid red;'; event.preventDefault(); }
             else { document.getElementById("Receiving_Officer").style = 'border: 1px solid #B9B59D;'; }*/
        });
    </script>

    <fieldset style='overflow-y: scroll; height: 300px;' id='Items_Fieldset'>
        <center>
            <table width=100%>
                <tr>
                <tr>
                    <td colspan='12'>
                        <hr>
                    </td>
                </tr>
                <td width=3%>Sn</td>
                <td width=30%>Item Name</td>
                <td width=6% style='text-align: right;'><?php echo substr($Department_Issue, 0, 15); ?> Balance</td>
                <td width=6% style='text-align: right;'><?php echo substr($Deparment_Requesting, 0, 15); ?> Balance</td>
                <td width=6% style='text-align: right;'>Quantity Required</td>
                <td width=6% style='text-align: right;'>Units Issued</td>
                <td width=6% style='text-align: center;'>Items per unit</td>
                <td width=6% style='text-align: right;'>Quantity Issued</td>
                <td width=6% style='text-align: right;'>Buying Price</td>
                <td width=6% style='text-align: right;'>Selling Price</td>
                <td width=6% style='text-align: right;'>Batch No</td>
                <td width=6% style='text-align: right;'>Outstanding</td>
                <tr>
                    <td colspan='12'>
                        <hr>
                    </td>
                </tr>
                </tr>

                <?php
                $temp = 1;

                $sql_date_time = mysqli_query($conn, "SELECT now() as Date_Time ") or die(mysqli_error($conn));
                while ($date = mysqli_fetch_array($sql_date_time)) {
                    $Current_Date_Time = $date['Date_Time'];
                }

                $duration = -3;
                $Filter_Value = substr($Current_Date_Time, 0, 11);

                $mod_date = date_create($Filter_Value);
                date_sub($mod_date, date_interval_create_from_date_string("$duration days"));
                $newdate =  date_format($mod_date, "Y-m-d");

                date_default_timezone_set('Africa/Dar_es_Salaam');
                $todayIs = date('Y-m-d');
                
                // $update_batch_status = mysqli_query($conn, "UPDATE tbl_batch_expire_date_control SET Batch_Status = 'expired' WHERE Sub_Deparment_Id = '$Department_Issue_ID' AND Expire_Date <= '$newdate' AND Batch != 'Not Required'") or die(mysqli_errno($conn));

                if (isset($_SESSION['Issue_Note']['Requisition_ID']) && isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
                    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
                    $Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];

                    $select_items = mysqli_query($conn, "SELECT i.Item_ID, rqi.Requisition_Item_ID, i.Product_Name,rqi.Bar_Code,rqi.Quantity_Issued, rqi.Quantity_Required,
                                                    rqi.Item_Remark, rqi.Requisition_Item_ID, rqi.Container_Issued, rqi.Items_Per_Container, rqi.Last_Buying_Price,rqi.Selling_Price, rq.Requisition_Status
													FROM tbl_requisition_items rqi, tbl_requisition rq, tbl_items i WHERE
													rq.Requisition_ID = rqi.Requisition_ID AND
													i.Item_ID = rqi.Item_ID AND
													rqi.Requisition_ID = '$Requisition_ID' AND
													rqi.Item_Status = 'active' AND rqi.Status = 'active' AND i.Status='Available'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select_items);
                    if ($no > 0) {
                        while ($data = mysqli_fetch_array($select_items)) {
                            $Item_ID = $data['Item_ID'];

                            //get item store balance
                            $sql_get = mysqli_query($conn, "select Item_Balance from tbl_items_balance where
										Sub_Department_ID = '$Department_Issue_ID' and
											Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $num_of_rows = mysqli_num_rows($sql_get);
                            if ($num_of_rows > 0) {
                                while ($balance = mysqli_fetch_array($sql_get)) {
                                    $Store_Balance = $balance['Item_Balance']; //Store balance
                                }
                            } else {
                                $Store_Balance = 0; // Store balance
                            }

                            //get requested balance
                            $sql_get_req = mysqli_query($conn, "select Item_Balance from tbl_items_balance where
										Sub_Department_ID = '$Department_Req_ID' and
											Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                            $num_of_rows = mysqli_num_rows($sql_get_req);
                            if ($num_of_rows > 0) {
                                while ($balance = mysqli_fetch_array($sql_get_req)) {
                                    $Store_Balance_Req = $balance['Item_Balance']; //Store balance
                                }
                            } else {
                                $Store_Balance_Req = 0; // Store balance
                            }

                            $Quantity_Required = $data['Quantity_Required'];
                            $Item_Remark = $data['Item_Remark'];
                            $Requisition_Item_ID = $data['Requisition_Item_ID'];
                            $Product_Name = $data['Product_Name'];


                            $select_appropriate_bar_code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Batch FROM tbl_batch_expire_date_control WHERE Item_Id = '$Item_ID' AND Sub_Deparment_Id = '$Sub_Department_ID' AND Batch_Status = 'not expired' AND Item_Balance > 0 ORDER BY Expire_Date ASC LIMIT 1"))['Batch'];
                ?>
                            <tr>
                                <td>
                                    <input type='text' readonly='readonly' value='<?php echo $temp; ?>'>
                                    <input type="hidden" name="itemsId" id='itemsId' value='<?= $Item_ID ?>'>
                                    <input type="hidden" name="itemsId" id='itemsId<?= $temp ?>' value='<?= $Item_ID ?>'>
                                </td>
                                <td><input type='text' name='Item_Name' id='Item_Name' class="validate_issueing" trg="<?php echo $temp; ?>" readonly='readonly' value='<?php echo $data['Product_Name']; ?>'></td>
                                <td><input type='text' name='Store_Balance' readonly='readonly' class="validate_issueing" trg="<?php echo $temp; ?>" id='Store_Balance<?php echo $temp; ?>' value='<?php echo $Store_Balance; ?>' style='text-align: right;'></td>
                                <td><input type='text' name='Req_Balance' readonly='readonly' class="validate_issueing" trg="<?php echo $temp; ?>" id='Req_Balance<?php echo $temp; ?>' value='<?php echo $Store_Balance_Req; ?>' style='text-align: right;'></td>
                                <td><input type='text' name='Quantity_Required' readonly='readonly' class="validate_issueing" trg="<?php echo $temp; ?>" id='Quantity_Required<?php echo $temp; ?>' value='<?php echo $data['Quantity_Required']; ?>' style='text-align: right;'></td>

                                <?php if ($Process_Status == 'processed') { ?>
                                    <td><input type='text' name='Container_Issued[]' id='Container_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Container_Issued']; ?>' style='text-align: center;'></td>
                                    <td><input type='text' name='Items_Issued[]' id='Items_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Items_Per_Container']; ?>' style='text-align: center;'></td>
                                    <td><input type='text' name='Quantity_Issued[]' class="validate_issueing" trg="<?php echo $temp; ?>" id='Quantity_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Quantity_Issued']; ?>' style='text-align: center;'></td>
                                <?php } else { ?>

                                    <td><input type='text' name='Container_Issued[]' id='Container_Issued<?php echo $temp; ?>' value="<?php
                                                                                                                                        if ($data['Container_Issued'] > 0) {
                                                                                                                                            echo $data['Container_Issued'];
                                                                                                                                        }
                                                                                                                                        ?>" required='required' autocomplete='off' style='text-align: center;' oninput="numberOnly(this);Generate_Quantity_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>)"></td>

                                    <td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' autocomplete='off' value="<?php
                                                                                                                                                        if ($data['Quantity_Issued'] > 0) {
                                                                                                                                                            echo $data['Quantity_Issued'];
                                                                                                                                                        }
                                                                                                                                                        ?>" style='text-align: center;' oninput="numberOnly(this);Generate_Container_Items_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>,<?= $select_appropriate_bar_code ?>,<?= $Item_ID ?>)"></td>

                                    <td><input type='text' name='Items_Issued[]' id='Items_Issued<?php echo $temp; ?>' required='required' value="<?php
                                                                                                                                                    if ($data['Items_Per_Container'] > 0) {
                                                                                                                                                        echo $data['Items_Per_Container'];
                                                                                                                                                    } ?>" autocomplete='off' style='text-align: center;' oninput="numberOnly(this);
                                               Generate_Quantity_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>)"></td>


                                <?php } ?>
                                <?php
                                if ($Process_Status == 'processed') {
                                    $Last_Buy_Price = $data['Last_Buying_Price'];
                                } else {
                                    $Last_Buy_Price = Get_Last_Buy_Price($Item_ID);
                                }
                                if ($Process_Status == 'processed') {
                                    $Selling_Price = $data['Selling_Price'];
                                } else {
                                    $Selling_Price = Get_Selling_Price($Item_ID, $Deparment_Requesting_ID);
                                }
                                if ($Selling_Price == "not_exist") {
                                    $Selling_Price = $Last_Buy_Price;
                                }
                                ?>
                                <td><input type="text" name="Last_Buy_Price" id="Last_Buy_Price" readonly="readonly" value="<?php echo $Last_Buy_Price; ?>" style="text-align: center;"></td>
                                <td><input type="text" name="Last_Buy_Price" id="Last_Buy_Price" readonly="readonly" value="<?php echo $Selling_Price; ?>" style="text-align: center;"></td>
                                <td>
                                    <?php if (empty($Bar_Code)) { ?>
                                        <input type="text" name="item_bar_code[]" id="item_bar_code<?= $temp ?>" style="text-align: right;" readonly="readonly" value="<?= $select_appropriate_bar_code ?>" style="text-align: center;">
                                        <input type="hidden" name="item_bar_code[]" id="item_bar_code<?= $temp ?>" placeholder="Item Bar Code" value="" style="text-align: center;">
                                    <?php } else { ?>
                                        <input type="text" name="item_bar_code" id="item_bar_code<?= $temp ?>" placeholder="" value="<?= $select_appropriate_bar_code ?>" style="text-align: center;">
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($Process_Status == 'processed') { ?>
                                        <input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>' value='<?php echo ($data['Quantity_Required'] - $data['Quantity_Issued']); ?>' style='text-align: right;'>
                                    <?php } else { ?>
                                        <input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>' value='<?php echo ($data['Quantity_Required'] - $data['Quantity_Issued']); ?>' style='text-align: right;'>
                                    <?php } ?>
                                    <input type='hidden' name='Array_Size' id='Array_Size' value='<?php echo ($no - 1); ?>'>
                                    <input type='hidden' name='Submit_Issue_Note' id='Submit_Issue_Note' value='True'>
                                    <input type='hidden' name='Requisition_Item_ID[]' id='Requisition_Item_ID[]' value='<?php echo $data['Requisition_Item_ID']; ?>'>
                                </td>
                            </tr>
                <?php
                            $temp++;
                        }
                    }
                }
                ?>
            </table>
        </center>
    </fieldset>
</form>

<div id="Invalid_Input">
    <center>Invalid Username or password</center>
</div>
<div id="No_Items">
    <center>No items found</center>
</div>
<div id="General_Sms">
    <center>Process fail. Try again</center>
</div>
<div id="Insufficient_Balance">
    <center>YOU DO NOT HAVE ENOUGH BALANCE FOR THIS ITEM</center>
</div>

<script>
    function checkBatch(itemNumber) {
        var quantity = $('#Items_Issued' + itemNumber).val();
        var item_bar_code = $('#item_bar_code' + itemNumber).val();
        var sub_department_id = '<?= $Sub_Department_ID ?>';
        var itemsId = $('#itemsId' + itemNumber).val();
        var request = 'check-items-in-batch';

        $.get('batch-configuration.php', {
            quantity: quantity,
            item_bar_code: item_bar_code,
            sub_department_id: sub_department_id,
            request: request,
            itemsId: itemsId
        }, (response) => {
            if (response != "go") {
                alert("Batch balance is : " + response);
                $('#Items_Issued' + itemNumber).css('border', '1px solid red');
                $('#Items_Issued' + itemNumber).val(0);
                $('#Quantity_Issued' + itemNumber).val(0);
            } else {
                $('#Items_Issued' + itemNumber).css('border', '1px solid #ccc')
            }
        })
    }
</script>

<script type="text/javascript">
    function check_if_valid_user_to_approve_this_document() {
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;
        var Issue_ID = '<?php echo $Issue_ID; ?>';
        $.ajax({
            type: 'GET',
            url: 'verify_approver_privileges_support.php',
            data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Issue_ID + "&document_type=issue_note",
            cache: false,
            success: function(feedback) {
                if (feedback == 'all_approve_success') {
                    $("#remove_button_column").hide();
                    Approve_Issue_Note();
                } else if (feedback == "invalid_privileges") {
                    alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                } else if (feedback == "fail_to_approve") {
                    alert("Fail to approve..please try again");
                } else {
                    alert(feedback);
                }
            }
        });
    }

    function Approve_Issue_Note() {
        var Supervisor_Username = document.getElementById("Supervisor_Username").value;
        var Supervisor_Password = document.getElementById("Supervisor_Password").value;
        var Supervisor_Comment = document.getElementById("Supervisor_Comment").value;
        var Issue_ID = '<?php echo $Issue_ID; ?>';
        var Deparment_Requesting_ID = '<?= $Deparment_Requesting_ID ?>';

        if (Supervisor_Username != null && Supervisor_Username != '' && Supervisor_Password != null && Supervisor_Password != '' && Issue_ID != null && Issue_ID != '') {
            var sms = confirm("Are you sure you want to approve this issue note");
            if (sms == true) {
                if (window.XMLHttpRequest) {
                    myObjectApprove = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectApprove = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectApprove.overrideMimeType('text/xml');
                }

                myObjectApprove.onreadystatechange = function() {
                    data_approve = myObjectApprove.responseText;
                    if (myObjectApprove.readyState == 4) {
                        var feedback = data_approve;
                        if (feedback == 1) {
                            alert("Issue Note approved successfully");
                            document.location = "issuenotes.php?Status=NewIssueNote&IssueNote=IssueNoteThisPage";
                        } else if (feedback == 'nop') {
                            document.getElementById("Supervisor_Username").value = '';
                            document.getElementById("Supervisor_Password").value = '';
                            $("#Invalid_Input").dialog("open");
                        } else if (feedback == 'non') {
                            $("#No_Items").dialog("open");
                        } else {
                            $("#General_Sms").dialog("open");
                        }
                    }
                }; //specify name of function that will handle server response........
                myObjectApprove.open('GET', 'Approve_Issue_Note.php?Supervisor_Username=' + Supervisor_Username + '&Supervisor_Password=' + Supervisor_Password + '&Issue_ID=' + Issue_ID + '&Supervisor_Comment=' + Supervisor_Comment + '&Deparment_Requesting_ID=' + Deparment_Requesting_ID, true);
                myObjectApprove.send();
            }
        } else {
            if (Supervisor_Username == null || Supervisor_Username == '') {
                document.getElementById("Supervisor_Username").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("Supervisor_Username").style = 'border: 3px solid white; text-align: center;';
            }

            if (Supervisor_Password == null || Supervisor_Password == '') {
                document.getElementById("Supervisor_Password").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("Supervisor_Password").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Generate_Container_Items_Issued(temp, Requisition_Item_ID, Batch_No, Item_Id) {
        document.getElementById("Container_Issued" + temp).value = 1;
        var Quantity_Issued = parseInt(document.getElementById("Quantity_Issued" + temp).value);
        var Store_Balance = parseInt(document.getElementById("Store_Balance" + temp).value);

        var object_response = checkItemsLeftInBatch(Batch_No, Item_Id, Quantity_Issued);

        if (object_response != 0) {
            alert("The batch contains : " + object_response + " Items");
        } else {
            if (Quantity_Issued > Store_Balance) {
                document.getElementById("Quantity_Issued" + temp).style.backgroundColor = 'red';
                document.getElementById('Submit').style.display = true;
                alert("Quantity issued is greater than Store balance");
                exit();
            } else {
                document.getElementById("Quantity_Issued" + temp).style.backgroundColor = 'white';
                if (Quantity_Issued == '') {
                    Quantity_Issued = 0;
                    document.getElementById("Quantity_Issued" + temp).value = Quantity_Issued;
                } else {
                    Quantity_Issued = parseInt(Quantity_Issued);
                }

                var Store_Balance = parseInt(document.getElementById("Store_Balance" + temp).value);
                var Quantity_Required = parseInt(document.getElementById("Quantity_Required" + temp).value);
                document.getElementById("Items_Issued" + temp).value = Quantity_Issued;

                var Outstanding = Quantity_Required - Quantity_Issued;
                document.getElementById(temp).value = Outstanding;

                Update_Total_Items(temp, Requisition_Item_ID);
            }
        }

    }

    function checkItemsLeftInBatch(Batch_No, Item_Id, Quantity_Issued) {
        var request_check_item_balance = 'request_check_item_balance';
        var obj = $.ajax({
            type: "GET",
            url: "issuer_note_handle.php",
            async: false,
            data: {
                Batch_No: Batch_No,
                Item_Id: Item_Id,
                Quantity_Issued: Quantity_Issued,
                sub_department_id: '<?= $Sub_Department_ID ?>',
                request_check_item_balance: request_check_item_balance
            },
            cache: false,
            success: (response) => {
                return response;
            }
        });

        return obj.responseText;
    }

    function Generate_Quantity_Issued(temp, Requisition_Item_ID) {
        var Containers = document.getElementById("Container_Issued" + temp).value;
        var Items_Issued = document.getElementById("Items_Issued" + temp).value;

        if (Containers == '') {
            Containers = 1;
            document.getElementById("Container_Issued" + temp).value = Containers;
        } else {
            Containers = parseInt(Containers);
        }
        if (Items_Issued == '') {
            Items_Issued = 0;
            document.getElementById("Items_Issued" + temp).value = Items_Issued;
        } else {
            Items_Issued = parseInt(Items_Issued);
        }

        var Store_Balance = parseInt(document.getElementById("Store_Balance" + temp).value);
        var Quantity_Required = parseInt(document.getElementById("Quantity_Required" + temp).value);
        var Quantity_Issued = Containers * Items_Issued;
        document.getElementById("Quantity_Issued" + temp).value = Quantity_Issued;

        var Outstanding = Quantity_Required - Quantity_Issued;

        Update_Total_Items(temp, Requisition_Item_ID);
        document.getElementById(temp).value = Outstanding;
    }
</script>

<script type="text/javascript">
    function Update_Requisition_Item_Quantity(Container_Issued, Items_Issued, Requisition_Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectUpdateItemsQuantity = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateItemsQuantity = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateItemsQuantity.overrideMimeType('text/xml');
        }

        myObjectUpdateItemsQuantity.onreadystatechange = function() {
            data_deleted = myObjectUpdateItemsQuantity.responseText;
            if (myObjectUpdateItemsQuantity.readyState == 4) {
                //alert(data_deleted)
            }
        }; //specify name of function that will handle server response........
        myObjectUpdateItemsQuantity.open('GET', 'Update_Requisition_Item_Quantity.php?Container_Issued=' + Container_Issued + '&Items_Issued=' + Items_Issued + '&Requisition_Item_ID=' + Requisition_Item_ID, true);
        myObjectUpdateItemsQuantity.send();
    }
</script>

<script type="text/javascript">
    function Update_Total_Items(Temp, Requisition_Item_ID) {
        var Container_Issued = parseInt(document.getElementById("Container_Issued" + Temp).value);
        var Items_Issued = parseInt(document.getElementById("Items_Issued" + Temp).value);
        var Quantity_Required = parseInt(document.getElementById("Quantity_Required"+Temp).value)
        var Quantity_Issued1 = parseInt(document.getElementById("Quantity_Issued"+Temp).value);
        var Store_Balance = parseInt(document.getElementById("Store_Balance"+Temp).value);
        
        if(Items_Issued > Quantity_Required ){
            alert("Quantity issued is greater than quantity required.");
            document.getElementById("Quantity_Issued"+Temp).value = 0
            document.getElementById("Items_Issued"+Temp).value = 0;
            Items_Issued = 0;
            Container_Issued = 0;
        }else if(Items_Issued > Store_Balance){
            alert("Quantity Issued is greater than Stock Balance.");
            document.getElementById("Quantity_Issued"+Temp).value = 0
            document.getElementById("Items_Issued"+Temp).value = 0;
            Items_Issued = 0;
            Container_Issued = 0;
        }

        if (window.XMLHttpRequest) {
            myObject_Update_Total = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Update_Total = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Update_Total.overrideMimeType('text/xml');
        }

        myObject_Update_Total.onreadystatechange = function() {
            data98987 = myObject_Update_Total.responseText;
            if (myObject_Update_Total.readyState == 4) {}
        }; //specify name of function that will handle server response........
        myObject_Update_Total.open('GET', 'Issue_Note_Update_Total_Items.php?Container_Issued=' + Container_Issued + '&Items_Issued=' + Items_Issued + '&Requisition_Item_ID=' + Requisition_Item_ID, true);
        myObject_Update_Total.send();
    }
</script>
<script>
    function validate_input(QuantityIssued, StoreBalance, Temp, Requisition_Item_ID) {
        var check = true;
        if (QuantityIssued > StoreBalance) {
            alert("Invalid Input! \nQuantity Issued Should Be Less Or Equal To Item Store Balance");
            document.getElementById("Quantity_Issued" + Temp).value = '';
            document.getElementById("Container_Issued" + Temp).value = '';
            document.getElementById("Items_Issued" + Temp).value = '';
            document.getElementById("Container_Issued" + Temp).focus();
            document.getElementById(Temp).value = '';
            check = false;

            //delete inserted values
            if (window.XMLHttpRequest) {
                myObjectDeleteInsertedValue = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectDeleteInsertedValue = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectDeleteInsertedValue.overrideMimeType('text/xml');
            }

            myObjectDeleteInsertedValue.onreadystatechange = function() {
                data_deleted = myObjectDeleteInsertedValue.responseText;
                if (myObjectDeleteInsertedValue.readyState == 4) {
                    //alert(data_deleted)
                }
            }; //specify name of function that will handle server response........
            myObjectDeleteInsertedValue.open('GET', 'Issue_Note_Delete_Inserted_Value.php?Requisition_Item_ID=' + Requisition_Item_ID, true);
            myObjectDeleteInsertedValue.send();
        }
        return check;
    }
</script>

<script type="text/javascript">
    function Confirm_Remove_Item(Requisition_Item_ID, Item_Name) {
        var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectConfirmRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectConfirmRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectConfirmRemove.overrideMimeType('text/xml');
            }

            myObjectConfirmRemove.onreadystatechange = function() {
                data999 = myObjectConfirmRemove.responseText;
                if (myObjectConfirmRemove.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = data999;
                    window.reload();
                }
            }; //specify name of function that will handle server response........
            myObjectConfirmRemove.open('GET', 'Issue_Note_Remove_Item_Store.php?Requisition_Item_ID=' + Requisition_Item_ID, true);
            myObjectConfirmRemove.send();
        }
    }
</script>

<script type="text/javascript">
    function Update_Container_Issue(temp, Requisition_Item_ID) {
        var Containers = document.getElementById("Container_Issued" + temp).value;
        var Items_Issued = document.getElementById("Items_Issued" + temp).value;

        if (Containers == '') {
            Containers = 0;
            document.getElementById("Container_Issued" + temp).value = Containers;
        } else {
            Containers = parseInt(Containers);
        }
        if (Items_Issued == '') {
            Items_Issued = 0;
            document.getElementById("Items_Issued" + temp).value = Items_Issued;
        } else {
            Items_Issued = parseInt(Items_Issued);
        }

        var Store_Balance = parseInt(document.getElementById("Store_Balance" + temp).value);
        var Quantity_Issued = Containers * Items_Issued;
        document.getElementById("Quantity_Issued" + temp).value = Quantity_Issued;

        /*if(window.XMLHttpRequest) {
         myObjectUpdateCont = new XMLHttpRequest();
         }else if(window.ActiveXObject){
         myObjectUpdateCont = new ActiveXObject('Micrsoft.XMLHTTP');
         myObjectUpdateCont.overrideMimeType('text/xml');
         }
         
         if (validate_input(Total , Store_Balance,temp,Requisition_Item_ID)) {
         var Outstanding = (Quantity_Required - Total);
         document.getElementById(temp).value = Outstanding;
         }
         
         myObjectUpdateCont.onreadystatechange = function (){
         data124 = myObjectUpdateCont.responseText;
         if (myObjectUpdateCont.readyState == 4) {
         //Code ..... ..... ..... ..
         }
         }; //specify name of function that will handle server response........
         myObjectUpdateCont.open('GET','Issue_Note_Update_Container_Issue.php?Requisition_Item_ID='+Requisition_Item_ID+'&Containers='+Containers,true);
         myObjectUpdateCont.send();*/
    }
</script>



<script type="text/javascript">
    function Update_Quantity_Issue(temp, Requisition_Item_ID) {
        var Quantity = document.getElementById("Quantity_Issued" + temp).value;
        if (window.XMLHttpRequest) {
            myObjectUpdateQuantity = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateQuantity = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateQuantity.overrideMimeType('text/xml');
        }

        myObjectUpdateQuantity.onreadystatechange = function() {
            data123 = myObjectUpdateQuantity.responseText;
            if (myObjectUpdateQuantity.readyState == 4) {
                //Code ..... ..... ..... ..
            }
        }; //specify name of function that will handle server response........
        myObjectUpdateQuantity.open('GET', 'Issue_Note_Update_Quantity_Issue.php?Requisition_Item_ID=' + Requisition_Item_ID + '&Quantity=' + Quantity, true);
        myObjectUpdateQuantity.send();
    }
</script>

<script type="text/javascript">
    function Update_Items_Issue(temp, Requisition_Item_ID) {
        var Items = document.getElementById("Items_Issued" + temp).value;
        var Quantity_Required = parseInt(document.getElementById("Quantity_Required" + Temp).value);

        if (Container_Issued > 0 && Items_Issued > 0) {
            Update_Total_Items(Temp, Requisition_Item_ID);
        }

        if (window.XMLHttpRequest) {
            myObjectUpdateItems = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateItems = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateItems.overrideMimeType('text/xml');
        }

        myObjectUpdateItems.onreadystatechange = function() {
            data14 = myObjectUpdateItems.responseText;
            if (myObjectUpdateItems.readyState == 4) {
                //Code ..... ..... ..... ..
            }
        }; //specify name of function that will handle server response........
        myObjectUpdateItems.open('GET', 'Issue_Note_Update_Items_Issue.php?Requisition_Item_ID=' + Requisition_Item_ID + '&Items=' + Items, true);
        myObjectUpdateItems.send();
    }
</script>


<script type="text/javascript">
    function Clear_Quantity(Location_ID, Requisition_Item_ID) {
        var Quantity_Issued = document.getElementById("Quantity_Issued" + Location_ID).value;
        Container_Issued = parseInt(document.getElementById("Container_Issued" + Location_ID).value = 1);
        Items_Issued = parseInt(document.getElementById("Items_Issued" + Location_ID).value = Quantity_Issued);
        Total = parseInt(document.getElementById("Quantity_Issued" + Location_ID).value);
        Store_Balance = parseInt(document.getElementById("Store_Balance" + Location_ID).value);
        var Quantity_Required = parseInt(document.getElementById("Quantity_Required" + Location_ID).value);

        /*if (validate_input(Total , Store_Balance,Location_ID,Requisition_Item_ID)) {
         var Outstanding = (Quantity_Required - Total);
         document.getElementById(Location_ID).value = Outstanding;
         }*/

        var Outstanding = (Quantity_Required - Total);
        document.getElementById(Location_ID).value = Outstanding;

        if (window.XMLHttpRequest) {
            myObjectClear = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectClear = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectClear.overrideMimeType('text/xml');
        }

        myObjectClear.onreadystatechange = function() {
            data20 = myObjectClear.responseText;
            if (myObjectClear.readyState == 4) {

            }
        }; //specify name of function that will handle server response........
        myObjectClear.open('GET', 'Issue_Note_Update_Entered_Values.php?Quantity_Issued=' + Quantity_Issued + '&Location_ID=' + Location_ID + '&Requisition_Item_ID=' + Requisition_Item_ID, true);
        myObjectClear.send();
    }
</script>

<script>
    function validate_input2(Requisition_Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectDeleteInsertedValue2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDeleteInsertedValue2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDeleteInsertedValue2.overrideMimeType('text/xml');
        }

        myObjectDeleteInsertedValue2.onreadystatechange = function() {
            data_deleted = myObjectDeleteInsertedValue2.responseText;
            if (myObjectDeleteInsertedValue2.readyState == 4) {

            }
        }; //specify name of function that will handle server response........
        myObjectDeleteInsertedValue2.open('GET', 'Issue_Note_Delete_Inserted_Value.php?Requisition_Item_ID=' + Requisition_Item_ID, true);
        myObjectDeleteInsertedValue2.send();
    }
</script>
<?php
//get details sent
if (isset($_POST['Submit_Issue_Note']) && isset($_SESSION['Issue_Note']['Requisition_ID'])) {
    $Quantity_Issued = $_POST['Quantity_Issued']; //array values
    $Bar_Code = $_POST['Bar_Code']; //array values
    $Array_Size = $_POST['Array_Size'];
    if ($Quantity_Issued[0] != null && $Quantity_Issued[0] != '' && $Quantity_Issued[$Array_Size] != null && $Quantity_Issued[$Array_Size] != '') {

        $Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];
        $Insert_Status = 'false';
        $Array_Size = $_POST['Array_Size'];
        $Issue_Description = $_POST['Issue_Description'];
        $IV_Number = $_POST['IV_Number'];
        $Receiving_Officer = $_POST['Receiving_Officer'];
        $Quantity_Issued = $_POST['Quantity_Issued']; //array values
        $Requisition_Item_ID = $_POST['Requisition_Item_ID']; //array values
        $Container_Issued = $_POST['Container_Issued']; //array values
        $Items_Issued = $_POST['Items_Issued']; //array values


        $insert_value = "INSERT into tbl_issues(Requisition_ID,Issue_Date_And_Time,Issue_Date,Employee_ID,Branch_ID,Issue_Description,Receiving_Officer,IV_Number)
						values('$Requisition_ID',(select now()),(select now()),'$Employee_ID','$Branch_ID','$Issue_Description','$Receiving_Officer','$IV_Number')";

        $resultISID = true;

        $_SESSION['HAS_ERROR'] = false;
        Start_Transaction();


        $get_Issue_ID = mysqli_query($conn, "SELECT Issue_ID FROM tbl_issues WHERE employee_id = '$Employee_ID' and
						Requisition_ID = '$Requisition_ID'
					        ORDER BY Issue_ID desc limit 1") or die(mysqli_error($conn));


        if (mysqli_num_rows($get_Issue_ID) > 0) {
            $data = mysqli_fetch_array($get_Issue_ID);
            $Issue_ID = $data['Issue_ID'];
            die("1");
        } else {
            $resultISID = mysqli_query($conn, $insert_value) or die(mysqli_error($conn));

            if (!$resultISID) {
                $_SESSION['HAS_ERROR'] = true;
                die("2");
            }

            $get_Issue_ID = mysqli_query($conn, "SELECT Issue_ID FROM tbl_issues WHERE employee_id = '$Employee_ID' and
						Requisition_ID = '$Requisition_ID'
					        ORDER BY Issue_ID DESC limit 1") or die(mysqli_error($conn));

            $data = mysqli_fetch_array($get_Issue_ID);
            $Issue_ID = $data['Issue_ID'];
        }

        for ($i = 0; $i <= $Array_Size; $i++) {

            if ($Issue_ID != 0) {
                //update tbl_requisition_items table
                $sql_select_item_id_result = mysqli_query($conn, "SELECT Item_ID FROM tbl_requisition_items WHERE Requisition_Item_ID = '$Requisition_Item_ID[$i]'") or die(mysqli_error($conn));
                $Item_ID = mysqli_fetch_assoc($sql_select_item_id_result)['Item_ID'];
                $Selling_Price = Get_Selling_Price($Item_ID, $Deparment_Requesting_ID);
                $Last_Buy_Price = Get_Last_Buy_Price($Item_ID);

                if ($Selling_Price == "not_exist") {
                    $Selling_Price = $Last_Buy_Price;
                }
                $update_items = "UPDATE tbl_requisition_items SET Quantity_Issued = '" . str_replace(',', '', $Quantity_Issued[$i]) . "',
								 Container_Issued = '" . str_replace(',', '', $Container_Issued[$i]) . "',
								 Items_Per_Container = '" . str_replace(',', '', $Items_Issued[$i]) . "',
                                 Last_Buying_Price='$Last_Buy_Price', Selling_Price='$Selling_Price',
                                 Issue_ID = '$Issue_ID' WHERE Requisition_Item_ID = '$Requisition_Item_ID[$i]'";

                // die($update_items);

                $result2 = mysqli_query($conn, $update_items) or die(mysqli_error($conn));
                if (!$result2) {
                    $_SESSION['HAS_ERROR'] = true;
                    die("3");
                }
            } else {
                $_SESSION['HAS_ERROR'] = true;
                die("4");
            }
        }

        $result3 = mysqli_query($conn, "UPDATE tbl_requisition SET Requisition_Status = 'Not Approved' WHERE Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        if (!$result3) {
            $_SESSION['HAS_ERROR'] = true;
            die("5");
        }

        if (!$_SESSION['HAS_ERROR']) {
            Commit_Transaction();
            echo "<script>
                        alert('Process Successful');
                        document.location = 'issuenotes.php?Status=ServedIssueNote&IssueNote=IssueNoteThisPage';
                     </script>";
        } else {
            Rollback_Transaction();
            echo "<script>
                        alert('Process Fail! Please Try Again');
                        document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=" . $Requisition_ID . "';
                    </script>";
        }
    } else {
        echo "<script>
                    alert('Please Fill All Required Details!');
                        </script>";
    }
}
?>

<style>
    #feeack_cusotom_table {
        font-size: 16px;
        border: 1px solid #ccc;
    }

    #feeack_cusotom_table tr td {
        padding: 5px;
    }
</style>
<div id='feedback_space' style="font-size:16px"></div>

<script type="text/javascript">
    function Preview_Grn_Issue_Note(Issue_ID) {
        window.open('previousissuenotereport.php?Issue_ID=' + Issue_ID + '&PreviousIssueNote=PreviousIssueNoteThisPage', '_blank');
    }

    /*function popupwindow(url, title, w, h) {
     var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
     var wTop = window.screenTop ? window.screenTop : window.screenY;
     
     var left = wLeft + (window.innerWidth / 2) - (w / 2);
     var top = wTop + (window.innerHeight / 2) - (h / 2);
     var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
     return mypopupWindow;
     }*/
</script>


<!-- <link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css"> -->

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<script src="script.responsive.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>

<style>
    .req_changes {
        border: 2px solid red;
    }
</style>

<script>
    $(document).ready(function() {
        $("#Invalid_Input").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#No_Items").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#General_Sms").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
        $("#Insufficient_Balance").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ ERROR!',
            modal: true
        });
    });
</script>

<script>

</script>

<script>
    function validate_issues() {
        var has_error = false;
        var has_bln_error = false;
        var item_bar_code = $("input[id='item_bar_code']").map(function() {
            return $(this).val();
        }).get();
        var item_id = $("input[id='itemsId']").map(function() {
            return $(this).val();
        }).get();

        var Qty_Issued = "";
        for (var i = 1; i <= item_id.length; i++) {
            var Qty_Issued = +$('#Quantity_Issued' + i).val() + ", ";
            console.log(Qty_Issued);
        }


        $('.validate_issueing').each(function() {
            var trg = $(this).attr('trg');
            var Store_Balance = parseInt($('#Store_Balance' + trg).val());
            var Quantity_Issued = parseInt($('#Quantity_Issued' + trg).val());

            if (isNaN(Quantity_Issued)) {
                $('#Quantity_Issued' + trg).css('border', '2px solid red');
                has_error = true;
            } else if (Quantity_Issued > Store_Balance) {
                $('#Store_Balance' + trg).css('border', '2px solid red');
                $('#Quantity_Issued' + trg).css('border', '2px solid red');
                has_bln_error = true;
            } else {
                $('#Store_Balance' + trg).css('border', '1px solid #ccc');
                $('#Quantity_Issued' + trg).css('border', '1px solid #ccc');
            }
        });

        if (has_error) {
            alertMsgSimple("Incorrect quantity issued.Please coorect the reded field", "Need correction", "error", 0, false, 'Ok');
        } else if (has_bln_error) {
            alertMsgSimple("You cannot issue what you don't have.Please coorect the reded field", "Need correction", "error", 0, false, 'Ok');
        } else {
            go();
        }

    }
</script>


<!-- depreciated -->
<script>
    function checkItemBarCodeBeforeDispense(itemBarCodes, itemsId, Qty_Issued) {
        var itemBarCodeRequest = "itemBarCodeRequest";
        var sub_department_id = '<?= $Sub_Department_ID ?>';
        var Requisition_ID = $('#Requisition_Number').val();
        $.ajax({
            type: "GET",
            url: "issuer_note_handle.php",
            data: {
                itemBarCodes: itemBarCodes,
                itemBarCodeRequest: itemBarCodeRequest,
                itemsId: itemsId,
                sub_department_id: sub_department_id,
                Qty_Issued: Qty_Issued,
                Requisition_ID: Requisition_ID
            },
            cache: false,
            success: (response) => {
                if (response != "good") {
                    $("#feedback_space").dialog({
                        autoOpen: false,
                        width: '35%',
                        height: 250,
                        title: 'WARNING RESPONSE',
                        modal: true
                    });
                    $("#feedback_space").dialog("open").html(response);
                } else {
                    go();
                }
            }
        });
    }
</script>
<!-- depreciated currently not used -->

<script>
    function go() {
        if (confirm('Are you sure you want to process this issue note?')) {
            document.getElementById('issu_Form').submit();
        }
    }
</script>

<?php
include("./includes/footer.php");
?>