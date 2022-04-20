<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<?php
    if(isset($_SESSION['userinfo'])){
	if(isset($_GET['Section']) && $_GET['Section'] == 'Pharmacy'){
?>
    <a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php  }else if(isset($_GET['Section']) && $_GET['Section'] == 'Storage'){  ?>
	<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Laboratory'){ ?>
	<a href='laboratory_setup.php?LaboratorySetup=LaboratorySetupThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Radiology'){  ?>
    <a href='radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(isset($_GET['Section']) && $_GET['Section'] == 'Doctor'){  ?>
    <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php }else if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes'){  ?>
    <a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPa' class='art-button-green'>
        BACK
    </a>
<?php } } ?>
<br/>
<br/>
<br/>
<br/>
<br/> 

 
<center> 
    <center>
        <fieldset>
            <legend align="center" ><b>ITEM CONFIGURATION</b></legend>
            <table width=100%>
                <tr>
                    <td style='text-align: center; height: 40px; width: 17%;'>
					    <!--<a href='mortuary_item_configuration.php'>-->
                                            <a href='add_item_mochwari.php'>						<button style='width: 100%; height: 100%'>
						    Mortuary Item Billing Configuration
						</button>
					    </a>
					</td>
					<td style='text-align: center; height: 40px; width: 17%;'>
					    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage'>
						<button style='width: 100%; height: 100%'>
						    Add New Category
						</button>
					    </a>
					</td>
					<td style='text-align: center; height: 40px; width: 17%;'>
					    <a href='editcategorylist.php?EditCategory=EditCategoryThisForm'> 
						<button style='width: 100%; height: 100%'>
						    Edit Category
						</button>
					    </a>
					</td>
					<td style='text-align: center; height: 40px; width: 17%;'>
					    <a href='removecategorylist.php?RemoveCategory=RemoveCategoryThisForm'> 
						<button style='width: 100%; height: 100%'>
						    Remove Category
						</button>
					    </a>
					</td>
					<td style='text-align: center; height: 40px; width: 17%;'>
					    <a href='addnewsubitemcategory.php?AddNewSubItemCategory=AddNewSubItemCategoryThisPage'>
						<button style='width: 100%; height: 100%'>
						    Add New Sub Category
						</button>
					    </a>
					</td>

					<td style='text-align: center; height: 40px; width: 16%;'>
					    <a href='editsubcategorylist.php?EditSubItemCategory=EditSubItemCategoryThisForm'> 
						<button style='width: 100%; height: 100%'>
						    Edit Sub Category
						</button>
					    </a>
					</td>
					
                </tr>
			    <tr>
				<td style='text-align: center; height: 40px;'>
				    <!--<a href='addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage'>-->
				    <a href='newitem.php?AddNewItemCategory=AddNewItemCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add New Item
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px;'>
				    <a href='edititems.php?EditItem=EditItemThisForm'> 
					<button style='width: 100%; height: 100%'>
					    Edit Items
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px;'>
				    <a href='sponsornonsuporteditems.php?SponsorNonSuportedItems=SponsorNonSuportedItemsThisForm'> 
					<button style='width: 100%; height: 100%'>
					    Sponsor Non Supported Items
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px;'>
				    <a href='configreorderlevel.php?ReorderLevelItems=SponsorNonSuportedItemsThisForm'>
					<button style='width: 100%; height: 100%'>
					    Re - Order Level Settings
					</button>
				    </a>
				</td>
                                <td style='text-align: center; height: 40px;'>
				    <a href='sponsorallowzeroitems.php'>
					<button style='width: 100%; height: 100%'>
					   Sponsor Allow Zero Items 
					</button>
				    </a>
				</td>
                    <td>
						<a href='enable_disable_item_sub_category.php'>
							<button style='width: 100%; height: 100%'>
						Enable/Disable Item Sub Category
						</button>
						</a>
                    </td>
			    </tr>
				<tr>
					<td style='text-align: center; height: 40px;'>
					    <a href='consultation_configuration.php'>
						<button style='width: 100%; height: 100%'>
						 Doctors Kada VS Consultation  Charges 
						</button>
					    </a>
					</td>
					<td style='text-align: center; height: 40px;'>
					    <a href='itemsupdate.php'>
						<button style='width: 100%; height: 100%'>
						   Mapping Consultation  Items 
						</button>
					    </a>
					</td>
                                        <td  style='text-align: center; height: 40px;'>
					    <a href='edit_item_details.php'>
						<button style='width: 100%; height: 100%'>
						  Edit Items Details
						</button>
					    </a>
					</td>
					<td  style='text-align: center; height: 40px;'>
					    <a href='itemsmanagement.php'>
						<button style='width: 100%; height: 100%'>
						   Managing Categories
						</button>
					    </a>
					</td>
					
                                        <td  style='text-align: center; height: 40px;'>
                                            <a href='apiitemsUpdate.php'>
						<button style='width: 100%; height: 100%'>
						   Load Item From API
						</button>
					    </a>
					</td>
                        <td  style='text-align: center; height: 40px;'>
                            <a href='items_from_excel_file.php'>
								<button style='width: 100%; height: 100%'>
							Load Item From EXCEL
							</button>
							</a>
						</td>
				</tr>
				<tr>
				<td style='text-align: center; height: 40px; width: 16%;'>
					    <a href='removesubcategorylist.php?RemoveSubItemCategory=RemoveSubItemCategoryThisForm'> 
						<button style='width: 100%; height: 100%'>
						    Remove Sub Category
						</button>
					    </a>
					</td>
					<td  style='text-align: center; height: 40px;'>
						<a href='nhif_excluded_item.php'>
							<button style='width: 100%; height: 100%'>
								NHIF Excluded Item
							</button>
						</a>
					</td>
					<td  style='text-align: center; height: 40px;'>
						<a href='batch_expire_date_setup.php'>
							<button style='width: 100%; height: 100%'>
								Item Batch And Expire Date
							</button>
						</a>
					</td>
					<td style='text-align: center; height: 40px; width: 16%;'>
					    <a href='nurse_can_add_items.php?RemoveSubItemCategory=RemoveSubItemCategoryThisForm'> 
						<button style='width: 100%; height: 100%'>
						    Nurse Can Add Items
						</button>
					    </a>
					</td>

			<td style='text-align: center; height: 40px;'>
						<a href='adjustment_reasons_.php'>
							<button style='width: 100%; height: 100%'>
								Adjustment Reasons Configuration
							</button>
						</a>
					</td>
</tr>

            </table>
        </fieldset>  
</center>
<?php include("./includes/footer.php"); ?>
