<?php
	session_start();
	include("./includes/connection.php");

	//get Storage name & ID
	/*if(isset($_SESSION['Storage_Info'])){
		$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
		$Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
	}*/

	//get employee name
//	if(isset($_SESSION['userinfo']['Employee_Name'])){
//		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
//	}else{
//		$Employee_Name = '';
//	}
//
//	//get employee id
//	if(isset($_SESSION['userinfo']['Employee_ID'])){
//		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
//	}else{
//		$Employee_ID = '';
//	}

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}


	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}

if(isset($_GET['Sub_Department_ID'])){
	$Sub_Department_ID = $_GET['Sub_Department_ID'];
}else{
	$Sub_Department_ID = 0;
}
        
if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

$filter = " ";

if (!empty($Start_Date) && !empty($End_Date)) {
    $filter = " and gob.Created_Date_Time between '$Start_Date' and '$End_Date' ";
}

if (!empty($Order_No)) {
    $filter = " and gob.Grn_Open_Balance_ID = '$Order_No' ";
}

if (!empty($Employee_ID)) {
    $filter .="  and gob.Employee_ID = '$Employee_ID'";
}

if($Sub_Department_ID != 0 && $Sub_Department_ID != null && $Sub_Department_ID != ''){
	$filter .=" and gob.Sub_Department_ID = '$Sub_Department_ID' ";
}
?>
 
<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<legend align=right><b>Previous Grn Open Balances</b></legend>
	<?php
	    $temp = 0;
		echo '<center><table width = 100% border=0><tr><td colspan="8"><hr></td></tr>';
		echo '<tr><td width=5% style="text-align: center;"><b>SN</b></td>
				<td width=10%><b>GRN NUMBER</b></td>
				<td width=10%><b>LOCATION</b></td>
				<td width=15%><b>PREPARED BY</b></td>
				<td width=15%><b>CREATED DATE</b></td>
				<td width=11%><b>GRN DESCRIPTION</b></td>
				<td width=15%><b>SUPERVISOR NAME</b></td>
				<td width=18% colspan=2></td></tr><tr><td colspan="8"><hr></td></tr>';
	    
	    //get top 50 grn open balances based on selected employee id
	    $Sub_Department_Name = $_SESSION['Storage'];
	    $sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_Description, gob.Employee_ID
									from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
									emp.Employee_ID = gob.Supervisor_ID and
									sd.Sub_Department_ID = gob.Sub_Department_ID and
									gob.Grn_Open_Balance_Status = 'saved' $filter order by Grn_Open_Balance_ID desc limit 100") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
                     $href = '';
            if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                $href = '<a href="editgrnopenbalance.php?Grn_Open_Balance_ID=' . $row['Grn_Open_Balance_ID'] . '" class="art-button-green" >Edit</a>';
            }
			//get employee prepared
			$Prep_Employee = $row['Employee_ID'];
			$sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
			$Pre_no = mysqli_num_rows($sel);
			if($Pre_no > 0){
				while ($dt = mysqli_fetch_array($sel)) {
					$Created_By = $dt['Employee_Name'];
				}
			}else{
				$Created_By = '';
			}
		    echo '<tr><td style="text-align: center;">'.++$temp.'</td>
					<td>'.$row['Grn_Open_Balance_ID'].'</td>
					<td>'.$row['Sub_Department_Name'].'</td>
					<td>'.$Created_By.'</td>
					<td>'.$row['Created_Date_Time'].'</td>
					<td>'.$row['Grn_Open_Balance_Description'].'</td>
					<td>'.$row['Employee_Name'].'</td>
					<td style="text-align: center;">
                                        <input type="button" name="Preview" id="Preview" class="art-button-green" value="Preview" onclick="Preview_Details('.$row['Grn_Open_Balance_ID'].')">
                                        '.$href.'   
                            </td>
                         </tr>';
		}
	    }
	    echo '</table>';
        ?>