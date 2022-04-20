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
            echo "<a href='laboratoryItemList.php' class='art-button-green'>BACK</a>";
            }
    }
               

if(!isset($_GET['update_item_status'])){
                ?>
                         <br/><br/><br/><br/><br/>
                            <center>               
                                <table style="width:50%;margin_top:20px;" class="hiv_table">
                                    <tr>
                                        <td>
                                            <fieldset>  
                                                <legend align=center><b>UPDATE STATUS</b></legend>
                                                    <form action='laboratory_item_availabilty.php?update_item_status=update_item_status' method='post' name='myForm' id='myForm'  enctype="multipart/form-data">
                                                        <table class="hiv_table" style="width:100%">
                                                            <tr>
                                                            <?php
                                                            $select_item_name =mysqli_query($conn,"SELECT * FROM tbl_items WHERE Item_ID ='".filter_input(INPUT_GET, 'Item_ID')."'");
                                                                        $row =mysqli_fetch_array($select_item_name); 
                                                            ?>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Item Name</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                        <input type='hidden' name='Item_ID' id='Item_ID' required='required' placeholder='Enter Parameter Name'
                                                                        value=" <?php
                                                                        echo $row['Item_ID'];
                                                                        ?>">
                                                                        <input type='text' name='Parameter_Name' id='Parameter_Name' required='required' placeholder='Enter Parameter Name'
                                                                        value=" <?php
                                                                        echo $row['Product_Name'];
                                                                        ?>">
                                                                            </td> 
                                                                                </tr>
                                                                                <tr><td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Current Status</td>
                                                                                <td style="color:blue;font-size:13px;font-weight:400px"><?php echo $row['Status']; ?></td>
                                                                                </tr>
                                                                                    <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">
                                                                                       New Status
                                                                                    </td>
                                                                                    <td>
                                                                                       <select name="status" required>
                                                                                           <option></option>
                                                                                    <?php
                                                                                     if($row['Status'] == 'Available'){
                                                                                        echo '<option value="Not Available">Not Available</option>';
                                                                                     }else{
                                                                                           echo '<option value="Available">Available</option>';
                                                                                     }
                                                                                        ?>   
                                                                                       </select> 
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">
                                                                                        Description
                                                                                    </td>
                                                                                    <td><textarea name="status_description" rows="4" width="100%"></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                     <tr>
                                                                                <td colspan=2 style='text-align: right;'>
                                                                            <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                                                                <input type='hidden' name='submit' value='true'/> 
                                                            </td>

                                                        </tr>
                                                    </table>
                                                </form>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>      
                            </center>
                <?php
    }else
     if(isset($_GET['update_item_status'])) {      




                                            $sample_insert=mysqli_query($conn,"UPDATE tbl_items SET Status ='".$_POST['status']."' WHERE Item_ID ='".$_POST['Item_ID']."'");


                                                if($sample_insert){
                                                    header('Location: laboratoryItemList.php?Status_Update=true'); 
                                            }else{
                                                    header('Location: laboratoryItemList.php?Status_Update=false'); 
                                            } 
                                        }


          
    include("./includes/footer.php");