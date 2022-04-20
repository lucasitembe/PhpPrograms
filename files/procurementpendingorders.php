<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    //get employee id 
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }
	
    if(!isset($_SESSION['Procurement_Supervisor'])){
        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
    }

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Procurement_Works'])){
	    if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    } 

    //get sub department id
    if(isset($_SESSION['Procurement_ID'])){
        $Sub_Department_ID = $_SESSION['Procurement_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $row['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    if(isset($_SESSION['userinfo']) && isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] == 1){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>NEW ORDER</a>";
        }
    
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
        }
    }else{
        if(isset($_SESSION['Procurement_Autentication_Level']) && $_SESSION['Procurement_Autentication_Level'] != 100){
            //calculate pending orders based on assigned level
            $before_assigned_level = $_SESSION['Procurement_Autentication_Level'] - 1;
            $p_orders = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po
            where po.Order_Status = 'submitted' and po.Approval_Level = '$before_assigned_level'
            AND (SELECT count(*) FROM tbl_purchase_order_items poi WHERE poi.Purchase_Order_ID = po.Purchase_Order_ID) > 0") or die(mysqli_error($conn));
            $p_num = mysqli_num_rows($p_orders);
            
            if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
                echo "<a href='approvalsprocurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage' class='art-button-green'>
                        PENDING ORDERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>".$p_num."</span>
                        </a>";
            }
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            if(isset($_GET['from']) && $_GET['from'] == "purchaseOrder") {
                echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage&from=purchaseOrder' class='art-button-green'>BACK</a>";
            }else {
                echo "<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>";
            }
        }
    }
?>


    <!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->
    
<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date2").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
<!--    end of datepicker script-->


<?php

    if(isset($_POST['submit'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
    }else{
        $Date_From = '';
        $Date_To = '';	
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
	    <td style='text-align: right;' width=7%><b>Start Date</b></td>
	    <td width=30%>
		<input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: right;' width=7%><b>End Date</b></td>
	    <td width=30%>
		<input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;'>
	    </td>
	    <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Previous_Requisition()'></td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Previous_Fieldset_List'>
 
<legend style='background-color:#006400;color:white;padding:5px;' align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo ucwords(strtolower($Sub_Department_Name)); }?>, pending orders</b></legend>
    
    <?php
	    $temp = 1;
		
		echo '<tr><td colspan="9"><hr></td></tr>';
		echo '<center><table width = 100% border=0>';
		echo "<tr id='thead'>
                <td width=4% style='text-align: center;'><b>Sn</b></td>
                <td width=12% style='text-align: center;'><b>Order Number</b></td>
                <td width=13%><b>Order Date & Time</b></td>
                <td width=10%><b>Store Need</b></td>
                <td width=15%><b>Supplier</b></td>
                <td width=20%><b>Order Description</b></td>
                <td style='text-align: center;'><b>Action</b></td>
            </tr>";
	    echo '<tr><td colspan="9"><hr></td></tr>';
	    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Sent_Date_Time, sd.Sub_Department_Name from
									tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
									rq.store_issue = sd.sub_department_id and
									emp.employee_id = rq.employee_id and
									rq.requisition_status = 'submitted' and
									rq.Store_Need = '$Sub_Department_ID' order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
			while($row = mysqli_fetch_array($sql_select)){
			    echo '<tr><td style="text-align: center;">'.$temp.'</td>
				<td style="text-align: center;">'.$row['Requisition_ID'].'</td>
				<td>'.$row['Created_Date_Time'].'</td>
			    <td>'.$row['Sent_Date_Time'].'</td>
				<td>'.$row['Sub_Department_Name'].'</td>	
			    <td>'.$row['Requisition_Description'].'</td> 
				<td style="text-align: center;"><input type="button" value="Preview" onclick="Preview_Requisition_Report('.$row['Requisition_ID'].')" class="art-button-green">&nbsp;&nbsp;&nbsp;</td></tr>';
				$temp++;
			}
	    }
	    echo '</table>';
	?>
</fieldset>

    <!--<iframe src='Previous_Requisitions_Iframe.php?Employee_ID=<?php //echo $Employee_ID; ?>&Date_From=<?php //echo $Date_From; ?>&Date_To=<?php //echo $Date_To; ?>' width=100% height=380px></iframe>-->

<script>
    function Get_Previous_Requisition() {
	var Start_Date = document.getElementById("date").value;
	var End_Date = document.getElementById("date2").value;
	
	if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
	    if(window.XMLHttpRequest) {
		    myObjectGetPrevious = new XMLHttpRequest();
	    }else if(window.ActiveXObject){ 
		    myObjectGetPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGetPrevious.overrideMimeType('text/xml');
	    }
		    
	    myObjectGetPrevious.onreadystatechange = function (){
		    data80 = myObjectGetPrevious.responseText;
		    if (myObjectGetPrevious.readyState == 4) {
			document.getElementById('Previous_Fieldset_List').innerHTML = data80;
		    }
	    }; //specify name of function that will handle server response........
		    
	    myObjectGetPrevious.open('GET','Get_Previous_Requisition.php?Start_Date='+Start_Date+'&End_Date='+End_Date,true);
	    myObjectGetPrevious.send();
	}else{
	    
	    if (Start_Date == null || Start_Date == '') {
		document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date").focus();
	    }else{
		document.getElementById("date").style = 'border: 3px; text-align: center;';
	    }
	    
	    if (End_Date == null || End_Date == '') {
		document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
		document.getElementById("date2").focus();
	    }else{
		document.getElementById("date2").style = 'border: 3px; text-align: center;';
	    }
	}
    }
</script>


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
    include('./includes/footer.php');
?>