<?php
@session_start();
include("./includes/connection.php");

//get start date & end date
if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
} else {
    $End_Date = '';
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

if(isset($_GET['Store_Issue'])){
    $Store_Issue = $_GET['Store_Issue'];
}else{
    $Store_Issue = 0;
}

//get employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}

//if (isset($_GET['Start_Date']) && isset($_GET['End_Date']) && $Start_Date != '' && $End_Date != '' && $Start_Date != null && $End_Date != null) {
    ?>


    <?php
    //get sub department name
    if (isset($_SESSION['Pharmacy_ID'])) {
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if ($no > 0) {
            $row = mysqli_fetch_assoc($select);
            $Sub_Department_Name = $row['Sub_Department_Name'];
        } else {
            $Sub_Department_Name = '';
        }
    } else {
        $Sub_Department_ID = 0;
        $Sub_Department_Name = '';
    }


    $filter = " AND rq.Store_Need = '$Sub_Department_ID' ";

    if (!empty($Start_Date) && !empty($End_Date)) {
        $filter = " AND isu.Issue_Date BETWEEN '$Start_Date' AND '$End_Date' AND rq.Store_Need = '$Sub_Department_ID' ";
    }

    if($Store_Issue != 0 && $Store_Issue != null && $Store_Issue != ''){
        $filter .= " AND rq.Store_Issue = '$Store_Issue' ";
    }

    if (!empty($Order_No)) {
        $filter = " AND grn.Grn_Issue_Note_ID = '$Order_No' ";
    }
    
    //echo $filter;exit;
    ?>

    <legend align=right><b><?php if (isset($_SESSION['Pharmacy_ID'])) {
        echo $Sub_Department_Name;
    } ?> ,GRN Against Issue Note, List Of Issues&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
    <center>
        <table width = 100% border=0>
            <tr id='thead'>
                <td width=4% style='text-align: center;'><b>Sn</b></td>
                <td width=7% style='text-align: left;'><b>Grn N<u>o</u></b></td>
                <td width=7% style='text-align: left;'><b>Grn Date</u></b></td>
                <td width=7% style='text-align: left;'><b>Issue N<u>o</u></b></td>
                <td width=7% style='text-align: left;'><b>Requisition N<u>o</u></b></td>
                <td width=13% style='text-align: left;'><b>Requested Date</b></td>
                <td width=17% style='text-align: left;'><b>Requisition Prepared By</b></td>
                <td width=13%><b>Issue Date & Time</b></td>
                <td width=15%><b>Received From</b></td>
                <td width=30%><b>Requisition Description</b></td>
                <td style='text-align: center;' width=10%><b>Action</b></td>
            </tr> 
            <?php
            $temp = 1;
            
            $sql_select = mysqli_query($conn,"select grn.Grn_Issue_Note_ID,grn.Created_Date_Time,rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
					tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu,tbl_grn_issue_note grn where
					    rq.store_issue = sd.sub_department_id and
						emp.employee_id = rq.employee_id and
						    rq.requisition_status = 'Received' and
							isu.Requisition_ID = rq.Requisition_ID and
                                                        isu.Issue_ID = grn.Issue_ID 
                                                        $filter
                                                        order by grn.Grn_Issue_Note_ID desc limit 200") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_select);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($sql_select)) {
                    $Sub_Department_ID = $row['Store_Issue'];
                    //get Sub_department_need
                    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    if ($no > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            $Sub_Department_Name = $data['Sub_Department_Name'];
                        }
                    } else {
                        $Sub_Department_Name = '';
                    }


                    echo '<tr><td style="text-align: center;">' . $temp . '</td>
                    <td>' .  $row['Grn_Issue_Note_ID'] . '</td>
                    <td>' . $row['Created_Date_Time'] . '</td>
                    <td><a href="previousissuenotereport.php?Issue_ID='.$row['Issue_ID'].'&PreviousIssueNote=PreviousIssueNoteThisPage" target="_blank" style="text-decoration: none;">'.$row['Issue_ID'].'</a></td>
                    <td><a href="requisition_preview.php?Requisition_ID='.$row['Requisition_ID'].'&RequisitionPreview=RequisitionPreviewThisPage" target="_blank" style="text-decoration: none;">'.$row['Requisition_ID'].'</a></td>
                    <td>' . $row['Sent_Date_Time'] . '</td>
                    <td>' . $row['Employee_Name'] . '</td>	
                    <td>' . $row['Issue_Date_And_Time'] . '</td>	
                    <td>' . $Sub_Department_Name . '</td> 	
                    <td>' . $row['Requisition_Description'] . '</td> 
                    <td style="text-align: center;">
                    <a href="Control_Pharmacy_Grn_Session.php?New_Grn=New&Issue_ID=' . $row['Issue_ID'] . '" class="art-button-green">&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a>
                    </td>
                </tr>';
                    $temp++;
                }
            }
            echo '</table>';
       // }
        ?>