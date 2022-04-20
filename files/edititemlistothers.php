<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage&from=itemOthers' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage&from=itemOthers' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewothercategory.php?AddNewItemCategory=AddNewItemCategoryThisForm&from=itemOthers' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='edititems.php?EditItem=EditItemThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


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
    function searchProduct(Product_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='Search_Item_list_Iframeothers2.php?Product_Name="+Product_Name+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=40%>
        <tr>
            <td>
                <input type='text' name='Search_Product' id='Search_Product' onclick='searchProduct(this.value)' onkeypress='searchProduct(this.value)' placeholder='Enter Product Name'>
            </td> 
        </tr>        
    </table>
</center>
<fieldset>  
            <legend align=center><b>ITEM LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=320px src='Search_Item_list_Iframeothers.php?Product_Name="+Product_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>