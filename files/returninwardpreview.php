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
            echo "<a href='Control_Return_Inward_Sessions.php?New_Return_Inward=True&Status=new' class='art-button-green'>NEW RETURN INWARD</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='pendingreturninwards.php?PendingReturnInward=PendingReturnInwardThisPage' class='art-button-green'>PENDING RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            echo "<a href='previousreturninwards.php?PreviousReturnInward=PreviousReturnInwardThisPage' class='art-button-green'>PREVIOUS RETURN INWARDS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
            if (isset($_GET['Status']) && $_GET['Status'] == 'PreviousReturnInward') {
                echo "<a href='previousreturninwards.php?PreviousReturnInward=PreviousReturnInwardThisPage' class='art-button-green'>BACK</a>";
            } else {
                echo "<a href='returninwards.php?ReturnInward=ReturnInwardThisPage' class='art-button-green'>BACK</a>";
            }
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
    if(isset($_GET['Inward_ID'])){
        $Inward_ID = $_GET['Inward_ID'];
    }else{
        $Inward_ID = 0;
    }

    $select = mysqli_query($conn,"SELECT
                              Inward_Date, ssd.Sub_Department_Name as ssd, rsd.Sub_Department_Name as rsd, Employee_Name
                           FROM
                              tbl_return_inward ri, tbl_sub_department ssd, tbl_sub_department rsd, tbl_employee emp
                           WHERE
                              ssd.Sub_Department_ID = ri.Store_Sub_Department_ID AND
                              rsd.Sub_Department_ID = ri.Return_Sub_Department_ID AND
                              emp.Employee_ID = ri.Employee_ID AND
                              Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Inward_Date = $data['Inward_Date'];
            $Store_Sub_Department_Name = $data['ssd'];
            $Return_Sub_Department_Name = $data['rsd'];
            $Employee_Name = $data['Employee_Name'];
        }
    }else{
        $Inward_Date = '000/00/00';
        $Store_Sub_Department_Name = '';
        $Return_Sub_Department_Name = '';
        $Employee_Name = '';
    }
?>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: left;" width="12%">Transaction No</td>
            <td width="15%"><input type="text" name="Transaction_No" id="Transaction_No" value="<?php echo $Inward_ID; ?>" readonly="readonly"></td>
            <td style="text-align: right;" width="12%">Returned From</td>
            <td><input type="text" name="Issuing_Store" id="Issuing_Store" value="<?php echo $Return_Sub_Department_Name; ?>" readonly="readonly"></td>
            <td style="text-align: right;">Store Receiving</td>
            <td><input type="text" name="Receiving_Store" id="Receiving_Store" value="<?php echo $Store_Sub_Department_Name; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td style="text-align: left;">Transaction Date</td>
            <td><input type="text" name="Transaction_Date" id="Transaction_Date" value="<?php echo date('d-m-Y', strtotime($Inward_Date)); ?>" readonly="readonly"></td>
            <td style="text-align: right;">Posted By</td>
            <td><input type="text" name="Posted_By" id="Posted_By" value="<?php echo $Employee_Name; ?>" readonly="readonly"></td>
            <td width="7%" style="text-align: right;" colspan="2"><input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report(<?php echo $Inward_ID; ?>)"></td>
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
        $select = mysqli_query($conn,"SELECT
                                  i.Unit_Of_Measure, i.Product_Name, rii.Quantity_Returned, i.Product_Code, rii.Item_Remark
                                FROM
                                  tbl_return_inward_items rii, tbl_items i
                                WHERE
                                  i.Item_ID = rii.Item_ID and Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
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
    function Preview_Report(Inward_ID){
        if(Inward_ID != 0 && Inward_ID != '' && Inward_ID != null){
            window.open("returninwardreport.php?Inward_ID="+Inward_ID+"&ReturnInwardReport=ReturnInwardReportThisPage","_blank");
        }else{

        }
    }
</script>
<?php     
	include("./includes/footer.php");
?>