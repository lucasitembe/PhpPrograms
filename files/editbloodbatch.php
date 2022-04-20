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

    <a href='SearchBatch.php' class='art-button-green'>
        BACK
    </a>



<?php

if(isset($_GET['Batch_ID'])){ 
    if(isset($_GET['Batch_ID'])){
		$Batch_ID = $_GET['Batch_ID']; 
	}else{
		$Batch_ID = 0; 
	}
if($Batch_ID != 0){
	$select_Batches = mysqli_query($conn,"select Batch_ID,Batch_Name
		from tbl_blood_batches where Batch_ID = '$Batch_ID' ") or die(mysqli_error($conn));
			
			 while($row = mysqli_fetch_array($select_Batches)){
               $Batch_Name = $row['Batch_Name'];
			} 
}else{
    $Batch_Name = '';
}
			
}				
?>



<?php
    if(isset($_POST['submittedAddNewBatchForm'])){
	$Batch_Name = mysqli_real_escape_string($conn,$_POST['Batch_Name']);
	//$Batch_ID = mysqli_real_escape_string($conn,$_POST['Batch_ID']);
	
	
	$Insert_New_blood_Type = "UPDATE tbl_blood_batches SET Batch_Name = '$Batch_Name' WHERE Batch_ID = '$Batch_ID'";
	
	if(!mysqli_query($conn,$Insert_New_blood_Type)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    
				echo    '<script>
					alert("\nBATCH NAME ALREADY EXISTS!\nTRY ANOTHER NAME");
					document.location="./addnewcategory.php";
				    </script>';
				    
				 
				    
				}
		}
		else {
		    echo '<script>
			alert("BATCH UPDATED");
			document.location="./SearchBatch.php";

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
            <legend align=center><b>EDIT BATCH NAME </b></legend>
        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25%><b>Blood Batch Name</b></td>
                    <td width=75%>
                        <input type='text' name='Batch_Name' id='Batch_Name' required='required' value='<?php echo $Batch_Name; ?>'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submittedAddNewBatchForm' value='true'/> 
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