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
	var x=document.forms['myForm']['account_Category_ID'].value;
	if (x==null || x=="Select category") {
	    alert("Please,select the account category for this subcategory.");
	    document.getElementById("account_Category_ID").focus();
	    return false;
	}else{
	    return true;
	}
    }
</script>
<?php
    if(isset($_POST['submit'])){
	//receive the data from the user form
	$account_Subcategory_Name=trim(mysqli_real_escape_string($conn,$_POST['account_Subcategory_Name']));
	$account_Category_ID=trim(mysqli_real_escape_string($conn,$_POST['account_Category_ID']));
	
	//run the query to insert data
	$query="INSERT INTO tbl_account_subcategory SET
		account_Subcategory_Name='$account_Subcategory_Name',
		account_Category_ID='$account_Category_ID' ";
		//execute the query
	$result=mysqli_query($conn,$query);
	if($result){
	    $message=("<b style='color:blue;font-size:14;font-family:verdana;'>The account subcategory is successifully created.</b>");
	}else{
	    $message=("<b style='color:red;font-size:14;font-family:verdana;'>Oops,something went wrong.</b>".mysqli_error($conn));
	}
    }
?>


<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW ACCCOUNT SUBCATEGORY</b></legend>
                    <table>
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
                                    <td width=30%><b>Account Subcategory Name</b></td>
                                    <td width=70%><input type='text' name='account_Subcategory_Name' required='required' size=70 id='account_Subcategory_Name' placeholder='Enter account subcategory name'></td>
                                </tr>
                                <tr>
                                    <td><b>Account category</b></td>
                                    <td><select name='account_Category_ID' id='account_Category_ID'>
				    <option selected="selected">Select category</option>
                                        <?php
                                            $data = mysqli_query($conn,"select * from tbl_account_category");
                                            while($row = mysqli_fetch_array($data)){
						$account_Category_ID=$row['account_Category_ID'];
                                                echo "<option value='$account_Category_ID'>".$row['account_Category_Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>