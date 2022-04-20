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
    $Current_Username = $_SESSION['userinfo']['Given_Username'];
   
    $sql_check_prevalage="SELECT add_diseases FROM tbl_privileges WHERE add_diseases='yes' AND "
            . "Given_Username='$Current_Username'";
    
   $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        var privalege= alert("You don't have the privelage to access this button")
                       // if(privalege){
                            document.location="./index.php?InvalidPrivilege=yes";
                       // }
                    </script>
                    <?php
    }
?>




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
    <a href='diseaseconfiguration.php?DiseaseConfiguration=DiseaseConfigurationThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?> 

<?php
    if(isset($_POST['submittedAddNewCategoryForm'])){
    $main_category_id = mysqli_real_escape_string($conn,$_POST['main_category_id']);
	$Category_Name = mysqli_real_escape_string($conn,$_POST['Category_Name']);
	$icd_10_or_icd_9 = mysqli_real_escape_string($conn,$_POST['icd_10_or_icd_9']);
	$Insert_New_Category = "insert into tbl_disease_category(category_discreption,main_category_id,icd_10_or_icd_9)
				    Values('$Category_Name','$main_category_id','$icd_10_or_icd_9')";
				    
        if(!mysqli_query($conn,$Insert_New_Category)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nDISEASE CATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewdiseasecategory.php";
				    </script>
				    <?php
				}
		}else {
		    echo '<script>
			alert("DISEASE CATEGORY ADDED SUCCESSFULLY");
		    </script>';	
		}
    }
?>
<br/><br/><br/><br/><br/><br/><br/><br/>
<center>
<table width=65%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>ADD DISEASE CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=35% style='text-align: right;'><b>Select Disease Main Category</b></td>
                    <td width=65%>
                        <select name='main_category_id' id='main_category_id' required='required'>
                            <option></option>
                <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_disease_maincategory");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option value="'.$row['main_category_id'].'" >'.$row['maincategory_name'].'</option>';
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
                            <option value="icd_9">ICD 9</option>
                            <option value="icd_10">ICD 10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width=35% style='text-align: right;'><b>Disease Category Name</b></td>
                    <td width=65%>
                        <input type='text' name='Category_Name' id='Category_Name' required='required' placeholder='Enter Category Name'>
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