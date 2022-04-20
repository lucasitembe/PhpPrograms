<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                        header("Location: ./index.php?InvalidPrivilege=yes");
                } 
        }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
            echo "<a href='returnoutwards.php?ReturnOutward=ReturnOutwardThisPage' class='art-button-green'>BACK</a>";
        }
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
<?php
    if(isset($_GET['Outward_ID'])){
        $Outward_ID = $_GET['Outward_ID'];
    }else{
        $Outward_ID = 0;
    }

    $select = mysqli_query($conn,"select Outward_Date, Sub_Department_Name, Supplier_Name, Employee_Name from 
                            tbl_return_outward ro, tbl_sub_department sd, tbl_supplier sp, tbl_employee emp where 
                            sd.Sub_Department_ID = ro.Sub_Department_ID and
                            sp.Supplier_ID = ro.Supplier_ID and
                            emp.Employee_ID = ro.Employee_ID and
                            Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Outward_Date = $data['Outward_Date'];
            $Sub_Department_Name = $data['Sub_Department_Name'];
            $Supplier_Name = $data['Supplier_Name'];
            $Employee_Name = $data['Employee_Name'];
        }
    }else{
        $Outward_Date = '000/00/00';
        $Sub_Department_Name = '';
        $Supplier_Name = '';
        $Employee_Name = '';
    }
?>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: left;" width="12%">Transaction No</td>
            <td width="15%"><input type="text" name="Transaction_No" id="Transaction_No" value="<?php echo $Outward_ID; ?>" readonly="readonly"></td>
            <td style="text-align: right;">Receiving Supplier</td>
            <td><input type="text" name="Receiving_Store" id="Receiving_Store" value="<?php echo $Supplier_Name; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Store Issued</td>
            <td><input type="text" name="Sub_Department_Name" id="Sub_Department_Name" value="<?php echo $Sub_Department_Name; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td style="text-align: left;">Transaction Date</td>
            <td><input type="text" name="Transaction_Date" id="Transaction_Date" value="<?php echo date('d-m-Y', strtotime($Outward_Date)); ?>" readonly="readonly"></td>
            <td style="text-align: right;">Posted By</td>
            <td><input type="text" name="Posted_By" id="Posted_By" value="<?php echo $Employee_Name; ?>" readonly="readonly"></td>
            <td width="7%" style="text-align: right;" colspan="2"><input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report(<?php echo $Outward_ID; ?>)"></td>
        </tr>
    </table>
</fieldset><br/>

<fieldset style='overflow-y: scroll; height: 350px; background-color: white;' id='Grn_List'>
    <table width="100%">
        <tr><td colspan="6"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="10%" style="text-align: center;"><b>ITEM CODE</b></td>
            <td><b>ITEM NAME</b></td>
            <td width="10%" style="text-align: center;"><b>UOM</b></td>
            <td width="10%" style="text-align: right;"><b>QTY RETURNED</b>&nbsp;&nbsp;&nbsp;</td>
            <td width="15%"><b>ITEM REMARK</b></td>
        </tr>
        <tr><td colspan="6"><hr></td></tr>
    <?php
        $temp = 0;
        $select = mysqli_query($conn,"select i.Unit_Of_Measure, i.Product_Name, roi.Quantity_Returned, i.Product_Code, roi.Item_Remark
                                from tbl_return_outward_items roi, tbl_items i where 
                                i.Item_ID = roi.Item_ID and Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($row = mysqli_fetch_array($select)) {
    ?>
                <tr>
                    <td><?php echo ++$temp; ?><b>.</b></td>
                    <td style="text-align: center;"><?php echo $row['Product_Code']; ?></td>
                    <td><?php echo $row['Product_Name']; ?></td>
                    <td style="text-align: center;"><?php echo $row['Unit_Of_Measure']; ?></td>
                    <td style="text-align: right;"><?php echo $row['Quantity_Returned']; ?>&nbsp;&nbsp;&nbsp;</td>
                    <td><?php echo $row['Item_Remark']; ?></td>
                </tr>
    <?php
            }
        }
    ?>
        <tr><td colspan="6"><hr></td></tr>
    </table>
</fieldset>
<script type="text/javascript">
    function Preview_Report(Outward_ID){
        if(Outward_ID != 0 && Outward_ID != '' && Outward_ID != null){
            window.open("returnoutwardreport.php?Outward_ID="+Outward_ID+"&ReturnOutwardReport=ReturnOutwardReportThisPage","_blank");
        }else{

        }
    }
</script>
<?php     
	include("./includes/footer.php");
?>