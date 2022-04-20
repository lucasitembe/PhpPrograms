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
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
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
    <a href='edititemlistreagents.php?EditItemOthers=EditItemOthersThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<br/><br/><br/><br/>

<center>
<?php
    if(isset($_POST['submittedAddNewItemForm'])){
	$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
	$Reagent_Category_ID = mysqli_real_escape_string($conn,$_POST['Reagent_Category_ID']); 
	
	$Insert_New_Item = "update tbl_reagents_items set Product_Name = '$Product_Name', Reagent_Category_ID = '$Reagent_Category_ID'
                                where Item_ID = '$Item_ID'";
	
        if(!mysqli_query($conn,$Insert_New_Item)){
            //die(mysqli_error($conn));
            $error = '1062yes';
            if(mysql_errno()."yes" == $error){
                ?>
                <script>
                    alert("PRODUCT NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
                    document.location="./editreorderitem.php?Item_ID=<?php echo $Item_ID; ?>&EditReagentItem=EditReagentThisPage";
                </script>
                
                <?php
                
            }
        }else{
            echo '<script>
                alert("PRODUCT EDITED SUCCESSFULLY");
            </script>';	
        }
    }
?>

<?php
    if(isset($_GET['Item_ID'])){
        //get all details
        $select = mysqli_query($conn,"SELECT * FROM tbl_reagents_items ri, tbl_reagents_category rc where
                                ri.Reagent_Category_ID = rc.Reagent_Category_ID and
                                    Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                $Product_Name = $row['Product_Name'];
                $Reagent_Name = $row['Reagent_Name'];
                $Reagent_Category_ID = $row['Reagent_Category_ID'];
            }
        }else{
            $Product_Name = '';
            $Reagent_Name = '';
            $Reagent_Category_ID = 0;
        }
    }else{
        $Product_Name = '';
        $Reagent_Name = '';
        $Reagent_Category_ID = 0;
    }
?>

<br/><br/><br/>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">    
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>Edit Item (Reagent)</b></legend>
        
            <table width=100%>
                <tr>
                    <td><b>Product Name</b></td>
                    <td>
                        <input type='text' name='Product_Name' id='Product_Name' placeholder='Enter Product Name' autocomplete='off' value='<?php echo $Product_Name; ?>' required='required'>
                    </td>
                </tr>
                <tr>
                    <td><b>Item Category</b></td>
                    <td> 
			<select name='Reagent_Category_ID' id='Reagent_Category_ID' onchange='getSubcategory(this.value)' required='required'>
			    <option selected='selected' value='<?php echo $Reagent_Category_ID; ?>'><?php echo $Reagent_Name; ?></option>
			    <?php
                                $data = mysqli_query($conn,"select * from tbl_reagents_category where Reagent_Category_ID <> '$Reagent_Category_ID'") or die(mysqli_error($conn));
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