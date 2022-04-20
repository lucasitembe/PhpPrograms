<?php
include("./includes/connection.php");
if(isset($_GET['order_id'])){
	$order_id =$_GET['order_id'];  
}else{ $order_id =0; } 	 		 

$sql = mysqli_query($conn,"select ord.*,dep.Sub_Department_Name,sup.* from tbl_purchase_order as ord "
    . " join tbl_sub_department as dep on ord.store_need = dep.Sub_Department_ID "
    . " join tbl_supplier as sup on ord.supplier_id = sup.supplier_id "
    . " where ord.order_id='$order_id'");

$disp =mysqli_fetch_array($sql);

$htm="<fildset><center>";

$htm.="<table style='float:left;width:95%;margin-left:10%;' >";
$htm.="<tr><td colspan='5' style='text-align:center;'><h2>PURCHASE ORDER REPORT</h2></td></tr>";
$htm.="<tr><td colspan='5'><hr width='100%'></td></tr>";
$htm.="</table>"; 

$htm.="<table style='float:left;width:95%;margin-left:10%;' >";
$htm.="<tr><td><b>PO Number:</b></td><td>".$disp['order_id'] ."</td><td> </td><td><b>PO Date:</b></td><td>".$disp['send_date'] ."</td></tr>";
$htm.="<tr><td><b>Vendor:</b></td><td>".$disp['supplier_id'] ."</td><td> </td><td><b>Store Need:</b></td><td>".$disp['Sub_Department_Name'] ."</td></tr>";
$htm.="<tr><td><b>Contact:</b></td><td>".$disp['Mobile'] ."</td><td> </td><td><b>Purechased By:</b></td><td>".$disp['employee_id'] ."</td></tr>";
$htm.="<tr><td><b>Phone:</b></td><td>".$disp['Phone'] ." </td><td> </td><td><b>Signature:</b></td><td>......................</td></tr>";
$htm.="<tr><td><b>Address:</b></td><td> ".$disp['Address'] ."</td><td> </td><td><b>Delivered To:</b></td><td>".$disp['employee_id'] ."</td></tr>";
$htm.="</table>";


$sql2 = mysqli_query($conn,"select item_name,quantity_required,balance_needed,unit_price,(select (quantity_required *unit_price)) as amount "
   					 . " from tbl_purchase_order_items where order_id ='$order_id'");

  	
	$htm.="<table style='float:left;width:95%;margin-left:10%;' >";
	$htm.="<tr><td ><hr width='100%'></td> </tr>";

$htm.="</table>";



	$htm.="<table style='float:left;width:95%;margin-left:10%;' >";
	$htm.="<tr><td><b>ITEM NAME</b></td> <td><b>QTY REQUIRED</b></td> <td><b>BALANCE NEED</b></td>
	<td><b>UNIT PRICE</b></td> <td><b>AMOUNT</b></td> </tr>";
		$htm.="<tr><td colspan='5'><hr width='100%'></td></tr>";
		

		$Total=0;
		while($disp1 =mysqli_fetch_array($sql2)){
				$htm.="<tr><td>".$disp1['item_name']."</td> <td>".number_format($disp1['quantity_required'])."</td> <td>".number_format($disp1['balance_needed'])."</td>
				<td>".number_format($disp1['unit_price'])."</td> <td>".number_format($disp1['amount'])."</td> </tr>";
					$htm.="<tr><td colspan='5'><hr width='100%'></td> </tr>";

		$Total +=$disp1['amount'];

		}
	$htm.="<tr><td colspan='5'><hr width='100%'></td> </tr>";
	$htm.="<tr><td colspan='3'></td><td>Total</td><td>". number_format($Total)."</td></tr>";
	$htm.="<tr><td colspan='5'><hr width='100%'></td> </tr>";

$htm.="</table>";





$htm.="</center></fildset>";

	include("MPDF/mpdf.php");
   $mpdf=new mPDF(); 
   $mpdf->WriteHTML($htm);
   $mpdf->Output();
  exit;


?>