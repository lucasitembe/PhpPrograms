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

    
    if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
		    echo "<a href='control_purchase_order_sessions.php?New_Purchase_Order=True&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>NEW ORDER</a>";
	    }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='pendingorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDER</a>";
        }
    }
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ 
            echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
        }
    }
 

    //get sub department name
    if(isset($_SESSION['Procurement_ID'])){
	$Sub_Department_ID = $_SESSION['Procurement_ID'];
	    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID'") or die(mysqli_error($conn));
	    $no = mysqli_num_rows($select);
	    if($no > 0){
		    while($data = mysqli_fetch_array($select)){
			    $Sub_Department_Name = $data['Sub_Department_Name'];
		    }
	    }else{
		    $Sub_Department_Name = '';
	    }
    }else{
	    $Sub_Department_Name = '';
    } 
        
If(isset($_GET['purchase']))
		switch($_GET['purchase']){
		case "new":
			$action="purchaseorder.php?fr=list";
                        $whereV="Sent";
		break;
		case "list":
                    if($_GET['lForm']=='sentData'){
                        $action=''; $whereV="Sent";
                                            }
                                            else if($_GET['lForm']=='saveData'){
                                                $action="purchaseorder.php?page=requizition";
                                                $whereV="Saved";
                                                }
		break;
	}
 
?>
 
<br/><br/>
<center>
<form action='#' method='post' name='myForm' id='myForm'>
    <table width=60%> 
        <tr> 
            <td><b>From<b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' required='required' autocomplete='off'>
            </td>
            <td><b>To<b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' required='required' autocomplete='off'>
            </td>
            <td><input type='submit' name='submit' value='FILTER' class='art-button-green'></td>
        </tr> 
    </table>
</form>
</center>

<fieldset>
    <legend align=right><b><?php if(isset($_SESSION['Procurement_ID'])){ echo $Sub_Department_Name; }?> ~ Pending orders prepared by : <?php echo $Employee_Name; ?></b></legend>
    <iframe src='Pending_Orders_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>' width=100% height=350px></iframe>
</fieldset>



<?php
    include('./includes/footer.php');
?>