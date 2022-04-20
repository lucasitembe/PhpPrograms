<?php
    include_once("./includes/connection.php");
    include_once("./includes/header.php");
    include_once("./functions/department.php");
    include_once("./functions/items.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['From'])){
            $From = mysqli_real_escape_string($conn,$_GET['From']);
        }else{
            $From = "";
        }

        if ($From == "ProcurementReports") {
            echo "<a href='procurementreports.php?ProcurementReports=ProcurementReportsThisPage' class='art-button-green'>BACK</a>";
        } else if ($From == "PhrmacyReports") {
            echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
        } else if ($From == "StorageAndSupplyReports") {
            echo "<a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>";
        }
    }

    include_once("./functions/scripts.php");
?>
    <br/><br/>
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

    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('select').select2();
        });
    </script>

    <?php
        $categories = '';
        $sqlCat = "SELECT Item_Category_Name, ca.Item_Category_ID FROM tbl_items i
                    JOIN tbl_item_subcategory isc ON isc.Item_Subcategory_ID=i.Item_Subcategory_ID
                    JOIN tbl_item_category ca ON ca.Item_Category_ID=isc.Item_Category_ID
                    WHERE i.Item_Type IN ('Pharmacy','Others') GROUP BY ca.Item_Category_ID
                    ";
        $data = mysqli_query($conn,$sqlCat)or die(mysqli_error($conn));

        while ($row = mysqli_fetch_array($data)) {
            $categories .= '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
        }
    ?>

    <fieldset>
        <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PURCHASING HISTORY</b></legend>
        <table width="100%">
            <tr>
                <td width="18%" style='text-align: left;'>
                    <select name='Classification' id='Classification' style="width: 100%"
                            onchange='getItemsList(this.value)'>
                        <option selected='All'>All</option>
                        <?php
                        $Classification_List = Get_Item_Classification();
                        foreach($Classification_List as $Classification) {
                            echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                        }
                        ?>
                    </select>
                </td>
                <td width="18%">
                    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered()' placeholder='~~~ ~~~ Enter Item Name ~~~ ~~~' style="text-align: center;">
                </td>
                <td width="7%" style="text-align: right;"><b>Start Date</b></td>
                <td width="15%"><input type="text" name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" style="text-align: center;">
                <td width="7%" style="text-align: right;"><b>End Date</b></td>
                <td width="15%"><input type="text" name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~" style="text-align: center;"></td>
                <td width="7%" style="text-align: center;"><input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Filter_Details()"></td>
                <td width="7%" style="text-align: center;"><input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Print_Details()"></td>
            </tr>
        </table>
        <?php
        $Title = '<tr><td colspan="3"><hr></td></tr>
				<tr>
				    <td width="5%"><b>SN</b></td>
				    <td ><b>ITEM NAME</b></td>
				    <td width="10%" style="text-align: right;"><b>BALANCE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				</tr>
				<tr><td colspan="3"><hr></td></tr>';
        ?>
        <table width="100%">
            <tr>
                <td width="35%">
                    <fieldset style="background-color:white; height:380px; overflow-y: scroll;">
                        <table width="100%" id='Items_Fieldset'>

                        </table>
                    </fieldset>
                </td>
                <td width="65%">
                    <table width="100%">
                        <tr>
                            <td>
                                <input type="text" name="Item_Name" id="Item_Name" value="" readonly="readonly"
                                       placeholder=" ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ Selected Product Name ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ "
                                       style="text-align: center;">
                                <input type="hidden" id="Item_ID" name="Item_ID" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <fieldset style="background-color:white; height:340px; overflow-y: scroll;" id="Ledger_Details">
                                    <table width="100%">
                                        <tr><td colspan="3"><hr></td></tr>
                                        <tr>
                                            <td width='20%'><b>Supplier</b></td>
                                            <td width='20%'><b>Purchase Date</b></td>
                                            <td width='20%'><b>Buying Price</b></td>
                                        </tr>
                                        <tr><td colspan="3"><hr></td></tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </fieldset>

    <script>
        $(document).ready(function(){
            getItemsList("All");
        });
    </script>

    <script type="text/javascript">
        function Filter_Details(){
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;
            var Item_ID = document.getElementById("Item_ID").value;

            if(Start_Date == null || Start_Date == ''){
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 1px solid #aaa; text-align: center;';
            }
            if(End_Date == null || End_Date == ''){
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 1px solid #aaa; text-align: center;';
            }
            if(Item_ID == null || Item_ID == ''){
                document.getElementById("Item_Name").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Item_Name").style = 'border: 1px solid #aaa; text-align: center;';
            }

            if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Item_ID != null && Item_ID != ''){
                if(window.XMLHttpRequest) {
                    myObjectF_Details = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    myObjectF_Details = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectF_Details.overrideMimeType('text/xml');
                }

                myObjectF_Details.onreadystatechange = function (){
                    data88 = myObjectF_Details.responseText;
                    if (myObjectF_Details.readyState == 4) {
                        document.getElementById('Ledger_Details').innerHTML = data88;
                        getItemsListFiltered();
                    }
                }; //specify name of function that will handle server response........
                myObjectF_Details.open('GET','Get_Item_Purchase_History.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_ID='+Item_ID,true);
                myObjectF_Details.send();
            }
        }
    </script>

    <script type="text/javascript">
        function Print_Details(){
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;
            var Item_ID = document.getElementById("Item_ID").value;
            if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Item_ID != null && Item_ID != ''){
                OpenNewTab('purchasinghistoryrepor_preview.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_ID='+Item_ID+
                    '&PreviewStockLedgerReport=PreviewStockLedgerReportThisPage');
            }else{
                if(Start_Date == null || Start_Date == ''){
                    document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
                }else{
                    document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
                }
                if(End_Date == null || End_Date == ''){
                    document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
                }else{
                    document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
                }
                if(Item_ID == null || Item_ID == ''){
                    document.getElementById("Item_Name").style = 'border: 3px solid red; text-align: center;';
                }else{
                    document.getElementById("Item_Name").style = 'border: 1px solid #555; text-align: center;';
                }
            }
        }
    </script>

    <script type="text/javascript">
        function Get_Selected_Details(Item_Name,Item_ID){
            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
            Filter_Details();
        }
    </script>
    <script>
        function getItemsListFiltered(){
            var Search_Value = document.getElementById("Search_Value").value;
            var Classification = document.getElementById("Classification").value;

            if(window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET','purchasinghistoryrepor_getitemlist.php?Classification='+Classification+'&Search_Value='+Search_Value,true);
            myObject.send();
        }
    </script>

    <script>
        function getItemsList(Classification){
            document.getElementById("Search_Value").value = '';

            if(window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }

            myObject2.onreadystatechange = function (){
                data2 = myObject2.responseText;
                if (myObject2.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data2;
                }
            }; //specify name of function that will handle server response........
            myObject2.open('GET','purchasinghistoryrepor_getitemlist.php?Classification='+Classification+'&FilterCategory=True',true);
            myObject2.send();
        }
    </script>

<?php
include("./includes/footer.php");
?>