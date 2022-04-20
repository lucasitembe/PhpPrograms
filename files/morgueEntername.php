<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    <a href='morgueName.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>




<?php
    if(isset($_POST['submittedAddNewLaundryForm'])){
	$Morgue_Name= mysqli_real_escape_string($conn,$_POST['Morgue_Name']);
	$Insert_New_Laundry_Type = "insert into tbl_Morgue_Name(Morgue_Name)
				    Values('$Morgue_Name')";
	
	if(!mysqli_query($conn,$Insert_New_Laundry_Type)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
				    <script>
					alert("\nCATEGORY NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewcategory.php";
				    </script>
				    
				    <?php
				    
				}
		}
		else {
		    echo '<script>
			alert("CATEGORY ADDED SUCCESSFUL");
		    </script>';	
		}
    }
?>


<br/><br/><br/><br/><br/><br/><br/><br/>
<center>               
<table width=50%>
    <tr>
        <td>
            <fieldset>  
            <legend align="center"><b>ENTER MORGUE NAME</b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=55%>
                        <input type='text' name='Morgue_Name' id='laundry_type_name' required='required' placeholder='......enter morgue name......'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=1 style='text-align:center;'>
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