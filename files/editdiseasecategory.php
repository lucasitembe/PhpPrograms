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
        ADD NEW DISEASE CATEGORY
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='editdiseasecategorylist.php?EditDiseaseCategory=EditDiseaseCategoryThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?> 

<?php
    if(isset($_GET['disease_category_ID'])){
        $disease_category_ID = $_GET['disease_category_ID'];
    }else{
        $disease_category_ID = '';
    }

    $select = mysqli_query($conn,"select * from tbl_disease_maincategory dm, tbl_disease_category dc where
                                                dm.main_category_id = dc.main_category_id and
                                                dc.disease_category_ID = '$disease_category_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $category_discreption = $data['category_discreption'];
            $maincategory_name = $data['maincategory_name'];
            $main_category_id = $data['main_category_id'];
            $disease_category_ID = $data['disease_category_ID'];
            $icd_10_or_icd_9 = $data['icd_10_or_icd_9'];
        }
    }else{
        $category_discreption = '';
        $maincategory_name = '';
        $main_category_id = '';
        $disease_category_ID = '';
    }

    if(isset($_POST['submittedAddNewCategoryForm'])){
    $main_category_id = mysqli_real_escape_string($conn,$_POST['main_category_id']);
	$Category_Name = mysqli_real_escape_string($conn,$_POST['Category_Name']);
	$icd_10_or_icd_9 = mysqli_real_escape_string($conn,$_POST['icd_10_or_icd_9']);
	$Insert_New_Category = "update tbl_disease_category set
                            icd_10_or_icd_9='$icd_10_or_icd_9',
                            category_discreption = '$Category_Name', main_category_id = '$main_category_id' 
                            where disease_category_ID ='$disease_category_ID'";

        if(!mysqli_query($conn,$Insert_New_Category)){
			$error = '1062yes';
			if(mysql_errno()."yes" == $error){
			    ?>
			    <script>
				alert("\nDISEASE CATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
				document.location="./editdiseasecategory.php?disease_category_ID=<?php echo $disease_category_ID; ?>&EditDiseaseMainCategory=EditDiseaseMainCategoryThisPage";
			    </script>
			    <?php
			}
		}else {
		    echo '<script>
			alert("DISEASE CATEGORY EDITED SUCCESSFULLY");
            document.location = "editdiseasecategorylist.php?EditDiseaseCategory=EditDiseaseCategoryThisForm";
		    </script>';	
		}
    }
?>
<br/><br/><br/><br/><br/>
<center>
<table width=80%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>EDIT DISEASE CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm'>
            <table width=100%>
                <tr>
                    <td width=30% style='text-align: right;'><b>Disease Main Category</b></td>
                    <td width=70%>
                        <select name='main_category_id' id='main_category_id' required='required'>
                <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_disease_maincategory");
                                while($row = mysqli_fetch_array($data)){
                                    if($main_category_id == $row['main_category_id']){
                                        echo '<option selected="selected" value="'.$row['main_category_id'].'" >'.$row['maincategory_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$row['main_category_id'].'" >'.$row['maincategory_name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>
                        <b>ICD 9 OR ICD 10</b>
                    </td>
                    <td>
                        <select name="icd_10_or_icd_9" required="">
                            <option value=""></option>
                            <option value="icd_9" <?php if($icd_10_or_icd_9=="icd_9"){ echo "selected='selected'"; } ?>>ICD 9</option>
                            <option value="icd_10" <?php if($icd_10_or_icd_9=="icd_10"){ echo "selected='selected'"; } ?>>ICD 10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width=35% style='text-align: right;'><b>Disease Category Name</b></td>
                    <td width=65%>
                        <input type='text' name='Category_Name' id='Category_Name' value="<?php echo $category_discreption; ?>" autocomplete='off' required='required' placeholder='Enter Category Name' autocomplete='off'>
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