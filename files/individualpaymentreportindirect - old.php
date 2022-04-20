<?php
    @session_start();
	 $htm= "";
	?>

<!DOCTYPE html >
<html>

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>EHMS SYSTEM:: PATIENT RECEIPT</title>
	
	<style>
		body {

			font-family:courier; 
			
		}
		table{
		  
		  font-weight:600;
		}
		
		.description{
		}
		.qty,.cost{
		  padding-left:20px;
		   padding-right:20px;
		}

</style>
</head>

<body>
<div id="page-wrap" style="font-size:28px;
	  font-weight:600;">

<h3 align="center" style="font-size:24px"><b>***START OF LEGAL RECEIPT***</b></h3>
		<div id="identity">
		 <center>
         <?php  include("./includes/reportheader.php"); echo $htm;
           $htm='';
		 ?>
          </center> 
		<br/>
		
		<div style="clear:both"></div>
		
		<?php include("./includes/connection.php");
if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }
    $total = 0;
    
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	$Date_Time = $row['datetime'];
    } 

            echo '<div id="customer">';
            
              
    $select_Transaction_Items = mysqli_query($conn,"select * from tbl_employee emp, tbl_patient_registration preg, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		where emp.employee_id = pp.employee_id and
		    preg.registration_id = pp.registration_id and
			pp.patient_payment_id = ppl.patient_payment_id and
			    pp.Patient_Payment_ID = '$Patient_Payment_ID' limit 1"); 
   
    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	if(strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit'){
            
echo '<table>';

echo '<tr><td colspan=2 style="text-align: center;"><b>DEBIT NOTE</b></td></tr></center></td></tr>'; 
	}else{
	    echo '<tr><td colspan=2 style="text-align: center;"><b>SALES RECEIPT</b></td></tr></center></td></tr>'; 
	}	
	echo '<tr><td colspan=2><hr height="2px"></td></tr>'; 
	//SELECT BILLING TYPE
	if(strtolower($row['Billing_Type']) == 'outpatient cash'){
	    $Billing_Type = 'Cash';
	}elseif(strtolower($row['Billing_Type']) == 'outpatient credit'){
	    $Billing_Type = 'Credit';
	}
	
	// Turn off all error reporting
error_reporting(0);
	//select the id of employee who made the transaction
	$Employee_ID = $row['Employee_ID'];
               echo ' <tr>
                    <td >Name:</td>
                    <td>'.$row['Patient_Name'].', </td></tr>';
               echo '<tr>

                    <td >Sponsor: </td>
                    <td>'.$row['Sponsor_Name'].', </td>
                </tr>';
                
                echo ' <tr>
                    <td >MRN: </td>
                    <td>'.$row['Registration_ID'].', </td></tr>';
               
               $Receipt_Number = $row['Patient_Payment_ID'];
               echo '<tr>
               
	<td >Receipt N<u>o</u>: </td>
		<td>'.$Receipt_Number.', </td>';
		
	$Receipt_Date = $row['Payment_Date_And_Time'];
	echo '<tr>
	<td >Receipt Date: </td>
		<td>'.$Receipt_Date.', </td>';
	echo '<tr>
                    <td >Mode: </td>
                    <td>'.$Billing_Type.', </td>
                </tr>';
                echo '<tr>
                    
                </tr> </table>';
		
		echo '</div>';
		
		
		  }
		  echo '<table id="items">';
		
		  echo '<tr>';
$select_category = mysqli_query($conn,"select * from tbl_item_category") or die(mysqli_error($conn));
		$isReceipt=false;
	$q=mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
	 $row=mysqli_fetch_assoc($q);
	 $exist=mysqli_num_rows($q);
	if ($exist >0){
	    $Paper_Type = $row['Paper_Type'];
		if($Paper_Type=='Receipt'){
		   $isReceipt=true;
		}
        
	}
  $subtotal=0;$total=0;		
  while($rowCat = mysqli_fetch_array($select_category)){
       $catName=$rowCat['Item_Category_Name'];
	   $catID=$rowCat['Item_Category_ID'];
		
		 $myQry=mysqli_query($conn,"SELECT t.Product_Name,ppl.Quantity,ppl.Price FROM tbl_patient_payments pp RIGHT JOIN 
			tbl_patient_payment_item_list ppl ON pp.patient_payment_id = ppl.patient_payment_id 
			JOIN tbl_items t ON t.item_id = ppl.item_id
			JOIN tbl_item_subcategory ts ON t.item_subcategory_id = ts.item_subcategory_id
			JOIN tbl_employee emp  ON emp.employee_id = pp.employee_id
			JOIN tbl_patient_registration preg ON preg.registration_id = pp.registration_id
			WHERE pp.Patient_Payment_ID = '$Patient_Payment_ID' AND ts.Item_category_ID='$catID'
				") or die(mysqli_error($conn));
		
	   
	  if(mysqli_num_rows($myQry)>0){
	    //$i=1;
		$htm .= "<tr>";
        $htm .= "<td colspan='3'><b>".$catName."</b></td></tr>";
	   
		 while($row = mysqli_fetch_array($myQry)){
		   if($isReceipt){
			$htm .= "<tr>
					 <td>".substr($row['Product_Name'],0,11)." </td>";
		   }else{
		     $htm .= "<tr>
					 <td>".$row['Product_Name']." </td>";
		   }
			$htm .= "<td width='8%'>".$row['Quantity']." </td>";
			$htm .= "<td style='text-align:right'>".number_format($row['Price'])."</td></tr>";
		
			$subtotal = $subtotal + (($row['Price'] - $row['Discount'])*$row['Quantity']);
			
		 //$i++;		
		}
	
	
	
	   $htm .= "<tr><td colspan='3'><hr height='3px'></td></tr>";
	   $htm .= "<tr><td style='text-align: right;' colspan='3'><b> SUB TOTAL: ".number_format($subtotal)."</b></td></tr>";
	   
	   $htm .= "<tr>";
        $htm .= "<td colspan='3'>&nbsp;<br/></td></tr>";
		$total = $total + $subtotal;
		$subtotal=0;
	}
   }   
	
        $htm .= "<tr><td colspan='3'><hr height='3px'></td></tr>";
        $htm .= "<tr><td style='text-align: right;' colspan='3'><b> TOTAL: ".number_format($total)."</b></td></tr>";
	$htm .= "<tr><td colspan='3'><hr height='3px'></td></tr>
	         </table>
			 
	         ";

    //select the name of the employee who made the transaction based on employee id we got above
    $select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Employee_Name)){
	$Employee_Name = $row['Employee_Name'];
    }
    $htm.="<table  border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $htm .='<tr><td style="text-align:left;width:50%">Prep By:</td>
		<td colspan="1">'.$Employee_Name.'</td></tr>';
    $htm .='<tr><td style="text-align:left"">Print By:</td>
		   <td colspan="1" style="text-align:left">'.$_SESSION['userinfo']['Employee_Name'].'</td></tr>';
    $htm .='<tr><td style="text-align:left">Date:</td><td colspan="1" style="text-align:left">'.$Date_Time.'<br/></td></tr>';
    $htm .= "<tr><td colspan='3'><hr height='3px'></td></tr>";
$htm .= '</table>';
    
?>

<?php
    //$html .= "</table></center>";

    echo $htm;
	 echo '<h3 align="center" style="font-size:24px"></br>*** END OF LEGAL RECEIPT ***</h3><br/><hr/>';
  
     // include("MPDF/mpdf.php");
    // $mpdf = new mPDF('', '',20, '');
	// $mpdf->setJS('this.print()');
	//$mpdf->SetWatermarkText('DRAFT');
	
	//$mpdf->showWatermarkText = true;
    //$mpdf->SetWatermarkImage('tzlogo.jpg', 0.15, 'F');
	//$mpdf->WriteHTML('<watermarkimage src"tzlogo.jpg" alpha="0.5" size="P" position="F" />');
	// $mpdf ->showWatermarkImage = true;	
    // $mpdf ->SetWatermarkImage('tzlogo.jpg', 0.05,'F', 'F');
    // $mpdf->WriteHTML($htm);
    // $mpdf->Output();
    //exit; 
?>
</div>
</div>
 <script>
 window.print(false);
 CheckWindowState();
 // window.onafterprint = function(){
   // window.close();
// }

 function PrintWindow() {                    
       window.print();            
       CheckWindowState();
    }

    function CheckWindowState()    {           
        if(document.readyState=="complete") {
            window.close(); 
        } else {           
            setTimeout("CheckWindowState()", 2000);
        }
    }
 </script>


 
</body>

</html>