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
    <a href='addnewdiseasecategory.php?AddNewCategoryItem=AddNewCategoryItemThisPage' class='art-button-green'>
        ADD DISEASE CATEGORY
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        ADD DISEASE SUBCATEGORY
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='adddisease.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        ADD DISEASE
    </a>
<?php  } } ?>



<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



<?php
    if(isset($_POST['submittedAddNewCategoryForm'])){
	$Main_Category_Name = mysqli_real_escape_string($conn,$_POST['Main_Category_Name']);
	$Insert_New_Main_Category = "insert into tbl_disease_maincategory(maincategory_name)
				    Values('$Main_Category_Name')";
				    
	if(!mysqli_query($conn,$Insert_New_Main_Category)){
                    $error = '1062yes';
                    if(mysql_errno()."yes" == $error){
                        ?>
                        <script>
                            alert("\nDISEASE MAIN CATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
                            document.location="./addnewdiseasemaincategory.php?AddNewDiseaseMainCategory=AddNewDiseaseMainCategoryThisPage";
                        </script>
                        <?php
                    }
		}
		else {
		    echo '<script>
			alert("DISEASE MAIN CATEGORY ADDED SUCCESSFULLY");
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
            <legend align=center><b>ADD DISEASE MAIN CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=35% style='text-align: right;'><b>Disease Main Category Name</b></td>
                    <td width=65%>
                        <input type='text' name='Main_Category_Name' id='Main_Category_Name' required='required' placeholder='Enter Main Category Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
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