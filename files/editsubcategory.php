<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='editsubcategorylist.php?EditSubItemCategory=EditSubItemCategoryThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>




<?php
    if(isset($_GET['Item_Subcategory_ID'])){
        $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
    }else{
        $Item_Subcategory_ID = 0;
    }
    
    if($Item_Subcategory_ID != 0){
        $select_Category_Name = mysqli_query($conn,"select * from tbl_item_subcategory isc,tbl_item_category ic  
		where isc.Item_category_ID=ic.Item_category_ID AND Item_Subcategory_ID = '$Item_Subcategory_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Category_Name);
        if($no > 0){
            while($row = mysqli_fetch_array($select_Category_Name)){
                $Item_Subcategory_Name = $row['Item_Subcategory_Name'];
		$Item_Category_Name = $row['Item_Category_Name'];
		$Item_Category_ID = $row['Item_Category_ID'];
            }
        }else{
            $Item_Subcategory_Name = '';
	   $Item_Category_Name = '';
	   $Item_Category_ID = '';
        }
    }else{
        $Item_Subcategory_Name = '';
	$Item_Category_Name = '';
	$Item_Category_ID = '';
    }

?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width="80%">
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT SUBCATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width="100%">
                <tr>
		<td style='text-align: right;' width="15%">Category Name</td>
		<td width="15%">
			<select  name='Item_Category_ID' id='Item_Category_ID' required='required'>
			    <option value='<?php echo $Item_Category_ID; ?>' selected='selected'><?php echo ucwords(strtolower($Item_Category_Name)); ?></option>
			    <?php
				$select = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category where Item_Category_Name <> '$Item_Category_Name'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
				    while($data = mysqli_fetch_array($select)){
			    ?>
					<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
			    <?php
				    }
				}
			    ?>
			</select>
					</td>
                    <td style='text-align: right;' width="15%">SubCategory Name</td>
                    <td width="55%">
                        <input type='text' name='Item_Subcategory_Name' id='Item_Subcategory_Name' required='required' value='<?php echo $Item_Subcategory_Name; ?>' placeholder='Enter Subcategory Name' autocomplete='off'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=4 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                        <a href='./editcategorylist.php?EditCategory=EditCategoryThisForm' class='art-button-green'>CANCEL</a>
                        <input type='hidden' name='submittedEditCategoryForm' value='true'/> 
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
    if(isset($_POST['submittedEditCategoryForm'])){
	$Item_Subcategory_Name = mysqli_real_escape_string($conn,$_POST['Item_Subcategory_Name']);
	$Item_Category_ID = mysqli_real_escape_string($conn,$_POST['Item_Category_ID']);
	$Update_Category = "update tbl_item_subcategory set Item_Subcategory_Name = '$Item_Subcategory_Name', Item_Category_ID = '$Item_Category_ID' where Item_Subcategory_ID = '$Item_Subcategory_ID'";
	//die($Update_Category);
	if(!mysqli_query($conn,$Update_Category)){
	    //die(mysqli_error($conn));
	    $error = '1062yes';
	    if(mysql_errno()."yes" == $error){
		?>
		<script>
		    alert("\nCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
		    document.location="./editcategory.php?Item_Subcategory_ID=<?php echo $Item_Subcategory_ID; ?>&EditCategory=EditCategoryThisForm";
		</script>
		<?php
		
	    }
	}else {
	    echo '<script>
		alert("SUBCATEGORY UPDATED SUCCESSFULLY");
		document.location="./editsubcategorylist.php?EditSubCategory=EditSubCategoryThisForm";
	    </script>';	
	}
    }
?>

<?php
    include("./includes/footer.php");
?>