<?php
include("./includes/connection.php");
$temp = 1;
if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
}


if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

if(isset($_GET['Store_Need'])){
    $Store_Need = $_GET['Store_Need'];
}else{
    $Store_Need = '0';
}

if(isset($_GET['Store_Issue'])){
    $Store_Issue = $_GET['Store_Issue'];
}else{
    $Store_Issue = '0';
}

$filter = " ";

if (!empty($Start_Date) && !empty($End_Date)) {
    $filter = " and  gin.Created_Date_Time between '$Start_Date' and '$End_Date' ";
}

if (!empty($Order_No)) {
    $filter = " and  gin.Grn_Issue_Note_ID = '$Order_No' ";
}

if (!empty($Employee_ID)) {
    $filter .="  and gin.Employee_ID = '$Employee_ID'";
}

if($Store_Need != 0 && $Store_Need != null && $Store_Need != ''){
    $filter .=" and req.Store_Need = '$Store_Need' ";
}

if($Store_Issue != 0 && $Store_Issue != null && $Store_Issue != ''){
    $filter .=" and req.Store_Issue = '$Store_Issue' ";
}

?>
<legend style="background-color:#006400;color:white;padding:5px;" align='right'><b>Previous grn against issue note</b></legend>
<table width=100% style="border-collapse:collapse !important; border:none !important;">
    <tr>
        <td width=4% style='text-align: center;'><b>SN</b></td>
        <td width=6% style='text-align:center;'><b>GRN N<u>O</u></b></td>
        <td width=6% style='text-align:center;'><b>ISSUE N<u>O</u></b></td>
        <td width=6% style='text-align:center;'><b>REQUISITION N<u>O</u></b></td>
        <td width=15%><b>GRN DATE</b></td>
        <td width=10%><b>STORE NEED</b></td>
        <td width=20%><b>STORE ISSUE</b></td>
        <td width=20%><b>PREPARED BY</b></td>
        <td width=40%><b>GRN DESCRIPTION</b></td>
        <td width=10%><b>ACTION</b></td>
    </tr>
    <tr><td colspan="10"><hr></td></tr>
    <?php
    //select order data
    $select_grn = mysqli_query($conn,"select req.Requisition_ID, gin.Issue_ID, gin.Grn_Issue_Note_ID, gin.Created_Date_Time, gin.Issue_Description, req.Store_Need, req.Store_Issue, sd.Sub_Department_Name, emp.Employee_Name
                                from tbl_grn_issue_note gin, tbl_issues i, tbl_requisition req, tbl_sub_department sd, tbl_employee emp where
                                gin.Issue_ID = i.Issue_ID and
                                i.Requisition_ID = req.Requisition_ID and
                                gin.Employee_ID = emp.Employee_ID and
                                req.Store_Need = sd.Sub_Department_ID $filter order by Grn_Issue_Note_ID desc limit 200") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_grn);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_grn)) {
            //get store issue
            $Store_Issue = $row['Store_Issue'];
            $slck = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($slck);
            if ($nm > 0) {
                while ($dt = mysqli_fetch_array($slck)) {
                    $Store_Issue_Name = $dt['Sub_Department_Name'];
                }
            } else {
                $Store_Issue_Name = '';
            }
            echo "<tr><td style='text-align:center;'><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "'  style='text-decoration: none;'>" . $temp . "</a></td>";
            echo "<td style='text-align: center;'><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "'  style='text-decoration: none;'>" . $row['Grn_Issue_Note_ID'] . "</a></td>";
            echo "<td style='text-align: center;'><a href='previousissuenotereport.php?Issue_ID=".$row['Issue_ID']."&PreviousIssueNote=PreviousIssueNoteThisPage' style='text-decoration: none;' target='_blank'>" . $row['Issue_ID'] . "</a></td>";
            echo "<td style='text-align: center;'><a href='requisition_preview.php?Requisition_ID=".$row['Requisition_ID']."&RequisitionPreview=RequisitionPreviewThisPage' style='text-decoration: none;' target='_blank'>" . $row['Requisition_ID'] . "</a></td>";
            echo "<td><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "' style='text-decoration: none;'>" . $row['Created_Date_Time'] . "</a></td>";
            echo "<td><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "' style='text-decoration: none;'>" . $row['Sub_Department_Name'] . "</a></td>";
            echo "<td><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "' style='text-decoration: none;'>" . $Store_Issue_Name . "</a></td>";
            echo "<td><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "' style='text-decoration: none;'>" . $row['Employee_Name'] . "</a></td>";
            echo "<td><a href='grnissuenote.php?Grn_Issue_Note_ID=" . $row['Grn_Issue_Note_ID'] . "' style='text-decoration: none;'>" . $row['Issue_Description'] . "</a></td>";
            echo "<td><a href='grnissuenotereport.php?Issue_ID=" . $row['Issue_ID'] . "' name='Preview' id='Preview' value='Preview' class='art-button-green' target='_blank' style='text-decoration: none;'>PREVIEW</a></td></tr>";
           $temp++;
        }
        //echo "</tr>";
    }
    ?>
</table>