<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>



<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
 
 

<?php
    if(isset($_POST['submit_Values'])){
	$Search_Criteria = $_POST['Search_Criteria'];
	$Search_Value = $_POST['Search_Value'];
    }
?>

<fieldset>  
    <legend align=right><b>AdHOC SEARCH</b></legend>
    <center>
	<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	    <table width=50%>
		<tr>
		    <td style='text-align: center; width: 40%;'>
			Select Value Type&nbsp;&nbsp;&nbsp;<select name='Search_Criteria' id='Search_Criteria' required='required'>
			    <option selected='selected'></option>
			    <option>Patient Name</option>
			    <option>Registration Number</option>
			    <option>Receipt Number</option>
			    <!--<option>Payment Date</option>-->
			</select>
		    </td>
		    <td>
			<input type='text' name='Search_Value' id='Seach_Value' size=10 required='required'>
		    </td>
		    <td width=10%>
			<input type='submit' id='submit_Values' name='submit_Values' value=' SEARCH ' class='art-button-green'>
		    </td>
		</tr>
	    </table>
	</form>
    </center>
</fieldset>
<fieldset>
    
    <?php if(isset($_POST['submit_Values'])){ ?>
	<iframe src='Adhoc_Search_Iframe.php?Search_Criteria=<?php echo $Search_Criteria; ?>&Search_Value=<?php echo $Search_Value; ?>' width=100% height=300px></iframe>
    <?php }else{ ?>
	<iframe src='Adhoc_Search_Iframe.php' width=100% height=300px></iframe>
    <?php } ?>
    
</fieldset>

<?php
    include("./includes/footer.php");
?>