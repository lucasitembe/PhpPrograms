<?php
	session_start();
	include("./includes/connection.php");

    include("./functions/department.php");

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}
	
	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

    if(isset($_GET['Search_Value'])){
        $Search_Value = $_GET['Search_Value'];
    }else{
        $Search_Value = '';
    }

    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = $_GET['Supplier_ID'];
    }else{
        $Supplier_ID = 'all';
    }

	//get sub department id
	if(isset($_SESSION['Procurement_ID'])){
		$Sub_Department_ID = $_SESSION['Procurement_ID'];
	}else{
		$Sub_Department_ID = '';
	}

    //get sub department name
    $Sub_Department_Name = Get_Sub_Department_Name($Sub_Department_ID);

	if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100){
        //calculate pending orders based on assigned level
        $before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
    }else{
    	$before_assigned_level = 99;
    }
?>

<legend style='background-color:#006400;color:white;padding:5px;' align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo ucwords(strtolower($Sub_Department_Name)); }?>, pending orders</b></legend>
    
<?php
	    $temp = 1;
		
		echo '<tr><td colspan="9"><hr></td></tr>';
		echo '<center><table width = 100% border=0>';
		echo "<tr id='thead'>
                <td width=4% style='text-align: center;'><b>SN</b></td>
                <td width=12% style='text-align: center;'><b>ORDER NUMBER</b></td>
                <td width=13%><b>ORDER DATE & TIME</b></td>
                <td width=10%><b>STORE NEED</b></td>
                <td width=15%><b>SUPPLIER NAME</b></td>
                <td width=20%><b>ORDER DESCRIPTION</b></td>
                <td style='text-align: center;'><b>ACTION</b></td>
            </tr>";
	    echo '<tr><td colspan="9"><hr></td></tr>';

        $Supplier_Statement = "";
        if (strtolower($Supplier_ID) != "all") {
            $Supplier_Statement = "AND po.Supplier_ID = {$Supplier_ID}";
        }

	    $sql_select = mysqli_query($conn,"SELECT
                                          po.Purchase_Order_ID, po.Sent_Date, po.Created_Date,
                                          sd.Sub_Department_Name, s.Supplier_Name, po.Order_Description
                                        FROM tbl_purchase_order po, tbl_sub_department sd, tbl_supplier s
                                        WHERE po.Sub_Department_ID = sd.Sub_Department_ID
                                        AND po.Supplier_ID = s.Supplier_ID
                                        {$Supplier_Statement}
                                        AND po.Purchase_Order_ID like '{$Search_Value}%'
                                        AND po.Approval_Level = '$before_assigned_level'
                                        AND po.Sent_Date BETWEEN '$Date_From' AND '$Date_To'
                                        AND po.Order_Status = 'submitted'
                                        ORDER BY po.Purchase_Order_ID DESC
                                        LIMIT 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
			while($row = mysqli_fetch_array($sql_select)){
			    echo '<tr><td style="text-align: center;">'.$temp.'</td>
            				<td style="text-align: center;">'.$row['Purchase_Order_ID'].'</td>
            				<td>'.$row['Sent_Date'].'</td>
            			    <td>'.$row['Sub_Department_Name'].'</td>
            				<td>'.$row['Supplier_Name'].'</td>	
            			    <td>'.$row['Order_Description'].'</td> 
            				<td style="text-align: center;" width="10%">
                                <input type="button" value="PROCESS ORDER" onclick="Display_Purchase_Details('.$row['Purchase_Order_ID'].')" class="art-button-green">&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>';
				$temp++;
			}
	    }
	    echo '</table>';
	?>