<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<input type="button" value="VIEW / CHANGE SPONSORS" onclick="Sponsor_Configuration()" class="art-button-green">
<a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>BACK</a>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = $Today - $original_Date; 
    }
?> 

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<br/><br/>
<center>
<fieldset>
    <table width=70%>
        <tr>
            <td><b>Category:</b></td>
            <td>
            <select id='Item_Category_ID' name='Item_Category_ID' onchange='Get_Items();'>
                <option selected="selected" value="0">All</option>
            <?php
                $select = mysqli_query($conn,"select * from tbl_item_category") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while($data = mysqli_fetch_array($select)){
            ?>
                        <option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo strtoupper($data['Item_Category_Name']); ?></option>
            <?php
                    }
                }
            ?>
            </select>
            </td>
            <td style="width:40%;">
                <input type='text' name='Search_Product' id='Search_Product' style="text-align: center;" onclick='searchProduct()' onkeypress='searchProduct()' oninput="searchProduct()" placeholder='~~~ ~~~ ~~~ Search Product Name ~~~ ~~~ ~~~'>
            </td>
            <td width="14%" style="text-align: right;">
                <input type="button" name="Preview" id="Preview" class="art-button-green" value="PREVIEW" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
    </fieldset>
</center>
<br/>

<div id="Sponsor_Conf">
    <center id="Sponsor_Area">
        
    </center>
</div>
<div id="available">
    <center>Selected sponsor already exists!</center>
</div>
<div id="exceed">
    <center>You are allowed to select only three sponsors</center>
</div>

<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Items_Fieldset'>
    <legend align=right><b>ITEM LIST</b></legend>
        <center>
            <table width=100%>
                <tr>
                    <td width="3%"><b>SN</b></td>
                    <td width=17%><b>CATEGORY</b></td>
                    <td width="7%"><b>P CODE</b></td>
                    <td><b>PRODUCT NAME</b></td>
                <?php
                    $select = mysqli_query($conn,"select sp.Sponsor_ID, sp.Guarantor_Name from tbl_sponsor sp, tbl_price_list_selected_sponsors ss where
                                            ss.Sponsor_ID = sp.Sponsor_ID order by sp.Sponsor_ID") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($select);
                    $Sub_Department = array('','','');
                    if($no > 0){
                        $Counter = 0;
                        while ($dt = mysqli_fetch_array($select)) {
                            $Sub_Department[$Counter] = $dt['Sponsor_ID'];
                            echo "<td width='14%' style='text-align: right;'><b>".$dt['Guarantor_Name']."&nbsp;&nbsp;&nbsp;</b></td>";
                            $Counter++;
                        }
                    }

                ?>
                </tr>
                <tr><td colspan="<?php echo 4+$no; ?>"><hr></td></tr>
                <?php
                    $nmz = 0;
                    $selected_sponsors = mysqli_query($conn,"select sp.Guarantor_Name, sp.Sponsor_ID from tbl_sponsor sp, tbl_price_list_selected_sponsors ss where
                                                        ss.Sponsor_ID = sp.Sponsor_ID order by sp.Sponsor_ID") or die(mysqli_error($conn));
                    $S_Num  = mysqli_num_rows($selected_sponsors);
                    $items = mysqli_query($conn,"select Product_Name, Product_Code, Item_ID, Item_Category_Name from tbl_items i, tbl_item_category ic, tbl_item_subcategory isu where
                                            i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
                                            isu.Item_category_ID = ic.Item_Category_ID and
                                            i.Can_Be_Sold = 'yes' order by Item_Category_Name limit 500") or die(mysqli_error($conn));
                    $nm = mysqli_num_rows($items);
                    if($nm > 0){
                        while ($dtz = mysqli_fetch_array($items)) {
                            $Item_ID = $dtz['Item_ID'];
                            if($S_Num > 0){
                ?>
                                   <tr>
                                        <td><?php echo ++$nmz; ?></td>
                                        <td><?php echo $dtz['Item_Category_Name']; ?></td>
                                        <td><?php echo $dtz['Product_Code']; ?></td>
                                        <td><?php echo $dtz['Product_Name']; ?></td>
                <?php
                                //generate suppotive sql
                                for($i = 0; $i <= $Counter - 1; $i++){
                                    $sql = mysqli_query($conn,"select Items_Price from tbl_item_price where Sponsor_ID = '$Sub_Department[$i]' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                                    $noz = mysqli_num_rows($sql);
                                    if($noz > 0){
                                        while ($td = mysqli_fetch_array($sql)) {
                                            echo "<td style='text-align: right;'>".number_format($td['Items_Price'])."&nbsp;&nbsp;&nbsp;</td>";
                                        }
                                    }else{
                                        echo "<td style='text-align: right;'>0&nbsp;&nbsp;&nbsp;</td>";
                                    }
                                }                            
                                echo "</tr>";
                            }
                        }
                    }
                ?>
            </table>
        </center>
</fieldset>

                <!-- <tr>
                    <td id='Search_Iframe'>
                        <iframe width='100%' height=340px src='Price_list_Iframe.php?Product_Name=&Item_Category_ID=ALL'></iframe>
                    </td>
                </tr> -->
<br/>

<script type="text/javascript">
    function Preview_Report(){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Search_Product = document.getElementById("Search_Product").value;
        window.open("previewprice.php?Item_Category_ID="+Item_Category_ID+"&Search_Product="+Search_Product+"&PreviewPrice=PreviewPriceThisPage", "_blank");
    }
</script>

<script type="text/javascript">
    function searchProduct(){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Search_Product = document.getElementById("Search_Product").value;
        if(window.XMLHttpRequest){
            myObjectCatVal = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectCatVal = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectCatVal.overrideMimeType('text/xml');
        }

        myObjectCatVal.onreadystatechange = function (){
            dataCatVal = myObjectCatVal.responseText;
            if (myObjectCatVal.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = dataCatVal;
            }
        }; //specify name of function that will handle server response........
        myObjectCatVal.open('GET','Price_List_Search_Product.php?Item_Category_ID='+Item_Category_ID+'&Search_Product='+Search_Product,true);
        myObjectCatVal.send();        
    }
</script>

<script type="text/javascript">
    function Get_Items(){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        document.getElementById("Search_Product").value = '';
        if(window.XMLHttpRequest){
            myObjectCat = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectCat = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectCat.overrideMimeType('text/xml');
        }

        myObjectCat.onreadystatechange = function (){
            dataCat = myObjectCat.responseText;
            if (myObjectCat.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = dataCat;
            }
        }; //specify name of function that will handle server response........
        myObjectCat.open('GET','Price_List_Get_Items.php?Item_Category_ID='+Item_Category_ID,true);
        myObjectCat.send();
    }
</script>

<script type="text/javascript">
    function Sponsor_Configuration(){
        if(window.XMLHttpRequest){
            myObjectSp = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSp = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSp.overrideMimeType('text/xml');
        }

        myObjectSp.onreadystatechange = function (){
            data = myObjectSp.responseText;
            if (myObjectSp.readyState == 4) {
                document.getElementById('Sponsor_Area').innerHTML = data;
                $("#Sponsor_Conf").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectSp.open('GET','Sponsor_Configuration.php',true);
        myObjectSp.send();
    }
</script>

<script type="text/javascript">
    function Add_Sponsor(Sponsor_ID){
        if(window.XMLHttpRequest){
            myObjectAdd = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectAdd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAdd.overrideMimeType('text/xml');
        }

        myObjectAdd.onreadystatechange = function (){
            data122 = myObjectAdd.responseText;
            if (myObjectAdd.readyState == 4) {
                var feedback = data122;
                if(feedback == 'available'){
                    $("#available").dialog("open");
                }else if(feedback == 'exceed'){
                    $("#exceed").dialog("open");
                }else{
                    document.getElementById('Selected_Sponsor_List').innerHTML = data122;
                }
            }
        }; //specify name of function that will handle server response........
        myObjectAdd.open('GET','Price_List_Add_Sponsor.php?Sponsor_ID='+Sponsor_ID,true);
        myObjectAdd.send();
    }
</script>

<script type="text/javascript">
    function Remove_Sponsor(Sponsor_ID){
        if(window.XMLHttpRequest){
            myObjectRem = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRem.overrideMimeType('text/xml');
        }

        myObjectRem.onreadystatechange = function (){
            data1229 = myObjectRem.responseText;
            if (myObjectRem.readyState == 4) {
                document.getElementById('Selected_Sponsor_List').innerHTML = data1229;
            }
        }; //specify name of function that will handle server response........
        myObjectRem.open('GET','Price_List_Remove_Sponsor.php?Sponsor_ID='+Sponsor_ID,true);
        myObjectRem.send();
    }
</script>

<script type="text/javascript">
    function Close_Dialog(){
        $("#Sponsor_Conf").dialog("close");
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function () {
        $("#Sponsor_Conf").dialog({autoOpen: false, width: '70%', height: 450, title: 'VIEW / CHANGE SPONSORS', modal: true});
        $("#available").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0', modal: true});
        $("#exceed").dialog({autoOpen: false, width: '30%', height: 150, title: 'eHMS 2.0', modal: true});
    });
</script>

<?php
    include("./includes/footer.php");
?>