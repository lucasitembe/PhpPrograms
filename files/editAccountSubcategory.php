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
<?php
    if(isset($_POST['submit'])){
	//receive the data from the user form
	$accountCategoryName=trim(mysqli_real_escape_string($conn,$_POST['account_Category_Name']));
	$branchID=trim(mysqli_real_escape_string($conn,$_POST['Branch_ID']));
	
	//run the query to insert data
	$query="INSERT INTO tbl_account_category SET
		account_Category_Name='$accountCategoryName',
		Branch_ID='$branchID' ";
		//execute the query
	$result=mysqli_query($conn,$query);
	if($result){
	    $message=("<b style='color:blue;font-size:14;font-family:verdana;'>The account category is successifully created.</b>");
	}else{
	    $message=("<b style='color:red;font-size:14;font-family:verdana;'>Oops,something went wrong.</b>".mysqli_error($conn));
	}
    }
?>


<center>
    <table width=30%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>EDIT ACCCOUNT CATEGORY</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
				<tr>
				    <td colspan="3" style="border: 0;">
					<?php
					    if(!empty($message)){
						echo $message;
					    }
					?>
				    </td>
				</tr>
                                <?php
                            echo "<tr>
                                            <td width='3%'><b>SN</b></td>
                                            <td width=''><b>Category name</b></td>
                                            <td width='' text-align='center'><b>Options</b></td>
                                        <tr>";
                                //run the query to select the the account categories and edit
                                $query="SELECT * FROM tbl_account_subcategory";
                                //execute the query
                                $result=mysqli_query($conn,$query);
                                $res=mysqli_num_rows($result);
                                for($i=0;$i<$res;$i++){
                                    $row=mysqli_fetch_array($result);
                                    $accountSubcategoryID=$row['account_Subcategory_ID'];
                                    $accountSubcategoryName=mysqli_real_escape_string($conn,$row['account_Subcategory_Name']);
                                    $edit="Edit";
                                    $delete="Delete";
                                    //display the results
                                    echo "<tr>
                                            <td>".($i+1)."</td>
                                            <td>".$accountSubcategoryName."</td>
                                            <td><a href='editAccountSubcategoryinfo.php?accountSubcatID=$accountSubcategoryID'>".$edit."&nbsp;&nbsp;</a>|<a href='deleteAccountSubcategoryinfo.php?accountSubcatID=$accountSubcategoryID' onclick=\"return confirm('Are you sure you want to delete this subcategory?');\");'>&nbsp;&nbsp;&nbsp;Delete</a></td>";
                                    echo "</tr>";
                                }   
                        ?>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
    include("./includes/footer.php");
?>