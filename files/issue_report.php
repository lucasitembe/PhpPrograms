<?php
include("./includes/connection.php");
if(isset($_GET['req_id'])){
	$requision_id=$_GET['req_id'];
	$issue_ID=$_GET['issue_ID'];

$report_query1=mysqli_query($conn,"SELECT req.*,issue.*,nd.Sub_Department_Name as sNeed,iss.Sub_Department_Name as sIssue "
             . "FROM tbl_issued issue "
             . "JOIN tbl_requizition as req ON req.Requizition_ID=issue.req_id "
             . "JOIN tbl_sub_department as nd ON nd.Sub_Department_ID=req.Store_Need "
             . "JOIN tbl_sub_department as iss ON iss.Sub_Department_ID=req.Store_Issue "
             . " WHERE issue.req_id='$requision_id' and issue.issue_ID='$issue_ID'");


$report_array1=mysqli_fetch_array($report_query1);

$report_query2=mysqli_query($conn,"SELECT * FROM tbl_requizition_items as itms WHERE Requizition_ID='$requision_id'");


$htm="<fildset><center>";

$htm.="<table width='95%'>";
$htm.="<tr><td colspan='4' style='text-align:center;'><h2>ISSUE REPORT</h2></td></tr>";
$htm.="<tr><td colspan='4'><hr width='100%'><td></tr>";
$htm.="</table>"; 

$htm.="<table width='95%'>";                               
$htm.="<tr><td width='25%'><b>Issue NO:</b></td><td>".$report_array1['issue_ID']."</td><td width='20%'><b>Issue Date:</b></td><td>".$report_array1['date_issued']."</td></tr>";
$htm.="<tr><td width='25%'><b>Requisition NO:</b></td><td>".$report_array1['Requizition_ID']."</td><td width='20%'><b>Requisition Date:</b></td><td>".$report_array1['Sent_Date_Time']."</td></tr>";
$htm.="<tr><td width='25%'><b>Requested By:</b></td><td>".$report_array1['Employee_ID']."</td><td width='20%'><b>Signature:</b></td><td width='25%'>.................................</td></tr>";      
$htm.="<tr><td width='25%'><b>Issue By:</b></td><td>".$report_array1['sNeed']."</td><td width='20%'><b>Approve By(signature):</b></td><td>.................................</td></tr>";
$htm.="<tr><td width='25%'><b>Store Provider:</b></td><td>".$report_array1['sIssue']."</td><td width='20%'><b>Signature:</b></td><td width='25%'>.................................</td></tr>";    
$htm.="<tr><td width='25%'><b>Description:</b></td><td colspan='3'>".$report_array1['Requizition_Descroption']."</td></tr>"; 
$htm.="<tr><td colspan='4'><hr width='100%'><td></tr>";
$htm.="</table>";

$htm.="<table width='95%'  cellspacing=0>";
$htm.="<tr><td><b>S/N</b></td><td><b>Item Description</b></td><td width='25%'><b>Quantity Required</b></td><td><b>Quantity Issue</b></td><td width='25%'><b>Remark</b></td></tr>";  
$htm.="<tr><td colspan='4'><hr width='100%'><td></tr>";
$i=1;
while($report_array2=mysqli_fetch_array($report_query2)){
$htm.="<tr><td>".$i."</td><td>".$report_array2['Item_Name']."</td><td width='25%'>".$report_array2['Quantity_Required']."</td>"
      . "<td>".$report_array2['Balance_Needed']."</td><td width='25%'>".$report_array2['Item_Remark']."</td></tr>";  
  $htm.="<tr><td colspan='4'><hr width='100%'><td></tr>";
$i++;
}
$htm.="</table>";
$htm.="</center></fildset>";

echo $htm;

   include("MPDF/mpdf.php");
    $mpdf=new mPDF(); 
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
}