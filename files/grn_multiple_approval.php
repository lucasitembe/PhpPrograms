<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//get employee id 
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}
?>
<a href="grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage" class="art-button-green">BACK</a>
<style>
    table,tr,td{
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    select{
        padding:5px;
    }
</style>
<fieldset style='overflow-y: scroll; height: 390px; background-color: white;' id='Grn_Fieldset_List'>
    <legend align=right><b>Pending Grn Open Balances prepared by : <?php echo ucwords(strtolower($Employee_Name)); ?></b></legend>
    <?php
    $temp = 0;
    echo '<center><table width = 100% border=0>';
    echo "<tr><td colspan='7'><hr></td></tr>";
    echo '<tr>
                    <td width=5% style="text-align: center;"><b>SN</b></td>
                    <td width=10% style="text-align: center;"><b>GRN NUMBER</b></td>
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
                                    gob.Employee_ID = '$Employee_ID' and
                                    gob.Grn_Open_Balance_Status = 'pending'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            echo '<tr><td style="text-align: center;">' . ++$temp . '</td>
                        <td style="text-align: center;">' . $row['Grn_Open_Balance_ID'] . '</td>
                        <td>' . ucwords(strtolower($row['Employee_Name'])) . '</td>
                        <td>' . ucwords(strtolower($row['Sub_Department_Name'])) . '</td>
                        <td>' . $row['Created_Date_Time'] . '</td>
                        <td>' . $row['Grn_Open_Balance_Description'] . '</td>
                        <td><a href="grnopenbalanceconfirm.php?ConfirmGrn=ConfirmGrnThisPage&from_grn_multiple_approval&Grn_Open_Balance_ID=' . $row['Grn_Open_Balance_ID'] . '" class="art-button-green">Approve</a></td></tr>';
        }
    }
    echo '</table>';
    ?>
</fieldset>
<?php
include('./includes/footer.php');
?>