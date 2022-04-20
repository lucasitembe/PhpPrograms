<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//	header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } //} ?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='addnewitemcategory.php?AddNewItegoryItem=AddNewItegoryItemThisPage' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } //} ?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>


<?php
    if(isset($_GET['from']) && $_GET['from'] == "itemsConf") {
?>
<a href='edititemlist.php?EditItemList=EditItemListThisPage&from=itemsConf' class='art-button-green'>BACK</a>
<?php
    } else if(isset($_GET['from']) && $_GET['from'] == "itemOthers") {
        ?>
            <a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm' class='art-button-green'>
                BACK
            </a>
        <?php
    } else {
?>
    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php
    }
?>
    
<?php  } //} ?>

<?php
    if(isset($_POST['submittedAddNewSubcategoryForm'])){
	$Category_ID = mysqli_real_escape_string($conn,$_POST['Category_ID']);
        $subcategory_Name = mysqli_real_escape_string($conn,$_POST['subcategory_Name']);
        
	$Insert_New_Subcategory = "insert into tbl_item_subcategory(Item_Subcategory_Name,Item_category_ID)
				    Values('$subcategory_Name','$Category_ID')";
	
	if(!mysqli_query($conn,$Insert_New_Subcategory)){ 
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nSUBCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewsubitemcategory.php?AddNewSubItemCategory=AddNewSubItemCategoryThisPage";
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("SUBCATEGORY ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>

<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>NEW SUBCATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25%><b>Select Category Name</b></td>
                    <td width=75%>
                        <select name='Category_ID' id='Category_ID' required='required'>
			    <option selected='selected'></option>
                            <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_item_category");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value="'.$row['Item_Category_ID'].'" >'.$row['Item_Category_Name'].'</option>';
                                }
                            ?> 
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td width=25%><b>Sub Category Name</b></td>
                    <td width=75%>
                        <input type='text' name='subcategory_Name' id='subcategory_Name' required='required' placeholder='Enter Subcategory Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewSubcategoryForm' value='true'/> 
                    </td>
		    
                </tr>
            </table>
	</form>
</fieldset>
        </td>
    </tr>
</table>      
        </center>
<br/>
<?php
    include("./includes/footer.php");
?>