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
?>      <legend style='background-color:#006400;color:white;padding:5px;' align=right><b>List of unapproved requisitions</b></legend>

<?php
    $temp = 1;
    echo '<center><table width = 100% border=0>';
	echo '<tr><td colspan="8"><hr></td></tr>';
    echo "<tr id='thead'>
        <td width=4% style='text-align: center;'><b>SN</b></td>
        <td width=8% style='text-align: left;'><b>REQUISITION N<u>O</u></b></td>
        <td width=12%'><b>SENT DATE & TIME</b></td>
        <td width=12%'><b>REQUEST STORE</b></td>
        <td width=12%'><b>ISSUING STORE</b></td>
        <td width=25%'><b>REQUISITION DESCRIPTION</b></td>
        <td style='text-align: center;' width=10%><b>ACTION</b></td>
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
                                rq.requisition_status = 'Not Approved' and
                                rq.Created_Date_Time between '$Start_Date' and '$End_Date' order by rq.Requisition_ID desc") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){

            //get store need
            $Store_Need = $row['Store_Need'];
            $Store_Issue = $row['Store_Issue'];
            $slct = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
            $slct_num = mysqli_num_rows($slct);
            if($slct_num > 0){
                while ($dt = mysqli_fetch_array($slct)) {
                    $Request_Store = $dt['Sub_Department_Name'];
                }
            }else{
                $Request_Store = '';
            }

            //get store issue
                $select_store_issue = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Issue'") or die(mysqli_error($conn));
                $no_of_rows = mysqli_num_rows($select_store_issue);
                if($no_of_rows > 0){
                    while($data = mysqli_fetch_array($select_store_issue)){
                        $Sub_Department_Name_Issue = $data['Sub_Department_Name'];
                    }
                }else{
                    $Sub_Department_Name_Issue = $row['Sub_Department_Name'];
                }       
                
            
            echo '<tr><td style="text-align: center;">'.$temp.'</td>
                    <td>'.$row['Requisition_ID'].'</td>
                    <td>'.$row['Sent_Date_Time'].'</td>
                    <td>'.$Sub_Department_Name.'</td>   
                    <td>'.$Sub_Department_Name_Issue.'</td> 
                    <td>'.$row['Requisition_Description'].'</td> 
                    <td style="text-align: center;">
                    <a href="Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID='.$row['Requisition_ID'].'" class="art-button-green">&nbsp;&nbsp;&nbsp;Process Approve&nbsp;&nbsp;&nbsp;</a>
                    </td>
                </tr>';
                $temp++;
            }
	    }
	    echo '</table>';
	
    }
?>