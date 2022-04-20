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
    <a href='setuplaundry.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    if(isset($_POST['submittedAddNewCategoryForm'])){
	$Category_Name = mysqli_real_escape_string($conn,$_POST['Category_Name']);
	$Insert_New_Category = "insert into tbl_item_category(Item_Category_Name)
				    Values('$Category_Name')";
	
	if(!mysqli_query($conn,$Insert_New_Category)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
						alert("\nCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
						document.location="./laundrynewtype.php";
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("CATEGORY LAUNDRY ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>


<?php
    if(isset($_POST['submittedAddNewLaundryForm'])){
	$laundry_name = mysqli_real_escape_string($conn,$_POST['laundry_name']);
	$Insert_New_Laundry = "insert into tbl_laundry_new_name(laundry_name)
				    Values('$laundry_name')";
	
		if(!mysqli_query($conn,$Insert_New_Laundry)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
				
					echo '<script>
					alert("CATEGORY ADDED SUCCESSFUL");
					document.location="./laundryname.php";
					</script>';	
		
    }
?>


<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align=center><b>NEW LAUNDRY NAME</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25%><b>Laundry Name</b></td>
                    <td width=75%>
                        <input type='text' name='laundry_name' id='laundry_name' required='required' placeholder='Enter Category Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewLaundryForm' value='true'/> 
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