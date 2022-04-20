<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
	if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='dhisworkpage.php?DhisWork=DhisWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

 
  
<center> 
        <center>
            <fieldset>
                    <legend align="center" ><b>DISEASE/DIAGNOSTIC CONFIGURATION</b></legend>
                    <table width=90%>
                            <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addnewdiseasemaincategory.php?AddNewMainCategory=AddNewMainCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add Disease Main Category
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editdiseasemaincategorylist.php?EditDiseaseMainCategory=EditMainDiseaseCategoryThisForm'> 
					<button style='width: 100%; height: 100%'>
					    Edit Disease Main Category
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
					<a href='mapdiseasetosubcategory.php?MapDiseaseToSubCategory=MapDiseaseToSubCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
						Map Disease To Sub Category
					</button>
					</a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
					<a href='clinic_remark.php?'>
					<button style='width: 100%; height: 100%'>
						Group Remark
					</button>
					</a>
				</td>
                            </tr>
                            <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addnewdiseasecategory.php?AddNewCategory=AddNewCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add Disease Category
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editdiseasecategorylist.php?EditDiseaseCategory=EditDiseaseCategoryThisForm'> 
					<button style='width: 100%; height: 100%'>
					    Edit Disease Category
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add Disease Sub Category
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editdiseasesubcategorylist.php?EditDiseaseSubCategory=EditDiseaseSubCategoryThisForm'>
					<button style='width: 100%; height: 100%'>
					    Edit Disease Sub Category
					</button>
				    </a>
				</td>
                            </tr>
			    <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='adddisease.php?AddNewItemCategory=AddNewItemCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add Disease
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editdiseaselist.php?EditDiseaseList=EditDiseaseListThisPage'> 
					<button style='width: 100%; height: 100%'>
					    Edit Disease
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='adddiseasegroup.php?AddNewItemCategory=AddNewItemCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add Disease Group
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage'>
					<button style='width: 100%; height: 100%'>
					    Edit Disease Group
					</button>
				    </a>
				</td>
			    </tr>
				<tr>
					<td style='text-align: center; height: 40px; width: 25%;'>
						<a href='mapdiseasegroup.php?MapDiseaseGroup=MapDiseaseGroupThisPage'>
						<button style='width: 100%; height: 100%'>
							Map Disease To Group
						</button>
						</a>
					</td>
					<td style='text-align: center; height: 40px; width: 25%;'>
						<a href='upload_disease_from_excel.php'>
						<button style='width: 100%; height: 100%'>
							<b>Upload Disease From Excel</b>
						</button>
						</a>
					</td>
					<td style='text-align: center; height: 40px; width: 25%;'>
						<a href='icd_9_icd_10_setup.php'>
						<button style='width: 100%; height: 100%'>
							ICD 10 OR ICD 9 SETUP
						</button>
						</a>
					</td>
					<td style='text-align: center; height: 40px; width: 25%;'>
						<a href='dhisconfiguration.php?Dhis2Configuration=Dhis2ConfigurationThisPage'>
						<button style='width: 100%; height: 100%'>
							<b>DHIS2 CONFIGURATION</b>
						</button>
						</a>
					</td>
				</tr>
                </table>
            </fieldset>  
</center>
<?php
    include("./includes/footer.php");
?>