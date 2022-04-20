 <?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
?>
    <a href='newitem.php?AddNewItemCategory=AddNewItemCategoryThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<br/><br/><br/><br/>

<center>
<?php
    if(isset($_POST['submittedAddNewItemForm'])){
	$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
	$Reagent_Category_ID = mysqli_real_escape_string($conn,$_POST['Reagent_Category_ID']); 
	
	$Insert_New_Item = "INSERT INTO tbl_reagents_items(Product_Name, Reagent_Category_ID)
                            Values('$Product_Name','$Reagent_Category_ID')";
	if(!mysqli_query($conn,$Insert_New_Item)){
                    //die(mysqli_error($conn));
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){
			?>
			<script>
			    alert("PRODUCT NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
			    document.location="./addnewreagent.php?AddNewReagentItem=AddNewReagentItemThisForm";
			</script>
			
			<?php
			
		    }
		}
		else {
		    echo '<script>
			alert("PRODUCT ADDED SUCCESSFULLY");
		    </script>';	
		}
    }
?>



<br/><br/><br/>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">    
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>New Item (Reagent)</b></legend>
        
            <table width=100%>
                <tr>
                    <td><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' placeholder='Enter Product Name' required='required'>
                    </td>
                </tr>
                <tr>
                    <td><b>Item Category</b></td>
                    <td> 
			<select name='Reagent_Category_ID' id='Reagent_Category_ID' onchange='getSubcategory(this.value)' required='required'>
			    <option selected='selected' value=''>Select Category</option>
			    <?php
                                $data = mysqli_query($conn,"select * from tbl_reagents_category") or die(mysqli_error($conn));
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['Reagent_Category_ID'].'>'.$row['Reagent_Name'].'</option>';
                                }
                            ?>   
			</select>&nbsp;&nbsp;&nbsp;<b>Select Category</b>
		    </td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewItemForm' value='true'/> 
                    </td>
                </tr>
            </table>
</fieldset>
        </td>
    </tr>
</table>
 </form>
        </center>
<br/>
<?php
    include("./includes/footer.php");
?>