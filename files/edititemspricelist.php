<script src='js/functions.js'></script>
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
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

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //get employee Name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = 0;
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='#' class='art-button-green'>PREVIOUS UPDATES</a>
    <a href='edititems.php?EditItem=EditItemThisForm' class='art-button-green'>BACK</a>
<?php  } } ?>
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
<br/>
<br/> 
<fieldset>
<table width="100%">
    <tr>
        <td style="text-align: right" width="13%"><b>Item Category</b></td>
        <td style="text-align: left" width="32%" id="Category1">
            <select name="Item_Category_ID1" id="Item_Category_ID1" onchange="Display_Sub_Categories()">
            <option value="0" selected="selected">All</option>
                <?php
                    //Select Item Categories
                    $select = mysqli_query($conn,"select * from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
                <?php
                        }
                    }
                ?>
            </select>
        </td>
        <td style="text-align: right" width="13%"><b>Item Category</b></td>
        <td style="text-align: left" width="20%" id="Destination_Category_Area">
            <select name="Item_Category_ID2" id="Item_Category_ID2" onchange="Display_Sub_Categories2();">
                <option value="0" selected="selected">All</option>
                <?php
                    //Select Item Categories
                    $select = mysqli_query($conn,"select * from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                            <option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
                <?php
                        }
                    }
                ?>
            </select>        
        </td>
        <td width="7%" style="text-align: right;"><b>Percentage</b></td>
        <td width="15%">
            <input type="text" name="Percentage" id="Percentage" autocomplete="off" placeholder="~~~ ~~~ % ~~~ ~~~" style="text-align: center;" oninput="numberOnly(this); Update_New_Price()" onkeydown="numberOnly(this)" onkeypress="numberOnly(this); Update_New_Price()" onkeyup="numberOnly(this)">
        </td>
    </tr>

    <tr>
        <td style="text-align: right"><b>Item Subcategory</b></td>
        <td style="text-align: left" id="Sourse_Item_Subcategory">
            <select name="subcategory_ID" id="subcategory_ID">
                <option value="0">All</option>
            </select>
        </td>
        <td style="text-align: right"><b>Item Subcategory</b></td>
        <td style="text-align: left" id="Destination_Disease_Subcategory">
        <select name="subcategory_ID2" id="subcategory_ID2" onchange="">
            <option value="">Select Sub Category</option>
        </select>            
        </td>
        <td width="7%" style="text-align: right;"><b>Sponsor</b></td>
        <td width="15%">
            <select name="Sponsor_ID" id="Sponsor_ID" onchange="Preview_Items_Prices()">
            <!-- <option value="">SELECT SPONSOR</option> -->
            <option value="0" selected="selected">GENERAL</option>
        <?php
            $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
            $nm = mysqli_num_rows($select);
            if($nm > 0){
                while ($data = mysqli_fetch_array($select)) {
        ?>
                <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo strtoupper($data['Guarantor_Name']); ?></option>
        <?php
                }
            }
        ?>
            </select>
        </td>
    </tr>
</table>
<center>
    <table width="100%">
        <tr>
            <td width="25%">
                <input type="text" style="text-align: center;" autocomplete='off' name="Search_Selected" id="Search_Selected" placeholder='~~~ ~~~ Search Unselected Item ~~~ ~~~' oninput="Filter_Assigned_Diseases()">
            </td>
            <td width="25%">
                <table width="100%">
                    <tr>
                        <td width="40%">
                            <input type="radio" name="Display_Type" id="Items_Display" checked="checked" onclick="Display_Items()">
                            <label for="Items_Display"><b>Items</b></label>
                        </td>
                        <!-- <td width="40%">
                            <input type="radio" name="Display_Type" id="Sub_Display" onclick="Display_Items()">
                            <label for="Sub_Display"><b>Sub Categories</b></label>
                        </td> -->
                        <input type="hidden" name="Sub_Display" id="Sub_Display" value="false">
                        <td width="60%">
                            <input type="radio" name="Display_Type" id="Cat_Display" onclick="Display_Items()">
                            <label for="Cat_Display"><b>Categories</b></label>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="25%">
                <input type="text" style="text-align: center;" autocomplete='off' name="Search_Unselected" id="Search_Unselected" placeholder='~~~ ~~~ Search Selected Item ~~~ ~~~'>
            </td>
            <td width="20%">
                <input type="radio" name="Percentage_Type" checked="checked" id="Increase" onclick="Update_New_Price()">
                <label for="Increase"><b>Increase</b></label>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="Percentage_Type" id="Decrease" onclick="Update_New_Price()">
                <label for="Decrease"><b>Decrease</b></label>
            </td>
            <td style="text-align: center;">
                <input type="button" value="UPDATE" class="art-button-green" onclick="Update_Price()">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Unselected_Items_Area'>
                <table width="100%">
                    
                    <?php
                        $Title2 = '<tr><td colspan="3"><hr></td></tr>
                                    <tr>
                                        <td width="6%"><b>SN</b></td>
                                        <td><b>ITEM NAME</b></td>
                                        <td width="13%" style="text-align: center;"><b>ACTION</b></td>
                                    </tr>
                                    <tr><td colspan="3"><hr></td></tr>';
                        echo $Title2;
                        $temp = 0;
                        $select = mysqli_query($conn,"select i.Item_ID, i.Product_Name from tbl_items i where
                                                Item_ID NOT IN (select Item_ID from tbl_edit_price_cache where Employee_ID = '$Employee_ID') order by Product_Name limit 150") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                                <tr>
                                    <td><?php echo ++$temp; ?></td>
                                    <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
                                    <td style="text-align: center;">
                                        <input type="button" name="Add_Selected" id="Add_Selected" value=">> >>" onclick="Add_Selected_Item(<?php echo $data['Item_ID']; ?>)">
                                    </td>
                                </tr>
                    <?php
                                if(($temp%51) == 0){
                                    echo $Title2;
                                }
                            }
                        }
                    ?>
                    </table>
                </fieldset>
            </td>
            <?php
                $Title = '<tr><td colspan="5"><hr></td></tr>
                            <tr>
                                <td width="5%"><b>SN</b></td>
                                <td><b>ITEM NAME</b></td>
                                <td width="15%" style="text-align: right;"><b>CURRENT PRICE</b>&nbsp;&nbsp;&nbsp;</td>
                                <td width="15%" style="text-align: right;"><b>NEW PRICE</b>&nbsp;&nbsp;&nbsp;</td>
                                <td width="6%"><b>ACTION</b></td>
                            </tr>
                            <tr><td colspan="5"><hr></td></tr>'; 
            ?>
            <td colspan="3">
                <fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Selected_Items_Area'>
                    <table width="100%">
                    <?php
                        echo $Title;
                        $counter = 0;
                        $select = mysqli_query($conn,"select i.Item_ID, i.Product_Name, gp.Items_Price from tbl_items i, tbl_general_item_price gp, tbl_edit_price_cache epc where
                                                i.Item_ID = gp.Item_ID and
                                                i.Item_ID = epc.Item_ID and
                                                epc.Employee_ID = '$Employee_ID' order by i.Product_Name limit 150") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($row = mysqli_fetch_array($select)) {
                    ?>
                            <tr>
                                <td width="5%"><?php echo ++$counter; ?></td>
                                <td><?php echo ucwords(strtolower($row['Product_Name'])); ?></td>
                                <td width="15%" style="text-align: right;"><?php echo number_format($row['Items_Price']); ?>&nbsp;&nbsp;&nbsp;</td>
                                <td width="15%" style="text-align: right;"><i>Unspecified</i>&nbsp;&nbsp;&nbsp;</td>
                                <td width="6%">
                                    <input type="button" name="Remove" id="Remove" value="<< <<" onclick="Remove_Selected_Item(<?php echo $data['Item_ID']; ?>)">
                                </td>
                            </tr>
                    <?php
                            if(($counter%51) == 0){
                                echo $Title;
                            }
                            }
                        }
                    ?>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</fieldset>

<?php
    //check if more than 150 items selected then display warning
    $checker = mysqli_query($conn,"select Item_ID from tbl_edit_price_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num_checker = mysqli_num_rows($checker);
    if($num_checker > 150){
?>
        <center id='Warning'><span style="color: #037CB0;"><i><b>NOTE: Some selected items are hidden</b></i></span></center>
<?php
    }else{
?>
            <center id='Warning'></center>
<?php
    }
?>

<div id="Confirm_Dialogy" style="width:50%;">
    <fieldset>
    <center>Prepared by <?php echo ucwords(strtolower($Employee_Name)); ?></center><br/>
        <table width=100% style='border-style: none;'>
            <tr>
                <td width="25%" style="text-align: left;"><?php echo $Employee_Name; ?> Username</td>
                <td><input type="text" name="Username1" id="Username1" placeholder="<?php echo ucwords(strtolower($Employee_Name)); ?> Usermane" autocomplete="off"></td>
                <td width="25%" style="text-align: right;"><?php echo $Employee_Name; ?> Password</td>
                <td><input type="password" name="Password1" id="Password1" placeholder="<?php echo ucwords(strtolower($Employee_Name)); ?> Password" autocomplete="off"></td>
            </tr>
            <tr>
                <td width="25%" style="text-align: left;">First Supervisor Username</td>
                <td><input type="text" name="Username2" id="Username2" placeholder="First Supervisor Usermane" autocomplete="off"></td>
                <td style="text-align: right;">First Supervisor Password</td>
                <td><input type="password" name="Password2" id="Password2" placeholder="First Supervisor Password" autocomplete="off"></td>
            </tr>
            <tr>
                <td width="25%" style="text-align: left;">Second Supervisor Username</td>
                <td><input type="text" name="Username3" id="Username3" placeholder="Second Supervisor Usermane" autocomplete="off"></td>
                <td style="text-align: right;">Second Supervisor Password</td>
                <td><input type="password" name="Password3" id="Password3" placeholder="Second Supervisor Password" autocomplete="off"></td>
            </tr>
            <tr><td colspan="4"><hr></td></tr>
            <tr>
                <td colspan="4" style="text-align: right;">
                <input type="button" value="UPDATE" class="art-button-green" onclick="Verify_Credential()">
                </td>
            </tr>
        </table>
    </fieldset> 
</div>


<div id="No_Items_Found" style="width:25%;">
    <center>
        <b>NO ITEMS FOUND</b><br/><br/>
    </center>
</div>

<div id="Error_Message" style="width:25%;">
    <b>PROCESS FAIL PLEASE TRY AGAIN</b><br/><br/>
</div>

<div id="Successfully_Updated" style="width:25%;">
    <b>ALL SELECTED ITEMS UPDATED SUCCESSFULLY</b><br/>
</div>

<div id="Unsuccessfully_Updated" style="width:25%;">
    <b>PROCESS FAIL!!. PLEASE TRY AGAIN</b><br/>
</div>

<div id="Credential_Erro1">
    <center><b>Invalid <?php echo strtolower($Employee_Name); ?> username or password!</b></center><br/>
</div>

<div id="Credential_Erro2">
    <center><b>Invalid first supervisor username or password!</b></center><br/>
</div>

<div id="Credential_Erro3">
    <center><b>Invalid second supervisor username or password!</b></center><br/>
</div>

<script type="text/javascript">
    function Add_Selected_Item(Item_ID){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Percentage = document.getElementById("Percentage").value;
        var Increase = document.getElementById("Increase").value;
        var Decrease = document.getElementById("Decrease").value;
        var Status = '';
        document.getElementById("Warning").innerHTML = '';
        document.getElementById('Selected_Items_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(document.getElementById("Increase").checked){
            Status = 'Increase';
        }else if(document.getElementById("Decrease").checked){
            Status = 'Decrease';
        }

        if(window.XMLHttpRequest){
            myObjectFilter = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }
        myObjectFilter.onreadystatechange = function (){
            data1 = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Selected_Items_Area').innerHTML = data1;
                Update_Unselected_Items();
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilter.open('GET','Edit_Item_Price_Add_Selected_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID+'&Percentage='+Percentage+'&Status='+Status,true);
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Update_Unselected_Items(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Percentage = document.getElementById("Percentage").value;
        if(window.XMLHttpRequest){
            myObjectUpdateUnselected = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateUnselected = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateUnselected.overrideMimeType('text/xml');
        }
        myObjectUpdateUnselected.onreadystatechange = function (){
            data2 = myObjectUpdateUnselected.responseText;
            if (myObjectUpdateUnselected.readyState == 4) {
                document.getElementById('Unselected_Items_Area').innerHTML = data2;
                //Preview_Items_Prices();
            }
        }; //specify name of function that will handle server response........
        
        myObjectUpdateUnselected.open('GET','Edit_Item_Price_Update_Unselected_Item.php',true);
        myObjectUpdateUnselected.send();
    }
</script>

<script type="text/javascript">
    function Remove_Selected_Item(Item_ID){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Percentage = document.getElementById("Percentage").value;
        var Increase = document.getElementById("Increase").value;
        var Decrease = document.getElementById("Decrease").value;
        var Status = '';
        document.getElementById("Warning").innerHTML = '';

        if(document.getElementById("Increase").checked){
            Status = 'Increase';
        }else if(document.getElementById("Decrease").checked){
            Status = 'Decrease';
        }

        if(window.XMLHttpRequest){
            myObjectRemoveSelected = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemoveSelected = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveSelected.overrideMimeType('text/xml');
        }
        myObjectRemoveSelected.onreadystatechange = function (){
            data3 = myObjectRemoveSelected.responseText;
            if (myObjectRemoveSelected.readyState == 4) {
                document.getElementById('Selected_Items_Area').innerHTML = data3;
                Display_Items();
            }
        }; //specify name of function that will handle server response........
        
        myObjectRemoveSelected.open('GET','Edit_Item_Price_Remove_Selected_Item.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID+'&Percentage='+Percentage+'&Status='+Status,true);
        myObjectRemoveSelected.send();
    }
</script>

<script type="text/javascript">
    function Preview_Items_Prices(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Percentage = document.getElementById("Percentage").value;
        document.getElementById("Warning").innerHTML = '';
        document.getElementById('Selected_Items_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectPreviewPrices = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectPreviewPrices = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreviewPrices.overrideMimeType('text/xml');
        }
        myObjectPreviewPrices.onreadystatechange = function (){
            data4 = myObjectPreviewPrices.responseText;
            if (myObjectPreviewPrices.readyState == 4) {
                document.getElementById('Selected_Items_Area').innerHTML = data4;
            }
        }; //specify name of function that will handle server response........
        
        if(Percentage != null && Percentage != ''){
            myObjectPreviewPrices.open('GET','Edit_Item_Price_Preview_Item_Prices.php?Sponsor_ID='+Sponsor_ID+'&Percentage='+Percentage,true);
        }else{
            myObjectPreviewPrices.open('GET','Edit_Item_Price_Preview_Item_Prices.php?Sponsor_ID='+Sponsor_ID,true);
        }
        myObjectPreviewPrices.send();
    }
</script>

<script type="text/javascript">
    function Update_New_Price(){
        var Percentage = document.getElementById("Percentage").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Increase = document.getElementById("Increase").value;
        var Decrease = document.getElementById("Decrease").value;
        var Status = '';
        document.getElementById("Warning").innerHTML = '';
        document.getElementById('Selected_Items_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(document.getElementById("Increase").checked){
            Status = 'Increase';
        }else if(document.getElementById("Decrease").checked){
            Status = 'Decrease';
        }
        
        if(window.XMLHttpRequest){
            myObjectUpdateNewPrice = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateNewPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateNewPrice.overrideMimeType('text/xml');
        }
        myObjectUpdateNewPrice.onreadystatechange = function (){
            data5 = myObjectUpdateNewPrice.responseText;
            if (myObjectUpdateNewPrice.readyState == 4) {
                document.getElementById('Selected_Items_Area').innerHTML = data5;
            }
        }; //specify name of function that will handle server response........
        
        myObjectUpdateNewPrice.open('GET','Edit_Item_Price_Update_New_Prices.php?Percentage='+Percentage+'&Sponsor_ID='+Sponsor_ID+'&Status='+Status,true);
        myObjectUpdateNewPrice.send();
    }
</script>

<script type="text/javascript">
    function Display_Items(){
        var Items_Display = document.getElementById("Items_Display").value;
        var Cat_Display = document.getElementById("Cat_Display").value;
        var Sub_Display = document.getElementById("Sub_Display").value;
        var Status = '';
        document.getElementById("Warning").innerHTML = '';

        if(document.getElementById("Items_Display").checked){
            Status = 'item';
        }else if(document.getElementById("Cat_Display").checked){
            Status = 'category';
        }else if(document.getElementById("Sub_Display").checked){
            Status = 'subcategory'
        }

        if(window.XMLHttpRequest){
            myObjectDisplay = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function (){
            data6 = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Unselected_Items_Area').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........
        myObjectDisplay.open('GET','Edit_Item_Price_Display_Unselected.php?Status='+Status,true);
        myObjectDisplay.send();
    }
</script>

<script type="text/javascript">
    function Add_Selected_Category(Item_Category_ID){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Percentage = document.getElementById("Percentage").value;
        var Increase = document.getElementById("Increase").value;
        var Decrease = document.getElementById("Decrease").value;
        var Status = '';
        document.getElementById("Warning").innerHTML = '';

        document.getElementById('Selected_Items_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(document.getElementById("Increase").checked){
            Status = 'Increase';
        }else if(document.getElementById("Decrease").checked){
            Status = 'Decrease';
        }

        if(window.XMLHttpRequest){
            myObjectFilter = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }
        myObjectFilter.onreadystatechange = function (){
            data1 = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Selected_Items_Area').innerHTML = data1;
                Display_Items();
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilter.open('GET','Edit_Item_Price_Add_Selected_Category.php?Item_Category_ID='+Item_Category_ID+'&Sponsor_ID='+Sponsor_ID+'&Percentage='+Percentage+'&Status='+Status,true);
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Update_Price(){
        var Percentage = document.getElementById("Percentage").value;
        if(Percentage != null && Percentage != ''){
            document.getElementById("Percentage").style = 'border: 3px solid white; text-align: center';
            if(window.XMLHttpRequest){
                myObjectVerify = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectVerify.overrideMimeType('text/xml');
            }
            myObjectVerify.onreadystatechange = function (){
                data10 = myObjectVerify.responseText;
                if (myObjectVerify.readyState == 4) {
                    var feedback = data10;
                    if(feedback == 'yes'){
                        $("#Confirm_Dialogy").dialog("open");
                    }else if(feedback == 'no'){
                        $("#No_Items_Found").dialog("open");
                    }else{
                        $("#Error_Message").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectVerify.open('GET','Edit_Item_Price_Check_Items.php',true);
            myObjectVerify.send();
        }else{
            if(Percentage == null || Percentage == ''){
                document.getElementById("Percentage").style = 'border: 3px solid red; text-align: center';
                document.getElementById("Percentage").focus();
            }else{
                document.getElementById("Percentage").style = 'border: 3px solid white; text-align: center';
            }
        }
    }
</script>

<script type="text/javascript">
    function Verify_Credential(){
        var Username1 = document.getElementById("Username1").value;
        var Password1 = document.getElementById("Password1").value;
        if(Username1 != '' && Username1 != null && Password1 != '' && Password1 != null){
            document.getElementById("Username1").style = 'border: 3px solid white';
            document.getElementById("Password1").style = 'border: 3px solid white';
            if(window.XMLHttpRequest){
                myObjectValidate = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectValidate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectValidate.overrideMimeType('text/xml');
            }
            myObjectValidate.onreadystatechange = function (){
                data11 = myObjectValidate.responseText;
                if (myObjectValidate.readyState == 4) {
                    var mrejesho = data11;
                    if(mrejesho == 'yes'){
                        Verify_Credential2();
                    }else if(mrejesho == 'no'){
                        $("#Credential_Erro1").dialog("open");
                    }else{
                        $("#Error_Process").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectValidate.open('GET','Edit_Item_Price_Check_Employee1.php?Username1='+Username1+'&Password1='+Password1,true);
            myObjectValidate.send();
        }else{
            if(Username1 == null || Username1 == ''){
                document.getElementById("Username1").style = 'border: 3px solid red';
            }else{
                document.getElementById("Username1").style = 'border: 3px solid white';
            }
            
            if(Password1 == null || Password1 == ''){
                document.getElementById("Password1").style = 'border: 3px solid red';
            }else{
                document.getElementById("Password1").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Verify_Credential2(){
        var Username2 = document.getElementById("Username2").value;
        var Password2 = document.getElementById("Password2").value;
        if(Username2 != '' && Username2 != null && Password2 != '' && Password2 != null){
            document.getElementById("Username2").style = 'border: 3px solid white';
            document.getElementById("Password2").style = 'border: 3px solid white';
            if(window.XMLHttpRequest){
                myObjectValidate2 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectValidate2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectValidate2.overrideMimeType('text/xml');
            }
            myObjectValidate2.onreadystatechange = function (){
                data12 = myObjectValidate2.responseText;
                if (myObjectValidate2.readyState == 4) {
                    var mrejesho2 = data12;
                    if(mrejesho2 == 'yes'){
                        Verify_Credential3();
                    }else if(mrejesho2 == 'no'){
                        $("#Credential_Erro2").dialog("open");
                    }else{
                        $("#Error_Process").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectValidate2.open('GET','Edit_Item_Price_Check_Employee2.php?Username2='+Username2+'&Password2='+Password2,true);
            myObjectValidate2.send();
        }else{
            if(Username2 == null || Username2 == ''){
                document.getElementById("Username2").style = 'border: 3px solid red';
            }else{
                document.getElementById("Username2").style = 'border: 3px solid white';
            }
            
            if(Password2 == null || Password2 == ''){
                document.getElementById("Password2").style = 'border: 3px solid red';
            }else{
                document.getElementById("Password2").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Verify_Credential3(){
        var Username3 = document.getElementById("Username3").value;
        var Password3 = document.getElementById("Password3").value;
        if(Username3 != '' && Username3 != null && Password3 != '' && Password3 != null){
            document.getElementById("Username3").style = 'border: 3px solid white';
            document.getElementById("Password3").style = 'border: 3px solid white';
            if(window.XMLHttpRequest){
                myObjectValidate3 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectValidate3 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectValidate3.overrideMimeType('text/xml');
            }
            myObjectValidate3.onreadystatechange = function (){
                data13 = myObjectValidate3.responseText;
                if (myObjectValidate3.readyState == 4) {
                    var mrejesho3 = data13;
                    if(mrejesho3 == 'yes'){
                        Update_Selected_Items();
                    }else if(mrejesho3 == 'no'){
                        document.getElementById("Username2").style = 'border: 3px solid red';
                        document.getElementById("Password2").style = 'border: 3px solid red';
                        document.getElementById("Username2").value = '';
                        document.getElementById("Password2").value = '';
                        $("#Credential_Erro3").dialog("open");
                    }else{
                        document.getElementById("Username3").style = 'border: 3px solid red';
                        document.getElementById("Password3").style = 'border: 3px solid red';
                        document.getElementById("Username3").value = '';
                        document.getElementById("Password3").value = '';
                        $("#Error_Process").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectValidate3.open('GET','Edit_Item_Price_Check_Employee3.php?Username3='+Username3+'&Password3='+Password3,true);
            myObjectValidate3.send();
        }else{
            if(Username3 == null || Username3 == ''){
                document.getElementById("Username3").style = 'border: 3px solid red';
            }else{
                document.getElementById("Username3").style = 'border: 3px solid white';
            }
            
            if(Password3 == null || Password3 == ''){
                document.getElementById("Password3").style = 'border: 3px solid red';
            }else{
                document.getElementById("Password3").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Update_Selected_Items(){
        var Username1 = document.getElementById("Username1").value;
        var Username2 = document.getElementById("Username2").value;
        var Username3 = document.getElementById("Username3").value;
        var Password1 = document.getElementById("Password1").value;
        var Password2 = document.getElementById("Password2").value;
        var Password3 = document.getElementById("Password3").value;
        var Percentage = document.getElementById("Percentage").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Increase = document.getElementById("Increase").value;
        var Decrease = document.getElementById("Decrease").value;
        var Status = '';
        if(document.getElementById("Increase").checked){
            Status = 'Increase';
        }else if(document.getElementById("Decrease").checked){
            Status = 'Decrease';
        }
        var msg = confirm("Are you sure you want to update all selected items?");
        if(msg == true && (Status == 'Increase' || Status == 'Decrease')){
            if(window.XMLHttpRequest){
                myObjectUpdateSelectedItems = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectUpdateSelectedItems = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdateSelectedItems.overrideMimeType('text/xml');
            }
            myObjectUpdateSelectedItems.onreadystatechange = function (){
                data140 = myObjectUpdateSelectedItems.responseText;
                if (myObjectUpdateSelectedItems.readyState == 4) {
                    var myfeedback = data140;
                    if(myfeedback == 'yes'){
                        Preview_Items_Prices();
                        $("#Confirm_Dialogy").dialog("close");
                        $("#Successfully_Updated").dialog("open");
                    }else{
                        Preview_Items_Prices();
                        $("#Confirm_Dialogy").dialog("close");
                        $("#Unsuccessfully_Updated").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectUpdateSelectedItems.open('GET','Edit_Item_Price_Update_All_Selected_Items.php?Username1='+Username1+'&Username2='+Username2+'&Username3='+Username3+'&Password1='+Password1+'&Password2='+Password2+'&Password3='+Password3+'&Percentage='+Percentage+'&Status='+Status+'&Sponsor_ID='+Sponsor_ID,true);
            myObjectUpdateSelectedItems.send();
        }
    }
</script>

<script type="text/javascript">
    function Display_Sub_Categories(){
        var Item_Category_ID = document.getElementById("Item_Category_ID1").value;

        if(window.XMLHttpRequest){
            myObjectFilterCategory = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilterCategory = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterCategory.overrideMimeType('text/xml');
        }
        myObjectFilterCategory.onreadystatechange = function (){
            dataz = myObjectFilterCategory.responseText;
            if (myObjectFilterCategory.readyState == 4) {
                document.getElementById('Sourse_Item_Subcategory').innerHTML = dataz;
                Display_Filtered_Items();
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilterCategory.open('GET','Filter_Category_Edit_Price.php?Item_Category_ID='+Item_Category_ID,true);
        myObjectFilterCategory.send();
    }
</script>

<script type="text/javascript">
    function Display_Filtered_Items(){
        var Item_Category_ID = document.getElementById("Item_Category_ID1").value;
        document.getElementById("Warning").innerHTML = '';

        if(window.XMLHttpRequest){
            myObjectDisplayFiltered = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectDisplayFiltered = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplayFiltered.overrideMimeType('text/xml');
        }
        myObjectDisplayFiltered.onreadystatechange = function (){
            data699 = myObjectDisplayFiltered.responseText;
            if (myObjectDisplayFiltered.readyState == 4) {
                document.getElementById('Unselected_Items_Area').innerHTML = data699;
            }
        }; //specify name of function that will handle server response........
        myObjectDisplayFiltered.open('GET','Edit_Item_Price_Display_Unselected_Filtered.php?Item_Category_ID='+Item_Category_ID,true);
        myObjectDisplayFiltered.send();
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>

<script>
   $(document).ready(function(){
      $("#Confirm_Dialogy").dialog({ autoOpen: false, width:'60%',height:350, title:'UPDATE ITEMS PRICE',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#No_Items_Found").dialog({ autoOpen: false, width:'30%',height:100, title:'eHMS 2.0 Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Successfully_Updated").dialog({ autoOpen: false, width:'30%',height:100, title:'eHMS 2.0 Information Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Unsuccessfully_Updated").dialog({ autoOpen: false, width:'30%',height:100, title:'eHMS 2.0 Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Error_Message").dialog({ autoOpen: false, width:'30%',height:100, title:'UPDATE ITEMS PRICE',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Credential_Erro1").dialog({ autoOpen: false, width:'40%',height:100, title:'eHMS 2.0 Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Credential_Erro2").dialog({ autoOpen: false, width:'40%',height:100, title:'eHMS 2.0 Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Credential_Erro3").dialog({ autoOpen: false, width:'40%',height:100, title:'eHMS 2.0 Alert Message',modal: true});
   });
</script>

<?php
    include("./includes/footer.php");
?>