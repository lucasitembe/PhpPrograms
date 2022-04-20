<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    include("./storeordering_navigation.php");
        
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 'Unknown Officer';
    }
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }
    
    
    //get requisition id
    if(isset($_SESSION['General_Order_ID'])){
        $Store_Order_ID = $_SESSION['General_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }
    
    
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])) {
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
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
    }else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
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
<fieldset>
<center>
    <table width="60%">
        <tr> 
            <td style='text-align: right;' width=10%><b>Start Date</b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;' style='text-align: center;'>
            </td>
            <td style='text-align: right;' width=10%><b>End Date</b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;' style='text-align: center;'>
            </td>
            <td style='text-align: center;' width=7%>
                <input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Previous_Orders()'>
            </td>
        </tr>
    </table>
</center>
</fieldset><br/>
<fieldset style='overflow-y: scroll; height: 370px; background-color: white;' id='Orders_Area'>
    <legend align="right"><b>PREVIOUS ORDERS</b></legend>
    <table width="100%">
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="7%"><b>ORDER NO</b></td>
            <td><b>PREPARED BY</b></td>
            <td><b>APPROVED BY</b></td>
            <td><b>SUB DEPARTMENT NAME</b></td>
            <td><b>CREATED DATE</b></td>
            <td><b>SUBMITTED DATE</b></td>
            <td><b>APPROVED DATE</b></td>
            <td width="7%" style="text-align: center;"></td>
        </tr>
        <tr><td colspan="9"><hr></td></tr>
    <?php
        $temp = 0;
        $select = mysqli_query($conn,"SELECT Store_Order_ID, emp.Employee_Name, so.Supervisor_ID, Created_Date_Time, sd.Sub_Department_Name, Sent_Date_Time, Approval_Date_Time
                               FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
                               WHERE Order_Status = 'Approved' AND
                                emp.Employee_ID = so.Employee_ID AND
                                so.Sub_Department_ID = sd.Sub_Department_ID
                               ORDER BY Store_Order_ID desc limit 100") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                //get supervisor id
                $Supervisor_ID = $data['Supervisor_ID'];
                $slct_supervisor = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Supervisor_ID'") or die(mysqli_error($conn));
                $slct_num = mysqli_num_rows($slct_supervisor);
                if($slct_num > 0){
                    while ($dt = mysqli_fetch_array($slct_supervisor)) {
                        $Supervisor_Name = $dt['Employee_Name'];
                    }
                }else{
                    $Supervisor_Name = '';
                }
    ?>
        <tr>
            <td><?php echo ++$temp; ?></td>
            <td><?php echo $data['Store_Order_ID']; ?></td>
            <td><?php echo ucwords(strtolower($data['Employee_Name'])); ?></td>
            <td><?php echo ucwords(strtolower($Supervisor_Name)); ?></td>
            <td><?php echo $data['Sub_Department_Name']; ?></td>
            <td><?php echo $data['Created_Date_Time']; ?></td>
            <td><?php echo $data['Sent_Date_Time']; ?></td>
            <td><?php echo $data['Approval_Date_Time']; ?></td>
            <td width="10%" style="text-align: center;">
                <input type="button" name="Preview" id="Preview" class="art-button-green" value="PREVIEW ORDER" onclick="Preview_Order(<?php echo $data['Store_Order_ID']; ?>)">
            </td>
        </tr>
    <?php                
            }
        }

    ?>
    </table>
</fieldset>

<script type="text/javascript">
    function Preview_Order(Store_Order_ID){
        window.open("previousstoreorderreport.php?Store_Order_ID="+Store_Order_ID+"&PreviousStoreOrder=PreviousStoreOrderThisPage","_blank");
    }
</script>

<script type="text/javascript">
    function Get_Previous_Orders(){
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            
            if(window.XMLHttpRequest) {
                myObjectOrders = new XMLHttpRequest();
            }else if(window.ActiveXObject){ 
                myObjectOrders = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectOrders.overrideMimeType('text/xml');
            }
                
            myObjectOrders.onreadystatechange = function (){
                data = myObjectOrders.responseText;
                if (myObjectOrders.readyState == 4) {
                    document.getElementById('Orders_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
                
            myObjectOrders.open('GET','Get_Previous_Orders.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
            myObjectOrders.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
            }
            if(End_Date == null || End_Date == ''){
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<?php
  include("./includes/footer.php");
?>