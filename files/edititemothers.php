<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewothercategory.php?AddNewItemCategory=AddNewItemCategoryThisForm' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='edititemlistothers.php?EditItem=EditItemThisPage' class='art-button-green'>
        BACK
    </a>
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

<br/><br/>
<center>

<?php
    //get all item details based on item id
    if(isset($_GET['Item_ID'])){
	$Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = '';
    }
    
    $Results = mysqli_query($conn,"select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
				where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
				    isc.Item_category_ID = ic.Item_category_ID and
					i.Item_ID = '$Item_ID'");
    $no = mysqli_num_rows($Results);
    if($no > 0){
        while($row = mysqli_fetch_array($Results)){
            $Item_Type = $row['Item_Type'];  
            $Product_Name = $row['Product_Name'];  
            $Item_Category_Name = $row['Item_Category_Name'];
            $Item_Subcategory_Name = $row['Item_Subcategory_Name']; 
            $Item_Status = $row['Status']; 
            $Can_Be_Substituted_In_Doctors_Page = $row['Can_Be_Substituted_In_Doctors_Page'];
        }
    }else{
        $Item_Type = 'Unknown Type';  
        $Product_Name = 'Unknown Product Name';  
        $Item_Category_Name = 'Unknown Coategory';
        $Item_Subcategory_Name = "Unknown SubCategory"; 
        $Item_Status = 'Unknown Status'; 
        $Can_Be_Substituted_In_Doctors_Page = 'no'; 
    }
?>



<?php
    if(isset($_POST['submittedEditItemForm'])){
	$itemtype = mysqli_real_escape_string($conn,$_POST['itemtype']);  
	$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);  
	$Item_Subcategory = mysqli_real_escape_string($conn,$_POST['Item_Subcategory']);
	$Item_Status = mysqli_real_escape_string($conn,$_POST['Item_Status']); 
	if(isset($_POST['Can_Be_Substituted_In_Doctors_Page'])) {
	    $Can_Be_Substituted_In_Doctors_Page = 'yes';
	}else{
	    $Can_Be_Substituted_In_Doctors_Page = 'no';
	} 
	
	$Update_New_Item = "UPDATE tbl_items set 
			    Item_Type = '$itemtype', Product_Name = '$Product_Name',
                                Item_Subcategory_ID = (select Item_Subcategory_ID from tbl_item_subcategory where Item_Subcategory_Name = '$Item_Subcategory'),
					Status = '$Item_Status', Can_Be_Substituted_In_Doctors_Page = '$Can_Be_Substituted_In_Doctors_Page'
						where item_id = '$Item_ID'";
			    
			 
	if(!mysqli_query($conn,$Update_New_Item)){ 
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nITEM NAME ALREADY EXISTS!\nPROCESS FAIL"); 
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("ITEM UPDATED SUCCESSFUL");
			document.location="./edititemlistothers.php?StatusEditItemList=EditItemListThisPage";
		    </script>';	
		}
    }
?>




 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT ITEM</b></legend>
        
            <table width=100%>
                <tr>
                    <td width=25%><b>Item Type</b></td>
                    <td width=75%>
                        <select name='itemtype' id='itemtype'>
                            <option selected='selected'><?php echo $Item_Type; ?></option> 
                        </select>
                    </td> 
                </tr> 
                <tr>
                    <td><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' required='required' value='<?php echo $Product_Name; ?>' placeholder='Enter Product Name'>
                    </td>
                </tr> 
                <tr>
                    <td><b>Item Category</b></td>
                    <td> 
			<select name='Item_Category' id='Item_Category' onchange='getSubcategory(this.value)'>
			    <option selected='selected'><?php echo $Item_Category_Name; ?></option>
			    <?php
                                $data = mysqli_query($conn,"
                                                    select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                                                        where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and
                                                            isc.Item_category_ID = ic.Item_category_ID and
                                                                Visible_Status = 'Others' group by ic.Item_Category_Name");
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
			    <option selected='selected'><?php echo $Item_Subcategory_Name; ?></option>
			</select>
		    </td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td>
                        <select name='Item_Status' id='Item_Status' required='required'>
                            <option selected='selected'><?php echo $Item_Status; ?></option>
			    <option>Available</option>
                            <option>Not Available</option>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td colspan=2>
                        <input type='checkbox' name='Can_Be_Substituted_In_Doctors_Page' id='Can_Be_Substituted_In_Doctors_Page' <?php if(strtolower($Can_Be_Substituted_In_Doctors_Page) == 'yes') { echo 'checked="checked"'; } ?> value='yes'>
                        <b>Can Be Substituted In Doctor's Page</b>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <?php if($no > 0) { ?>
                        <input type='submit' name='submit' id='submit' value='   UPDATE  ' class='art-button-green'>
                        <?php } ?>
                        <a href='edititemlistothers.php?EditItem=EditItemThisPage' class='art-button-green'>CANCEL</a> 
                        <input type='hidden' name='submittedEditItemForm' value='true'/> 
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