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
        /*
       * crate edit button if user login
       */
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='requisition_list.php?requisition=list&page=requisition_list' class='art-button-green'>
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
    $requision_id=$_GET['requision_id'];
    $requizition_query=mysqli_query($conn,"SELECT req.*,nd.Sub_Department_Name as NeedDP,iss.Sub_Department_Name as isDP 
                            FROM tbl_requizition as req
                          JOIN tbl_requizition_items as reqit ON req.Requizition_ID=reqit.Requizition_ID
                          JOIN tbl_sub_department as nd on req.Store_Need=nd.Sub_Department_ID
                          JOIN tbl_sub_department as iss on req.Store_Issue=iss.Sub_Department_ID
                          WHERE req.Requizition_ID='$requision_id'");
    $display=  mysqli_fetch_array($requizition_query);

    
    ?>
<form action='req_process.php?action=edit&page=req_process' method='post' name='myForm' id='myForm'>
<fieldset>   
        <center> 
            <table width=100%>
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                            <td width='16%'><b>Number</b></td>
                            <td width='26%'><input type='text' name='requision_id'  id='requision_id'
                                      <?php echo "value='".$display['Requizition_ID']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            <td width='13%'><b>Description</b></td>
                            <td width='16%'><input type='text' name='description' 
                                     <?php echo "value='".$display['Requizition_Descroption']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            <td width='13%'><b>Purchase Date</b></td>
                            <td width='16%'><input type='text' name='requision_date' 
                                    <?php echo "value='".$display['Created_Date_Time']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            </tr> 
                            <tr>
                            <td width='13%'><b>Store Need</b></td>
                            <td width='16%'><input type='text' name='Store_Need' 
                                    <?php echo "value='".$display['NeedDP']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            <td><b>Supplier</b></td>
                            <td><input type='text' name='Store_Issue' 
                                    <?php echo "value='".$display['isDP']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
                            <td><b>Purchase Officer</b></td>
                            <td><input type='text' name='Employee_ID' 
                                    <?php echo "value='".$display['Employee_ID']."'"; if($_GET['action']=='edited'){ echo " disabled='disabled'";} ?> /></td>
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
					<td>Balance Store Need</td><td>Unit Price</td><td>Remark</td><td>&nbsp;</td>
                </tr>
<?php

$result4=mysqli_query($conn,"select * from tbl_requizition_items where Requizition_ID='$requision_id'");
   

   while($disp4=mysqli_fetch_array($result4)){             
			echo "<tr>
                    <td>".$disp4['Requizition_Item_ID']."<input type='hidden' name='Requizition_Item_ID[]' value='".$disp4['Requizition_Item_ID']."'></td> 
                    <td>".$disp4['Item_Name']."</td>
                    <td><input name='Quantity_Required[]' type='text' value='".$disp4['Quantity_Required']."'></td>
                    <td><input name='Balance_Needed[]' type='text' value='".$disp4['Balance_Needed']."'></td>
                    <td><input name='Balance_Issued[]' type='text' value='".$disp4['Balance_Issued']."'></td>
                    <td><input name='Item_Remark[]' type='text' value='".$disp4['Item_Remark']."'></td>
                    <td width='5%'><a id='delete[]' href='#?".$disp4['Requizition_Item_ID']."' class='art-button-green'>Delete</a></td>
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

