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

<?php
    if(isset($_GET['from']) && $_GET['from'] == "itemOthers") {
?>
<a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm' class='art-button-green'>BACK</a>
<?php
    } else {
?>
<a href="newitem.php?AddNewItemCategory=AddNewItemCategoryThisPage" class="art-button-green">BACK</a>
<?php
    }
?>
    
<?php  } } ?>


<script type="text/javascript" language="javascript">
    function getSubcategory(Item_Category_ID) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetSubcategory.php?Item_Category_ID='+Item_Category_ID,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText; 
	document.getElementById('Item_Subcategory').innerHTML = data;	
    }
</script>

<br/><br/><br/><br/>
<center>



<?php
    if(isset($_POST['submittedAddNewAnotherItemForm'])){
	$itemtype = mysqli_real_escape_string($conn,$_POST['itemtype']);
	$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
	$Item_Subcategory = mysqli_real_escape_string($conn,$_POST['Item_Subcategory']);  
	$Item_Status = mysqli_real_escape_string($conn,$_POST['Item_Status']);  
	
	$Insert_New_Item = "INSERT INTO tbl_items(Item_Type, Product_Name, Item_Subcategory_ID, Status,visible_status)
                            
                            Values(
			    '$itemtype','$Product_Name','$Item_Subcategory','$Item_Status','Others')";
							
    // die($Insert_New_Item);
	if(!mysqli_query($conn,$Insert_New_Item)){
                    //die(mysqli_error($conn));
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nITEM NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewothercategory.php?AddNewOtherItemCategory=AddNewOtherItemCategoryThisPage";
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("ITEM ADDED SUCCESSFULLY");
		    </script>';	
		}
    }
?>




 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>New Item (Others)</b></legend>
        
            <table width=100%>
                <tr>
                    <td width=25%><b>Item Type</b></td>
                    <td width=75%>
                        <select name='itemtype' id='itemtype'>
                            <option selected='selected'>Service</option> 
                        </select>
                    </td> 
                </tr>
                <tr>
                    <td><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' placeholder='Enter Product Name' required='required'>
                    </td>
                </tr>
                <tr>
                    <td><b>Item Category</b></td>
                    <td> 
			<select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)' required='required'>
			    <option selected='selected'></option>
			    <?php
                                $data = mysqli_query($conn,"select * from tbl_item_category");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['Item_Category_ID'].'>'.$row['Item_Category_Name'].'</option>';
                                }
                            ?>   
			</select>&nbsp;&nbsp;&nbsp;<b>Select Category</b>
		    </td>
                </tr>
                <tr>
                    <td><b>Sub Category</b></td>
                    <td>
			<select name='Item_Subcategory' id='Item_Subcategory'>
			    
			</select>
		    </td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td>
                        <select name='Item_Status' id='Item_Status'>
                            <option selected='selected'>Available</option>
                            <option>Not Available</option>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewAnotherItemForm' value='true'/> 
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