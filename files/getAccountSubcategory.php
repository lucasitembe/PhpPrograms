<?php
    @session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
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
<select name='subCatID' id='subCatID'>
<option selected>Select sub category</option>
<?php
//if the id is selected,pull the sub category data for that category
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }else{
        $id = 0;
    }
    
    $Select_Items = "SELECT * FROM tbl_account_subcategory,tbl_account_category
			WHERE tbl_account_subcategory.account_Category_ID=tbl_account_category.account_Category_ID
			AND tbl_account_subcategory.account_Category_ID=$id
			AND tbl_account_category.account_Category_ID=$id ";
    $result = mysqli_query($conn,$Select_Items);
    ?>
    
    <?php
    
    while($row = mysqli_fetch_array($result)){
	$subCatID=$row[account_Subcategory_ID];
        ?>
        <option value='<?php echo $subCatID;?>'><?php echo $row['account_Subcategory_Name']; ?></option>
    <?php
    }
?>
</select>