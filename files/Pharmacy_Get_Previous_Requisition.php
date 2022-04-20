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

    //get sub sub_department_id
    if(isset($_SESSION['Pharmacy_ID'])){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    //get sub sub_department_Name
    if(isset($_SESSION['Pharmacy_Name'])){
        $Sub_Department_Name = $_SESSION['Pharmacy_Name'];
    }else{
        $Sub_Department_Name = 0;
    }

    
    if(isset($_GET['Start_Date']) && isset($_GET['End_Date']) && $Start_Date != '' && $End_Date != '' && $Start_Date != null && $End_Date != null){
?>      <legend align=right><b>
    <?php if(isset($_SESSION['Pharmacy_Name'])){ echo $_SESSION['Pharmacy_Name']; }?>, Previous Requisitions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>

<?php
    $temp = 0;
    echo '<center><table width = 100% border=0>';
    echo "<tr id='thead'>
            <td width=4% style='text-align: center;'><b>Sn</b></td>
            <td width=10% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
            <td width=15%><b>Created Date & Time</b></td>
            <td width=15%><b>Sent Date & Time</b></td>
            <td width=15%><b>Issuing Store</b></td>
            <td width=30%><b>Requisition Description</b></td>
            <td style='text-align: center;' width=10%><b>Action</b></td>
        </tr>";
    
    //get top 50 grn open balances based on selected employee id
    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name from
                                tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    rq.store_issue = sd.sub_department_id and
                                            emp.employee_id = rq.employee_id and rq.requisition_status = 'submitted' and
                                                rq.Created_Date between '$Start_Date' and '$End_Date'
                                                    order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){
            echo '<tr><td style="text-align: center;">'.++$temp.'</td>
                <td>'.$row['Requisition_ID'].'</td>
                <td>'.$row['Created_Date_Time'].'</td>
                <td>'.$row['Sent_Date_Time'].'</td>
                <td>'.$row['Sub_Department_Name'].'</td>	
                <td>'.$row['Requisition_Description'].'</td> 
                <td style="text-align: center;">
                    <input type="button" value="Preview" onclick="Preview_Requisition_Report('.$row['Requisition_ID'].');" class="art-button-green">
                </td></tr>';
        }
	    }
	    echo '</table>';	
    }
?>