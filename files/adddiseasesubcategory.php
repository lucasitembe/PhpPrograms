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
    <a href='addnewdiseasecategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        ADD DISEASE CATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){ 
?>
    <a href='adddisease.php?AddNewItegoryItem=AddNewItegoryItemThisPage' class='art-button-green'>
        ADD DISEASE
    </a>
<?php  } } ?>

<a href="diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>

<?php
    if(isset($_POST['submittedAddNewSubcategoryForm'])){
	$disease_category_ID = mysqli_real_escape_string($conn,$_POST['disease_category_ID']);
        $subcategory_description = mysqli_real_escape_string($conn,$_POST['subcategory_description']);
        
	$Insert_New_Subcategory = "insert into tbl_disease_subcategory(subcategory_description,disease_category_ID)
				    Values('$subcategory_description','$disease_category_ID')";
	if(!mysqli_query($conn,$Insert_New_Subcategory)){
				$error = '1062yes';
				if(mysqli_errno($conn)."yes" == $error){
				    ?>
				    <script>
					alert("\nDISEASE SUBCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewsubitemcategory.php?AddNewSubItemCategory=AddNewSubItemCategoryThisPage";
				    </script>
				    <?php
				}
		}
		else {
			echo "<script>
			alert('DISEASE SUBCATEGORY ADDED SUCCESSFUL');
			document.location = 'adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage';
			</script>";
		}
    }
?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>ADD DISEASE SUBCATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25%><b>Disease Category</b></td>
                    <td width=75%>
                        <select name='disease_category_ID' id='disease_category_ID' required='required'>
                            <option></option>
			    <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_disease_category");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value="'.$row['disease_category_ID'].'" >'.$row['category_discreption'].'</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width=25%><b>Disease Sub Category</b></td>
                    <td width=75%>
                        <input type='text' name='subcategory_description' id='subcategory_description' required='required' placeholder='Enter Subcategory Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
			<input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
			<input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
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