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
        <td width=12% style='text-align: center;'><b>Order Number</b></td>
        <td width=13%><b>Order Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
        <td width=15%><b>Supplier</b></td>
        <td width=20%><b>Order Description</b></td>
        <td style='text-align: center;'><b>Action</b></td>
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
    $select_data = "select po.Purchase_Order_ID, po.Created_Date, sd.Sub_Department_Name, po.Order_Description, sp.Supplier_Name from
                        tbl_purchase_order po, tbl_sub_department sd, tbl_employee emp, tbl_supplier sp where
                        po.sub_department_id = sd.sub_department_id and
                        po.Supplier_ID = sp.Supplier_ID and
                        emp.employee_id = po.employee_id and po.order_status = 'submitted'
                        order by po.Purchase_Order_ID desc limit 150";
                                
    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Purchase_Order_ID']."</td>";
        echo "<td>".$row['Created_Date']."</td>";
        echo "<td>".$row['Sub_Department_Name']."</td>";
        echo "<td>".$row['Supplier_Name']."</td>";
        echo "<td>".$row['Order_Description']."</td>";
        echo "<td width=4%><a href='previouspurchaseorderreport.php?Purchase_Order_ID=".$row['Purchase_Order_ID']."&PreviousOrderReport=PreviousOrderReportThisPage' class='art-button-green' target='_blank'>&nbsp;&nbsp;&nbsp;Preview&nbsp;&nbsp;&nbsp;</a></td></tr>";
        $temp++;
    }
?>
</table>