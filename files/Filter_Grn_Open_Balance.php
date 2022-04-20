<?php
session_start();
include("./includes/connection.php");
//get employee id 
//if (isset($_SESSION['userinfo']['Employee_ID'])) {
//    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
//} else {
//    $Employee_ID = '';
//}
//get employee name

$Employee_ID=0;

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
}

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

$filter = " ";

if (!empty($Start_Date) && !empty($End_Date)) {
    $filter = " and gob.Created_Date_Time between '$Start_Date' and '$End_Date' ";
}

if (!empty($Order_No)) {
    $filter = " and gob.Grn_Open_Balance_ID = '$Order_No' ";
}

if (!empty($Employee_ID)) {
    $filter .="  and gob.Employee_ID = '$Employee_ID'";
}


$qr = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ORDER BY Employee_Name ASC";
$emp_results = mysqli_query($conn,$qr) or die(mysqli_error($conn));
$Employee_Name = mysqli_fetch_assoc($emp_results)['Employee_Name'];

?>
<legend align=right><b>Pending Grn Open Balances prepared by : <?php echo $Employee_Name; ?></b></legend>
<?php
$temp = 1;
echo '<center><table width = 100% border=0>';
echo "<tr><td colspan='7'><hr></td></tr>";
echo '<tr>
                    <td width=5% style="text-align: center;"><b>SN</b></td>
                    <td width=10%><b>GRN NUMBER</b></td>
                    <td width=15%><b>PREPARED BY</b></td>
                    <td width=15%><b>LOCATION</b></td>
                    <td width=15%><b>CREATED DATE</b></td>
                    <td width=30%><b>GRN DESCRIPTION</b></td>
                    <td width=7%></td></tr>';
echo "<tr><td colspan='7'><hr></td></tr>";

//get top 50 grn open balances based on selected employee id

$sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_Description, gob.Grn_Open_Balance_ID
                                    from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
                                    emp.Employee_ID = gob.Employee_ID and
                                    sd.Sub_Department_ID = gob.Sub_Department_ID and
                                    gob.Grn_Open_Balance_Status = 'pending'
                                    $filter
                                    ") or die(mysqli_error($conn));
$num = mysqli_num_rows($sql_select);
if ($num > 0) {
    while ($row = mysqli_fetch_array($sql_select)) {
        //check if this grn already processed
            $Grn_Open_Balance_ID=$row['Grn_Open_Balance_ID'];
            $sql_check_if_all_approve_result=mysqli_query($conn,"SELECT document_type FROM tbl_document_approval_control WHERE document_type='grn_physical_counting_as_open_balance' AND document_number='$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_check_if_all_approve_result)>0){}else{
        echo '<tr><td style="text-align: center;">' . $temp . '</td>
                        <td>' . $row['Grn_Open_Balance_ID'] . '</td>
                        <td>' . $row['Employee_Name'] . '</td>
                        <td>' . $row['Sub_Department_Name'] . '</td>
                        <td>' . $row['Created_Date_Time'] . '</td>
                        <td>' . $row['Grn_Open_Balance_Description'] . '</td>
                        <td><a href="Control_Grn_Open_Balance_Sessions.php?Pending_Grn_Open_Balance=True&Grn_Open_Balance_ID=' . $row['Grn_Open_Balance_ID'] . '" class="art-button-green">Process</a></td></tr>';
    }
    
            }
}
echo '</table>';
?>