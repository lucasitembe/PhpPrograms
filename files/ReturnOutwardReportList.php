<?php
    include_once("./includes/connection.php");
    include_once("./includes/header.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
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
        } else if ($From == "GeneralLedgerCenter") {
            echo "<a href='generalledgercenter.php?GeneralLedgerCenter=GeneralLedgerCenterThisPage' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>";
        }
    }

    include_once("./functions/items.php");
    include_once("./functions/scripts.php");
    include_once("./functions/department.php");
    include_once("./functions/supplier.php");

?>

    <br/><br/>
    <style>
        table,tr,td{ border-collapse:collapse !important; }
        tr:hover{ background-color:#eeeeee; cursor:pointer; }
    </style>

    <center>
        <fieldset>
            <table width="100%">
                <tr>
                    <?php
                        $Today_Date = Get_Today_Date();
                        $This_Month_Start_Date = Get_This_Month_Start_Date();
                    ?>
                    <td width="10%" style="text-align: right;"><b>Start Date</b></td>
                    <td><input name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~"
                               style="text-align: center;" type="text" value="<?php echo $This_Month_Start_Date; ?>"></td>
                    <td width="10%" style="text-align: right;"><b>End Date</b></td>
                    <td><input name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~"
                               style="text-align: center;" type="text" value="<?php echo $Today_Date; ?>"></td>
                </tr>
                <tr>
                    <td width="10%" style="text-align: right;"><b>Classification</b></td>
                    <td style='text-align: left;'>
                        <select name='Classification' id='Classification' onchange='Get_Items_List_Filtered()'>
                            <option selected='All'>All</option>
                            <?php
                            $Classification_List = Get_Item_Classification();
                            foreach($Classification_List as $Classification) {
                                echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width="10%" style="text-align: right;"><b>Supplier</b></td>
                    <td style='text-align: left;'>
                        <select name='Supplier_ID' id='Supplier_ID' onchange='Get_Items_List_Filtered()'>
                            <?php
                            echo "<option value='all'> All </option>";
                            $Supplier_List = Get_Supplier_All();
                            foreach($Supplier_List as $Supplier) {
                                echo "<option value='{$Supplier['Supplier_ID']}'> {$Supplier['Supplier_Name']} </option>";
                            }
                            ?>
                        </select>
                    </td>
                    <!--td colspan=2>
                        <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='Get_Items_List_Filtered()' placeholder='~~~~ ~~~~ ~~~~ ~~~~ Enter Item Name ~~~~ ~~~~ ~~~~ ~~~~' style="text-align: center;">
                    </td-->
                </tr>
                <tr>
                    <td width="10%" style="text-align: right;"><b>Report Type</b></td>
                    <td style='text-align: left;'>
                        <select name='Report_Type' id='Report_Type' onchange='Get_Items_List_Filtered()'>
                            <option value='SUMMARY'> SUMMARY </option>
                            <option value='DETAIL'> DETAIL </option>
                        </select>
                    </td>
                    <td style="text-align: right;" colspan=2>
                        <input type="button" name="Search" id="Search" value="SEARCH" class="art-button-green" onclick="Get_Items_List_Filtered();">
                        <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Stock_Details();">
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset style="background-color:white; height:430px; overflow-y: scroll;">
            <legend align='right' style="background-color:#006400;color:white;padding:5px;">
                <b>RETURN OUTWARD REPORT</b>
            </legend>
            <table width="100%" id='Items_Fieldset'> </table>
        </fieldset>
    </center>

    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('select').select2();

            Get_Items_List_Filtered();
        });
    </script>
    <style> .select2 { width: 100% !important; } </style>

    <script>
        function Preview_Stock_Details(){
            var Classification = document.getElementById("Classification").value;
            var Report_Type = document.getElementById("Report_Type").value;
           var Supplier_ID = document.getElementById("Supplier_ID").value;
//            var Supplier_ID=0;
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;

            var URL = 'returnOutwardreport_preview.php?Classification='+Classification
                +'&Supplier_ID='+Supplier_ID+'&Report_Type='+Report_Type
                +'&Start_Date='+Start_Date+'&End_Date='+End_Date;
            OpenNewTab(URL);
        }
    </script>

    <script>
        function Get_Items_List_Filtered(){
            var Classification = document.getElementById("Classification").value;
            var Report_Type = document.getElementById("Report_Type").value;
            var Supplier_ID = document.getElementById("Supplier_ID").value;
//            var Supplier_ID=0;
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;

            if(window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET','returnOutward_search.php?Classification='+Classification
                +'&Supplier_ID='+Supplier_ID+'&Report_Type='+Report_Type
                +'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
            myObject.send();
        }
    </script>

<?php include("./includes/footer.php"); ?>