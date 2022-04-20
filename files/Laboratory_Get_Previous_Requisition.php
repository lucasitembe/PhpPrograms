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


    //get sub department id
    if(isset($_SESSION['Laboratory_ID'])){
        $Sub_Department_ID = $_SESSION['Laboratory_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while($row = mysqli_fetch_array($select)){
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    if(isset($_GET['Start_Date']) && isset($_GET['End_Date']) && $Start_Date != '' && $End_Date != '' && $Start_Date != null && $End_Date != null){
?>      <legend align=right><b>
    <?php if(isset($_SESSION['Laboratory_ID'])){ echo $Sub_Department_Name; }?>, Previous Requisitions Prepared By : <?php echo $Employee_Name; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>

<?php
    $temp = 1;
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
                                                rq.Created_Date between '$Start_Date' and '$End_Date' and
                                                    rq.store_need = '$Sub_Department_ID' and
                                                        rq.Employee_ID = '$Employee_ID' order by rq.Requisition_ID desc limit 50") or die(mysqli_error($conn));
    $num = mysqli_num_rows($sql_select);
    if($num > 0){
        while($row = mysqli_fetch_array($sql_select)){
            echo '<tr><td style="text-align: center;">'.$temp.'</td>
                <td>'.$row['Requisition_ID'].'</td>
                        <td>'.$row['Created_Date_Time'].'</td>
			    <td>'.$row['Sent_Date_Time'].'</td>
                                <td>'.$row['Sub_Department_Name'].'</td>
                                    <td>'.$row['Requisition_Description'].'</td>
                                        <td style="text-align: center;"><a href="previousrequisitionreport.php?Requisition_ID='.$row['Requisition_ID'].'&RequisitionReport=RequisitionReportThisPage" target="_blank" class="art-button-green">&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a></td></tr>';
        }
	    }
	    echo '</table>';

    }
?>