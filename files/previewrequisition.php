<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    //get Purchase_Order_ID
    if(isset($_GET['Requisition_ID'])){
        $Requisition_ID = $_GET['Requisition_ID'];
    }else{
        $Requisition_ID = 0;
    }
    echo "<a href='requisition.php?Requisition=RequisitionThisPage' class='art-button-green'>BACK</a>";
?>
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
<?php
    //get all basic information
    $select_info = mysqli_query($conn,"SELECT * from tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
                                    rq.Store_Issue = sd.Sub_Department_ID and
                                            rq.Employee_ID = emp.Employee_ID and
                                                rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    
    while($row = mysqli_fetch_array($select_info)){
        $Sent_Date_Time = $row['Sent_Date_Time'];

        //get store need
        $Sub_Department_ID2 = $row['Store_Need'];
        $select_store_need = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID2'") or die(mysqli_error($conn));
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
?>
<br/><br/>
<fieldset>
    <table width=100%>
        <tr>
            <td width=13% style="text-align: right;">Requisition N<u>o</u></td><td width=20%><input type='text' value='<?php echo $Requisition_ID; ?>' readonly='readonly'></td>
            <td width=13% style="text-align: right;">Requisition Date </td><td><input type='text' value='<?php echo $Sent_Date_Time; ?>' readonly='readonly'></td>
            <td width=13% style="text-align: right;">Store Need</td><td style='text-align: left;'><input type='text' value='<?php echo $Store_Need; ?>' readonly='readonly'></td>
        </tr>
        <tr>
            <td style="text-align: right;">Store Issue  </td><td><input type='text' value='<?php echo $Store_Issue; ?>' readonly='readonly'></td>
            <td style="text-align: right;">Prepared By </td><td style='text-align: left;'><input type='text' value='<?php echo $Prepared_By; ?>' readonly='readonly'></td>
            <td style="text-align: right;">Description </td><td><input type='text' value='<?php echo $Description; ?>' readonly='readonly'></td>
        </tr>        
    </table>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 320px; background-color: white;' id='Items_Fieldset'>
    <table width='100%'>
        <tr>
            <td width=3%>SN</td>
            <td>ITEM NAME</td>
            <td width=12% style='text-align: right;'>CONTAINERS</td>
            <td width=15% style='text-align: right;'>ITEMS PER CONTAINER</td>
            <td width=12% style='text-align: right;'>QUANTITY</td>
        </tr>
        <tr><td colspan=5><hr></td></tr>
<?php
    //select data from the table tbl_purchase_order_items
    $temp = 0; $Amount = 0; $Grand_Total = 0;
    $select_data = mysqli_query($conn,"SELECT * from tbl_requisition_items rqi, tbl_items it where
                                rqi.item_id = it.item_id and Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
?>
        <tr><td><?php echo ++$temp; ?></td><td><?php echo $row['Product_Name']; ?></td>
        <td style='text-align: right;'><?php echo $row['Container_Qty']; ?></td>
        <td style='text-align: right;'><?php echo $row['Items_Qty']; ?></td>
        <td style='text-align: right;'><?php echo $row['Quantity_Required']; ?></td>
<?php
        $temp++;
    }
?>
</table>

<script type="text/javascript">
    function Preview_Report(){
        var Requisition_ID = '<?php echo $Requisition_ID; ?>';
        window.open("requisition_preview.php?Requisition_ID="+Requisition_ID+"&RequisitionPreview=RequisitionPreviewThisPage","_blank");
    }
</script>
<?php
    include("./includes/footer.php");
?>