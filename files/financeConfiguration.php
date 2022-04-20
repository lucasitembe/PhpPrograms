<?php
    include("./includes/connection.php");
    include("./includes/header.php");
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
<br/> 
<br/> 
<br/> 
 


<center>
    <table width=100%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>FINANCE SETUP AND CONFIGURATION</b></legend> 
                        <table width = 100%>
                            <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addNewAccountCategory.php?AddNewAccountCategoryThisForm'>
					<button style='width: 100%; height: 100%'>
					    <b>Add New Account Category</b>
					</button>
				    </a>
				</td>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editAccountCategory.php'>
					<button style='width: 100%; height: 100%'>
					    <b>Edit Account Category</b>
					</button>
				    </a>
				</td>
                                <td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addNewAccountSubcategory.php'>
					<button style='width: 100%; height: 100%'>
					    <b>Add New Account Subcategory</b>
					</button>
				    </a>
				</td>
                                <td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='editAccountSubcategory.php'>
					<button style='width: 100%; height: 100%'>
					    <b>Edit Account Subcategory</b>
					</button>
				    </a>
				</td>
                            </tr>    
			    <!-- -->
                         </fieldset>
                        </table>
        </center>
    </td></tr></table>
</center>
                                


<?php
    include("./includes/footer.php");
?>