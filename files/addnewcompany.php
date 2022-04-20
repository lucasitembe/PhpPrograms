<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href="companypage.php?CompanyConfiguration=CompanyConfigurationThisPage" class="art-button-green">BACK</a>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 
 <?php
	if(isset($_POST['submittedAddNewCompanyForm'])){
		$Company_Name = mysqli_real_escape_string($conn,$_POST['Company_Name']); 
		
		$sql = "insert into tbl_Company(Company_Name)
				values('$Company_Name')";
		
		if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
						$controlforminput = 'not valid';
				}
		}
		else {
		    echo '<script>
			alert("Company Added Successful");
		    </script>';	
		}
	}
?>

<?php if($controlforminput == ''){ ?>
 <center>
 <table width=50%><tr><td>
<center><fieldset>
            <legend align="center" ><b>ADD NEW COMPANY</b></legend>
    <table>
    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
       
            <tr>
                <td width=30%><b>COMPANY NAME</b></td>
                <td width=70%><input type='text' name='Company_Name' required='required' size=70 id='Company_Name' placeholder='Enter Company Name'></td>
            </tr>
            <tr>
                <td colspan=2 style='text-align: right;'>
                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    <input type='hidden' name='submittedAddNewCompanyForm' value='true'/> 
                </td>
            </tr>
    </form></table>
</center></td></tr></table></center>
 
<?php }else { ?>


<center> <?php //echo "<span style='color: red;'><b>COMPANY NAMED </span>".$Company_Name."  <span style='color: red;'>ALREADY EXIST!</b></span>"; ?> 
 <?php echo "<script type='text/javascript'>
    alert('SORRY! COMPANY NAME ALREADY EXIST!');
 </script>"; ?>
 <table width=50%><tr><td>
<center><fieldset>
            <legend align="center" ><b>ADD NEW COMPANY</b></legend>
    <table>
    <form action='#' method='post' name='myForm' id='myForm' name="myForm" onsubmit="return validateForm();" enctype="multipart/form-data">
       
            <tr>
                <td width=30%><b>COMPANY NAME</b></td>
                <td width=70%><input type='text' name='Company_Name' required='required' size=70 id='Company_Name' value='<?php echo $Company_Name; ?>'></td>
            </tr>
            <tr>
                <td width=30%><b>SELECT COMPANY BUNNER</b></td>
                <td width=70%><input type="file" name="Bunner_Name" id='Bunner_Name' title='SELECT COMPANY BUNNER' value='<?php $Bunner_Name; ?>'></td>
            </tr>
            <tr>
                <td colspan=2 style='text-align: right;'>
                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    <input type='hidden' name='submittedAddNewCompanyForm' value='true'/> 
                </td>
            </tr>
    </form></table>
</center></td></tr></table></center>

<?php } ?>

<?php
    include("./includes/footer.php");
?>