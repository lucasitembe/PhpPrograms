<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
?>


<?php
    /*if(isset($_SESSION['userinfo'])){ 
?>
    <a href='edititemlist.php?Section=&EditItemList=EditItemListThisPage' class='art-button-green'>
        EDIT ITEMS PRICE
    </a>
<?php  }*/ ?>


<?php
if(isset($_SESSION['userinfo'])){ 
 if(isset($_GET['FromRevenue'])){
    if($_GET['FromRevenue']=='Revenues'){
       echo "<a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
        BACK
    </a>";  
    } 
 }else{
  echo "<a href='searchvisitorsoutpatientlist.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>
        BACK
    </a>";   
     
 }
}
?>

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
    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=420px src='Price_list_Iframe.php?Item_Category_ID="+Item_Category_ID+"&Product_Name="+Product_Name+"&Sponsor_ID="+Sponsor_ID+"'></iframe>";
    }
</script>

<script>
    function clear_search(){
        document.getElementById("Search_Product").value = '';
        document.getElementById("Search_Product").focus();
    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td>Sponsor</td>
            <td>
                <select name="Sponsor_ID" id="Sponsor_ID" onchange="searchProduct()">
                    <option selected="selected" value="0">General Price</option>
                <?php
                    //get sponsors
                    $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                    <option value="<?php echo $data['Sponsor_ID']; ?>">
                        <?php echo $data['Guarantor_Name']; ?></option>
                <?php
                        }
                    }
                ?>
                </select>
            </td>
    	    <td><b>Category:</b></td>
    	    <td>
    		<select id='Item_Category_ID' style='width: 100%' name='Item_Category_ID' onchange='clear_search(); searchProduct();'>
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
            <td style="width:40%;">
                <input type='text' name='Search_Product' id='Search_Product' style="text-align: center;" onclick='searchProduct()' onkeypress='searchProduct(this.value)' oninput="searchProduct(this.value)" placeholder='~~~~~~~~~~~~~~~Search Product Name~~~~~~~~~~~~~~~'>
            </td> 
        </tr>        
    </table>
</center>
<br/>
<fieldset>  
            <legend align=right><b>ITEM LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<iframe width='100%' height=340px src='Price_list_Iframe.php?Product_Name=&Item_Category_ID=ALL'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>