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
			<td width=12%><b>Employee Created </b></td>
			<td width=10% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
			<td width=15%><b>Sent Date</b></td>
			<td width=15%><b>Requesting Store</b></td>
			<td width=30%><b>Requisition Description</b></td>
			<td style='text-align: center;' width=10%><b>Action</b></td>
		    </tr>";
	    
	    //get top 500 grn open balances based on selected employee id
	    $sql_select = mysqli_query($conn,"select emp.Employee_Name, rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from
									tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
									rq.store_issue = sd.sub_department_id and rq.requisition_status = 'submitted' and
									emp.Employee_ID = rq.Employee_ID and
									rq.Sent_Date_Time between '$Start_Date' and '$End_Date' and
									rq.Store_Issue = '$Sub_Department_ID' order by rq.Requisition_ID desc") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
		    $Store_Need = $row['Store_Need'];
		    //get store need - accuracy
		    $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Need'") or die(mysqli_error($conn));
		    $no_of_rows = mysqli_num_rows($select_store_need);
		    if($no_of_rows > 0){
			while($data = mysqli_fetch_array($select_store_need)){
			    $Sub_Department_Name = $data['Sub_Department_Name'];
			}
		    }else{
			$Sub_Department_Name = $row['Sub_Department_Name'];
		    }
		    
		    
		    
		    
		    echo '<tr><td style="text-align: center;">'.$temp.'</td>
			    <td>'.$row['Employee_Name'].'</td>
			    <td>'.$row['Requisition_ID'].'</td>
			    <td>'.$row['Sent_Date_Time'].'</td>
			    <td>'.$Sub_Department_Name.'</td>	
			    <td>'.$row['Requisition_Description'].'</td> 
			    <td style="text-align: center;">
			    <a href="Control_Purchase_Order_Sessions.php?Requisition_ID='.$row['Requisition_ID'].'&Selected_Requisition=True" class="art-button-green" target="_parent">&nbsp;&nbsp;&nbsp;Load Requisition&nbsp;&nbsp;&nbsp;</a>
			    </td>
			</tr>';
                    $temp++;
		}
	    }
	    echo '</table>';
	?>