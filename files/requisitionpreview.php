<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
        }
    }
    //get Purchase_Order_ID
    if(isset($_SESSION['Requisition_Preview'])){
        $Requisition_ID = $_SESSION['Requisition_Preview'];
    }else{
        $Requisition_ID = 0;
    }
    
    //get all basic information
    $select_info = mysqli_query($conn,"select * from tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                rq.Store_Issue = sd.Sub_Department_ID and
                                rq.Employee_ID = emp.Employee_ID and
                                rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    
    $num = mysqli_num_rows($select_info);
    if($num > 0){
        while($row = mysqli_fetch_array($select_info)){
            $Requisition = $row['Requisition_ID'];
            $Requisition_Date = $row['Created_Date_Time'];
            
            //get store need
            $Sub_Department_ID2 = $row['Store_Need'];
            $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID2'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select_store_need);
            if($no > 0){
                while($row2 = mysqli_fetch_array($select_store_need)){
                    $Store_Need = $row2['Sub_Department_Name'];
                }
            }else{
                $Store_Need = '';
            }
            
            $Store_Issue = $row['Sub_Department_Name'];
            $Prepared_By = ucwords(strtolower($row['Employee_Name']));
            $Description = $row['Requisition_Description'];
        }
    }else{
        $Requisition = '';
        $Requisition_Date = '';
        $Store_Issue = '';
        $Prepared_By = '';
        $Description = '';
    }
?>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
 </style> 
<fieldset>
    <legend align="left"><b>Requisition preview</b></legend>
    <table width="100%">
        <tr>
            <td width="12%">Requisition Number</td>
            <td width="15%"><input type="text" value="<?php echo $Requisition_ID; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Requisition Date</td>
            <td width="15%"><input type="text" value="<?php echo $Requisition_Date; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Prepared By</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Prepared_By)); ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td>Store Need</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Store_Need)); ?>" readonly="readonly"></td>
            <td style="text-align: right;">Store Issue</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Store_Issue)); ?>" readonly="readonly"></td>
            <td style="text-align: right;">Requisition Description</td>
            <td><input type="text" value="<?php echo $Description; ?>" readonly="readonly"></td>
        </tr>
    </table> 
</fieldset>

<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW REQUISITION" onclick="Preview_Requisition_Report(<?php echo $Requisition_ID; ?>);">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 305px; background-color: white;' id='Items_Fieldset'>
    </table><table width='100%'>
        <tr>
            <td width=3%><b>Sn</b></td>
            <td><b>Item Name</b></td>
            <td width=8% style='text-align: right;'><b>Containers</b></td>
            <td width=10% style='text-align: right;'><b>Items per Container</b></td>
            <td width=8% style='text-align: right;'><b>Quantity</b>&nbsp;&nbsp;&nbsp;</td>
            <td width=25% style='text-align: left;'><b>&nbsp;&nbsp;&nbsp;Item Remark</b></td>
        </tr>
        <tr><td colspan="6"><hr></td></tr>
<?php
    //select data from the table tbl_purchase_order_items
    $temp = 1; $Amount = 0; $Grand_Total = 0;
    $select_data = mysqli_query($conn,"select * from tbl_requisition_items rqi, tbl_items it where
                                rqi.item_id = it.item_id and Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
?>
        <tr><td><?php echo $temp; ?></td><td><?php echo $row['Product_Name']; ?></td>
        <td style='text-align: right;'><?php echo $row['Container_Qty']; ?></td>
        <td style='text-align: right;'><?php echo $row['Items_Qty']; ?></td>
        <td style='text-align: right;'><?php echo $row['Quantity_Required']; ?>&nbsp;&nbsp;&nbsp;</td>
        <td style='text-align: left;'>&nbsp;&nbsp;&nbsp;<?php echo $row['Item_Remark']; ?></td>
<?php
	   $temp++;
    }
    echo "<tr><td colspan='6'><hr></td></tr>";
    echo "</table>";
?>
</table>
</fieldset>
<script type="text/javascript">
    function Preview_Requisition_Report(Requisition_ID){
        var winClose=popupwindow('previousrequisitionreport.php?Requisition_ID='+Requisition_ID, 'REQUISITION DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>
<?php
    include("./includes/footer.php");
?>