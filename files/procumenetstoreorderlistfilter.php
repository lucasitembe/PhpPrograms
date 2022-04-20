<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}


	if(isset($_SESSION['Procurement_ID'])){
		$Sub_Department_ID = $_SESSION['Procurement_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	//get sub department name
	if(isset($_SESSION['Procurement_ID'])){
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while($data = mysqli_fetch_array($select)){
				$Sub_Department_Name = $data['Sub_Department_Name'];
			}
		}else{
			$Sub_Department_Name = '';
		}
	}else{
		$Sub_Department_Name = '';
	}
?>

<legend align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo $Sub_Department_Name; }?> ~ List Of Requisitions</b></legend>
	<?php 
	    $temp = 1;
		echo '<center><table width = 100% border=0>';
		echo "<tr id='thead'>
			<td width=4% style='text-align: center;'><b>Sn</b></td>
            <td width=10%><b>Employee Created </b></td>
            <td width=10% style='text-align: left;'><b>Store Order N<u>o</u></b></td>
            <td width=15%><b>Approved Date</b></td>
            <td width=15%><b>Ordering Store</b></td>
            <td width=15%><b>Order Description</b></td>
            <td style='text-align: center;' width=30%><b>Action</b></td>
		    </tr>";
	    

        //get top 500 grn open balances based on selected employee id
        $Approved_Store_Order_SQL = mysqli_query($conn,"SELECT so.Store_Order_ID, so.Approval_Date_Time, emp.Employee_Name,
                                        sd.Sub_Department_Name
                                   FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                                   WHERE Order_Status = 'Approved' AND
                                        emp.Employee_ID = so.Employee_ID AND
                                        so.Sub_Department_ID = sd.Sub_Department_ID AND
                                        so.Approval_Date_Time between '$Start_Date' and '$End_Date' AND

                                        (SELECT count(*) FROM tbl_store_order_items soi
                                         WHERE soi.Store_Order_ID = so.Store_Order_ID AND
                                         Procurement_Status in ('active', 'selected') ) > 0

                                   ORDER BY Store_Order_ID DESC limit 100") or die(mysqli_error($conn));
        $Approved_Store_Order_Num = mysqli_num_rows($Approved_Store_Order_SQL);
        if($Approved_Store_Order_Num > 0){
            while($row = mysqli_fetch_array($Approved_Store_Order_SQL)){
                echo    '<tr>
                            <td style="text-align: center;">'.$temp.'</td>
                            <td>'.$row['Employee_Name'].'</td>
                            <td>'.$row['Store_Order_ID'].'</td>
                            <td>'.$row['Approval_Date_Time'].'</td>
                            <td>'.$row['Sub_Department_Name'].'</td>
                            <td>'.$row['Employee_Name'].'</td>
                            <td style="text-align: center;">
                                <a href="Control_Purchase_Order_Sessions.php?Store_Order_ID='.$row['Store_Order_ID'].
                                    '&Single_Supplier=True&Selected_Store_Order=True"
                                    class="art-button-green" target="_parent">
                                    PO Single Supplier
                                </a>
                                <a href="" class="art-button-green" target="_parent">
                                    PO Multi Supplier
                                </a>
                            </td>
                        </tr>';
                    $temp++;
		}
	    }
	    echo '</table>';
	?>