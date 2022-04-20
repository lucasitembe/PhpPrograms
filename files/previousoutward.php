<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 1;

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

<br/><br/>
<center>
    <table width=60%> 
        <tr> 
            <td width="10%" style="text-align: right;"><b>Start Date<b></td>
            <td><input type='text' name='Date_From' id='date' required='required' style="text-align: center;"></td>
            <td width="10%" style="text-align: right;"><b>End Date<b></td>
            <td><input type='text' name='Date_To' id='date2' required='required' style="text-align: center;"></td>
            <td width=15% style="text-align: center;">
                <input name='' type='submit' value='FILTER' class='art-button-green' onclick="Get_Previous_Outwards()">
            </td>
        </tr>
    </table>
</center>
 <br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Grn_List'>
    <legend style="background-color:#006400;color:white;padding:5px;" align='right'><b>PREVIOUS OUTWARDS</b></legend>
    <table width=100% style="border-collapse:collapse !important; border:none !important;">
        <tr>
            <td width=5% style='text-align: center;'><b>SN</b></td>
            <td width=10% style='text-align:center;'><b>TRANSACTION NO</b></td>
            <td width=12% style='text-align:center;'><b>TRANSACTION DATE</b></td>
            <td width=15%><b>STORE ISSUED</b></td>
            <td width=20%><b>SUPPLIER NAME</b></td>
            <td width=20%><b>PREPARED BY</b></td>
            <td width=10%><b>ACTION</b></td>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
<?php
    //select order data
    $select_grn = mysqli_query($conn,"select Outward_ID, Outward_Date, Sub_Department_Name, Supplier_Name, Employee_Name from 
                                tbl_return_outward ro, tbl_sub_department sd, tbl_supplier sp, tbl_employee emp where 
                                sd.Sub_Department_ID = ro.Sub_Department_ID and
                                sp.Supplier_ID = ro.Supplier_ID and
                                emp.Employee_ID = ro.Employee_ID order by Outward_ID desc limit 100") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_grn);
    if($no > 0){
        while($row = mysqli_fetch_array($select_grn)){
            $Outward_ID = $row['Outward_ID'];
            $Outward_Date = $row['Outward_Date'];
            $Sub_Department_Name = $row['Sub_Department_Name'];
            $Supplier_Name = $row['Supplier_Name'];
            $Employee_Name = $row['Employee_Name'];

            echo "<tr><td style='text-align:center;'>".$temp."</td>";
            echo "<td style='text-align: center;'>".$Outward_ID."</td>";
            echo "<td>".date('d-m-Y', strtotime($Outward_Date))."</td>";
            echo "<td>".$Sub_Department_Name."</td>";
            echo "<td>".$Supplier_Name."</td>";
            echo "<td>".$Employee_Name."</td>";
            echo "<td><input type='button' name='Preview' id='Preview' value='Preview' class='art-button-green' onclick='Preview_Report(".$row['Outward_ID'].")'></td></tr>";
            $temp++;
        }
        echo "</tr>";
    }
?>
</table>
</fieldset>
<script>
    function Preview_Report(Outward_ID){
        window.open("returnoutwardreport.php?Outward_ID="+Outward_ID+"&ReturnOutwardReport=ReturnOutwardReportThisPage","_blank");
    }
</script>

 
<?php     
	include("./includes/footer.php");
?>
