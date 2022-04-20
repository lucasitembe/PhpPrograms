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
    <a href='laundrynewitem.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW LAUNDRY CATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='laundrynewtype.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW LAUNDRY TYPE
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='laundrylocation.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW LAUNDRY LOCATION
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='laundryname.php' class='art-button-green'>
        NEW LAUNDRY
    </a>
<?php  } } ?>

<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='laundryworkpage.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>


<br/><br/><br/><br/><br/>
<center>

<?php
    if(isset($_POST['submittedAddNewItemForm'])){
	$Itemtype = mysqli_real_escape_string($conn,$_POST['Itemtype']);
	$Number_Item = mysqli_real_escape_string($conn,$_POST['Number_Item']);
	$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
	$Item_Category = mysqli_real_escape_string($conn,$_POST['Item_Category']);
	$Item_Status = mysqli_real_escape_string($conn,$_POST['Item_Status']);

	
	
	$Insert_Item = "INSERT INTO tbl_laundry(Itemtype,Number_Item,Product_Name,Item_Category,Item_Status )
			VALUES
			('$Itemtype','$Number_Item','$Product_Name','$Item_Category','$Item_Status')";
	if(!mysqli_query($conn,$Insert_Item)){ 
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nITEM NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage";
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("ITEM ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>




 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>NEW ITEM</b></legend>
        
            <table width=100%>
                <tr>
                    <td width=25%><b>Item Type</b></td>
                    <td width=75%>
                        <select name='Itemtype' id='Itemtype' required='required'>
                          <option selected='selected'></option>
						<?php
                                $data = mysqli_query($conn,"select * from tbl_laundry_type");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value='.$row['laundry_type_ID'].'>'.$row['laundry_type_name'].'</option>';
                                }
                            ?> 
							
                        </select>
                    </td> 
                </tr>
               
               
                <tr>
                    <td><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' required='required' placeholder='Enter Product Name'>
                    </td>
                </tr>
				
                  <tr>
                    <td><b>Item Category</b></td>
                    <td> 
						<select name='Item_Category' id='Item_Category'  >
							<option selected='selected'></option>
							<?php
											$datas = mysqli_query($conn,"select * from tbl_laundry_categ");
											while($row = mysqli_fetch_array($datas)){
												echo '<option value='.$row['laundry_categ_Name'].'>'.$row['laundry_categ_Name'].'</option>';
											}
										?>   
						</select>
		    </td>
                </tr>
				 <tr>
                    <td><b>Number</b></td>
                    <td>
                        <input type='text' name='Number_Item' id='Number_Item' placeholder='Enter Number'>
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