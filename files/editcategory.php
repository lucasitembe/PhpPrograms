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
    <a href='editcategorylist.php?EditCategory=EditCategoryThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>




<?php
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = 0;
    }
    
    if($Item_Category_ID != 0){
        $select_Category_Name = mysqli_query($conn,"select * from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Category_Name);
        if($no > 0){
            while($row = mysqli_fetch_array($select_Category_Name)){
                $Item_Category_Name = $row['Item_Category_Name'];
		$Category_Type = $row['Category_Type'];
		$can_be_used_on_registration = $row['can_be_used_on_registration'];
            }
        }else{
            $Item_Category_Name = '';
	    $Category_Type = '';
        }
    }else{
        $Item_Category_Name = '';
	$Category_Type = '';
        $can_be_used_on_registration="no";
    }

?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
		    <td style='text-align: right;' width=15%>Category Type</td>
		    <td width=15>
			<select name='Category_Type' id='Category_Type' required='required'>
			    <option selected='selected'><?php echo $Category_Type; ?></option>
			    <?php if($Category_Type != 'Service') { echo "<option>Service</option>"; } ?>
			    <?php if($Category_Type != 'Pharmacy') { echo "<option>Pharmacy</option>"; } ?>
			</select>
		    </td>
                </tr>
                <tr>
                    <td style='text-align: right;' width=15%>Category Name</td>
                    <td>
                        <input type='text' name='Category_Name' id='Category_Name' required='required' value='<?php echo $Item_Category_Name; ?>' placeholder='Enter Category Name'>
                    </td> 
                </tr>
                <tr>
                    <td style="text-align: right">
                        <input type="checkbox" <?php if($can_be_used_on_registration=="yes"){echo "checked='checked'";}?> name="can_be_used_on_registration">
                    </td>
                    <td>
                        can be used on registration
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
	$Category_Type = mysqli_real_escape_string($conn,$_POST['Category_Type']);
	$Category_Name = mysqli_real_escape_string($conn,$_POST['Category_Name']);
        if(isset($_POST['can_be_used_on_registration'])){
          $can_be_used_on_registration="yes";  
        }else{
            $can_be_used_on_registration="no";
        }
	$Update_Category = "update tbl_item_category set Item_Category_Name = '$Category_Name', Category_Type = '$Category_Type',can_be_used_on_registration='$can_be_used_on_registration' where Item_Category_ID = '$Item_Category_ID'";
	
	if(!mysqli_query($conn,$Update_Category)){
	    $error = '1062yes';
	    if(mysql_errno()."yes" == $error){
		?>
		<script>
		    alert("\nCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
		    document.location="./editcategory.php?Item_Category_ID=<?php echo $Item_Category_ID; ?>&EditCategory=EditCategoryThisForm";
		</script>
		<?php
		
	    }
	}else {
	    echo '<script>
		alert("CATEGORY UPDATED SUCCESSFULLY");
		document.location="./editcategorylist.php?EditCategory=EditCategoryThisForm";
	    </script>';	
	}
    }
?>

<?php
    include("./includes/footer.php");
?>