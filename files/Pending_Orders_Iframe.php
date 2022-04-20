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
        <td width=8% style='text-align: center;'><b>Order Number</b></td>
        <td width=13%><b>Order Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
        <td width=15%><b>Supplier</b></td>
        <td width=20%><b>Order Description</b></td>
        <td></td>
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
                                    emp.employee_id = po.employee_id and po.order_status = 'pending' and
                                        po.Employee_ID = '$Employee_ID' order by po.Purchase_Order_ID";
                                
    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
        echo "<tr><td><input type='text' value='".$temp."' readonly='readonly' style='text-align: center;'></td>";
        echo "<td><input type='text' value='".$row['Purchase_Order_ID']."' readonly='readonly' style='text-align: center;'></td>";
        echo "<td><input type='text' value='".$row['Created_Date']."' readonly='readonly'></td>";
        echo "<td><input type='text' value='".$row['Sub_Department_Name']."' readonly='readonly'></td>";
        echo "<td><input type='text' value='".$row['Supplier_Name']."' readonly='readonly'></td>";
        echo "<td width=40%><input type='text' value='".$row['Order_Description']."' readonly='readonly'></td>";
        echo "<td width=4%><a href='Control_Purchase_Order_Sessions.php?Pending_Purchase_Order=True&Purchase_Order_ID=".$row['Purchase_Order_ID']."&PurchaseOrder=PurchaseOrderThisPage' target='_Parent' class='art-button-green'>&nbsp;&nbsp;&nbsp;Process&nbsp;&nbsp;&nbsp;</a></td></tr>";
        $temp++;
    }
?>
</table>