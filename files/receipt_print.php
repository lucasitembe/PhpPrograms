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
         <?php  include("./includes/reportheader.php"); echo $htm; ?>
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
                    <td>'.$Billing_Type.'. </td>
                </tr>';
                echo '</table>';
		
		echo '</div>';
		
		echo '<table id="items">';
		
		  echo '<tr>';
		  }
$select_Transaction_Items = mysqli_query($conn,"select * from
			    tbl_employee emp, tbl_patient_registration preg,
				tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where emp.employee_id = pp.employee_id and
				preg.registration_id = pp.registration_id and
				pp.patient_payment_id = ppl.patient_payment_id and
				t.item_id = ppl.item_id and
				t.item_subcategory_id = ts.item_subcategory_id and
				ts.item_category_id = ic.item_category_id and
				pp.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn)); 

		     
		       echo "<th style='text-align:left;'>Description</th>";
		      echo "<th>Qty</th>";
		     echo " <th>price</th>";
		     echo  "<th>Amount</th>";
		 echo " </tr>";
         echo "<tr><td colspan='4'><hr height='3px'></td></tr>";
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
 while($row = mysqli_fetch_array($select_Transaction_Items, MYSQL_BOTH))
{
		  echo '<tr class="item-row">';
		   if($isReceipt){
			echo "<tr>
					 <td class='description'>".substr($row['Product_Name'],0,11)." </td>";
		   }else{
		     echo "<tr>
					 <td class='description'>".$row['Product_Name']." </td>";
		   }
		     //echo '<td class="description">'.$row['Product_Name'].'</td>';
		      echo '<td class="qty">'.$row['Quantity'].'</td>';
		      echo '<td class="cost">'.$row['Price'].'</td>';
		      
		      echo '<td><span class="price">'.number_format(($row['Price'] - $row['Discount'])*$row['Quantity']);
		      echo '</td></tr>';
		  
		  $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity'])."</i>";
	echo "<tr><tdwidth='11px' height='4px'></td></tr>";
	//echo "<tr><td colspan=6 style='text-align: right;'>Sub Total : ".number_format(($row['Price'] - $row['Discount'])*$row['Quantity']);"
    } 
	
    //  echo  "<tr><td style='text-align: right;' colspan=7><strong><u> TOTAL : ".number_format(($row['Price']-$row['Discount'])*$row['Quantity']);"</u></strong></td></tr>";
	//echo "<hr/>";
	echo "<tr><td colspan='4'><hr height='3px'></td><br/></tr>";
	echo  "<tr><td style='text-align: right;font-weight:bold;font-size:30px;' colspan='4'><strong><u> TOTAL : ".number_format($total)."</u></strong></td></tr>";
	echo "<tr><td colspan='4'><hr height='3px'></td><br/></tr>";
	// Turn off all error reporting
error_reporting(0);
		  
		   //select the name of the employee who made the transaction based on employee id we got above
    $select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_Employee_Name)){
	$Employee_Name = $row['Employee_Name'];
    }

   echo '<tr><td colspan="">Printed By:</td><td colspan="3">'.$_SESSION['userinfo']['Employee_Name'].' </td><tr><td colspan="4">Date:  '.$Date_Time.' </td></tr>';
		
   
   
echo  '</table>';

   echo '<h3 align="center" style="font-size:24px"></br>*** END OF LEGAL RECEIPT ***</h3><br/><hr/>';
?>
		
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
  //window.onfocus=function(){ window.close();}
// (function() {
//
//    var beforePrint = function() {
//        console.log('Functionality to run before printing.');
//    };
//
//    var afterPrint = function() {
//        console.log('Functionality to run after printing');
//    };
//
//    if (window.matchMedia) {
//        var mediaQueryList = window.matchMedia('print');
//        mediaQueryList.addListener(function(mql) {
//            if (mql.matches) {
//                beforePrint();
//            } else {
//                afterPrint();
//            }
//        });
//    }
//
//    window.onbeforeprint = beforePrint;
//    window.onafterprint = afterPrint;
//
//}());
 </script>


 	
</body>

</html>