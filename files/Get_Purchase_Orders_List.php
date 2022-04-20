<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
        
    
	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}
        
        if(isset($_GET['Supplier_ID'])){
		$Supplier_ID = $_GET['Supplier_ID'];
	}
        
        if(isset($_GET['Order_No'])){
		$Order_No = $_GET['Order_No'];
	}

    if(isset($_SESSION['Storage_Info'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    if(isset($_SESSION['Storage_Info'])){
        $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }else{
        $Sub_Department_Name = 0;
    }
    
  $filter=" and sd.sub_department_id = '$Sub_Department_ID' ";  
  
  if(!empty($Start_Date) && !empty($End_Date)){
      $filter=" and po.Sent_Date between '$Start_Date' and '$End_Date' and sd.sub_department_id = '$Sub_Department_ID' ";    
  }
  
  if(!empty($Order_No)){
    $filter=" and po.Purchase_Order_ID = '$Order_No' ";    
  }
  
  if(!empty($Supplier_ID)){
    $filter .=" and po.supplier_id = '$Supplier_ID' ";    
  }
  
 // echo $filter;
    
?>
<legend align='right'><b><?php if(isset($_SESSION['Storage_Info'])){ echo $Sub_Department_Name; }?>, GRN Agains Purchase Order</b></legend>
    <table width=100%>
    <tr><td colspan="7"><hr></td></tr>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>SN</b></td>
        <td width=6% style='text-align:left;'><b>ORDER N<u>O</u></b></td>
        <td width=15%><b>ORDER DATE & TIME</b></td>
        <td width=10%><b>STORE NEED</b></td>
        <td width=20%><b>SUPPLIER NAME</b></td>
        <td width=40%><b>ORDER DESCRIPTION</b></td>
        <td style='text-align: center;'><b>ACTION</b></td>
    </tr> 
    <tr><td colspan="7"><hr></td></tr>
<?php
        //select order data
    $select_Order_Details = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
                                            po.sub_department_id = sd.sub_department_id and
                                            po.supplier_id = sp.supplier_id and
                                            po.order_status = 'submitted' 
                                            $filter
                                           group by purchase_order_id order by Purchase_Order_ID desc limit 100") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Order_Details);
    if($no > 0){
        while($row = mysqli_fetch_array($select_Order_Details)){
            echo "<tr><td style='text-align:center;'>".++$temp."<b>.</b></td>";
            echo "<td>".$row['Purchase_Order_ID']."</td>";
            echo "<td>".$row['Created_Date']."</td>";
            echo "<td>".$row['Sub_Department_Name']."</td>";
            echo "<td>".$row['Supplier_Name']."</td>";
            echo "<td>".$row['Order_Description']."</td>";
            echo "<td><a href='grnpurchaseorder.php?Purchase_Order_ID=".$row['Purchase_Order_ID']."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Process</a></td></tr>";
        }
    }
?>
</table>