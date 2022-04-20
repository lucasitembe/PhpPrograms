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
    
      $Current_Username = $_SESSION['userinfo']['Given_Username'];
    
    $sql_check_prevalage="SELECT edit_diseases FROM tbl_privileges WHERE edit_diseases='yes' AND "
            . "Given_Username='$Current_Username'";
    $sql_check_prevalage_result=mysqli_query($conn,$sql_check_prevalage);
    if(!mysqli_num_rows($sql_check_prevalage_result)>0){
        ?>
                    <script>
                        
                        var privalege= alert("You don't have the privelage to access this button")
                            document.location="./index.php?InvalidPrivilege=yes";
                    </script>
                    <?php
    } 
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='addnewdiseasecategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        ADD DISEASE CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='adddiseasesubcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        ADD DISEASE SUBCATEGORY
    </a>
<?php  } } ?>

<a href="diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm" class="art-button-green">BACK</a>
<script language="javascript" type="text/javascript">
    function searchDisease(Disease_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='Edit_Disease_List_Iframe.php?Disease_Name="+Disease_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Disease' id='Search_Disease' onclick='searchDisease(this.value)' onkeypress='searchDisease(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Disease Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
    </table>
</center>
<fieldset>  
            <legend align=center>LIST OF DISEASES</legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=320px src='Edit_Disease_List_Iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>