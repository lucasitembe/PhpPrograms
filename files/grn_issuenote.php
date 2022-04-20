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
            echo "<a href='goodreceivednote.php' class='art-button-green'>GOOD RECEIVING NOTE</a>";
            }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a href='grn_issuenote_list.php?lform=sentData' class='art-button-green'>NEW</a>";
            }
    }

            if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a href='grn_issuenote_list.php?lform=saveData' class='art-button-green'>PREVIOUS </a>";
            }
    }
        if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
 


	

    if(isset($_GET['fr'])){
         $display=false;
        if(isset($_POST['check_value'])){
                $all_value=$_POST['check_value'];
                $value_array=split('/',$all_value); 
                $req_id=$value_array[0];
                $iss_id=$value_array[1];
                $display=true;
            }else if(isset($_GET['issue_ID'])){ $iss_id=$_GET['issue_ID']; $req_id=$_GET['req_id']; $display=true;}
        $sql_grn1['grn_issue_id']=$sel_req1['date_issued']=$sql_grn1['create_date']='';
        $query_data=mysqli_query($conn,"SELECT req.*,sN.Sub_Department_Name AS sNeed,sN.Sub_Department_ID AS sNeed_ID,sI.Sub_Department_Name AS sIssued,sI.Sub_Department_ID AS sIssued_ID FROM tbl_requizition AS req
                                    JOIN tbl_sub_department AS sN ON sN.Sub_Department_ID=req.Store_Need
                                    JOIN tbl_sub_department AS sI ON sI.Sub_Department_ID=req.Store_Issue
                                    WHERE Requizition_ID='$req_id'");
        $sel_req=mysqli_fetch_array($query_data);
        
        $query_item=mysqli_query($conn,"SELECT * FROM tbl_requizition_items WHERE Requizition_ID='$req_id'"); 
                $query_item1=mysqli_query($conn,"SELECT * FROM tbl_issued WHERE issue_id='$iss_id'"); 
                $sel_req1=mysqli_fetch_array($query_item1);

        if(isset($_GET['issue_ID'])){
                $sql_grn=mysqli_query($conn,"SELECT * FROM tbl_grnissue WHERE issue_id='$iss_id'");
                $sql_grn1=mysqli_fetch_array($sql_grn);
                } 
}

  ?> 
<fieldset>
<legend align="right"><b>GRN Against Issue Note</b></legend>     
<form action='grn_process.php?pgfrom=issuenote&page=pgr_process' method='post' name='myForm' id='myForm'>
	<fieldset>   
        <center> 
                        <table width=100%>
                                <td width='16%'><b>Issue Number</b></td>
                                <td width='26%'><input type='text' name='issue_number'  id='' value='<?php if(isset($display)){ echo $iss_id; }?>' /></td>
                                <td width='13%'><b>GRN Number</b></td>
                                <td width='16%'><input type='text' name='grn_number'  id='grn_number' value='<?php if(isset($display)){ echo $sql_grn1['grn_issue_id']; }?>' /></td> 
                           </tr>                               
                            <tr>
                                <td width='16%'><b>Issue Date</b></td>
                                <td width='26%'><input type='text' name='issue_date'  id='issue_date' value='<?php if(isset($display)){ echo $sel_req1['date_issued']; }?>' /></td>
                                <td width='13%'><b>GRN Date</b></td>
                                <td width='16%'><input type='text' name='grn_date'  id='grn_date' value='<?php if(isset($display)){ echo $sql_grn1['create_date'];} ?>'/></td>
                           </tr> 
                            <tr>
                                <td width='16%'><b>Supplier</b></td>
                                <td width='26%'><input type='text' name='supplier'  id='supplier' <?php if(isset($display)){echo "value='".$sel_req['sIssued']."' "; }?> readonly/>
                                                <input type='hidden' name='supplier_ID'  id='supplier_ID' <?php if(isset($display)){echo "value='".$sel_req['sIssued_ID']."' "; }?> /></td>
                                <td width='16%'><b>Receiver</b></td>
                                <td width='26%'><input type='text' name='receiver'  id='receiver' <?php if(isset($display)){echo "value='".$sel_req['sNeed']."' "; }?>  />
                                                <input type='hidden' name='receiver_ID'  id='receiver_ID' <?php if(isset($display)){echo "value='".$sel_req['sNeed_ID']."' "; }?>  /></td>
                           </tr> 
                        </table>
        </center>
	</fieldset>
<fieldset> 
<fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td>SN</td><td>Item Description</td><td>Quantity Required</td><td>Balance Required</td><td>Quantity Issued</td><td>Quantity Received</td>

                </tr>
               
    <?php
                
                if(isset($_GET['fr'])){
                    $i=1;
            while($result_query=mysqli_fetch_array($query_item)){
            echo" <tr>
                        <td>".$i."<input name=\"requisition_id[]\" type=\"hidden\" value=\"".$req_id."\" /></td>
                        <td>".$result_query['Item_Name']."</td>
                        <td>".$result_query['Quantity_Required']."</td> 
                        <td>".$result_query['Balance_Needed']."</td>
                        <td>".$result_query['Quantity_Issued']."</td>
                        <td><input name='Requizition_Item_ID[]' type='hidden' value=".$result_query['Requizition_Item_ID']."  >";
                        ?>
<input type='text' name='Quantity_Recieved[]' id='Quantity' placeholder='Quantity'  required <?php if($display){echo "value='".$result_query['quantity_received']."' ";}?>' >
                        </td>
                </tr>
                <?php
                $i++;
                }
                }
            ?>
            
        
            
            </table>
            <input name='Requizition_id' type='hidden' value="<?php  if(isset($_POST['check_value'])){ echo $_POST['check_value']; } ?>"  >
           <?php
            if(!isset($_GET['issue_ID'])){ 
            echo"<input name='submit' type='submit' value=\"SUBMIT\" class='art-button-green' >";
         }
         ?>
    </form>   
        </center>
</fieldset>
</fieldset>  

<?php
    include("./includes/footer.php");
?>
        