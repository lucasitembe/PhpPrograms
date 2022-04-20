<link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
</style>

<table width=100% style="border-collapse:collapse !important;
		border:none !important;">
    <tr>
        <td width=4% style='text-align: center; background-color:silver'><b>SN</b></td>
        <td width=6% style='text-align:center; background-color:silver'><b>Order N<u>o</u></b></td>
        <td style='background-color:silver;' width=15%><b>Order Date & Time</b></td>
        <td style='background-color:silver;' width=10%><b>Store Need</b></td>
        <td style='background-color:silver;' width=20%><b>Supplier Name</b></td>
        <td style='background-color:silver;' width=40%><b>Order Description</b></td>
    </tr> 
<?php
    @session_start();
    $temp = 1;
    include("./includes/connection.php");
    if(isset($_SESSION['Storage'])){
        $Sub_Department_Name = $_SESSION['Storage'];
    }else{
        $Sub_Department_Name = '';
    }
    
    //select order data
    $select_Order_Details = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
                                po.sub_department_id = sd.sub_department_id and
                                    po.supplier_id = sp.supplier_id and
                                        po.order_status = 'served' and
                                        sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by purchase_order_id") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Order_Details);
    if($no > 0){
        while($row = mysqli_fetch_array($select_Order_Details)){
            echo "<tr><td style='text-align:center;'><input type='text' readonly='readonly' value = '".$temp."' style='text-align:center;'></td>";
            echo "<td><input type='text' readonly='readonly' value = '".$row['Purchase_Order_ID']."' style='text-align:center;'></td>";
            echo "<td><input type='text' readonly='readonly' value = '".$row['Created_Date']."'></td>";
            echo "<td><input type='text' readonly='readonly' value = '".$row['Sub_Department_Name']."'></td>";
            echo "<td><input type='text' readonly='readonly' value = '".$row['Supplier_Name']."'></td>";
            echo "<td><input type='text' readonly='readonly' value = '".$row['Order_Description']."'></td>";
            echo "<td><a href='grnpurchaseorder.php?Purchase_Order_ID=".$row['Purchase_Order_ID']."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Preview</a></td></tr>";
            $temp++;
        }
        echo "</tr>";
    }
?>

</table>