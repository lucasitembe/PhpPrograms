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

    if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='#issue_list.php?requisition=new&page=issue_list' class='art-button-green'>NEW ISSUE</a>";
			}
	}
       if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='#issue_list.php?lform=saveData&page=issue_list' class='art-button-green'>PROCESS ISSUE</a>";
			}
	}
 
//            if(isset($_SESSION['userinfo'])){
//		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
//			{ 
//			echo "<a href='#' class='art-button-green'>EDIT ISSUE</a>";
//			}
//	}  
            if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='#issue_list.php?lform=sentData&page=issue_list' class='art-button-green'>PREVIOUS ISSUE</a>";
			}
	}
        if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }

	
//$_POST['check_value']	
if(isset($_GET['fr3'])){
                $all_value=$_POST['check_value'];
                $value_array=split('/',$all_value); 
                $req_id=$value_array[0];
                $iss_id=$value_array[1];
   
		$query_data=mysqli_query($conn,"SELECT req.*,sN.Sub_Department_Name AS sNeed,sI.Sub_Department_Name AS sIssued FROM tbl_requizition AS req
									JOIN tbl_sub_department AS sN ON sN.Sub_Department_ID=req.Store_Need
									JOIN tbl_sub_department AS sI ON sI.Sub_Department_ID=req.Store_Issue
									WHERE Requizition_ID='$req_id'");
		$sel_req=mysqli_fetch_array($query_data);
		
		$query_item=mysqli_query($conn,"SELECT * FROM tbl_requizition_items WHERE Requizition_ID='$req_id'"); 
                $query_item1=mysqli_query($conn,"SELECT * FROM tbl_issued WHERE issue_id='$iss_id'"); 
                $sel_req1=mysqli_fetch_array($query_item1);
}

  ?>      
<form action='req_process.php?issue=true' method='post' name='myForm' id='myForm'>
	<fieldset>   
        <center> 
                        <table width=100%>
                            <tr>
                                <td width='16%'><b>Issue Number</b></td>
                                <td width='26%'><input type='text' name='issue_id'  id='requision_id' value='<?php if(isset($_POST['check_value'])){ echo $iss_id;}?>' /></td>
                                <td width='13%'><b>Issue Date</b></td>
                                <td width='16%'><input type='text' name='date_issued'  id='requision_id' value='<?php if(isset($_POST['check_value'])){ echo $sel_req1['date_issued'];}?>' /></td> 
                           </tr>                               
                            <tr>
                                <td width='16%'><b>Requisition Number</b></td>
                                <td width='26%'><input type='text' name='req_id'  id='requision_id' <?php if(isset($_POST['check_value'])){echo "value='".$req_id."' disabled='disabled'"; }?> ' /></td>
                                <td width='13%'><b>Requisition Date</b></td>
                                <td width='16%'><input type='text' name='requision_id'  id='requision_id' <?php if(isset($_POST['check_value'])){ echo "value='".$sel_req['Created_Date_Time']."' disabled='disabled'"; }?>' /></td>
                           </tr> 
                            <tr>
                                <td width='16%'><b>Store Need</b></td>
                                <td width='26%'><input type='text' name='requision_id'  id='requision_id' <?php if(isset($_POST['check_value'])){echo "value='".$sel_req['sNeed']."' disabled='disabled'";}?>' /></td>
                                <td width='13%'><b>Store Issued</b></td>
                                <td width='16%'><input type='text' name='requision_id'  id='requision_id' <?php if(isset($_POST['check_value'])){echo "value='".$sel_req['sIssued']."' disabled='disabled'";}?>' /></td>
                           </tr> 
                            <tr>
                                <td width='16%'><b>Requisition Description</b></td>
                                <td width='26%'><input type='text' name='requision_id'  id='requision_id' <?php if(isset($_POST['check_value'])){ echo "value='".$sel_req['Requizition_Descroption']."' disabled='disabled'";}?>' /></td>
                                <td width='13%'><b>Requisition Officer</b></td>
                                <td width='16%'><input type='text' name='requision_id'  id='requision_id' <?php if(isset($_POST['check_value'])){ echo "value='".$sel_req['Employee_ID']."' disabled='disabled'";}?>' /></td>
                           </tr> 
                        </table>
        </center>
	</fieldset>
<fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td>SN</td><td>Item Description</td><td>Quantity Required</td><td>Balance Required</td><td>Quantity Issued</td>
                </tr>
               
	<?php
				
                if(isset($_GET['f3r'])){
                    $i=1;
			while($result_query=mysqli_fetch_array($query_item)){
			echo" <tr>
						<td>".$i."</td>
						<td>".$result_query['Item_Name']."</td>
						<td>".$result_query['Quantity_Required']."</td> 
						<td>".$result_query['Balance_Needed']."</td>
						<td>
						<input name='Requizition_Item_ID[]' type='hidden' value=".$result_query['Requizition_Item_ID']."  >";
                        ?>
<input type='text' name='quantityissued[]' id='Quantity' placeholder='Quantity'  required <?php if(isset($result_query['Quantity_Issued'])){ echo "value='".$result_query['Quantity_Issued']."'"; } ?> >
						</td>
                </tr>
                <?php
				$i++;
				}
                }
			?>
			
		
			
            </table>
                <input name='Requizition_id' type='hidden' value="<?php  if(isset($_POST['check_value'])){ echo $_POST['check_value']; } ?>"  >
		<input name='submit' type='submit' value="SUBMIT" class='art-button-green' >
                <input name='submit' type='submit' value="SAVE" class='art-button-green' >
	</form>   
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>  