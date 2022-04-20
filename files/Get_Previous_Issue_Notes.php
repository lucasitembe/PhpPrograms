<?php
    @session_start();
    include("./includes/connection.php");
    
    //get start date & end date
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
    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
    //if(isset($_GET['Start_Date']) && isset($_GET['End_Date']) && $Start_Date != '' && $End_Date != '' && $Start_Date != null && $End_Date != null){
?>      <legend style='background-color:#006400;color:white;padding:5px;' align=right><b><?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; } ?> ~ Previous Issue Notes</b></legend>

<?php
    //get sub department name
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
            $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                    $row = mysqli_fetch_assoc($select);
                    $Sub_Department_Name = $row['Sub_Department_Name'];
            }else{
                    $Sub_Department_Name = '';
            }
    }else{
        $Sub_Department_ID = 0;
        $Sub_Department_Name = '';
    }
    
    
if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

$filter = " ";

if (!empty($Start_Date) && !empty($End_Date)) {
    $filter = " and rq.Store_Issue = '$Sub_Department_ID' and iss.Issue_Date between '$Start_Date' and '$End_Date' ";
}

if (!empty($Order_No)) {
    $filter = " and  iss.Issue_ID = '$Order_No' ";
}

//if (!empty($Employee_ID)) {
//    $filter .="  and gin.Employee_ID = '$Employee_ID'";
//}
?>
<br>
<center>
    <table width = 100% border=0>
        <tr id='thead'>
		        <tr><td colspan="9"><hr></td></tr>
                <td width=4% style='text-align: center;'><b>Sn</b></td>
                <td width=10% style='text-align: left;'><b>Issue N<u>o</u></b></td>
                <td width=10% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
                <td width=13% style='text-align: left;'><b>Requested Date</b></td>
                <td width=17% style='text-align: left;'><b>Requisition Prepared By</b></td>
                <td width=13%><b>Issue Date & Time</b></td>
                <td width=15%><b>Store Need</b></td>
                <td width=30%><b>Requisition Description</b></td>
                <td style='text-align: center;' width=10%><b>Action</b></td>
				<tr><td colspan="9"><hr></td></tr>
            </tr>
	 
<?php 
    $temp = 1;   
    //get top 50 issue notes based on selected employee id
    $Sub_Department_Name = $_SESSION['Storage'];
    $sql_select = mysqli_query($conn,"SELECT * from tbl_issues iss, tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
				iss.Requisition_ID = rq.Requisition_ID and
				sd.sub_department_id = rq.Store_Issue and
				emp.Employee_ID = iss.Employee_ID $filter ORDER BY iss.Issue_ID desc
				") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
	while($row = mysqli_fetch_array($sql_select)){
	    //get store need
	    $Store_Need = $row['Store_Need'];
	    $select = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
	    $no_of_rows = mysqli_num_rows($select);
	    if($no_of_rows){
		while($data = mysqli_fetch_array($select)){
		    $Sub_Department_Name = $data['Sub_Department_Name'];
		}
	    }else{
		$Sub_Department_Name = '';
	    }
	    echo '<tr><td style="text-align: center;">'.$temp.'</td>
		    <td>'.$row['Issue_ID'].'</td>
		    <td>'.$row['Requisition_ID'].'</td>
		    <td>'.$row['Sent_Date_Time'].'</td>
		    <td>'.$row['Employee_Name'].'</td>	
		    <td>'.$row['Issue_Date_And_Time'].'</td>	
		    <td>'.$Sub_Department_Name.'</td> 	
		    <td>'.$row['Requisition_Description'].'</td> 
		    <td style="text-align: center;">
                <input type="button" value="Preview" class="art-button-green" onclick="Preview_Grn_Issue_Note('.$row['Issue_ID'].');">
		    </td>
		</tr>';
	    $temp++;
	}
    }
    echo '</table>';
    //}
?>