<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//    header("Location: ./index.php?InvalidPrivilege=yes");
	//}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
      
    $Current_Username = $_SESSION['userinfo']['Given_Username'];
     
    $sql_check_prevalage="SELECT edit_items FROM tbl_privileges WHERE edit_items='yes' AND "
            . "Given_Username='$Current_Username'";
    
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } //} ?>
<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>EDIT ITEM</b></legend>
                    <table width=60%>
                           <tr>
                <td style='text-align: center; height: 40px;'>
                    <a href='edititemlist.php?EditItemList=EditItemListThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Edit Item
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='add_item_mochwari.php?add_item_mochwari=add_item_mochwarithispage'>
                        <button style='width: 100%; height: 100%'>
                            Add Mortuary Billing Items
                        </button>
                    </a>
                </td>
            </tr>
                         
                           <!-- <tr> 
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='edititemlist.php?EditItemList=EditItemListThisPage'>
					<button style='width: 100%; height: 100%'>
					    Edit Item
					</button>
				    </a>
				</td> 
			    </tr>-->
			    <!-- TUMEADD Configuration ya kuongeza items kwenye mortuary billing-->
                               <!--<tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='mortuary_item_configuration.php?mortuary_item_configuration=mortuary_item_configurationthispage'>
					<button style='width: 100%; height: 100%'>
					   Add Mortuary Billing Items 
					</button>
				    </a>
				</td>  
                            </tr> -->
			   <!-- <tr>
					<td style='text-align: center; height: 40px; width: 25%;'>
					    <a href='#edititemspricelist.php?EditItemPriceList=EditItemPriceListThisPage'>
						<button style='width: 100%; height: 100%'>
						    Change Items Price (in Percentage)
						</button>
					    </a>
					</td> 
			    </tr>
			    <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
                                    <a href='itemsvisibilitymanagement.php?EditItemvisibility=EditItemvisibilityThisForm'>
					<button style='width: 100%; height: 100%'>
					    Edit Item Visibility
					</button>
				    </a>
				</td>  
                            </tr> --> 
                            <!--kunta-->
                            <tr>
              <td style='text-align: center; height: 40px;width: 25%;'>
                    <a href='#edititemspricelist.php?EditItemPriceList=EditItemPriceListThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Change Items Price (in Percentage)
                    </button>
                    </a>
         </td> <td style='text-align: center; height: 40px;width: 25%;' >
                    <a href='itemsvisibilitymanagement.php?EditItemvisibility=EditItemvisibilityThisForm'>
                    <button style='width: 100%; height: 100%'>
                     Edit Item Visibility
                    </button>
                    </a>
                </td>
            </tr> 
			    <!-- kunta
                             <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm'>
					<button style='width: 100%; height: 100%'>
					    Edit Item (Others)
					</button>
				    </a>
				</td>  
                            </tr>  
                             <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='apiitemsUpdate.php'>
					<button style='width: 100%; height: 100%'>
					   API Item Update
					</button>
				    </a>
				</td>  
                            </tr>  -->
                            <tr>
                <td style='text-align: center; height: 40px;width: 25%;' >
                    <a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm'> 
                        <button style='width: 100%; height: 100%'>
                            Edit Item (Others)
                        </button>
                    </a>
                </td>
                <td style='text-align: center; height: 40px;'>
                    <a href='apiitemsUpdate.php'>
                        <button style='width: 100%; height: 100%'>
                            API Item Update
                        </button>
                    </a>
                </td>
            </tr> 	
                            <!--kunta-->
                             <!--<tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='#'>
					<button style='width: 100%; height: 100%'>
					   Free Items Configuration
					</button>
				    </a>
				</td>  
                            </tr>  
                             <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='item_brand_name.php'>
					<button style='width: 100%; height: 100%'>
					 Items Brand Name Configuration
					</button>
				    </a>
				</td>  
                            </tr>  -->
                            <!--kunta-->
                            <tr>
              <td style='text-align: center; height: 40px;width: 25%;' >
                        <a href='Theater_setup.php?TheaterSetup=Setup'>
                            <button style='width: 100%; height: 100%'>
                               Free Items / Inclusive service Configuration
                            </button>
                        </a>
	     </td>
              <td style='text-align: center; height: 40px;width: 25%;' >
                  <!--<?php 
                        //$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        //$sql_check_privileges=mysqli_query($conn,"select can_change_system_parameters from tbl_privileges where Employee_ID='$Employee_ID' AND can_change_system_parameters='yes'") or die(mysqli_error($conn));
                         //if(mysqli_num_rows($sql_check_privileges)>0){
                            ?>-->
                              <a href='item_brand_name.php'>
                                <button style='width: 100%; height: 100%'>
                                    Items Brand Name Configuration
                                </button>
                            </a>   
                             <?php 
                         //} else {
                             ?>
                               <!-- <a href='#' onclick="alert('Access Denied')">
                                    <button style='width: 100%; height: 100%'>
                                        <!--System Parameters-->
                                         <!--Items Brand Name Configuration
                                   <!-- </button>
                                </a> -->
                             <?php
                        // }
                          ?>
                  
	     </td>
            </tr>
            <tr>
                <td colspan=2> <a href='attach_item_to_template.php'>
                                <button style='width: 100%; height: 100%'>
                                    Merge Item To Template
                                </button>
                            </a> </td>            
            </tr>
        </table>
        </center>
</fieldset><br/>

                            <!--kunta-->
			    <!--<tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm'>
					<button style='width: 100%; height: 100%'>
					    Items Management
					</button>
				    </a>
				</td>  
                            </tr>-->
                   <!-- </table>
            </fieldset>-->
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>