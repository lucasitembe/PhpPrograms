<link rel="stylesheet" href="table.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="css_style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
        <script src="js/tabcontent.js" type="text/javascript"></script>
    


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<?php
    @session_start();
    include("./includes/connection.php");
?>
<table width=100% border=0>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>Sn</b></td>
        <td width=8% style='text-align: center;'><b>Requisition N<u>o</u></b></td>
        <td width=13%><b>Created Date & Time</b></td>
        <td width=10%><b>Store Issue</b></td>
        <td width=20%><b>Requisition Description</b></td>
        <td style='text-align: center;'><b>Action</b></td>
    </tr>
    
    
    
<?php
    $temp = 1;

	//get sub department id
	if(isset($_SESSION['Pharmacy_ID'])){
		$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	}else{
		$Sub_Department_ID = 0;
	}
	
	
    //get details depends on date from and date to (if and only if is inserted)
    if(isset($_GET['Date_From']) && isset($_GET['Date_To']) && ($_GET['Date_From'] != '') && ($_GET['Date_To'] != '') && ($_GET['Date_From'] != null)){
        $Date_From = $_GET['Date_From'];
        $Date_To = $_GET['Date_To'];
        $select_data = "select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, sd.Sub_Department_Name from
                        tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                            rq.store_issue = sd.sub_department_id and 
                                emp.employee_id = rq.employee_id and rq.requisition_status = 'pending' and
										rq.Store_Need = '$Sub_Department_ID' and
											Created_Date_Time between '$Date_From' and '$Date_To' order by rq.Requisition_ID limit 200";
    }else{
        $select_data = "select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, sd.Sub_Department_Name from
                        tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                            rq.store_issue = sd.sub_department_id and 
								rq.Store_Need = '$Sub_Department_ID' and
                                    emp.employee_id = rq.employee_id and rq.requisition_status = 'pending' order by rq.Requisition_ID limit 200";
    }
    
    
    
    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Requisition_ID']."</td>";
        echo "<td>".$row['Created_Date_Time']."</td>";
        echo "<td>".$row['Sub_Department_Name']."</td>";
        echo "<td width=40%>".$row['Requisition_Description']."</td>";
        echo "<td width=4%><a href='Pharmacy_Control_Requisition_Sessions.php?Requisition_ID=".$row['Requisition_ID']."&Pending_Requisition=True&Requisition=RequisitionThisPage' class='art-button-green' target='_parent'>&nbsp;&nbsp;&nbsp;Continue Process&nbsp;&nbsp;&nbsp;</a></td></tr>";
        $temp++;
    }
?>
</table>