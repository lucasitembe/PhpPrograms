<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
	//	header("Location: ./index.php?InvalidPrivilege=yes");
	//    }
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
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
?>
    <a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } //} ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

 
<script language="javascript" type="text/javascript">
    function searchCategory(Category_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='editsubcategorylist_iframe.php?Category_Name="+Category_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Category' style='text-align: center;' id='Search_Category' onclick='searchCategory(this.value)' onkeypress='searchCategory(this.value)' onkeyup='searchCategory(this.value)' placeholder='Enter Subcategory Name'>
            </td> 
        </tr>        
    </table>
</center>
<fieldset =50%>  
            <legend align=center><b>SUBCATEGORY  LIST</b></legend>
        <center>
            <table width=50%>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=380px src='editsubcategorylist_iframe.php'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>