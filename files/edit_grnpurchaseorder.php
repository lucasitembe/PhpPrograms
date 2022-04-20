<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

$Grn_Status = '';
$Grn_Purchase_Order_ID = '';

$Insert_Status = 'fasle';
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


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //get number of pending request
        if (isset($_SESSION['Storage'])) {
            $Sub_Department_Name = $_SESSION['Storage'];
        } else {
            $Sub_Department_Name = '';
        }

        $select_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
					    po.sub_department_id = sd.sub_department_id and
					    po.supplier_id = sp.supplier_id and
						po.order_status = 'submitted' and
						po.Approval_Level = po.Approvals and
                        sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by purchase_order_id") or die(mysqli_error($conn));
        $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage' class='art-button-green'>NEW GRN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        $Select_Pending_Order_Number = mysqli_query($conn,"select po.Pending_Purchase_Order_ID from tbl_pending_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
                            po.sub_department_id = sd.sub_department_id and
                            po.supplier_id = sp.supplier_id and
                            po.order_status = 'pending' and
                            sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by pending_purchase_order_id") or die(mysqli_error($conn));
        $number = mysqli_num_rows($Select_Pending_Order_Number);
        echo "<a href='grnpendingpurchaseorderlist.php?PendingPurchaseOrderList=PendingPurchaseOrderListThisPage' class='art-button-green'>PENDING PURCHASE ORDER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = 0;
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='previousgrnlist.php?PreviousGrn=PreviousGrnThisPage' class='art-button-green'>PREVIOUS GRN</a>";
    }
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='grnpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' class='art-button-green'>BACK</a>";
    }
}

//echo $Purchase_Order_ID;
//exit();
?>

<?php
//get sub department id
if (isset($_SESSION['Storage'])) {
    $Sub_Department_Name = $_SESSION['Storage'];

    $sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'") or die(mysqli_error($conn));
    $no_rows = mysqli_num_rows($sql_select);
    if ($no_rows > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            $Sub_Department_ID = $row['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }
}
?>


<?php

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

//get information
$select_purchase_details = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_supplier sp
						where po.supplier_id = sp.supplier_id and 
									po.purchase_order_id = '$Purchase_Order_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_purchase_details);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_purchase_details)) {
        $Purchase_Order_ID = $row['Purchase_Order_ID'];
        $Created_Date = $row['Created_Date'];
        $Supplier_Name = $row['Supplier_Name'];
        $Supplier_ID = $row['Supplier_ID'];
    }
} else {
    $Purchase_Order_ID = '';
    $Created_Date = '';
    $Supplier_Name = '';
    $Supplier_ID = '';
}
?>

<?php
//get the GRN status
if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = 0;
}


$select_GRN_Status = mysqli_query($conn,"select Order_Status,Grn_Purchase_Order_ID from tbl_purchase_order where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
$nop = mysqli_num_rows($select_GRN_Status);
if ($nop > 0) {
    while ($Grn_row = mysqli_fetch_array($select_GRN_Status)) {
        $Grn_Status = $Grn_row['Order_Status'];
        $Grn_Purchase_Order_ID = $Grn_row['Grn_Purchase_Order_ID'];
    }
    if (strtolower($Grn_Status) == 'served' && $Grn_Purchase_Order_ID != 0) {
        $select_ID_and_Date = mysqli_query($conn,"SELECT
                                                  Grn_Purchase_Order_ID, Created_Date_Time,
                                                  Debit_Note_Number, Invoice_Number, Delivery_Date, Delivery_Person
                                               FROM
                                                  tbl_grn_purchase_order
                                               WHERE
							                      Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID'");
        $no = mysqli_num_rows($select_ID_and_Date);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($select_ID_and_Date)) {
                $Grn_Purchase_Order_ID = $row['Grn_Purchase_Order_ID'];
                $Created_Date_Time = $row['Created_Date_Time'];

                $Debit_Note_Number = $row['Debit_Note_Number'];
                $Invoice_Number = $row['Invoice_Number'];
                $Delivery_Date = $row['Delivery_Date'];
                $Delivery_Person = $row['Delivery_Person'];
            }
        } else {
            $Grn_Purchase_Order_ID = '';
            $Created_Date_Time = '';

            $Debit_Note_Number = '';
            $Invoice_Number = '';
            $Delivery_Date = '';
            $Delivery_Person = '';
        }
    } else {
        $Grn_Purchase_Order_ID = '';
        $Created_Date_Time = '';

        $Debit_Note_Number = '';
        $Invoice_Number = '';
        $Delivery_Date = '';
        $Delivery_Person = '';
    }
} else {
    $Grn_Purchase_Order_ID = '';
    $Created_Date_Time = '';

    $Debit_Note_Number = '';
    $Invoice_Number = '';
    $Delivery_Date = '';
    $Delivery_Person = '';
}

?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    td:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend style="background-color:#006400;color:white;padding:5px;" align="right"><?php if (isset($_SESSION['Storage'])) {
    echo $_SESSION['Storage'];
} ?>, GRN Against Purchase Order</legend>      
        <!--<form action='grn_process.php?pgfrom=purchase_order' method='post' name='myForm' id='myForm'>-->
        <fieldset>   
            <center> 
                <table width=100%>
                    <br/>
                    <tr>
                        <td width='10%' style='text-align: right;'>LPO Number</td>
                        <td width='26%'>
                            <input type='text' name='order_id'  id='order_id' value='<?php echo $Purchase_Order_ID; ?>' readonly='readonly'>
                        </td>
                        <td width='13%' style='text-align: right;'>GRN Number</td>
                        <td width='16%'><input type='text' name='grn_number'  id='grn_number' value='<?php echo $Grn_Purchase_Order_ID; ?>' readonly='readonly'></td> 
                    </tr>                               
                    <tr>
                        <td width='10%' style='text-align: right;'>Order Date</td>
                        <td width='26%'>
                            <input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Created_Date; ?>" >
                        </td>
                        <td width='13%' style='text-align: right;'>GRN Date</td>
                        <td width='16%'><input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php echo $Created_Date_Time; ?>'></td>
                    </tr> 
                    <tr>
                        <td width='10%' style='text-align: right;'>Supplier</td>
                        <td width='26%'><input type='text' name='Supplier_Name'  id='Supplier_Name' value='<?php echo $Supplier_Name; ?>' readonly='readonly'></td>
                        <td width='16%' style='text-align: right;'>Receiver</td>
                        <td width='26%'>
                            <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php if ($Employee_ID != 0 && $Employee_Name != '') {
                    echo $Employee_Name;
                    } ?>'  >
                        </td>
                    </tr>
                    <tr>
                        <td width='10%' style='text-align: right;'>Delivery Note Number</td>
                        <td width='26%'>
                            <input type='text' name='Debit_Note_Number'  id='Debit_Note_Number' value='<?php echo $Debit_Note_Number; ?>'/>
                        </td>
                        <td width='16%' style='text-align: right;'>Invoice Number</td>
                        <td width='26%'>
                            <input type='text' name='Invoice_Number'  id='Invoice_Number' value='<?php echo $Invoice_Number; ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <td width='10%' style='text-align: right;'>Delivery Date</td>
                        <td width='26%'>
                            <input type='text' name='Delivery_Date'  id='Delivery_Date' value='<?php echo $Delivery_Date; ?>' readonly="readonly"/>
                        </td>
                        <td width='16%' style='text-align: right;'>Delivery Person</td>
                        <td width='26%'>
                            <input type='text' name='Delivery_Person'  id='Delivery_Person' value='<?php echo $Delivery_Person; ?>'/>
                        </td>
                    </tr>
                </table>
            </center>
        </fieldset>	

        <?php
        if (isset($_POST['Submitted_Grn_Purchase_Order_Form'])) {
            
            $Insert_Status = 'false';
            $Array_Size = $_POST['Array_Size'];
            //$Quantity_Supplied = $_POST['Quantity_Supplied']; //array values
            $Qty_Received = $_POST['Qty_Received']; //array values
            $Container = $_POST['Container']; //array values
            $Items_Per_Container = $_POST['Items_Per_Container']; //array values
            $Buying_Price = $_POST['Buying_Price']; //array values
            $Order_Item_ID = $_POST['Order_Item_ID']; //array values
            $Item_ID = $_POST['Item_ID']; //array values
            $Expire_Date = $_POST['Expire_Date'];
            $Grn_Items_Status = $_POST['Grn_Status'];

            $Debit_Note_Number = $_POST['Debit_Note_Number'];
            $Invoice_Number = $_POST['Invoice_Number'];
            $Delivery_Date = $_POST['Delivery_Date'];
            $Delivery_Person = $_POST['Delivery_Person'];

            for ($i = 0; $i <= $Array_Size; $i++) {

                    $currentbalance=  mysqli_query($conn,"SELECT Quantity_Received FROM tbl_purchase_order_items WHERE Order_Item_ID='$Order_Item_ID[$i]'");
                    $num_rows=  mysqli_num_rows($currentbalance);
                    if($num_rows>0){
                        
                    $resul=mysqli_fetch_assoc($currentbalance);
                    $Quantity =$resul['Quantity_Received']; 
                    if($Quantity==$Qty_Received[$i]){
                      $balanceFinal=$Qty_Received[$i];   
                    }else{
                      $balanceFinal=$Quantity-$Qty_Received[$i]; 
                    }
                    }else{
                      $balanceFinal=$Qty_Received[$i];  
                    }
                    
                    
                    
                    if ($Grn_Purchase_Order_ID != 0) {
                        //update tbl_purchase_order_items table
                        
//                        echo $balanceFinal;
//                        exit();
                        $update_items = "UPDATE
                                        tbl_purchase_order_items
                                        SET
                                        Containers_Received = '$Container[$i]',
                                        Items_Per_Container = '$Items_Per_Container[$i]',
                                        Quantity_Received = '$Qty_Received[$i]',
                                        Buying_Price = '$Buying_Price[$i]',
                                        Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID',
                                        Expire_Date = '$Expire_Date[$i]',
					Grn_Status = '$Grn_Items_Status[$i]'
					WHERE Order_Item_ID = '$Order_Item_ID[$i]'";
                       
                        $result2 = mysqli_query($conn,$update_items);
                        if (!$result2) {
                            echo "<script>
                                        alert('Process Fail! Please Try Again');
                                        document.location = 'grnpurchaseorder.php?Purchase_Order_ID=" . $Purchase_Order_ID . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
                                </script>";
                         } else {
                            $Insert_Status = 'true';
                            mysqli_query($conn,"update tbl_purchase_order set Order_Status = 'Served', Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID' where Purchase_Order_ID = '$Purchase_Order_ID'");

                            //get pre balance
                            $slct_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                            $slct_balance_num = mysqli_num_rows($slct_balance);
                            if ($slct_balance_num > 0) {
                                while ($sl = mysqli_fetch_array($slct_balance)) {
                                    $Pre_Balance = $sl['Item_Balance'];
                                }
                            } else {
                                mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID[$i]','$Sub_Department_ID')") or die(mysqli_error($conn));
                                $Pre_Balance = 0;
                            }

                            //update balance by adding items received
                            $b_update = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance + '$balanceFinal') where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                            if ($b_update) {
                                //insert data into tbl_stock_ledger_controler for auditing
                                $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
															Item_ID, Sub_Department_ID, Movement_Type, External_Source, 
															Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
														
														values('$Item_ID[$i]','$Sub_Department_ID','From External','$Supplier_ID',
															'$Quantity',$balanceFinal,(select now()),(select now()),'$Grn_Purchase_Order_ID')") or die(mysqli_error($conn));

                                if ($insert_audit) {
                                    
                                    //update stock value details
                                    $get_items_details = mysqli_query($conn,"select Last_Buy_Price from tbl_items where Item_ID = '$Item_ID[$i]'") or die(mysqli_error($conn));
                                    $num_items_details = mysqli_num_rows($get_items_details);
                                    if ($num_items_details > 0) {
                                        while ($dt_items = mysqli_fetch_array($get_items_details)) {
                                            $Last_Buy_Price = $dt_items['Last_Buy_Price'];
                                        }
                                    } else {
                                        $Last_Buy_Price = 0;
                                    }

                                    //calculate stock value => formula ((QtyA * pA) + (QtyB * pB) / (QtyA + QtyB))
                                    $Last_P = ((($Pre_Balance * $Last_Buy_Price) + ($Qty_Received[$i] * $Buying_Price[$i])) / ($Pre_Balance + $Qty_Received[$i]));

                                    //update Last_Buy_Price
                                    $update_previous_rec = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Last_P' where Item_ID = '$Item_ID[$i]'") or die(mysqli_error($conn));
                                }
                                //insert the last buy price
                                //$i_price = mysqli_query($conn,"update tbl_items set Last_Buy_Price = '$Buying_Price[$i]' where Item_ID = '$Item_ID[$i]'") or die(mysqli_error($conn));
                            }
                        }
                        
                    }
//                }
            }

            //Creating Pending Purchase Order
            $Get_Purchase_Order_ID = mysqli_query($conn,"SELECT
                                                      *
                                                  FROM
                                                      tbl_purchase_order_items
                                                  WHERE
                                                      Grn_Status = 'PENDING' AND
                                                      Purchase_order_id = '$Purchase_Order_ID'
                                                  ORDER BY Grn_Purchase_Order_ID DESC") or die(mysqli_error($conn));

            $Get_Purchase_Order_ID_Rows = mysqli_num_rows($Get_Purchase_Order_ID);
            if ($Get_Purchase_Order_ID_Rows > 0) {
                $Select_Purchase_Order = mysqli_query($conn,"SELECT
                                                      *
                                                  FROM
                                                      tbl_purchase_order
                                                  WHERE
                                                      Purchase_order_id = '$Purchase_Order_ID'
                                                  LIMIT 1") or die(mysqli_error($conn));
                $Select_Purchase_Order_Rows = mysqli_num_rows($Select_Purchase_Order);
                if ($Select_Purchase_Order_Rows > 0) {
                    $Select_Purchase_Order_Data = array();
                    while ($data = mysqli_fetch_array($Select_Purchase_Order)) {
                        $Select_Purchase_Order_Data = $data;
                    }

                    $Insert_Pending_Purchase_Order_Sql = "INSERT INTO tbl_pending_purchase_order
                                                            (Purchase_Order_ID, Created_Date, Sub_Department_ID,
                                                            Supplier_ID, Employee_ID, Grn_Purchase_Order_ID)
                                                      VALUES
                                                            ('" . $Select_Purchase_Order_Data['Purchase_Order_ID'] . "'," .
                            "(select now()), '" . $Select_Purchase_Order_Data['Sub_Department_ID'] . "'," .
                            "'" . $Select_Purchase_Order_Data['Supplier_ID'] . "'," .
                            "'" . $Employee_ID . "'," .
                            "'0')";
                    $Insert_Pending_Purchase_Order = mysqli_query($conn,$Insert_Pending_Purchase_Order_Sql) or die(mysqli_error($conn));

                    if ($Insert_Pending_Purchase_Order) {
                        $Pending_Purchase_Order_ID = mysql_insert_id();

                        while ($data = mysqli_fetch_array($Get_Purchase_Order_ID)) {
                            $Pending_Quantity_Required = $data['Quantity_Required'] - $data['Quantity_Received'];

                            $Insert_Pending_Purchase_Order_Items_Sql = "INSERT INTO tbl_pending_purchase_order_items
                                                                    (Purchase_Order_ID, Quantity_Required, Containers_Required,
                                                                    Items_Per_Container_Required, Price, Remark,
                                                                    Item_ID, Pending_Purchase_Order_ID)
                                                                    VALUES
                                                                    ('" . $data['Purchase_Order_ID'] . "', '" . $Pending_Quantity_Required . "', '1', " .
                                    "'" . $Pending_Quantity_Required . "', '" . $data['Price'] . "', '" . $data['Remark'] . "', " .
                                    "'" . $data['Item_ID'] . "', '".$Pending_Purchase_Order_ID."')";
                            $Insert_Pending_Purchase_Order_Items = mysqli_query($conn,$Insert_Pending_Purchase_Order_Items_Sql) or die(mysqli_error($conn));
                        }
                    }
                }
            }
        }
        ?>

        <style>
            table,tr,td{
                border-collapse:collapse !important;
                border:none !important;

            }

        </style>

        <fieldset>
            <table width=100%>
                <tr>
          <?php if (strtolower($Grn_Status) != 'served') { ?>
                        <td style='text-align: right;'>
                            <input type='checkbox' name='Bacode_Scanner' id='Bacode_Scanner' value='true'><b>Use Bacode Scanner</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='submit' name='submit' id='submit' value='SUBMIT' class='art-button-green' >
                        </td>
            <?php } else { ?>
                        <td style='text-align: right;'>
                            <input type="button" value="Preview GRN" class="art-button-green" onclick="Preview_Grn_Report(<?php echo $Grn_Purchase_Order_ID; ?>);">
                            <input type='submit' name='submit' id='submit' value='Save Changes' class='art-button-green' >
                        
                        </td>
        <?php } ?>
               </tr>
            </table>
        </fieldset>

        <script>
            document.getElementById("submit").addEventListener("click", function (event) {
                Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
                Invoice_Number = document.getElementById("Invoice_Number").value;
                Delivery_Date = document.getElementById("Delivery_Date").value;

                if (Debit_Note_Number == '') {
                    document.getElementById("Debit_Note_Number").style = 'border: 3px solid red;';
                    event.preventDefault();
                }
                else {
                    document.getElementById("Debit_Note_Number").style = 'border: 1px solid #B9B59D;';
                }

                if (Invoice_Number == '') {
                    document.getElementById("Invoice_Number").style = 'border: 3px solid red;';
                    event.preventDefault();
                }
                else {
                    document.getElementById("Invoice_Number").style = 'border: 1px solid #B9B59D;';
                }

                if (Delivery_Date == '') {
                    document.getElementById("Delivery_Date").style = 'border: 3px solid red;';
                    event.preventDefault();
                }
                else {
                    document.getElementById("Delivery_Date").style = 'border: 1px solid #B9B59D;';
                }
            });
        </script>

        <fieldset style='overflow-y: scroll; height: 240px;'>   
            <center>
                <table width=100%>
                    <tr>
                    <tr><td colspan="11"><hr></td></tr>
                    <td width=3% style='text-align: center;'>Sn</td>
                    <td>Item Name</td>
                    <td width=9% style='text-align: center;'>Units Required</td>
                    <td width=9% style='text-align: center;'>Items required per unit</td>
                    <td width=7% style='text-align: center;'>Quantity required</td>
                    <td width=7% style='text-align: center;'>Units received</td>
                    <td width=7% style='text-align: center;'>Items received per unit</td>
                    <td width=7% style='text-align: center;'>Quantity Received</td>
                    <td width=7% style='text-align: center;'>Buying Price</td>
                    <td width=7% style='text-align: center;'>Expire Date</td>
                    <td width=7% style='text-align: center;'>Status</td>

                    <?php
                    if ($Grn_Purchase_Order_ID != '') {
                        echo "<td width=6% style='text-align: right;'>Amount</td>";
                    }
                    ?>
                    <tr><td colspan="11"><hr></td></tr>
                    </tr>
                    <?php
                    //get list of item ordered
                    $select_items = mysqli_query($conn,"SELECT
                                          *
                                     FROM
                                          tbl_purchase_order po, tbl_purchase_order_items poi, tbl_items itm
                                     WHERE
						                  po.Purchase_Order_ID = poi.Purchase_Order_ID AND
							              poi.item_id = itm.item_id AND
								          po.purchase_order_id = '$Purchase_Order_ID'") or die(mysqli_error($conn));
                    $no2 = mysqli_num_rows($select_items);
                    $temp = 1;
                    $Amount = 0;
                    $Grand_Total = 0;

//                    if ($Grn_Purchase_Order_ID != '') {
//                        
//                        echo 'hapa hapa';
//                        exit();
                        if ($no2 > 0) {
                            while ($row = mysqli_fetch_array($select_items)) {
                                ?>
                                <!-- date picker to generate expire date for each item ordered	-->
                                <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
                                <script src="js/jquery-1.9.1.js"></script>
                                <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
                                <script>
                                $(function () {
                                addDatePicker($("#<?php echo $temp; ?>"));
                                });
                                </script>
                               
                                <?php
                                echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
                                echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'>";
                                echo "<input type='hidden' value='" . $row['Item_ID'] . "' name='Item_ID[]' id='Item_ID[]'></td>";
                                if ($row['Containers_Required'] < 1) {
                                    echo "<td><input type='text' value='1' readonly='readonly' style='text-align: right;'></td>";
                                } else {
                                    echo "<td><input type='text' value='" . $row['Containers_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                                }
                                if ($row['Containers_Required'] < 1) {
                                    echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                                } else {
                                    echo "<td><input type='text' value='" . $row['Items_Per_Container_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                                }
                                echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                                //echo "<td><input type='text' value='".number_format($row['Price'])."' readonly='readonly' style='text-align: right;'></td>";
                                //echo "<td><input type='text' name='Quantity_Supplied[]' id='Quantity_Supplied[]' autocomplete='off' autocomplete='off' value='".$row['Quantity_Supplied']."' readonly='readonly' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                                echo "<td><input type='text' name='Container[]' id='Container_" . $row['Order_Item_ID'] . "' autocomplete='off' value='" . $row['Containers_Received'] . "' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                                echo "<td><input type='text' name='Items_Per_Container[]' id='Items_" . $row['Order_Item_ID'] . "' autocomplete='off' value='" . $row['Items_Per_Container'] . "' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                                echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received_" . $row['Order_Item_ID'] . "' autocomplete='off' value='" . $row['Quantity_Received'] . "' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";

                                $Select_Buying_Price = ($row['Buying_Price'] != '') ? number_format($row['Buying_Price']) : number_format($row['Price']);
                                echo "<td><input type='text' name='Buying_Price[]' id='Buying_Price[]' autocomplete='off' value='"
                                . $Select_Buying_Price .
                                "' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";

                                echo "<td><input type='text' style='text-align: right;' name='Expire_Date[]' autocomplete='off' id='" . $temp . "' value='" . $row['Expire_Date'] . "'></td>";

                                echo "<td><select name='Grn_Status[]' id='Grn_Status_" . $row['Order_Item_ID'] . "' >";
                                echo "<option value='RECEIVED'> RECEIVED </option>";
                                echo "<option value='OUTSTANDING'> OUTSTANDING </option>";
                                echo "<option value='PENDING'> PENDING </option>";
                                echo "<option value='DISCARD'> DISCARD </option>";
                                echo "</select></td>";
                                echo "<script>$(document).ready(function(){ Select_Grn_Status('" . $row['Grn_Status'] . "'," . $row['Order_Item_ID'] . "); });</script>";

                                echo "<input type='hidden' name='Array_Size' id='Array_Size' value='" . ($no2 - 1) . "'>";
                                $Amount = $Amount + ($row['Buying_Price'] * $row['Quantity_Received']);
                                echo "<td><input type='text' name='Amount[]' id='Amount[]' value='" . number_format($Amount) . "' style='text-align: right;'></td>";
                                echo "<input type='hidden' name='Order_Item_ID[]' id='Order_Item_ID[]' value='" . $row['Order_Item_ID'] . "'>";
//                                  echo "<td><input type='button' value='Edit' id='' class='art-button edit-button' style='border-radius:5px'> &nbsp;&nbsp;</td>";
                                echo "<input type='hidden' name='Submitted_Grn_Purchase_Order_Form' id='Submitted_Grn_Purchase_Order_Form' value='True'>";
                                echo "</tr>";
                                $Grand_Total = $Grand_Total + $Amount;
                                $Amount = 0;
                                $temp++;
                            }
                        }
                     
//                        
//                        
//                        
//                        
//                    } else {
//
////                        echo 'else hapa';
////                        exit();
                                
                                
                                
                                
                                
                                
//                        if ($no2 > 0) {
//
//                            if ($no2 == 1) {
//                                while ($row = mysqli_fetch_array($select_items)) {
//                                    ?>	
                                    <!-- date picker to generate expire date for each item ordered	-->
                                    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
                                    <script src="js/jquery-1.9.1.js"></script>
                                    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
                                    <script>
                $(function () {
                addDatePicker($("#<?php echo $temp; ?>"));
                });
                                    </script>
                                    <!--    end of datepicker script-->

                                    <?php
//                                    echo "<tr><td><input type='text' value='" . $temp . "' readonly='readonly' style='text-align: center;'></td>";
//                                    echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'>";
//                                    echo "<input type='hidden' value='" . $row['Item_ID'] . "' name='Item_ID[]' id='Item_ID[]'></td>";
//                                    if ($row['Containers_Required'] < 1) {
//                                        echo "<td><input type='text' value='1' readonly='readonly' style='text-align: right;'></td>";
//                                    } else {
//                                        echo "<td><input type='text' value='" . $row['Containers_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    }
//                                    if ($row['Containers_Required'] < 1) {
//                                        echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    } else {
//                                        echo "<td><input type='text' value='" . $row['Items_Per_Container_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    }
//                                    echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    //echo "<td><input type='text' value='".number_format($row['Price'])."' readonly='readonly' style='text-align: right;'></td>";
//                                    //echo "<td><input type='text' name='Quantity_Supplied[]' id='Quantity_Supplied[]' autocomplete='off' autocomplete='off' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    echo "<td><input type='text' name='Container[]' id='Container_" . $row['Order_Item_ID'] . "' autocomplete='off' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    echo "<td><input type='text' name='Items_Per_Container[]' id='Items_" . $row['Order_Item_ID'] . "' autocomplete='off' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received_" . $row['Order_Item_ID'] . "' autocomplete='off' required='required' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    echo "<td><input type='text' name='Buying_Price[]' id='Buying_Price[]' autocomplete='off' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    echo "<td><input type='text' name='Expire_Date[]' autocomplete='off' id='" . $temp . "'></td>";
//
//                                    echo "<td><select name='Grn_Status[]' id='Grn_Status_" . $row['Order_Item_ID'] . "' onchange='Grn_Status_Changed(this.value," . $row['Order_Item_ID'] . ");'>";
//                                    echo "<option value='RECEIVED'> RECEIVED </option>";
//                                    echo "<option value='OUTSTANDING'> OUTSTANDING </option>";
//                                    echo "<option value='PENDING'> PENDING </option>";
//                                    echo "<option value='DISCARD'> DISCARD </option>";
//                                    echo "</select></td>";
//                                    echo "<script>$(document).ready(function(){ Select_Grn_Status('" . $row['Grn_Status'] . "'," . $row['Order_Item_ID'] . "); });</script>";
//                                    echo "<input type='hidden' name='Array_Size' id='Array_Size' value='" . ($no2 - 1) . "'>";
//                                    $Amount = $Amount + ($row['Buying_Price'] * $row['Quantity_Received']);
//                                    echo "<td><input type='text' name='Amount[]' id='Amount[]' value='" . number_format($Amount) . "' style='text-align: right;'></td>";
//                                    echo "<input type='hidden' name='Order_Item_ID[]' id='Order_Item_ID[]' value='" . $row['Order_Item_ID'] . "'>";
//                                    echo "<input type='hidden' name='Submitted_Grn_Purchase_Order_Form' id='Submitted_Grn_Purchase_Order_Form' value='True'>";
//                                    echo "</tr>";
//                                    $temp++;
//                                }
//                                
//                            } else {
//                                while ($row = mysqli_fetch_array($select_items)) {
//                                    $Date_ID = 'Date_' . $row['Order_Item_ID'];
//                                    ?>	

                                    <!-- date picker to generate expire date for each item ordered	-->
                                    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
                                    <script src="js/jquery-1.9.1.js"></script>
                                    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
                                    <script>
                                    $(function () {
                                    addDatePicker($("#//<?php echo $Date_ID; ?>"));
                                    });
                                    </script>
                                    <!--    end of datepicker script-->

                                    <?php
//                                    echo "<tr><td><input type='text' value='" . $temp . "' readonly='readonly' style='text-align: center;'></td>";
//                                    echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'>";
//                                    echo "<input type='hidden' value='" . $row['Item_ID'] . "' name='Item_ID[]' id='Item_ID[]'></td>";
//                                    if ($row['Containers_Required'] < 1) {
//                                        echo "<td><input type='text' value='1' readonly='readonly' style='text-align: right;'></td>";
//                                    } else {
//                                        echo "<td><input type='text' id='Containers_Required_" . $row['Order_Item_ID'] . "' value='" . $row['Containers_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    }
//                                    if ($row['Containers_Required'] < 1) {
//                                        echo "<td><input type='text' id='Items_Per_Container_Required_" . $row['Order_Item_ID'] . "' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    } else {
//                                        echo "<td><input type='text' id='Items_Per_Container_Required_" . $row['Order_Item_ID'] . "' value='" . $row['Items_Per_Container_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    }
//                                    echo "<td><input type='text' id='Quantity_Required_" . $row['Order_Item_ID'] . "' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
//                                    //echo "<td><input type='text' value='".number_format($row['Price'])."' readonly='readonly' style='text-align: right;'></td>";
//                                    //echo "<td><input type='text' name='Quantity_Supplied[]' id='Quantity_Supplied[]' autocomplete='off' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
//                                    //get Container value
//                                    if ($row['Containers_Received'] > 0) {
//                                        $Container_Value = $row['Containers_Received'];
//                                    } else {
//                                        $Container_Value = '';
//                                    }
//                                    //get Items per container value
//                                    if ($row['Items_Per_Container'] > 0) {
//                                        $Items_Value = $row['Items_Per_Container'];
//                                    } else {
//                                        $Items_Value = '';
//                                    }
//
//                                    echo "<td><input type='text' name='Container[]' id='Container_" . $row['Order_Item_ID'] . "' autocomplete='off' style='text-align: center;' value='" . $Container_Value . "' onchange='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' onkeyup='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' onkeypress='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' oninput='Add_Containers(" . $row['Order_Item_ID'] . ");'></td>";
//                                    echo "<td><input type='text' name='Items_Per_Container[]' id='Items_" . $row['Order_Item_ID'] . "' autocomplete='off' style='text-align: center;' value='" . $Items_Value . "' onchange='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' onkeyup='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' onkeypress='numberOnly(this);  Calculate_Quantity(" . $row['Order_Item_ID'] . ");' oninput='Add_Items_Per_Containers(" . $row['Order_Item_ID'] . ");'></td>";
//                                    echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received_" . $row['Order_Item_ID'] . "' autocomplete='off' required='required' style='text-align: center;' value='" . $row['Quantity_Received'] . "' onchange='numberOnly(this); Clear_Quantity(" . $row['Order_Item_ID'] . ");' onkeyup='numberOnly(this); Clear_Quantity(" . $row['Order_Item_ID'] . ");' onkeypress='numberOnly(this); Clear_Quantity(" . $row['Order_Item_ID'] . ");'></td>";
//
//                                    $Item_Buying_Price = ($row['Buying_Price'] != '') ? $row['Buying_Price'] : $row['Price'];
//                                    echo "<td><input type='text' name='Buying_Price[]' id='Buying_Price_" . $row['Order_Item_ID'] . "' autocomplete='off' required='required' style='text-align: right;' value='"
//                                    . $Item_Buying_Price .
//                                    "' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)' oninput='Update_Buying_Price(" . $row['Order_Item_ID'] . ")'></td>";
//
//                                    echo "<td><input type='text' name='Expire_Date[]' autocomplete='off' id='".$Date_ID."' value='".$row['Expire_Date'] . "' onchange='Update_Date(this.value," . $row['Order_Item_ID'] . ");'></td>";
//
//                                    echo "<td><select name='Grn_Status[]' id='Grn_Status_" . $row['Order_Item_ID'] . "' onchange='Grn_Status_Changed(this.value," . $row['Order_Item_ID'] . ");'>";
//                                    echo "<option value='RECEIVED'> RECEIVED </option>";
//                                    echo "<option value='OUTSTANDING'> OUTSTANDING </option>";
//                                    echo "<option value='PENDING'> PENDING </option>";
//                                    echo "<option value='DISCARD'> DISCARD </option>";
//                                    echo "</select></td>";
//                                    echo "<script>$(document).ready(function(){ Select_Grn_Status('" . $row['Grn_Status'] . "'," . $row['Order_Item_ID'] . "); });</script>";
//
//                                    echo "<input type='hidden' name='Array_Size' id='Array_Size' value='".($no2 - 1)."'>";
//                                    $Amount = $Amount + ($row['Buying_Price'] * $row['Quantity_Received']);
//                                    echo "<td><input type='text' name='Amount[]' id='Amount[]' value='" . number_format($Amount) . "' style='text-align: right;'></td>";
//                                    echo "<input type='hidden' name='Order_Item_ID[]' id='Order_Item_ID[]' value='" . $row['Order_Item_ID'] . "'>";
//                                    echo "<input type='hidden' name='Submitted_Grn_Purchase_Order_Form' id='Submitted_Grn_Purchase_Order_Form' value='True'>";
//                                    echo "<td width=3%><input type='button' value='Remove' class='art-button-green'></td></tr>";
//                                    $temp++;
//                                }
//                            }
//                        }
//                    }
                    ?>
                </table>
                </form>   
            </center>
        </fieldset>
</form>
<!-- <table width=100%>
        <tr>
                <td style='text-align: right;' width=93%>Grand Total</td>
                <td style='text-align: right;'>
                        <input type='text' value='<?php //echo number_format($Grand_Total);  ?>' readonly='readonly'>
                </td>
        </tr>
</table> -->
<?php
if ($Insert_Status == 'true') {
    echo "<script>
			alert('Process Successful');
			document.location = 'grnpurchaseorder.php?Purchase_Order_ID=" . $Purchase_Order_ID . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
			</script>";
}
?>

<script>
    function Select_Grn_Status(Select_Status, Order_Item_ID) {
        document.getElementById("Grn_Status_" + Order_Item_ID).value = Select_Status;
    }

    function Grn_Status_Changed(Select_Status, Order_Item_ID) {
        /*if (Select_Status == "RECEIVED") {
         document.getElementById("Container_"+Order_Item_ID).value = document.getElementById("Containers_Required_"+Order_Item_ID).value;
         document.getElementById("Items_"+Order_Item_ID).value = document.getElementById("Items_Per_Container_Required_"+Order_Item_ID).value;
         document.getElementById("Qty_Received_"+Order_Item_ID).value = document.getElementById("Quantity_Required_"+Order_Item_ID).value;
         Calculate_Quantity(Order_Item_ID);
         Add_Containers(Order_Item_ID);
         Add_Items_Per_Containers(Order_Item_ID);
         }*/
        if (Select_Status == "DISCARD") {
            document.getElementById("Container_" + Order_Item_ID).value = 0;
            document.getElementById("Items_" + Order_Item_ID).value = 0;
            document.getElementById("Qty_Received_" + Order_Item_ID).value = 0;
            Calculate_Quantity(Order_Item_ID);
            Add_Containers(Order_Item_ID);
            Add_Items_Per_Containers(Order_Item_ID);
        }

        if (Select_Status != '') {
            if (window.XMLHttpRequest) {
                myObjectAddGrnStatus = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddGrnStatus = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddGrnStatus.overrideMimeType('text/xml');
            }

            myObjectAddGrnStatus.onreadystatechange = function () {
                data2320 = myObjectAddGrnStatus.responseText;
                if (myObjectAddGrnStatus.readyState == 4) {
                    //Codes...........
                }
            };
            myObjectAddGrnStatus.open('GET', 'Update_Grn_Status.php?Grn_Status=' + Select_Status + '&Order_Item_ID=' + Order_Item_ID, true);
            myObjectAddGrnStatus.send();
        }
    }
</script>

<script type="text/javascript">
    function Calculate_Quantity(Order_Item_ID) {
        var Containers = document.getElementById("Container_" + Order_Item_ID).value;
        var Items_Quantity = document.getElementById("Items_" + Order_Item_ID).value;
        var Total_Quantity = (Items_Quantity * Containers);

        if (Items_Quantity != null && Items_Quantity != '' && Containers != null && Containers != '') {
            document.getElementById("Qty_Received_" + Order_Item_ID).value = (Items_Quantity * Containers);
        }

        if (Containers > 0 && Items_Quantity > 0 && Containers != null && Containers != '' && Items_Quantity != null && Items_Quantity != '') {
            if (window.XMLHttpRequest) {
                myObjectAddCalculate = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddCalculate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddCalculate.overrideMimeType('text/xml');
            }

            myObjectAddCalculate.onreadystatechange = function () {
                data2320 = myObjectAddCalculate.responseText;
                if (myObjectAddCalculate.readyState == 4) {
                    //Codes...........
                }
            }; //specify name of function that will handle server response........
            myObjectAddCalculate.open('GET', 'Update_Grn_Quantity_Calculated.php?Total_Quantity=' + Total_Quantity + '&Order_Item_ID=' + Order_Item_ID, true);
            myObjectAddCalculate.send();
        }
    }
</script>

<script type="text/javascript">
    function Clear_Quantity(Order_Item_ID) {
        var Quantity = document.getElementById("Qty_Received_" + Order_Item_ID).value;
        Containers = document.getElementById("Container_" + Order_Item_ID).value = 1;
        Items_Quantity = document.getElementById("Items_" + Order_Item_ID).value = Quantity;

        if (window.XMLHttpRequest) {
            myObjectClear = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectClear = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectClear.overrideMimeType('text/xml');
        }

        myObjectClear.onreadystatechange = function () {
            data20 = myObjectClear.responseText;
            if (myObjectClear.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectClear.open('GET', 'Grn_Update_Entered_Values.php?Quantity=' + Quantity + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectClear.send();
    }
</script>

<script type="text/javascript">
    function Add_Containers(Containers_ID) {
        var Containers_Received = document.getElementById("Container_" + Containers_ID).value;
        if (window.XMLHttpRequest) {
            myObjectAdd = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAdd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAdd.overrideMimeType('text/xml');
        }

        myObjectAdd.onreadystatechange = function () {
            data20 = myObjectAdd.responseText;
            if (myObjectAdd.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectAdd.open('GET', 'Grn_Add_Containers.php?Containers_Received=' + Containers_Received + '&Containers_ID=' + Containers_ID, true);
        myObjectAdd.send();
    }
</script>



<script type="text/javascript">
    function Add_Items_Per_Containers(Items_ID) {
        var Items_Per_Container = document.getElementById("Items_" + Items_ID).value;
        if (window.XMLHttpRequest) {
            myObjectAddItems = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItems = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItems.overrideMimeType('text/xml');
        }

        myObjectAddItems.onreadystatechange = function () {
            data2302 = myObjectAddItems.responseText;
            if (myObjectAddItems.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectAddItems.open('GET', 'Grn_Add_Items_Per_Containers.php?Items_Per_Container=' + Items_Per_Container + '&Items_ID=' + Items_ID, true);
        myObjectAddItems.send();
    }
</script>
<script type="text/javascript">
    function Update_Buying_Price(Order_Item_ID) {
        var Buying_Price = document.getElementById("Buying_Price_" + Order_Item_ID).value;
        if (window.XMLHttpRequest) {
            myObjectUpdatePrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdatePrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdatePrice.overrideMimeType('text/xml');
        }

        myObjectUpdatePrice.onreadystatechange = function () {
            data230 = myObjectUpdatePrice.responseText;
            if (myObjectUpdatePrice.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectUpdatePrice.open('GET', 'Grn_Update_Buying_Price.php?Buying_Price=' + Buying_Price + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectUpdatePrice.send();
    }
</script>

<script type="text/javascript">
    function Update_Date(Date_Value, Order_Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectUpdateExpireDate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateExpireDate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateExpireDate.overrideMimeType('text/xml');
        }

        myObjectUpdateExpireDate.onreadystatechange = function () {
            data2305 = myObjectUpdateExpireDate.responseText;
            if (myObjectUpdateExpireDate.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectUpdateExpireDate.open('GET', 'Grn_Update_Expire_Date.php?Date_Value=' + Date_Value + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectUpdateExpireDate.send();
    }
</script>


<script type="text/javascript">
    function Preview_Grn_Report(Grn_Purchase_Order_ID) {
        var winClose = popupwindow('grnpurchaseorderreport.php?Grn_Purchase_Order_ID=' + Grn_Purchase_Order_ID + '&GrnPurchaseOrder=GrnPurchaseOrderThisPage', 'PURCHASE ORDER REPORT', 1200, 500);
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
    $(document).ready(function () {
        addDatePicker($("#Delivery_Date"));
    });
</script>
<?php
include("./includes/footer.php");
?>

