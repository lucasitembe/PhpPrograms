<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
	   @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                        header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    @session_start();
                    if(!isset($_SESSION['Storage_Supervisor'])){ 
                        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
            }
        }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $canPakage = false;
    $display = "style='display:none'";

    if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
        $canPakage = true;
        $display = "";
    }

    echo "<a href='Control_Grn_Open_Balance_Sessions.php?New_Grn_Open_Balance=True&Status=new' class='art-button-green'>NEW OPEN BALANCE</a>";
    echo "<a href='penginggpnopenbalance.php?PendingGrnOpenBalance=PendingGrnOpenBalanceThisPage' class='art-button-green'>PENDING OPEN BALANCES</a>";
    echo "<a href='Control_Grn_Open_Balance_Sessions.php?Previous_Grn_Open_Balance=True&Status=Previous' class='art-button-green'>PREVIOUS OPEN BALANCES</a>";
    echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";

    //GET EMPLOYEE  NAME & ID
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
    }

    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
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
    $select_data = mysqli_query($conn,"select emp.Employee_Name, gob.Grn_Open_Balance_Description, gob.Created_Date_Time, sd.Sub_Department_Name, gob.Grn_Open_Balance_ID from
                                tbl_employee emp, tbl_grn_open_balance gob, tbl_sub_department sd where
                                emp.Employee_ID = gob.Employee_ID and
                                emp.Employee_ID = '$Employee_ID' and
                                sd.Sub_Department_ID = gob.Sub_Department_ID order by gob.Grn_Open_Balance_ID desc limit 1") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_data);
    if($num > 0){
        while($row = mysqli_fetch_array($select_data)){
            $Employee_Name = $row['Employee_Name'];
            $Sub_Department_Name = $row['Sub_Department_Name'];
            $Created_Date_Time = $row['Created_Date_Time'];
            $Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
            $Grn_Open_Balance_ID = $row['Grn_Open_Balance_ID'];
        }
    }else{
        $Employee_Name = '';
        $Sub_Department_Name = '';
        $Created_Date_Time = '';
        $Grn_Open_Balance_ID = '';
        $Grn_Open_Balance_Description = '';
        $Grn_Open_Balance_ID = 0;
    }
    
    $date = new DateTime($Created_Date_Time);
    $date_Display = $date->format('d-m-Y H:i:s');
?>


<center>
<fieldset>
    <legend align="right" ><b><?php echo strtoupper($E_Name); ?> LAST OPEN BALANCE</b></legend>
    <table width=100%>
        <tr>
            <td width=10% style='text-align: right;'>Store Name</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Sub_Department_Name; ?>'>
            </td>
            <td width=10% style='text-align: right;'>GRN Number</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Grn_Open_Balance_ID; ?>'>
            </td>
            <td width=10% style='text-align: right;'>Created Date & Time</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Created_Date_Time; ?>'>
            </td>
        </tr>
        <tr>
            <td width=10% style='text-align: right;'>GRN Description</td>
            <td width=15% colspan=3>
                <input type='text' name='Grn_Description' id='Grn_Description' value='<?php echo $Grn_Open_Balance_Description; ?>' onclick='update_Grn_Description()' onkeyup='update_Grn_Description()' onkeypress='update_Grn_Description()'>
            </td> 
            <td width=10% style='text-align: right;'>Prepared By</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right">
                <input type="button" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 300px; background-color: white;' id='Items_Fieldset_List'>
    <?php
        $Grand_Total = 0;
        echo '<center><table width = 100% border=0>';
        echo '<tr><td colspan="10"><hr></td></tr>';
        echo '<tr><td width=4% style="text-align: center;">Sn</td>
        <td width=30%>Item Name</td>
        <td width=9% style="text-align: center;">Unit Of Measure</td>
        <td '.$display.' width="9%">Containers</td>
        <td '.$display.' width="9%">Items Per Container</td>
        <td width=9% style="text-align: center;">Quantity</td>
        <td width=9% style="text-align: right;">Buying Price</td>
        <td width=7% style="text-align: right;">Manuf Date</td>
        <td width=7% style="text-align: right;">Expire Date</td>
        <td width=7% style="text-align: right;">Sub Total</td></tr>';
        echo '<tr><td colspan="10"><hr></td></tr>';
        
        $select_Open_Balance_Items = mysqli_query($conn,"select obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price,
		obi.Manufacture_Date, obi.Expire_Date, obi.Container_Qty, obi.Items_Per_Container, itm.Unit_Of_Measure
		    from tbl_grn_open_balance_items obi, tbl_items itm where
			itm.Item_ID = obi.Item_ID and
			    obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' order by obi.Open_Balance_Item_ID desc") or die(mysqli_error($conn)); 
        
        $Temp=1;
        while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
            echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
            echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
            echo "<td><input type='text' readonly='readonly' value='" . $row['Unit_Of_Measure'] . "' style='text-align: center;'></td>";
            echo "<td ".$display." ><input type='text' name='Container_Qty' id='Container_Qty' value='".$row['Container_Qty']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td ".$display." ><input type='text' name='Items_Per_Container' id='Items_Per_Container' value='".$row['Items_Per_Container']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td><input type='text' readonly='readonly' name='Item_Quantity' id='Item_Quantity' value='".$row['Item_Quantity']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td style='text-align: right;'><input type='text' readonly='readonly' name='Buying_Price' id='Buying_Price' value='".$row['Buying_Price']."' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Manufacture_Date']."'></td>";
            echo "<td><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Expire_Date']."'></td>";
            echo "<td style='text-align: right;'><input type='text' value='".number_format($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
            echo "</tr>";
            $Temp++;
            $Grand_Total += ($row['Item_Quantity'] * $row['Buying_Price']);
        }
        echo '</table>';
    ?>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <b>GRAND TOTAL : <?php echo number_format($Grand_Total); ?></b>
            </td>
        </tr>
    </table>
</fieldset>

<script type="text/javascript">
    function Preview_Report(){
        var Grn_Open_Balance_ID = '<?php echo $Grn_Open_Balance_ID; ?>';
        window.open("Preview_Grn_Details_Report.php?Grn_Open_Balance_ID="+Grn_Open_Balance_ID+"&PreviewGrnDetailsReport=PreviewGrnDetailsReportThisPage","_blank");
    }
</script>

<?php
    include("./includes/footer.php");
?>
