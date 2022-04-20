<?php
include("./includes/connection.php");
if(isset($_GET['req_id'])){
  $requision_id=$_GET['req_id'];
  }else{  $requision_id=0; }

$sql =mysqli_query($conn,"select grnI.grn_issue_id,grnI.issue_id,grnI.create_date,grnI.supplier,grnI.receiver ,iss.date_issued,iss.employeeIssue,req.Sent_Date_Time
        from tbl_grnissue as grnI"
    . " JOIN tbl_issued as iss on grnI.issue_id = iss.issue_ID"
    . " JOIN tbl_requizition as req on req.Requizition_ID = iss.req_id
     where req.Requizition_ID ='$requision_id'");

  $disp =mysqli_fetch_array($sql);                                              
                    

$htm="<fildset><center>";

$htm.="<table width='95%'>";
$htm.="<tr><td colspan='4' style='text-align:center;'><h2>GRN ISSUE REPORT</h2></td></tr>";
$htm.="<tr><td colspan='4'><hr width='100%'><td></tr>";
$htm.="</table>"; 

$htm.="<table width='95%'>";                               
$htm.="<tr><td width='25%'><b>GRN NO:</b></td><td>".$disp['grn_issue_id']."</td><td width='20%'><b>GRN date:</b></td><td>".$disp['create_date']."</td></tr>";
$htm.="<tr><td width='25%'><b>Issue NO:</b></td><td>".$disp['issue_id']."</td><td width='20%'><b>Issue Date:</b></td><td>".$disp['Sent_Date_Time']."</td></tr>";

$htm.="<tr><td width='25%'><b>Requested By:</b></td><td>".$disp['employeeIssue']."</td><td width='20%'><b>Signature:</b></td><td width='25%'>.................................</td></tr>";      
$htm.="<tr><td width='25%'><b>Store Issued:</b></td><td>".$disp['supplier']."</td><td width='20%'><b>Approve By(signature):</b></td><td>.................................</td></tr>";
$htm.="<tr><td width='25%'><b>Store Provider:</b></td><td>".$disp['receiver']."</td><td width='20%'><b>Signature:</b></td><td width='25%'>.................................</td></tr>"; 
$htm.="</table>";


$sql1=mysqli_query($conn,"select Requizition_ID, Item_Name,Quantity_Required,Quantity_Issued,quantity_received "
    . " from tbl_requizition_items where Requizition_ID ='$requision_id'");

$htm.="<table width='95%'  cellspacing=0>";
$htm.="<tr><td colspan='5'><hr width='100%'><td></tr>";
$htm.="<tr><td><b>S/N</b></td><td><b>ITEM NAME</b></td><td width='25%'><b>QTY REQUIRED</b></td><td><b>QTY INSUED</b></td><td width='25%'><b>QTY RECEIVED</b></td></tr>";  
$htm.="<tr><td colspan='5'><hr width='100%'><td></tr>";             

    $i=1;
        while($disp1=mysqli_fetch_array($sql1)){
        $htm.="<tr><td><b>".$i."</b></td><td>".$disp1['Item_Name']."</td><td width='25%'>".number_format($disp1['Quantity_Required'])."</td>
        <td>".number_format($disp1['Quantity_Issued'])."</td><td width='25%'>".number_format($disp1['quantity_received'])."</td></tr>";  
        $i++;
        }
$htm.="</table>";
$htm.="</center></fildset>";

   include("MPDF/mpdf.php");
    $mpdf=new mPDF(); 
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;