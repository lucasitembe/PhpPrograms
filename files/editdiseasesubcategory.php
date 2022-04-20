<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Mtuha_Reports'])){
	    if($_SESSION['userinfo']['Mtuha_Reports'] != 'yes'){
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
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        ADD DISEASE SUB CATEGORY
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='editdiseasesubcategorylist.php?EditDiseaseSubCategory=EditDiseaseSubCategoryThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?> 

<?php
    if(isset($_GET['subcategory_ID'])){
        $subcategory_ID = $_GET['subcategory_ID'];
    }else{
        $subcategory_ID = '';
    }

    $select = mysqli_query($conn,"select * from tbl_disease_subcategory ds, tbl_disease_category dc where
                            ds.disease_category_ID = dc.disease_category_ID and
                            ds.subcategory_ID = '$subcategory_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($row = mysqli_fetch_array($select)) {
            $disease_category_ID = $row['disease_category_ID'];
            $subcategory_description = $row['subcategory_description'];
        }
    }else{
        $disease_category_ID = '';
        $subcategory_description = '';
    }
    if(isset($_POST['submittedAddNewSubcategoryForm'])){
    	$disease_category_ID = mysqli_real_escape_string($conn,$_POST['disease_category_ID']);
        $subcategory_description = mysqli_real_escape_string($conn,$_POST['subcategory_description']);
            
    	$edit_subcategory = "update tbl_disease_subcategory set 
                            subcategory_description = '$subcategory_description', disease_category_ID = '$disease_category_ID'
				            where subcategory_ID = '$subcategory_ID'";

        if(!mysqli_query($conn,$edit_subcategory)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
		?>
				    <script>
    					alert("\nDISEASE SUBCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
    					document.location="./editdiseasesubcategory.php?subcategory_ID=<?php echo $subcategory_ID; ?>&EditDiseaseSubCategory=EditDiseaseSubCategoryThisPage";
				    </script>
		<?php
				}
		}
		else {
			echo "<script>
			alert('DISEASE SUBCATEGORY EDITED SUCCESSFULLY');
			document.location = 'editdiseasesubcategorylist.php?EditDiseaseSubCategory=EditDiseaseSubCategoryThisForm';
			</script>";
		}
    }
?>
<br/><br/><br/><br/>
<center>               
<table width=70%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT DISEASE SUBCATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25% style="text-align: right;"><b>Disease Category</b></td>
                    <td width=75%>
                        <select name='disease_category_ID' id='disease_category_ID' required='required'>
			    <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_disease_category");
                                while($row = mysqli_fetch_array($data)){
                                    if($disease_category_ID == $row['disease_category_ID']){
                                        echo '<option selected="selected" value="'.$row['disease_category_ID'].'" >'.$row['category_discreption'].'</option>';
                                    }else{                                        
                                        echo '<option value="'.$row['disease_category_ID'].'" >'.$row['category_discreption'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width=25% style="text-align: right;"><b>Disease Sub Category</b></td>
                    <td width=75%>
                        <input type='text' name='subcategory_description' id='subcategory_description' value="<?php echo $subcategory_description; ?>" autocomplete='off' required='required' placeholder='Enter Subcategory Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
			<input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
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