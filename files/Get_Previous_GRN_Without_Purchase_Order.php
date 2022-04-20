<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<?php

include_once("./includes/connection.php");
include_once("./functions/employee.php");
include_once("./functions/grnwithoutpurchaseorder.php");

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
}

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
} else {
    $Employee_ID = null;
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
} else {
    $Supplier_ID = null;
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}else{
    $Order_No=null;
}

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_ID = '';
    $Sub_Department_Name = '';
}

$temp = 1;


echo "<legend style='background-color:#006400;color:white;padding:5px;' align=right>";
echo "<b>{$Sub_Department_Name}, Previous GRN Without Purchase Order</b>";
echo "</legend>";
echo "<tr><td colspan=9><hr></td></tr>";
echo "<center><table width = 100% border=0>";
echo "<tr id='thead'>
                    <td width=4% style='text-align: center;'><b>SN</b></td>
                    <td width=10% style='text-align: center;'><b>GRN N<u>O</u></b></td>
                    <td width=15%><b>Created Date & Time</b></td>
                    <td width=15%><b>SUPERVISOR NAME</b></td>
                    <td width=12%><b>RECEIVER NAME</b></td>
                    <td width=15%><b>SUPPLIER NAME</b></td>
                    <td width=15%><b>LOCATION</b></td>
                    <td style='text-align: center;' width=10%><b>ACTION</b></td>
                    </tr>";
echo '<tr><td colspan="9"><hr></td></tr>';

//echo $Sub_Department_ID.' '.Get_Day_Beginning($Start_Date).' '.Get_Day_Ending($End_Date).' '. $Employee_ID.' '. $Supplier_ID.' '.$Order_No.'200';exit;
$GRN_Without_Purchase_Order_List = Get_GRN_Without_Purchase_Order_List($Sub_Department_ID, Get_Day_Beginning($Start_Date), Get_Day_Ending($End_Date), $Employee_ID, $Supplier_ID,$Order_No, 200);
foreach ($GRN_Without_Purchase_Order_List as $GRN_Without_Purchase_Order) {
       $href = '';
            if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                $href = "<a href='Edit_grnwithoutpurchaseorder.php?Grn_ID=" . $GRN_Without_Purchase_Order['Grn_ID'] . "' class='art-button-green'>Edit</a>";
            }
            $GRN_ID_EMPTY=$GRN_Without_Purchase_Order['Grn_ID'];

                $select_grn_ID = mysqli_query($conn,"SELECT Grn_ID FROM tbl_grn_without_purchase_order_items WHERE Grn_ID='$GRN_ID_EMPTY'");
                if(mysqli_num_rows($select_grn_ID) >0){
                    //get supervisor name
                    $Supervisor_ID = $GRN_Without_Purchase_Order['Supervisor_ID'];
                    $Supervisor = Get_Employee($Supervisor_ID);
                    $Supervisor_Name = $Supervisor['Employee_Name'];
                    echo "<tr>";
                    echo "<td style='text-align: center;'> {$temp} </td>";
                    echo "<td style='text-align: center;'> {$GRN_Without_Purchase_Order['Grn_ID']} </td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Grn_Date_And_Time'] . "</td>";
                    echo "<td>" . ucwords(strtolower($Supervisor_Name)) . "</td>";
                    echo "<td>" . ucwords(strtolower($GRN_Without_Purchase_Order['Employee_Name'])) . " </td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Supplier_Name'] . "</td>";
                    echo "<td>" . $GRN_Without_Purchase_Order['Sub_Department_Name'] . "</td>";
                    echo "<td style='text-align: center;'>";
                    echo "<a target='_blank' class='art-button-green'";
                    echo "  href='Session_Control_Grn_Without_Perchase_Order.php?Status=Previous&Grn_ID=";
                    echo $GRN_Without_Purchase_Order['Grn_ID'] . "'";
                    echo ">Preview</a>";
                    echo "</td>"
                    . "<td>$href</td>  "
                    . "</tr>";
                    $temp++;
                }
    
}
echo '</table>';
