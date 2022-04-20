<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//    header("Location: ./index.php?InvalidPrivilege=yes");
	//}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){
?>
    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } //} ?>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>



<center>
    <table width=50%><tr><td>
        <center>
            <fieldset>
                <legend align="center" ><b>ADD NEW ITEM</b></legend>
                    <table>

                  <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addnewitemcategory.php?AddNewItemCategory=AddNewItemCategoryThisPage'>
					<button style='width: 100%; height: 100%'>
					    Add New Item
					</button>
				    </a>
				</td>

			    </tr>
			    <!--<tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addnewreagent.php?AddNewReagentItem=AddNewReagentItemThisForm'>
					<button style='width: 100%; height: 100%'>
					    Add New Item (Reagents)
					</button>
				    </a>
				</td>
                            </tr> -->
			    <tr>
				<td style='text-align: center; height: 40px; width: 25%;'>
				    <a href='addnewothercategory.php?AddNewItemCategory=AddNewItemCategoryThisForm'>
					<button style='width: 100%; height: 100%'>
					    Add New Item (Others)
					</button>
				    </a>
				</td>
                            </tr>

                            <tr>
                              <td style='text-align: center; height: 40px; width: 25%;'>
                      				    <a href='addnonmedicalitems.php?AddNewItemCategory=AddNewItemCategoryThisPage'>
                      					<button style='width: 100%; height: 100%'>
                      					    Add Non Medical Item
                      					</button>
                      				    </a>
                      				</td>
                            </tr>
                    </table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>
