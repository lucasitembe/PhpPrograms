<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/items.php");

$Grn_Status = '';
$Grn_Issue_Note_ID = '';

$Insert_Status = 'false';

if (isset($_SESSION['HAS_ERROR'])) {
    unset($_SESSION['HAS_ERROR']);
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }if ($_SESSION['userinfo']['can_edit'] != 'yes') {
           //commented due to confusion to new update
           //  header("Location: ./grnpreviouslist.php?GrnPreviousList=GrnPreviousListThisPage");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$Store_Need=$_SESSION['Storage_Info']['Sub_Department_ID'];
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get sub departments
$Search_Values = '';
// $select = mysqli_query($conn,"select Sub_Department_ID from tbl_employee_sub_department where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
// $nm = mysqli_num_rows($select);
if ($nm > 0) {
    while ($row = mysqli_fetch_array($select)) {
        if ($Search_Values == '') {
            $Search_Values .= $row['Sub_Department_ID'];
        } else {
            $Search_Values .= ',' . $row['Sub_Department_ID'];
        }
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
//get number of pending request
        if (isset($_SESSION['Storage_Info'])) {
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        } else {
            $Sub_Department_ID = '';
        }

        $select_Order_Number = mysqli_query($conn,"select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
														tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
														rq.store_issue = sd.sub_department_id and
														emp.employee_id = rq.employee_id and
														rq.requisition_status = 'served' and
														isu.Requisition_ID = rq.Requisition_ID and Store_Need='$Store_Need' order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
        $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='grnissuenotelist.php?GrnIssueNoteList=GrnIssueNoteListThisPage' class='art-button-green'>NEW GRN</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnpreviouslist.php?GrnPreviousList=GrnPreviousListThisPage' class='art-button-green'>PREVIOUS GRN</a>"; //against issue note
    }
}

$from = "";
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        if(isset($_GET['from']) && $_GET['from'] == "prevgrn") {
            $from = $_GET['from'];
            echo "<a href='grnpreviouslist.php?GrnPreviousList=GrnPreviousListThisPage&from=prevgrn' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='grnissuenotelist.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
        }
    }
}

//get sub department id & name
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
}
?>


<?php
//get all important Requisition details
if (isset($_SESSION['Grn_Issue_ID'])) {
    $Issue_ID = $_SESSION['Grn_Issue_ID'];

    if (isset($_GET['Grn_Issue_Note_ID'])) {
        $Grn_Issue_Note_ID = $_GET['Grn_Issue_Note_ID'];
        //Get Issue_ID
        $select = mysqli_query($conn,"select Issue_ID from tbl_grn_issue_note where Grn_Issue_Note_ID = '$Grn_Issue_Note_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            $result = mysqli_fetch_assoc($select);
            $Issue_ID = $result['Issue_ID'];
        }else{
            $Issue_ID = 0;
        }
    }
} else if (isset($_GET['Grn_Issue_Note_ID'])) {
    $Grn_Issue_Note_ID = $_GET['Grn_Issue_Note_ID'];
    //Get Issue_ID
    $select = mysqli_query($conn,"select Issue_ID from tbl_grn_issue_note where Grn_Issue_Note_ID = '$Grn_Issue_Note_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $result = mysqli_fetch_assoc($select);
        $Issue_ID = $result['Issue_ID'];
    }else{
        $Issue_ID = 0;
    }
} else {
    $Issue_ID = 0;
}
//echo "====>$Grn_Issue_Note_ID";
//echo "===>$Issue_ID";
$filter_req="";
if(isset($_SESSION['Requisition_ID'])){
    $Requisition_ID=$_SESSION['Requisition_ID'];
    $filter_req="AND ri.Requisition_ID='$Requisition_ID'";
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}


$select = mysqli_query($conn,"select rq.Requisition_ID, rq.Sent_Date_Time, rq.Requisition_Status, rq.Requisition_Description, emp.Employee_Name, rq.Employee_ID, rq.Store_Need, rq.Store_Issue
				from tbl_requisition rq, tbl_requisition_items ri, tbl_employee emp where
					rq.Requisition_ID = ri.Requisition_ID and
						emp.Employee_ID = rq.Employee_ID and
							Issue_ID = '$Issue_ID' $filter_req group by Issue_ID") or die(mysqli_error($conn));
$no = mysqli_num_rows($select);

if ($no > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Requisition_ID = $data['Requisition_ID'];
        $Sent_Date_Time = $data['Sent_Date_Time'];
        $Requisition_Description = $data['Requisition_Description'];
        $Employee_Name = $data['Employee_Name'];
        $Store_Need = $data['Store_Need'];
        $Store_Issue = $data['Store_Issue'];
        $Requisition_Status = $data['Requisition_Status'];
        $Temp_Employee_ID = $data['Employee_ID']; //employee id (prepare the requisition)
    }
} else {
    $Requisition_ID = 0;
    $Sent_Date_Time = '';
    $Requisition_Description = '';
    $Employee_Name = '';
    $Store_Need = 0;
    $Store_Issue = 0;
    $Requisition_Status = '';
    $Temp_Employee_ID = 0; //employee id (prepare the requisition)
}

//get sub department names and employee prepare selected requisition
if ($Requisition_ID != 0) {
//get store need
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Temp_Store_Need = $row['Sub_Department_Name'];
        }
    } else {
        $Temp_Store_Need = '';
    }


//get store issue
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Temp_Store_Issue = $row['Sub_Department_Name'];
        }
    } else {
        $Temp_Store_Issue = '';
    }


//get employee prepare 
    $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Temp_Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Temp_Employee_Name = $row['Employee_Name'];
        }
    } else {
        $Temp_Employee_Name = '';
    }
} else {
    $Temp_Store_Issue = '';
    $Temp_Store_Need = '';
    $Temp_Employee_Name = '';
}

//get employee name and id
//employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}
?>

<?php
$select_grn_details = mysqli_query($conn,"select * from tbl_grn_issue_note  g join tbl_employee e ON g.Employee_ID=e.Employee_ID 
						where Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
$nop = mysqli_num_rows($select_grn_details);
$Control = 'False';
if ($nop > 0) {
    $Control = 'True';
    while ($Grn_row = mysqli_fetch_array($select_grn_details)) {
        $Created_Date_Time = $Grn_row['Created_Date_Time'];
        $Grn_Issue_Note_ID = $Grn_row['Grn_Issue_Note_ID'];
        $Issue_Description = $Grn_row['Issue_Description'];
        $Receiver = $Grn_row['Employee_Name'];
    }
} else {
    $Control = 'False';
    $Grn_Issue_Note_ID = 'New';
    $Created_Date_Time = '';
    $Issue_Description = '';
    $Receiver = '';
}
?>



<fieldset>
    <legend align="right">
        Grn against issue note
    </legend>

    <table width=100%>
        <tr>
            <td width='10%' style='text-align: right;'>Requisition Number</td>
            <td width='26%'>
                <input type='text' name='order_id'  id='order_id' value='<?php echo $Requisition_ID; ?>' readonly='readonly'>
            </td>
            <td width='10%' style='text-align: right;'>GRN Number</td>
            <td width='16%'>
                <input type='text' name='grn_number'  id='grn_number' value='<?php
                if (strtolower($Requisition_Status) == 'received') {
                    echo $Grn_Issue_Note_ID;
                }
                ?>' readonly='readonly'>
            </td>
        </tr>                               
        <tr>
            <td width='10%' style='text-align: right;'>Sent Date & Time</td>
            <td width='26%'>
                <input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Sent_Date_Time; ?>" >
            </td>
            <td style='text-align: right;'>GRN Date & Time</td>
            <td width='16%'>
                <input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php
                if (strtolower($Requisition_Status) == 'received') {
                    echo $Created_Date_Time;
                }
                ?>'>
            </td>
        </tr>                              
        <tr>
            <td width='10%' style='text-align: right;'>Received From</td>
            <td width='26%'>
                <input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Temp_Store_Issue; ?>" >
            </td>
            <td style='text-align: right;'>Store Need</td>
            <td width='16%'><input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php echo $Temp_Store_Need; ?>'></td>
        </tr> 
        <tr>
            <td width='10%' style='text-align: right;'>Requisition Description</td>
            <td width='26%'><input type='text' name='Supplier_Name'  id='Supplier_Name' value='<?php echo $Requisition_Description; ?>' readonly='readonly'></td>

            <td style='text-align: right;'>Received By</td>
            <?php
            //get employee name from the session
            if (!isset($Receiver)) {
                $Receiver = $_SESSION['userinfo']['Employee_Name'];
            }
            ?>
            <td width='26%'>
                <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo $Receiver; ?>'  >
            </td>
        </tr>
        <form action='grnissuenote.php' method='post' name='myForm' id='myForm'>
            <tr>
                <td style='text-align: right;'>Prepared By</td>
                <td><input type='text' name='Employee_Name' id='Employee_Name' value='<?php echo $Temp_Employee_Name; ?>' readonly='readonly'></td>
                <td style='text-align: right;'>GRN Description</td>
                <td>
                    <input type='text' name='Issue_Description' id='Issue_Description' value='<?php echo $Issue_Description; ?>'>
                </td>
            </tr>
    </table>
</center>
</fieldset>






<?php
if (isset($_POST['Subbmit_Grn_Issue_Note'])) {
    $Insert_Status = 'false';

    $Array_Size = $_POST['Array_Size'];
    $Qty_Received = $_POST['Qty_Received']; //array values
    $Item_ID = $_POST['Item_ID']; //array values
    $Requisition_Item_ID = $_POST['Requisition_Item_ID']; //array values
    $Issue_Description = $_POST['Issue_Description'];

    $insert_value = "insert into tbl_grn_issue_note(
						Created_Date,Created_Date_Time,Employee_ID,
							Issue_ID,Issue_Description)
						values((select now()),(select now()),'$Employee_ID',
							'$Issue_ID','$Issue_Description')";


    $_SESSION['HAS_ERROR'] = false;

    Start_Transaction();

//insert data into tbl_grn_issue_note

    $resultGRN = true;


    $get_Grn_Issue_Note_ID = mysqli_query($conn,"select Grn_Issue_Note_ID from
															tbl_grn_issue_note where employee_id = '$Employee_ID' and
															Issue_ID = '$Issue_ID' order by Grn_Issue_Note_ID desc limit 1") or die(mysqli_error($conn));


    if (mysqli_num_rows($get_Grn_Issue_Note_ID) > 0) {
        $data = mysqli_fetch_array($get_Grn_Issue_Note_ID);
        $Grn_Issue_Note_ID = $data['Grn_Issue_Note_ID'];
        $Insert_Status = 'true';
    } else {
        $resultGRN = mysqli_query($conn,$insert_value) or die(mysqli_error($conn));



        if (!$resultGRN) {
            $_SESSION['HAS_ERROR'] = true;
        }

        $get_Grn_Issue_Note_ID_after = mysqli_query($conn,"select Grn_Issue_Note_ID from
															tbl_grn_issue_note where employee_id = '$Employee_ID' and
															Issue_ID = '$Issue_ID' order by Grn_Issue_Note_ID desc limit 1") or die(mysqli_error($conn));
        $data = mysqli_fetch_array($get_Grn_Issue_Note_ID_after);
        $Grn_Issue_Note_ID = $data['Grn_Issue_Note_ID'];
        $Insert_Status = 'true';
    }

    $filter_req_it="";
    if(isset($_SESSION['Requisition_ID'])){
        $Requisition_ID=$_SESSION['Requisition_ID'];
        $filter_req_it="AND Requisition_ID='$Requisition_ID'";
    }
    for ($i = 0; $i <= $Array_Size; $i++) {
        if ($Grn_Issue_Note_ID != 0) {
//update tbl_purchase_order_items table
            
            $update_items = "update tbl_requisition_items set
							Quantity_Received = '" . str_replace(',', '', $Qty_Received[$i]) . "',
								Item_Status = 'received' where
									Requisition_Item_ID = '$Requisition_Item_ID[$i]' and
										Issue_ID = '$Issue_ID' $filter_req_it";
            $result2 = mysqli_query($conn,$update_items);

            if (!$result2) {
                $_SESSION['HAS_ERROR'] = true;
            } else {
//get pre balance
                $select_pre_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($select_pre_balance);
                if ($nm > 0) {
                    while ($dt = mysqli_fetch_array($select_pre_balance)) {
                        $Pre_Balance = $dt['Item_Balance'];
                    }
                } else {
                    $Pre_Balance = 0;
                }

//update balance by adding items received
                $add = mysqli_query($conn,"UPDATE tbl_items_balance set Item_Balance = Item_Balance + '" . str_replace(',', '', $Qty_Received[$i]) . "' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));

                if (!$add) {
                    $_SESSION['HAS_ERROR'] = true;
                }
//update balance by adding items received
//$minus = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance - '".str_replace(',', '', $Qty_Received[$i])."' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
//insert data into tbl_stock_ledger_controler for auditing
                $insert_audit = mysqli_query($conn,"INSERT into tbl_stock_ledger_controler(
                                                        Item_ID, Sub_Department_ID, Movement_Type, 
                                                        Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)

													values('$Item_ID[$i]','$Store_Need','GRN Agains Issue Note',
                                                            '$Pre_Balance',($Pre_Balance + " . str_replace(',', '', $Qty_Received[$i]) . "),(select now()),(select now()),'$Requisition_ID')") or die(mysqli_error($conn));

                if (!$insert_audit) {
                    $_SESSION['HAS_ERROR'] = true;
                }

                // select * from tbl_grn_issue_note g join tbl_employee e ON g.Employee_ID=e.Employee_ID where Issue_ID = '357' 
            }
        }
    }

    //update Requisition
    $resultUpd = mysqli_query($conn,"UPDATE tbl_requisition set Requisition_Status = 'Received' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    $Insert_Status = 'true';

    if (!$resultUpd) {
        $_SESSION['HAS_ERROR'] = true;
    }

    //update grn tbl_grn_issue_note;

    $updteGRNTAB = mysqli_query($conn,"UPDATE tbl_grn_issue_note set Issue_ID = '$Issue_ID' where Grn_Issue_Note_ID = '$Grn_Issue_Note_ID'") or die(mysqli_error($conn));

    if (!$updteGRNTAB) {
        $_SESSION['HAS_ERROR'] = true;
    }

    if (!$_SESSION['HAS_ERROR']) {
        Commit_Transaction();
        echo "<script>
                alert('Process Successful');
                document.location = 'grnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
             </script>";
    } else {
        Rollback_Transaction();
        echo "<script>
                                alert('Process Fail! Please Try Again');
                                document.location = 'grnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
                        </script>";
    }
}
?>



<fieldset>
    <table width=100%>
        <tr>
            <?php if (strtolower($Grn_Status) != 'served') { ?>
                
                    <?php if (strtolower($Requisition_Status) == 'received') { ?>
                        <td style='text-align: right;'>                                                <!--<input type="button" name="Preview" id="Preview" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Grn_Issue_Note(<?php // echo $Issue_ID;            ?>)">-->
                            <a href='grnissuenotereport.php?Issue_ID=<?php echo $Issue_ID; ?>' class='art-button-green' target="_blank">PREVIEW REPORT</a> 
                        </td>
                        <?php } else { ?>
                        <td style='text-align: right;'>
                            <input type='text' placeholder="Enter Username" id="username"/></td><td><input type='password' placeholder="Enter Password" id="password"/></td><td><input type="submit" name='submit' class='hide' id='special_button_for_submit'/><input type='button' name='' id='submit' value='APPROVE' onclick='approve_this_issue_note()' class='art-button-green'>
                        </td>
                    <?php } ?>
               
            <?php } else { ?>
                <td style='text-align: right;'>
                    <a href='grnpurchaseorderreport.php?Grn_Purchase_Order_ID=<?php echo $Grn_Issue_Note_ID; ?>&GrnIssueNote=GrnIssueNoteThisPage' target='_Blank' class='art-button-green'>Preview GRN </a>
                </td>
            <?php } ?>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 240px;'>   
    <center>
        <table width=100%>
                <tr>
                    <td width=3% style='text-align: center;'>Sn</td>
			<td width='5%' style='text-align: center;'>Item Code</td>
                    <td>Item Name</td>
                    <td width=9% style='text-align: right;'>Quantity Required</td>
<?php if($canPakage){ ?>
                    <td width=9% style='text-align: right;'>Units Issued</td>
                    <td width=9% style='text-align: right;'>Items per Unit</td>
<?php } ?>
                    <td width=9% style='text-align: right;' >Quantity Issued</td>
                    <td width=9% style='text-align: right;'>Quantity Received</td>
                    <td width=9% style='text-align: right;'>Buying Price</td>
                    <td width=9% style='text-align: right;'>Total</td>
                    <td width=9% style='text-align: left;'>Item Description</td>
                </tr>
    <?php
//get list of item ordered
$select_items = mysqli_query($conn,"select ri.Requisition_Item_ID,i.Product_Name,i.Product_Code, ri.Quantity_Issued, ri.Quantity_Required, ri.Quantity_Issued, ri.Quantity_Received, i.Item_ID, ri.Requisition_Item_ID, ri.Item_Remark, ri.Container_Issued, ri.Items_Per_Container, ri.Last_Buying_Price from
						tbl_items i, tbl_requisition_items ri where
							i.Item_ID = ri.Item_ID and
								ri.Issue_ID = '$Issue_ID' $filter_req") or die(mysqli_error($conn));
            $no2 = mysqli_num_rows($select_items);
            $temp = 1;
            if ($no2 > 0) {
                while ($row = mysqli_fetch_array($select_items)) {
                    echo "<tr><td><input type='text' value='" . $temp . "'  readonly='readonly' style='text-align: center;'></td>";
		    echo "<td><input type='text' value='" . $row['Product_Code']. "'  readonly='readonly' style='text-align: center;'></td>";
                    echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'>";
                    echo "<input type='hidden' value='" . $row['Item_ID'] . "' name='Item_ID[]' id='Item_ID[]'></td>";
                    echo "<input type='hidden' value='" . $row['Requisition_Item_ID'] . "' name='Requisition_Item_ID[]' id='Requisition_Item_ID[]'></td>";
                    echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";

                    //  if ($canPakage) {
                    echo "<td $display><input type='text' value='" . $row['Container_Issued'] . "' readonly='readonly' style='text-align: right;'></td>";
                    echo "<td $display><input type='text' value='" . $row['Items_Per_Container'] . "' readonly='readonly' style='text-align: right;'></td>";
                    // }

                    echo "<td><input type='text' name='Quantity_Issued' id='Quantity_Issued" . $temp . "' class='qty_issued_" . $row['Requisition_Item_ID'] . "'  value='" . $row['Quantity_Issued'] . "' readonly='readonly' style='text-align: right;'></td>";

                    if ($Grn_Issue_Note_ID == 'New') {
                        //echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received" . $temp . "' autocomplete='off' value='' style='text-align: right;' required='required' onchange='numberOnly(this)' onkeyup='numberOnly(this),update_thi_item_quantity()' onkeypress='numberOnly(this)'  oninput='Validate_Quantity_Received(" . $temp . ")'></td>";
                    $Quantity_Received=$row['Quantity_Received'];
                    $Quantity_Issued=$row['Quantity_Issued'];
                    if($Quantity_Received<=0){
                        $Quantity_Received=$Quantity_Issued;
                    }
                        echo "<td>
                                       <input type='text' title='You can still edit this quantity' class='numberonly qtychanged reqid_" . $row['Requisition_Item_ID'] . "'  name='Qty_Received[]' id='Qty_Received" . $temp . "' autocomplete='off' value='" . $Quantity_Received . "' req_id='" . $row['Requisition_Item_ID'] . "' style='text-align: right;display:inline;margin:0;float:left;'>
<input type='hidden'  id='cache_" . $row['Requisition_Item_ID'] . "' value='" . $Quantity_Received . "' />                                      
<img style='text-align: right;display:inline;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_" . $row['Requisition_Item_ID'] . "'> 
                                           </td>";
                    } else {
                        echo "<td>
                                       <input type='text' readonly='readonly' title='You can still edit this quantity' class='numberonly qtychanged reqid_" . $row['Requisition_Item_ID'] . "'  name='Qty_Received[]' id='Qty_Received" . $temp . "' autocomplete='off' value='" . $row['Quantity_Received'] . "' req_id='" . $row['Requisition_Item_ID'] . "' style='text-align: right;display:inline;margin:0;float:left;'>
<input type='hidden'  id='cache_" . $row['Requisition_Item_ID'] . "' value='" . $row['Quantity_Received'] . "' />                                      
<img style='text-align: right;display:inline;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_" . $row['Requisition_Item_ID'] . "'> 
                                           </td>";
                    }

        echo "<td><input type='text' name ='Last_Buying_Price[]' id ='Last_Buying_Price".$temp."' value='".number_format($row['Last_Buying_Price'])."' style='text-align: right;' readonly='readonly'>";
        echo "<td><input type='text' name = 'Sub_Total[]' id = 'Sub_Total".$temp."' style='text-align: right;' value='".number_format($row['Last_Buying_Price'] * $row['Quantity_Received'])."' readonly='readonly'>";
        echo "<td><input type='text' name='Item_Remark' id='Item_Remark' value='" . $row['Item_Remark'] . "' readonly='readonly'>";
        echo "<input type='hidden' name='Array_Size' id='Array_Size' value='" . ($no2 - 1) . "'>";
        echo "<input type='hidden' name='Subbmit_Grn_Issue_Note' id='Subbmit_Grn_Issue_Note' value='true'>";
        echo "</tr>";
        $temp++;
    }
}
?>
        </table>
        </form>   
    </center>
</fieldset>
</form>




<script>
    function approve_this_issue_note(){
        var password=$("#password").val();
        var username=$("#username").val();
        if(username==""){
            $("#username").css("border","1px solid red");
           return false;
        }else{
            $("#username").css("border","");
        }
        if(password==""){
           $("#password").css("border","1px solid red");
           return false;
        }else{
            $("#password").css("border","");
        }
        var Supervisor_Password=$("#password").val();
        var Supervisor_Username=$("#username").val();
        
        var Requisition_ID = '<?php echo $Requisition_ID; ?>';
         $.ajax({
                        type: 'GET',
                        url: 'verify_approver_privileges_support.php',
                        data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Requisition_ID+"&document_type=grn_against_issue_note",
                        cache: false,
                        success: function (feedback) {
                            if (feedback == 'all_approve_success') {
                                alert("Approved Successfully");
                                //function() {
                                    //document.getElementById("myForm").submit();
                                   // $("#myForm").submit();special_button_for_submit
                               // }
                               // document.getElementById("myForm").submit();
                                    $("#special_button_for_submit").click();
                               //return true;
                            }else if(feedback=="invalid_privileges"){
                                alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                                
                                }else if(feedback=="fail_to_approve"){
                                alert("Fail to approve..please try again"); 
                                
                            }else{
                                alert(feedback);
                                
                            }
                        }
                    });
                  //  return false;
    }
    function Validate_Quantity_Received(Temp) {
        var Quantity_Received = parseInt(document.getElementById("Qty_Received" + Temp).value);
        var Quantity_Issued = parseInt(document.getElementById("Quantity_Issued" + Temp).value);

        if (Quantity_Received == null || Quantity_Received == '') {
            Quantity_Received = 0;
        }

        if (Quantity_Received > Quantity_Issued) {
            alert("Invalid Input! Quantity Received Should Be Less Or Equal To Quantity Issued");
            document.getElementById("Qty_Received" + Temp).value = '';
            document.getElementById("Sub_Total"+Temp).value = 0;
            document.getElementById("Qty_Received" + Temp).focus();
        }else{
            Calculate_Sub_Total(Temp);
        }
    }
</script>

<script type="text/javascript">
    function Calculate_Sub_Total(Temp){
        var Quantity_Received = document.getElementById("Qty_Received" + Temp).value;
        var Last_Buying_Price = document.getElementById("Last_Buying_Price" + Temp).value;
        
       if(window.XMLHttpRequest){
            myObjectCalculate = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectCalculate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectCalculate.overrideMimeType('text/xml');
        }
        
        myObjectCalculate.onreadystatechange = function (){
            dataCal = myObjectCalculate.responseText;
            if (myObjectCalculate.readyState == 4) {
                document.getElementById("Sub_Total"+Temp).value = dataCal;
            }
        }; //specify name of function that will handle server response........
         
        myObjectCalculate.open('GET','Grn_Calculate_Sub_Total.php?Quantity_Received='+Quantity_Received+'&Last_Buying_Price='+Last_Buying_Price,true);
        myObjectCalculate.send();
    }
</script>

<script type="text/javascript">
    function Preview_Grn_Issue_Note(Issue_ID) {
        var winClose = popupwindow('grnissuenotereport.php?Issue_ID=' + Issue_ID + '&GrnIssueNoteReport=GrnIssueNoteReportThisPage', 'GRN ISSUE NOTE DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>
<script>
    $('.qtychanged').focusin().blur(function () {
        var id = $(this).attr('req_id');
        var cachedValue = $('#cache_' + id).val();
        var currentValue = $(this).val();
        var qty_issued = $('.qty_issued_' + id).val();
        var classname = "qty_issued_" + id;

        if (currentValue != '') {
            if (cachedValue != currentValue) {
                if (parseInt(qty_issued) < parseInt(currentValue)) {
                    // alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button)
                    alertMsg("Quantity received shold not be greater than the issued one", "Invalid Data", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                    $(this).val(cachedValue);
                    $(this).focus();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'modifygrnissuenote.php',
                        data: 'action=edit&Requisition_Item_ID=' + id + '&New_Quantity_Received=' + currentValue,
                        cache: false,
                        beforeSend: function (xhr) {
                            $('.reqid_' + id).css('width', '80%');
                            $('#img_' + id).show();
                        },
                        success: function (result) {
                            if (parseInt(result) == 0) {
                                alertMsg("Couldn't complete your request. If problem persits contanct administrator for support", "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                            } else if (parseInt(result) == 1) {
                                $('#cache_' + id).val(currentValue)
                                alertMsg(" Quantity updated successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                            }
                        }, complete: function (jqXHR, textStatus) {
                            $('.reqid_' + id).css('width', '100%');
                            $('#img_' + id).hide();
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            alertMsg(errorThrown, "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                        }
                    });
                }
            } else {

            }
        } else {
            //alert('Vital cannot be empty');
            $(this).val(cachedValue);
        }
    });
</script>
<script>
    $('.qtychanged').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var id = $(this).attr('req_id');
            var cachedValue = $('#cache_' + id).val();
            var currentValue = $(this).val();
            var qty_issued = $('.qty_issued_' + id).val();
            var classname = "qty_issued_" + id;

            if (currentValue != '') {
                if (cachedValue != currentValue) {
                    if (parseInt(qty_issued) < parseInt(currentValue)) {
                        // alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button)
                        alertMsg("Quantity received shold not be greater than the issued one", "Invalid Data", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                        $(this).val(cachedValue);
                        $(this).focus();
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: 'modifygrnissuenote.php',
                            data: 'action=edit&Requisition_Item_ID=' + id + '&New_Quantity_Received=' + currentValue,
                            cache: false,
                            beforeSend: function (xhr) {
                                $('.reqid_' + id).css('width', '80%');
                                $('#img_' + id).show();
                            },
                            success: function (result) {
                                if (parseInt(result) == 0) {
                                    alertMsg("Couldn't complete your request. If problem persits contanct administrator for support", "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                                } else if (parseInt(result) == 1) {
                                    $('#cache_' + id).val(currentValue)
                                    alertMsg(" Quantity updated successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                                }
                            }, complete: function (jqXHR, textStatus) {
                                $('.reqid_' + id).css('width', '100%');
                                $('#img_' + id).hide();
                            }, error: function (jqXHR, textStatus, errorThrown) {
                                alertMsg(errorThrown, "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                            }
                        });
                    }
                } else {

                }
            } else {
                //alert('Vital cannot be empty');
                $(this).val(cachedValue);
            }
        }

    });
</script>

<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>

<?php
include("./includes/footer.php");
?>
