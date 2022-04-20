<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	//    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
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
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW CATEGORY ITEM
    </a>
<?php  } //} ?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewsubitemcategory.php?AddNewSubategoryItem=AddNewSubategoryItemThisPage' class='art-button-green'>
        NEW SUBCATEGORY ITEM
    </a>
<?php  } //} ?>

<?php
    if(isset($_SESSION['userinfo'])){
       // if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
?>
    <a href='addnewitemcategory.php?AddNewCategory=AddNewCategoryThisPage' class='art-button-green'>
        NEW ITEM
    </a>
<?php  } //} ?>

<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' || $_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
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
    function searchProduct(){
	var Product_Name = document.getElementById('Search_Product').value;
	var Item_Category_ID = document.getElementById('Item_Category_ID').value;
        var Sponsor_ID = document.getElementById('Sponsor_ID').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=420px src='sponsornonsuporteditems_Iframe.php?Item_Category_ID="+Item_Category_ID+"&Product_Name="+Product_Name+"&Sponsor_ID="+Sponsor_ID+"'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=80%>
        <tr>
	    <td>Category :</td>
	    <td>
		<select id='Item_Category_ID' style='width: 100%' name='Item_Category_ID' onchange='searchProduct()'>
		    <option>ALL</option>
		    <?php
		    $cat_qr = "SELECT * FROM tbl_item_category";
		    $cat_result = mysqli_query($conn,$cat_qr);
		    while($cat_row = mysqli_fetch_assoc($cat_result)){
			?>
			<option value="<?php echo $cat_row['Item_Category_ID']; ?>"><?php
			echo $cat_row['Item_Category_Name'];
			?></option>
			<?php
		    }
		    ?>
		</select>
	    </td>
            <td>Sponsor :</td>
	    <td>
		<select id='Sponsor_ID' style='width: 100%' name='Sponsor_ID' onchange='searchProduct()'>
		    <option></option>
		    <?php
		    $sponsor_qr = "SELECT * FROM tbl_sponsor";
		    $sponsor_result = mysqli_query($conn,$sponsor_qr);
		    while($sponsor_row = mysqli_fetch_assoc($sponsor_result)){
			?>
			<option value="<?php echo $sponsor_row['Sponsor_ID']; ?>"><?php
			echo $sponsor_row['Guarantor_Name'];
			?></option>
			<?php
		    }
		    ?>
		</select>
	    </td>
            <td>
                <input type='text' name='Search_Product' id='Search_Product' onclick='searchProduct()' onkeypress='searchProduct(this.value)' placeholder='Enter Product Name'>
            </td> 
        </tr>        
    </table>
</center>
<fieldset>  
            <legend align=center><b>SPONSOR NON SUPORTED ITEM LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=440px src='sponsornonsuporteditems_Iframe.php?Product_Name=&Item_Category_ID=ALL'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>