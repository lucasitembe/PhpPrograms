<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
	if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
		{
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

      if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a href='storageandsupply.php' class='art-button-green'>STORAGE AND SUPPLY</a>";
            }
    }
	
/*
 * display send and edit only if there is requision created soon
 * 
 */	

  
              if(isset($_SESSION['userinfo'])){
                  if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                  { 
                                          echo "<a href='requisition_list.php?requisition=list&lForm=saveData&page=requisition_list' class='art-button-green'>PROCESS REQUISITION</a>";
                                  }
                  }
           
            if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                    echo "<a href='requisition_list.php?requisition=list&lForm=sentData&page=requisition_list' class='art-button-green'>
                    PREVIOUS ORDER</a>";
                    }
            }
                if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }

/*
 * create a form for edit
 */
if(isset($_GET['action']))
if($_GET['action']=='edit' or $_GET['action']=='edited' ){
    $purchase_id=$_GET['purchase_id'];
                $result3=mysqli_query($conn,"
                                        select pur.*,nd.Sub_Department_Name as storeneed,iss.name as storeissued 
                                        from tbl_purchase_order as pur
                                        JOIN tbl_sub_department as nd on pur.Store_Need=nd.Sub_Department_ID
                                        JOIN tbl_supplier as iss on pur.supplier_id=iss.supplier_id
                                        where order_id='$purchase_id'");

                $display=mysqli_fetch_array($result3);

    
    ?>
<form action='purchase_process.php?action=edit&page=purchase_process' method='post' name='myForm' id='myForm'>
<fieldset>   
        <center> 
            <table width=100%>
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='16%'><b>Number</b></td> 	 	 	 	 	 	 
                                <td width='26%'><input type='text' name='order_id'  id='requision_id'
                                      <?php echo "value='".$display['order_id']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                                <td width='13%'><b>Description</b></td>
                                <td width='16%'><input type='text' name='description' 
                                     <?php echo "value='".$display['order_description']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                                <td width='13%'><b>Date</b></td>
                                <td width='16%'><input type='text' name='created_date' 
                                    <?php echo "value='".$display['created_date']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            </tr> 
                            <tr>
                            <td width='13%'><b>Store Need</b></td>
                            <td width='16%'><input type='text' name='Store_Need' 
                                    <?php echo "value='".$display['storeneed']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                                <td><b>Supplier</b></td>
                                <td><input type='text' name='Store_Issue' 
                                    <?php echo "value='".$display['storeissued']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                                <td><b>Requisit Officer</b></td>
                                <td><input type='text' name='Employee_ID' 
                                    <?php echo "value='".$display['employee_id']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
				<td></td>
                            </tr>
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>
<fieldset>   
        <center>

            <table width=100%>
                <tr>
					<td>Item Number</td><td>Item Description</td><td>Quantity Required</td>
					<td>Balance Needed</td><td>Unit Price</td><td>Remark</td><td>Delete</td>
                </tr>
<?php

$result4=mysqli_query($conn,"select * from tbl_purchase_order_items where order_id='$purchase_id'");
   

   while($disp4=mysqli_fetch_array($result4)){            
			echo "<tr>
                    <td>".$disp4['order_item_id']."<input type='hidden' name='Requizition_Item_ID[]' value='".$disp4['order_item_id']."'></td> 
                    <td>".$disp4['Item_Name']."</td>
                    <td><input name='Quantity_Required[]' type='text' value='".$disp4['quantity_required']."'></td>
                    <td><input name='Balance_Needed[]' type='text' value='".$disp4['balance_needed']."'></td>
                    <td><input name='unit_price[]' type='text' value='".$disp4['unit_price']."'></td>
                    <td><input name='Item_Remark[]' type='text' value='".$disp4['remark']."'></td>
                    <td><input name='delete[]' type='checkbox' value='".$disp4['order_item_id']."'></td>
                </tr>";
	}
       echo"</table>";
       if($_GET['action']=='edited'){ }else{
         echo"<input name='submit' value='Save' type='submit' style='padding:2px;font-size:16px;width:20%;float:right;'>";
       }
      echo"</form>
	   </center>
	   </fieldset>";         
         }

	include("./includes/footer.php");
?>

