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
    
    if(isset($_GET['Start_Date']) && isset($_GET['End_Date']) && $Start_Date != '' && $End_Date != '' && $Start_Date != null && $End_Date != null){
?>      <legend style='background-color:#006400;color:white;padding:5px;' align=right><b>Previous Requisitions</b></legend>

<?php
    $temp = 1;
    echo '<center><table width = 100% border=0>';
	echo '<tr><td colspan="8"><hr></td></tr>';
    echo "<tr id='thead'>
        <td width=4% style='text-align: center;'><b>Sn</b></td>
        <td width=8% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
        <td width=12%'><b>Created Date & Time</b></td>
        <td width=12%'><b>Sent Date & Time</b></td>
        <td width=12%'><b>Request Store</b></td>
        <td width=12%'><b>Issuing Store</b></td>
        <td width=25%'><b>Requisition Description</b></td>
        <td style='text-align: center;' width=10%><b>Action</b></td>
        </tr>";
		echo '<tr><td colspan="8"><hr></td></tr>';
    
    //get top 50 grn open balances based on selected employee id
    $Sub_Department_Name = $_SESSION['Storage'];
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
	$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
	$Sub_Department_ID = 0;
    }
    
    $Sub_Department_Name = $_SESSION['Storage'];
    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from
                                tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                rq.store_issue = sd.sub_department_id and
                                emp.employee_id = rq.employee_id and
                                rq.requisition_status = 'submitted' and
                                rq.Created_Date between '$Start_Date' and '$End_Date' order by rq.Requisition_ID desc") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){

            //get store need
            $Store_Need = $row['Store_Need'];
            $slct = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
            $slct_num = mysqli_num_rows($slct);
            if($slct_num > 0){
                while ($dt = mysqli_fetch_array($slct)) {
                    $Request_Store = $dt['Sub_Department_Name'];
                }
            }else{
                $Request_Store = '';
            }
            
            echo '<tr><td style="text-align: center;">'.$temp.'</td>
                <td style="text-align: center;">'.$row['Requisition_ID'].'</td>
                <td>'.$row['Created_Date_Time'].'</td>
                <td>'.$row['Sent_Date_Time'].'</td>
                <td>'.ucwords(strtolower($Request_Store)).'</td>    
                <td>'.ucwords(strtolower($row['Sub_Department_Name'])).'</td>   
                <td>'.$row['Requisition_Description'].'</td> 
                <td style="text-align: center;"><input type="button" value="Preview" onclick="Preview_Requisition_Report('.$row['Requisition_ID'].')" class="art-button-green">&nbsp;&nbsp;&nbsp;</td></tr>';
                $temp++;
            }
	    }
	    echo '</table>';
	
    }
?>