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
    <a href='addnewdiseasemaincategory.php?AddNewMainCategory=AddNewMainCategoryThisPage' class='art-button-green'>
        ADD DISEASE MAIN CATEGORY
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='editdiseasemaincategorylist.php?EditDiseaseMainCategory=EditMainDiseaseCategoryThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<?php
    //GET DETAILSS
    if(isset($_GET['Main_Category_ID'])){
        $Main_Category_ID = $_GET['Main_Category_ID'];
    }else{
        $Main_Category_ID = '';
    }

    $select = mysqli_query($conn,"select maincategory_name from tbl_disease_maincategory where Main_Category_ID = '$Main_Category_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $maincategory_name = $data['maincategory_name'];
        }
    }else{
        $maincategory_name = '';
    }



    if(isset($_POST['submittedAddNewCategoryForm'])){
    	$Main_Category_Name = mysqli_real_escape_string($conn,$_POST['Main_Category_Name']);
    	$update_main_category = "update tbl_disease_maincategory set maincategory_name = '$Main_Category_Name' where main_category_id = '$Main_Category_ID'";
				    
	if(!mysqli_query($conn,$update_main_category)){
                    $error = '1062yes';
                    if(mysql_errno()."yes" == $error){
                        ?>
                        <script>
                            alert("\nDISEASE MAIN CATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
                            document.location="./editdiseasemaincategory.php?Main_Category_ID=<?php echo $Main_Category_ID; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage";
                        </script>
                        <?php
                    }
		}
		else {
		    echo '<script>
			alert("DISEASE MAIN CATEGORY EDITED SUCCESSFULLY");
            document.location = "editdiseasemaincategorylist.php?EditDiseaseMainCategory=EditMainDiseaseCategoryThisForm";
		    </script>';	
		}
    }
?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<center>
<table width=60%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT DISEASE MAIN CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=35% style='text-align: right;'><b>Disease Main Category Name</b></td>
                    <td width=65%>
                        <input type='text' name='Main_Category_Name' id='Main_Category_Name' autocomplete='off' required='required' placeholder='Enter Main Category Name' value="<?php echo $maincategory_name; ?>">
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewCategoryForm' value='true'/> 
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