<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
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
<br/><br/><br/><br/><br/><br/>
<script type="text/javascript">
    function validateForm(){
	var x=document.forms['myForm']['Branch_ID'].value;
	if (x==null || x=="Select branch") {
	    alert("Please,select the branch for this account category");
	    document.getElementById("Branch_ID").focus();
	    return false;
	}else{
	    return true;
	}
    }
</script>
<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW ACCCOUNT CATEGORY</b></legend>
                    <table>
			<?php
			    //get the category id and use it to select info for edit
			    if($_GET['accountCatID']){
				$accountCatID=$_GET['accountCatID'];
			    }
			    //run the query to select the info
			    $select_id="DELETE FROM tbl_account_category WHERE account_Category_ID='$accountCatID'";
			    //execute the query
			    $result=mysqli_query($conn,$select_id);
			    if($result){
				$message=("<b style='color:red;font-size:14;font-family:verdana;'>Account category successifully deleted.</b>".mysqli_error($conn));
			    }else{
				$message=("<b style='color:red;font-size:14;font-family:verdana;'>Oops,no data associated with that Identifier.</b>".mysqli_error($conn));
			    }
			?>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
				<tr>
				    <td colspan="2" style="border: 0;">
					<?php
					    if(!empty($message)){
						echo $message;
					    }
					?>
				    </td>
				</tr>
				<tr>
				    <td colspan="2" style="border: 0;">
					<a href='editAccountCategory.php'>
					    <b>Back to category list</b>
				    </a>
				    </td>
				</tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>