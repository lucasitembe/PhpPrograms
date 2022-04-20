<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//	header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
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
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } //} ?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>
    <a href='addnewitemcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } //} ?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>

<?php
    if(isset($_GET['from']) && $_GET['from'] == "itemsConf") {
?>
<a href='edititemlist.php?EditItemList=EditItemListThisPage&from=itemsConf' class='art-button-green'>BACK</a>
<?php
    } else if(isset($_GET['from']) && $_GET['from'] == "itemOthers") {
        ?>
            <a href='edititemlistothers.php?EditItemOthers=EditItemOthersThisForm' class='art-button-green'>
                BACK
            </a>
        <?php
    } else {
?>
<a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>BACK</a>
<?php
    }
?>

<?php  } //} ?>

<br/><br/><br/><br/><br/><br/><br/><br/>
<center>
<table width=60%>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>NEW CATEGORY</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
            <tr>
		    <td style='text-align: right;' width=15%>Category Type</td>
		    <td width=15>
			<select name='Category_Type' id='Category_Type' required='required'>
			    <option selected='selected'></option>
			    <option>Service</option>
			    <option>Pharmacy</option>
			    <option>Radiology</option>
			</select>
		    </td>
                </tr>
                <tr>
                    <td style='text-align: right;' width=15%>Category Name</td>
                    <td>
                        <input type='text' name='Category_Name' id='Category_Name' required='required' placeholder='Enter Category Name'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Select Main Department
                    </td>
                    <td>
                        <select class="form-control" name="idara_id" required="">
                            <option value=""></option>
                            <?php
                                $sql_select_main_department_result=mysqli_query($conn,"SELECT * FROM tbl_idara_kuu  WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_main_department_result)>0){
                                    while($idar_kuu_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                                       $idara_name=$idar_kuu_rows['idara_name'];
                                       $idara_id=$idar_kuu_rows['idara_id'];
                                       echo "<option value='$idara_id'>$idara_name</option>";
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">
                        <input type="checkbox" name="can_be_used_on_registration">
                    </td>
                    <td>
                        can be used on registration
                    </td>

                </tr>
                <tr>
                  <td style="text-align:right">
                    <input type="checkbox" name="categoryStatus" value="yes">
                  </td><td colspan='2'>
                    <span>
                       </span>
                    <span>Non MEdical Item Category</span></td>
                </tr>
                <tr>
                    <td colspan=4 style='text-align: right;'>
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
    if(isset($_POST['submittedAddNewCategoryForm'])){
	$Category_Type = mysqli_real_escape_string($conn,$_POST['Category_Type']);
	$Category_Name = mysqli_real_escape_string($conn,$_POST['Category_Name']);
	$idara_id = mysqli_real_escape_string($conn,$_POST['idara_id']);
	$can_be_used_on_registration = mysqli_real_escape_string($conn,$_POST['can_be_used_on_registration']);

        if(isset($_POST['can_be_used_on_registration'])){
          $can_be_used_on_registration="yes";
        }else{
            $can_be_used_on_registration="no";
        }

  if (isset($_POST['categoryStatus']) == "yes") {
    $categoryStatus = "non medical";
  }else{
    $categoryStatus = 'medical';
  }

	$Insert_New_Category = "insert into tbl_item_category(Category_Type,Item_Category_Name,can_be_used_on_registration,idara_id,status)
				    Values('$Category_Type','$Category_Name','$can_be_used_on_registration','$idara_id','$categoryStatus')";
$save_result=mysqli_query($conn,$Insert_New_Category) or die(mysqli_error($conn));
	if(!$save_result){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewcategory.php";
				    </script>

				    <?php

				}
	}else {
	    echo '<script>
		alert("CATEGORY ADDED SUCCESSFUL");
	    </script>';
	}
    }
?>
<?php
    include("./includes/footer.php");
?>
