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
        <td width=5% style='text-align: center;'><b>Sn</b></td>
        <td width=8% style='text-align: center;'><b>GRN Number</b></td>
        <td width=13%><b>GRN Date & Time</b></td>
        <td width=10%><b>Store Name</b></td>
        <td width=20%><b>GRN Description</b></td>
        <td width=15%><b>Prepared By</b></td>
        <td width=5%><b>Action</b></td>
    </tr>
    
    
    
<?php
    $temp = 1;
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    //get details
    $select_data = "select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_Description from
                        tbl_grn_open_balance gob, tbl_sub_department sd, tbl_employee emp where
                            gob.sub_department_id = sd.sub_department_id and
                                    emp.employee_id = gob.employee_id and
                                        gob.Grn_Open_Balance_Status = 'pending' and
                                            gob.Employee_ID = '$Employee_ID' order by gob.Grn_Open_Balance_ID";
                                
    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
        echo "<tr><td width=5%><input type='text' value='".$temp."' readonly='readonly' style='text-align: center;'></td>";
        echo "<td width=7%><input type='text' value='".$row['Grn_Open_Balance_ID']."' readonly='readonly' style='text-align: center;'></td>";
        echo "<td width=10%><input type='text' value='".$row['Created_Date_Time']."' readonly='readonly'></td>";
        echo "<td width=10%><input type='text' value='".$row['Sub_Department_Name']."' readonly='readonly'></td>";
        echo "<td width=40%><input type='text' value='".$row['Grn_Open_Balance_Description']."' readonly='readonly'></td>";
        echo "<td width=10%><input type='text' value='".$row['Employee_Name']."' readonly='readonly'></td>";
        echo "<td width=4%><a href='Control_Grn_Open_Balance_Sessions.php?Pending_Grn_Open_Balance=True&Grn_Open_Balance_ID=".$row['Grn_Open_Balance_ID']."&PurchaseOrder=PurchaseOrderThisPage' target='_Parent' class='art-button-green'>&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a></td></tr>";
        $temp++;
    }
?>
</table>